<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable(['id_tarjeta_general'])]
class TarjetaGeneral extends Model
{
    use HasFactory;

    protected $table = 'tarjeta_general';
    protected $primaryKey = 'id_tarjeta_general';

    public function tarjeta()
    {
        return $this->belongsTo(Tarjeta::class, 'id_tarjeta_general', 'id_tarjeta');
    }

    
}
