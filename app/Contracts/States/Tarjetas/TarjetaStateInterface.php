<?php

namespace App\Contracts\States\Tarjetas;

use App\Models\Tarjeta;

interface TarjetaStateInterface
{
    public function pagar(Tarjeta $tarjeta, float $monto):void;
    public function recargar(Tarjeta $tarjeta, float $monto):void;
}
