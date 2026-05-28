<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable(['id_tarjeta_estudiante', 'institucion_educativa', 'vigencia_estudiante'])]
class TarjetaEstudiante extends Model
{
    use HasFactory;

    protected $table = 'tarjeta_estudiante';
    protected $primaryKey = 'id_tarjeta_estudiante';

    public function tarjeta()
    {
        return $this->belongsTo(Tarjeta::class, 'id_tarjeta_estudiante', 'id_tarjeta');
    }
}
