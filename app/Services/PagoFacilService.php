<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PagoFacilService
{
    private $apiUrl;
    private $tokenService;
    private $tokenSecret;
    private $callbackUrl;
    private $paymentMethod;
    private $commerceId;
    private $clientCode;
    private $testAmount;
    private $accessToken = null;
    private $tokenExpiration = null;

    public function __construct()
    {
        $this->apiUrl = env('PAGOFACIL_API_URL', 'https://masterqr.pagofacil.com.bo/api/services/v2');
        $this->tokenService = env('PAGOFACIL_TOKEN_SERVICE');
        $this->tokenSecret = env('PAGOFACIL_TOKEN_SECRET');
        $this->callbackUrl = env('PAGOFACIL_CALLBACK_URL');
        $this->paymentMethod = env('PAGOFACIL_PAYMENT_METHOD', 34);
        $this->commerceId = env('PAGOFACIL_COMMERCE_ID');
        $this->clientCode = env('PAGOFACIL_CLIENT_CODE', 'VET01');
        $this->testAmount = env('PAGOFACIL_PAYMENT_AMOUNT');
    }

    /**
     * Autenticar con PagoFácil y obtener accessToken
     */
    public function login()
    {
        try {
            $response = Http::withHeaders([
                'tctokenservice' => $this->tokenService,
                'tctokensecret' => $this->tokenSecret,
            ])->post("{$this->apiUrl}/login");

            $data = $response->json();

            if ($data['error'] === 0 && isset($data['values']['accessToken'])) {
                $this->accessToken = $data['values']['accessToken'];
                $this->tokenExpiration = now()->addMinutes($data['values']['expiresInMinutes']);
                
                Log::info('PagoFácil login exitoso', [
                    'expires_in' => $data['values']['expiresInMinutes']
                ]);

                return [
                    'success' => true,
                    'token' => $this->accessToken,
                    'expires_at' => $this->tokenExpiration
                ];
            }

            Log::error('PagoFácil login falló', ['response' => $data]);
            return ['success' => false, 'error' => $data['message'] ?? 'Error de autenticación'];
        } catch (\Exception $e) {
            Log::error('PagoFácil login exception', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Generar QR de pago
     */
    public function generateQr($inscripcionId, $clientName, $email, $phoneNumber, $amount = 0.10)
    {
        // En modo prueba, usar monto fijo desde .env para evitar pagos reales
        if ($this->testAmount !== null) {
            $amount = (float) $this->testAmount;
        }

        // Autenticar si no hay token o expiró
        if (!$this->accessToken || now() >= $this->tokenExpiration) {
            $loginResult = $this->login();
            if (!$loginResult['success']) {
                return $loginResult;
            }
        }

        $paymentNumber = "INS-{$inscripcionId}-" . time();

        try {
            $body = [
                'paymentMethod' => (int) $this->paymentMethod,
                'clientName' => $clientName,
                'documentType' => 1,
                'documentId' => '123456',
                'phoneNumber' => $phoneNumber,
                'email' => $email,
                'paymentNumber' => $paymentNumber,
                'amount' => $amount,
                'currency' => 2, // BOB
                'clientCode' => $this->clientCode,
                'callbackUrl' => $this->callbackUrl,
                'orderDetail' => [
                    [
                        'serial' => 1,
                        'product' => 'Pago Consulta Veterinaria',
                        'quantity' => 1,
                        'price' => $amount,
                        'discount' => 0,
                        'total' => $amount
                    ]
                ]
            ];

            if ($this->commerceId) {
                $body['commerceId'] = $this->commerceId;
            }

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->accessToken}",
                'Content-Type' => 'application/json',
                'tctokenservice' => $this->tokenService,
                'tctokensecret' => $this->tokenSecret,
            ])->post("{$this->apiUrl}/generate-qr", $body);

            $data = $response->json();

            if ($data['error'] === 0 && isset($data['values'])) {
                Log::info('QR generado exitosamente', [
                    'transaction_id' => $data['values']['transactionId'],
                    'payment_number' => $paymentNumber
                ]);

                return [
                    'success' => true,
                    'qr_base64' => $data['values']['qrBase64'],
                    'transaction_id' => $data['values']['transactionId'],
                    'payment_number' => $paymentNumber,
                    'status' => $data['values']['status'],
                    'expiration_date' => $data['values']['expirationDate'] ?? null
                ];
            }

            Log::error('Error al generar QR', ['response' => $data]);
            return ['success' => false, 'error' => $data['message'] ?? 'Error generando QR'];
        } catch (\Exception $e) {
            Log::error('Excepción generando QR', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Verificar callback de PagoFácil
     */
    public function verifyCallback($callbackData)
    {
        Log::info('Callback recibido de PagoFácil', $callbackData);

        $pedidoId = $callbackData['PedidoID'] ?? null;
        $estado = $callbackData['Estado'] ?? null;
        $fecha = $callbackData['Fecha'] ?? null;
        $hora = $callbackData['Hora'] ?? null;
        $metodoPago = $callbackData['MetodoPago'] ?? null;

        if (!$pedidoId || !$estado) {
            return [
                'success' => false,
                'error' => 'Datos incompletos en callback'
            ];
        }

        // Estado 2 = Pagado
        if ($estado == 2) {
            return [
                'success' => true,
                'payment_number' => $pedidoId,
                'status' => 'paid',
                'date' => $fecha,
                'time' => $hora,
                'method' => $metodoPago
            ];
        }

        // Estado 5 = En revisión
        if ($estado == 5) {
            return [
                'success' => false,
                'status' => 'review',
                'payment_number' => $pedidoId
            ];
        }

        return [
            'success' => false,
            'status' => 'pending',
            'payment_number' => $pedidoId
        ];
    }

    /**
     * Respuesta estándar para callback (requerida por PagoFácil)
     */
    public function getCallbackResponse()
    {
        return [
            'error' => 0,
            'status' => 1,
            'message' => 'Callback recibido correctamente',
            'values' => true
        ];
    }
}
