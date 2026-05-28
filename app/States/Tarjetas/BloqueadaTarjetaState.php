<?php

namespace App\States\Tarjetas;

use App\Contracts\States\Tarjetas\TarjetaStateInterface;
use App\Models\Tarjeta;
use Exception;

class BloqueadaTarjetaState implements TarjetaStateInterface
{
    public function pagar(Tarjeta $tarjeta, float $monto):void
    {
        throw new Exception("No puede pagar con una tarjeta bloqueada.");
    }

    public function recargar(Tarjeta $tarjeta, float $monto):void
    {
        $tarjeta->increment('saldo_actual',$monto);
    }
}
