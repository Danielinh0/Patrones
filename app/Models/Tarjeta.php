<?php

namespace App\Models;

use App\Contracts\States\Tarjetas\TarjetaStateInterface;
use App\States\Tarjetas\ActivaTarjetaState;
use App\States\Tarjetas\BloqueadaTarjetaState;
use App\States\Tarjetas\VencidaTarjetaState;
use App\Strategies\Tarifas\TarifaAdultoMayorStrategy;
use App\Strategies\Tarifas\TarifaEstudianteStrategy;
use App\Strategies\Tarifas\TarifaGeneralStrategy;
use App\Strategies\Tarifas\TarifaTuristaStrategy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Strategies\Tarifas;
use App\Contracts\Strategies\Tarifas\TarifaStrategyInterface;


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

    public function getTarifaStrategy(): TarifaStrategyInterface
    {
        return match (strtolower($this->tipo)) {
            'estudiante' => new TarifaEstudianteStrategy() ,
            'adulto_mayor' => new TarifaAdultoMayorStrategy(),
            'turista' => new TarifaTuristaStrategy(),
            default => new TarifaGeneralStrategy(),
        };
    }

    public function getEstado() :TarjetaStateInterface
    {
        return match (strtolower($this->estado)) {
            'bloqueada' => new BloqueadaTarjetaState(),
            'vencida' => new VencidaTarjetaState(),
            default => new ActivaTarjetaState(),
        };
    }

    public function pagarViaje(float $monto):void{
        $this->getEstado()->pagar($this,$monto);
    }

    public function recargarSaldo(Float $monto):void
    {
        $this->getEstado()->recargar($this,$monto);
    }

    public function getSaldoAttribute()
    {
        return $this->saldo_actual;
    }
}

