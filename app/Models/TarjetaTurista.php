<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Contracts\TarjetaInterface;

#[Fillable(['id_tarjeta_turista', 'fecha_vigencia_turista'])]
class TarjetaTurista extends Model implements TarjetaInterface
{
    use HasFactory;
    protected $table = 'tarjeta_turista';
    protected $primaryKey = 'id_tarjeta_turista';

    public function tarjeta()
    {
        return $this->belongsTo(Tarjeta::class, 'id_tarjeta_turista', 'id_tarjeta');
    }

    //metodos de la interfaz TarjetaInterface
    public function getTipo(): string{
        return 'TURISTA';
    }
}
