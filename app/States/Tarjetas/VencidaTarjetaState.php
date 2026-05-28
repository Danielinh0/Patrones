<?php

namespace App\States\Tarjetas;

use App\Contracts\States\Tarjetas\TarjetaStateInterface;
use App\Models\Tarjeta;
use Exception;

class VencidaTarjetaState implements TarjetaStateInterface
{
    public function pagar(Tarjeta $tarjeta, float $monto):void
    {
        throw new Exception("No puede pagar con una tarjeta vencida.");
    }

    public function recargar(Tarjeta $tarjeta, float $monto):void
    {
        throw new Exception("No puede recargar una tarjeta vencida.");
    }
}
