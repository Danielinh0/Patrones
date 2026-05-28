<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Contracts\TarjetaInterface;

#[Fillable(['id_tarjeta_adulto_mayor', 'folio_inapam'])]
class TarjetaAdultoMayor extends Model implements TarjetaInterface
{
    use HasFactory;

    protected $table = 'tarjeta_adulto_mayor';
    protected $primaryKey = 'id_tarjeta_adulto_mayor';

    public function tarjeta()
    {
        return $this->belongsTo(Tarjeta::class, 'id_tarjeta_adulto_mayor', 'id_tarjeta');
    }

    //metodos de la interfaz TarjetaInterface

    public function getTipo(): string{
        return 'ADULTO_MAYOR';
    }
}
