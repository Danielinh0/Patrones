<?php

namespace App\Strategies\Tarifas;

use App\Contracts\Strategies\Tarifas\TarifaStrategyInterface;
use App\Models\Tarjeta;

class TarifaGeneralStrategy implements TarifaStrategyInterface
{

    public function calcularTarifa(Tarjeta $tarjeta): float
    {
        // Implementación específica para tarifa general
        return 10; // Ejemplo de tarifa estándar
    }
}
