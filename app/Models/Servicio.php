<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $table = 'servicio';
    protected $fillable = ['codigo_servicio', 'nombre', 'descripcion', 'categoria_id', 'duracion_estimada', 'precio', 'estado'];

    protected $casts = [
        'precio' => 'decimal:2',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function consultas()
    {
        return $this->belongsToMany(Consulta::class, 'consulta_servicio', 'servicio_id', 'consulta_id')
            ->withPivot(['cantidad', 'precio', 'subtotal'])
            ->withTimestamps();
    }
}
