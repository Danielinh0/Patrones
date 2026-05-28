<?php

namespace App\Contracts;

interface TarjetaInterface
{
    public function calcularTarifa(): float;
    public function pagar(): float;
    public function recargar(): float;
    public function getTipo(): string;
}