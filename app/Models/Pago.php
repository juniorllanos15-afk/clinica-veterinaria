<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pago';
    protected $fillable = ['consulta_id', 'tipo_pago', 'cantidad_cuotas', 'total', 'fecha_pago', 'estado', 'transaction_id', 'payment_number', 'estado_pago', 'qr_base64'];

    protected $casts = [
        'total' => 'decimal:2',
        'fecha_pago' => 'date:Y-m-d',
    ];

    public function consulta()
    {
        return $this->belongsTo(Consulta::class, 'consulta_id');
    }

    public function cuotas()
    {
        return $this->hasMany(PagoCuota::class, 'pago_id');
    }

    public function montoPagado()
    {
        return $this->cuotas()->where('estado', 'Pagado')->sum('monto');
    }

    public function saldoPendiente()
    {
        return $this->total - $this->montoPagado();
    }
}
