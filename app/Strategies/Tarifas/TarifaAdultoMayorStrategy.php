<?php

namespace App\Strategies\Tarifas;

use App\Contracts\Strategies\Tarifas\TarifaStrategyInterface;
use App\Models\Tarjeta;
class TarifaAdultoMayorStrategy implements TarifaStrategyInterface
{
    /**
     * Create a new class instance.
     */
    public function calcularTarifa(Tarjeta $tarjeta): float
    {
        // Implementación específica para tarifa general
        return 1; // Ejemplo de tarifa estándar
    }
}
