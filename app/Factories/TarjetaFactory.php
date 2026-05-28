<?php
// app/Factories/TarjetaFactory.php  ← carpeta correcta

namespace App\Factories;

use App\Contracts\TarjetaInterface;
use App\Models\Tarjeta;
use App\Models\TarjetaGeneral;
use App\Models\TarjetaEstudiante;
use App\Models\TarjetaAdultoMayor;
use App\Models\TarjetaTurista;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;

class TarjetaFactory
{
    public static function crear(string $tipo, array $datosBase, array $datosEspecificos): TarjetaInterface
    {
        return DB::transaction(function () use ($tipo, $datosBase, $datosEspecificos) {

            $tarjeta = Tarjeta::create([
                'saldo_actual' => $datosBase['saldo_inicial'] ?? 0,
                'estado'       => 'Activa',
                'tipo'         => strtoupper($tipo),
                'id_titular'   => $datosBase['id_titular'],
            ]);

            $id = $tarjeta->id_tarjeta; // ✅ usa la PK correcta

            match(strtoupper($tipo)) {
                'ESTUDIANTE' => TarjetaEstudiante::create([
                    'id_tarjeta_estudiante' => $id,
                    'institucion_educativa' => $datosEspecificos['institucion_educativa'],
                    'vigencia_estudiante'   => $datosEspecificos['vigencia_estudiante'],
                ]),
                'ADULTO_MAYOR' => TarjetaAdultoMayor::create([
                    'id_tarjeta_adulto_mayor' => $id,
                    'folio_inapam'            => $datosEspecificos['folio_inapam'],
                ]),
                'TURISTA' => TarjetaTurista::create([
                    'id_tarjeta_turista'    => $id,
                    'fecha_vigencia_turista' => $datosEspecificos['fecha_vigencia_turista'],
                ]),
                'GENERAL' => TarjetaGeneral::create([
                    'id_tarjeta_general' => $id,
                ]),
                default => throw new InvalidArgumentException("Tipo no soportado: $tipo"),
            };

            return self::fromModel($tarjeta); // ✅ self:: no TarjetaFactory::
        });
    }

    public static function fromModel(Tarjeta $tarjeta): TarjetaInterface
    {
        $id = $tarjeta->id_tarjeta;

        return match($tarjeta->tipo) {
            'GENERAL'      => TarjetaGeneral::findOrFail($id),
            'ESTUDIANTE'   => TarjetaEstudiante::findOrFail($id),
            'ADULTO_MAYOR' => TarjetaAdultoMayor::findOrFail($id),
            'TURISTA'      => TarjetaTurista::findOrFail($id),
            default        => throw new \InvalidArgumentException("Tipo desconocido: {$tarjeta->tipo}"),
        };
    }
}