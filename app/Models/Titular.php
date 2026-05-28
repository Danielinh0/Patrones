<?php

namespace App\Models;

use App\Models\Tarjeta;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
Use Illuminate\Foundation\Auth\User as Authenticatable;

#[Fillable(['nombre', 'edad','curp'])]
class Titular extends Model
{
    use HasFactory;

    protected $table = 'titular';
    protected $primaryKey = 'id_titular';

    public function tarjetas()
    {
        return $this->hasMany(Tarjeta::class, 'id_titular', 'id_titular');
    } 

}
