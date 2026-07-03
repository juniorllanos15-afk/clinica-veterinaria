<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    protected $table = 'consulta';
    protected $fillable = [
        'fecha_consulta', 'motivo_consulta', 'diagnostico', 'tratamiento',
        'observaciones', 'estado', 'mascota_id', 'veterinario_id'
    ];

    protected $casts = [
        'fecha_consulta' => 'date:Y-m-d',
    ];

    public function mascota()
    {
        return $this->belongsTo(Mascota::class, 'mascota_id');
    }

    public function veterinario()
    {
        return $this->belongsTo(Usuario::class, 'veterinario_id');
    }

    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'consulta_servicio', 'consulta_id', 'servicio_id')
            ->withPivot(['cantidad', 'precio', 'subtotal'])
            ->withTimestamps();
    }

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'consulta_producto', 'consulta_id', 'producto_id')
            ->withPivot(['cantidad', 'precio', 'subtotal'])
            ->withTimestamps();
    }

    public function pago()
    {
        return $this->hasOne(Pago::class, 'consulta_id');
    }

    public function totalServicios()
    {
        return $this->servicios->sum(fn($s) => $s->pivot->subtotal);
    }

    public function totalProductos()
    {
        return $this->productos->sum(fn($p) => $p->pivot->subtotal);
    }

    public function totalGeneral()
    {
        return $this->totalServicios() + $this->totalProductos();
    }
}
