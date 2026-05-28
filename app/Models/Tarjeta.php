<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;


#[Fillable(['saldo_actual', 'estado', 'tipo', 'id_titular'])]
class Tarjeta extends Model
{
    use HasFactory;

    protected $table = 'tarjeta';
    protected $primaryKey = 'id_tarjeta';

    public function titular()
    {
        return $this->belongsTo(Titular::class, 'id_titular', 'id_titular');
    }    

    public function transacciones()
    {
        return $this->hasMany(Transaccion::class, 'id_tarjeta', 'id_tarjeta');
    }

    public function tarjetaGeneral()
    {
        return $this->hasOne(TarjetaGeneral::class, 'id_tarjeta_general', 'id_tarjeta');
    }

    public function tarjetaEstudiante()
    {
        return $this->hasOne(TarjetaEstudiante::class, 'id_tarjeta_estudiante', 'id_tarjeta');
    }

    public function tarjetaAdultoMayor()
    {
        return $this->hasOne(TarjetaAdultoMayor::class, 'id_tarjeta_adulto_mayor', 'id_tarjeta');
    }

    public function tarjetaTurista()
    {
        return $this->hasOne(TarjetaTurista::class, 'id_tarjeta_turista', 'id_tarjeta');
    }
}
