<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'producto';
    protected $fillable = ['codigo_producto', 'nombre', 'descripcion', 'stock', 'precio', 'categoria_id', 'estado'];

    protected $casts = [
        'precio' => 'decimal:2',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function consultas()
    {
        return $this->belongsToMany(Consulta::class, 'consulta_producto', 'producto_id', 'consulta_id')
            ->withPivot(['cantidad', 'precio', 'subtotal'])
            ->withTimestamps();
    }
}
