<?php

namespace App\Contracts\Strategies\Tarifas;

use App\Models\Tarjeta;

interface TarifaStrategyInterface
{
    public function calcularTarifa(Tarjeta $tarjeta): float;
}

