<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    // Especifica la tabla asociada con el modelo
    protected $table = 'persona'; // Asegúrate de que el nombre es correcto

    // Campos que se pueden asignar masivamente
    protected $fillable = ['nombre', 'email', 'edad', 'sexo', 'imagen'];

    /**
     * @param string|null $value
     * @return string
     */
    public function getImagenAttribute($value)
    {
        return $value ? $value : 'storage/images/default.jpg';
    }
}
