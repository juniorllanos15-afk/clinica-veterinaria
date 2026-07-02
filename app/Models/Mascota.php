<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Mascota extends Model
{
    protected $table = 'mascota';
    protected $fillable = ['nombre', 'especie', 'raza', 'fecha_nacimiento', 'sexo', 'color', 'peso', 'estado', 'dueno_id'];

    protected $casts = [
        'peso' => 'decimal:2',
        'fecha_nacimiento' => 'date',
    ];

    public function dueno()
    {
        return $this->belongsTo(Usuario::class, 'dueno_id');
    }

    public function consultas()
    {
        return $this->hasMany(Consulta::class, 'mascota_id');
    }

    public function calcularEdad()
    {
        return $this->fecha_nacimiento?->age;
    }
}
