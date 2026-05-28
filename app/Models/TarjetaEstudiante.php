<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Contracts\TarjetaInterface;

#[Fillable(['id_tarjeta_estudiante', 'institucion_educativa', 'vigencia_estudiante'])]
class TarjetaEstudiante extends Model implements TarjetaInterface
{
    use HasFactory;

    protected $table = 'tarjeta_estudiante';
    protected $primaryKey = 'id_tarjeta_estudiante';

    public function tarjeta()
    {
        return $this->belongsTo(Tarjeta::class, 'id_tarjeta_estudiante', 'id_tarjeta');
    }


    //metodos de la interfaz TarjetaInterface
    public function calcularTarifa(): float
    {
        // Implementación específica para tarjeta de estudiante
        return 1.0; // Ejemplo de tarifa reducida
    }

     public function pagar(): float{
        // Implementación del método pagar para tarjeta de estudiante
        return $this->calcularTarifa(); // Paga la tarifa calculada
     }
    public function recargar(): float{
        // Implementación del método recargar para tarjeta de estudiante
        return 10.0; // Ejemplo de recarga mínima
    }
    public function getTipo(): string{
        return 'ESTUDIANTE';
    }
}
