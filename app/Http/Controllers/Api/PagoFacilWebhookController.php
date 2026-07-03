<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use App\Models\PagoCuota;
use App\Services\PagoFacilService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PagoFacilWebhookController extends Controller
{
    protected $pagoFacilService;

    public function __construct(PagoFacilService $pagoFacilService)
    {
        $this->pagoFacilService = $pagoFacilService;
    }

    /**
     * Recibe callbacks de PagoFácil cuando cambia el estado de una transacción
     */
    public function handleCallback(Request $request)
    {
        try {
            Log::info('Callback PagoFácil recibido', [
                'payload' => $request->all(),
                'headers' => $request->headers->all(),
            ]);

            $result = $this->pagoFacilService->verifyCallback($request->all());

            if (!$result['success']) {
                Log::warning('Callback PagoFácil no válido', [
                    'message' => $result['message'],
                    'data' => $request->all(),
                ]);
                return $this->pagoFacilService->getCallbackResponse();
            }

            $paymentNumber = $result['payment_number'];

            // Buscar primero si es un pago de cuota
            $cuota = PagoCuota::where('payment_number', $paymentNumber)->first();

            if ($cuota) {
                $cuota->update([
                    'estado' => 'pagado',
                    'estado_pago' => 'paid',
                    'fecha_pago' => Carbon::today(),
                ]);

                Log::info('Cuota marcada como pagada', [
                    'cuota_id' => $cuota->id,
                    'payment_number' => $paymentNumber,
                ]);

                // Verificar si todas las cuotas del pago están pagadas
                $pago = $cuota->pago;
                $cuotasRestantes = $pago->cuotas()->where('estado', '!=', 'pagado')->count();

                if ($cuotasRestantes === 0) {
                    $pago->update([
                        'estado' => 'pagado',
                        'estado_pago' => 'paid',
                    ]);
                    Log::info('Pago marcado como pagado (todas las cuotas pagadas)', [
                        'pago_id' => $pago->id,
                    ]);
                }

                return $this->pagoFacilService->getCallbackResponse();
            }

            // Buscar si es un pago principal (sin cuotas)
            $pago = Pago::where('payment_number', $paymentNumber)->first();

            if ($pago) {
                $pago->update([
                    'estado' => 'pagado',
                    'estado_pago' => 'paid',
                ]);

                // Marcar todas sus cuotas como pagadas
                $pago->cuotas()->update([
                    'estado' => 'pagado',
                    'fecha_pago' => Carbon::today(),
                ]);

                Log::info('Pago y cuotas marcados como pagados', [
                    'pago_id' => $pago->id,
                    'payment_number' => $paymentNumber,
                ]);

                return $this->pagoFacilService->getCallbackResponse();
            }

            Log::error('No se encontró ni pago ni cuota con payment_number', [
                'payment_number' => $paymentNumber,
            ]);

            return $this->pagoFacilService->getCallbackResponse();

        } catch (\Exception $e) {
            Log::error('Error procesando callback PagoFácil', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payload' => $request->all(),
            ]);

            return $this->pagoFacilService->getCallbackResponse();
        }
    }
}
