<?php

namespace App\Contracts;

use App\Models\Tarjeta;

interface TarjetaInterface
{
    public function getTipo(): string;
}