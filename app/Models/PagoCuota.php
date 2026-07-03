<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PagoCuota extends Model
{
    protected $table = 'pago_cuota';
    protected $fillable = ['pago_id', 'numero_cuota', 'monto', 'fecha_vencimiento', 'fecha_pago', 'estado', 'transaction_id', 'payment_number', 'estado_pago', 'qr_base64'];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_vencimiento' => 'date',
        'fecha_pago' => 'date',
    ];

    public function pago()
    {
        return $this->belongsTo(Pago::class, 'pago_id');
    }
}
