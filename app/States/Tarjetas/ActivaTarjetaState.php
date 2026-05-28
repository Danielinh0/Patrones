<?php

namespace App\States\Tarjetas;

use App\Contracts\States\Tarjetas\TarjetaStateInterface;
use App\Models\Tarjeta;
use App\Contracts;
use Exception;

class ActivaTarjetaState implements TarjetaStateInterface
{
    public function pagar(Tarjeta $tarjeta, float $monto): void
    {
        if ($tarjeta->saldo < $monto ) {
            throw new Exception("Saldo insuficiente.");
        }
        $tarjeta->decrement('saldo_actual',$monto);
    }

    public function recargar(Tarjeta $tarjeta, float $monto):void
    {
        $tarjeta->increment('saldo_actual',$monto);
    }
}
