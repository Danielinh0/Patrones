<?php

use Livewire\Component;
use App\Models\Titular;
use App\Models\Tarjeta;
use App\Factories\TarjetaFactory;
use Illuminate\Support\Facades\DB;

new class extends Component
{
    // Titular data selection
    public $useExistingTitular = true;
    public $selectedTitularId = '';
    public $titulares = [];

    // New Titular creation data
    public $nombre = '';
    public $curp = '';
    public $edad = '';

    // Card data
    public $tipo = 'GENERAL';
    public $saldoInicial = 50;

    // Specific type data
    public $institucionEducativa = '';
    public $vigenciaEstudiante = '';
    public $folioInapam = '';
    public $fechaVigenciaTurista = '';

    // Status and emitted card reference
    public $emittedCard = null;
    public $errorMsg = null;
    public $successMsg = null;

    // Previous card status and selections locks
    public ?Tarjeta $tarjetaAnterior = null;
    public array $disabledTypes = [
        'GENERAL' => false,
        'ESTUDIANTE' => false,
        'ADULTO_MAYOR' => false,
        'TURISTA' => false
    ];

    public function mount()
    {
        $this->loadTitulares();
        $this->vigenciaEstudiante = now()->addYear()->format('Y-m-d');
        $this->fechaVigenciaTurista = now()->addDays(7)->format('Y-m-d');
        $this->checkPreviousCard();
    }

    public function loadTitulares()
    {
        $this->titulares = Titular::orderBy('nombre')->get();
        if ($this->titulares->isNotEmpty() && empty($this->selectedTitularId)) {
            $this->selectedTitularId = $this->titulares->first()->id_titular;
        }
    }

    public function updatedSelectedTitularId()
    {
        $this->checkPreviousCard();
    }

    public function updatedUseExistingTitular()
    {
        $this->checkPreviousCard();
    }

    public function checkPreviousCard()
    {
        // Reset locks
        $this->disabledTypes = [
            'GENERAL' => false,
            'ESTUDIANTE' => false,
            'ADULTO_MAYOR' => false,
            'TURISTA' => false
        ];
        $this->tarjetaAnterior = null;

        if ($this->useExistingTitular && $this->selectedTitularId) {
            // Buscamos la tarjeta más reciente de este titular
            $this->tarjetaAnterior = Tarjeta::where('id_titular', $this->selectedTitularId)->latest()->first();

            if ($this->tarjetaAnterior) {
                $tipoAnterior = strtoupper($this->tarjetaAnterior->tipo);
                
                if ($tipoAnterior === 'TURISTA') {
                    // Si era turista, sólo puede elegir turista
                    $this->disabledTypes['GENERAL'] = true;
                    $this->disabledTypes['ESTUDIANTE'] = true;
                    $this->disabledTypes['ADULTO_MAYOR'] = true;
                    $this->tipo = 'TURISTA';
                } elseif ($tipoAnterior === 'ESTUDIANTE') {
                    // Si era estudiante, sólo general y estudiante
                    $this->disabledTypes['ADULTO_MAYOR'] = true;
                    $this->disabledTypes['TURISTA'] = true;
                    $this->tipo = 'ESTUDIANTE';
                } elseif ($tipoAnterior === 'ADULTO_MAYOR') {
                    // Si era adulto mayor, sólo adulto mayor y general
                    $this->disabledTypes['ESTUDIANTE'] = true;
                    $this->disabledTypes['TURISTA'] = true;
                    $this->tipo = 'ADULTO_MAYOR';
                } else {
                    // Si era general, le aparecen todas (no se bloquea nada)
                    $this->tipo = 'GENERAL';
                }
            } else {
                $this->tipo = 'GENERAL';
            }
        } else {
            $this->tipo = 'GENERAL';
        }
    }

    public function setTipo($tipo)
    {
        if (!$this->disabledTypes[strtoupper($tipo)]) {
            $this->tipo = $tipo;
            $this->errorMsg = null;
        }
    }

    public function emitir()
    {
        $this->errorMsg = null;
        $this->successMsg = null;

        // 1. Validation Rules
        $rules = [
            'tipo' => 'required|in:GENERAL,ESTUDIANTE,ADULTO_MAYOR,TURISTA',
            'saldoInicial' => 'required|numeric|min:0|max:10000',
        ];

        if ($this->useExistingTitular) {
            $rules['selectedTitularId'] = 'required|exists:titular,id_titular';
        } else {
            $rules['nombre'] = 'required|string|min:3|max:255';
            $rules['curp'] = 'required|string|size:18';
            $rules['edad'] = 'required|integer|min:1|max:120';
        }

        // Conditional specific validations
        if ($this->tipo === 'ESTUDIANTE') {
            $rules['institucionEducativa'] = 'required|string|min:3|max:255';
            $rules['vigenciaEstudiante'] = 'required|date|after_or_equal:today';
        } elseif ($this->tipo === 'ADULTO_MAYOR') {
            $rules['folioInapam'] = 'required|string|min:3|max:255';
        } elseif ($this->tipo === 'TURISTA') {
            $rules['fechaVigenciaTurista'] = 'required|date|after_or_equal:today';
        }

        $validated = $this->validate($rules);

        // Additional CURP validations for new titular
        if (!$this->useExistingTitular) {
            $curpUpper = strtoupper($this->curp);
            $exists = Titular::where('curp', $curpUpper)->exists();
            if ($exists) {
                $this->errorMsg = "El CURP ingresado ya está registrado con otro titular.";
                return;
            }
        }

        try {
            DB::transaction(function() {
                // 2. Resolve/Create Titular
                if ($this->useExistingTitular) {
                    $idTitular = $this->selectedTitularId;
                } else {
                    $titular = Titular::create([
                        'nombre' => $this->nombre,
                        'curp' => strtoupper($this->curp),
                        'edad' => (int)$this->edad,
                    ]);
                    $idTitular = $titular->id_titular;
                }

                // 3. Build parameters for the Factory
                $datosBase = [
                    'saldo_inicial' => (float)$this->saldoInicial,
                    'id_titular' => $idTitular
                ];

                $datosEspecificos = [];
                if ($this->tipo === 'ESTUDIANTE') {
                    $datosEspecificos = [
                        'institucion_educativa' => $this->institucionEducativa,
                        'vigencia_estudiante' => $this->vigenciaEstudiante
                    ];
                } elseif ($this->tipo === 'ADULTO_MAYOR') {
                    $datosEspecificos = [
                        'folio_inapam' => $this->folioInapam
                    ];
                } elseif ($this->tipo === 'TURISTA') {
                    $datosEspecificos = [
                        'fecha_vigencia_turista' => $this->fechaVigenciaTurista
                    ];
                }

                // 4. Call the pattern (Factory) to emit the card
                $tarjetaInterface = TarjetaFactory::crear($this->tipo, $datosBase, $datosEspecificos);
                
                // Get the ID of the newly created card
                $idTarjeta = $tarjetaInterface->id_tarjeta_general 
                    ?? $tarjetaInterface->id_tarjeta_estudiante 
                    ?? $tarjetaInterface->id_tarjeta_adulto_mayor 
                    ?? $tarjetaInterface->id_tarjeta_turista 
                    ?? $tarjetaInterface->id_tarjeta;
                
                if (!$idTarjeta && isset($tarjetaInterface->id_tarjeta)) {
                    $idTarjeta = $tarjetaInterface->id_tarjeta;
                }

                $this->emittedCard = Tarjeta::with('titular')->find($idTarjeta);
                $this->successMsg = "Tarjeta emitida con éxito utilizando la fábrica.";
                
                // Reset form fields
                $this->reset(['nombre', 'curp', 'edad', 'institucionEducativa', 'folioInapam']);
                $this->loadTitulares();
                $this->checkPreviousCard();
            });
        } catch (\Exception $e) {
            $this->errorMsg = "Error al emitir tarjeta: " . $e->getMessage();
        }
    }

    public function resetEmission()
    {
        $this->emittedCard = null;
        $this->successMsg = null;
        $this->errorMsg = null;
        $this->saldoInicial = 50;
        $this->loadTitulares();
        $this->checkPreviousCard();
    }
};
?>

<div class="max-w-3xl mx-auto">
    @if($emittedCard)
        <!-- Pantalla de Éxito / Recibo de Tarjeta Emitida -->
        <div class="bg-white border border-outline-variant rounded-xl shadow-lg p-lg text-center flex flex-col items-center gap-lg">
            <div class="bg-secondary-container text-on-secondary-container p-md rounded-full">
                <span class="material-symbols-outlined text-[48px]">check_circle</span>
            </div>
            
            <div>
                <h2 class="font-headline-lg text-primary">¡Tarjeta Emitida Exitosamente!</h2>
                <p class="text-on-surface-variant text-body-sm mt-xs">La nueva tarjeta de transporte ha sido registrada y vinculada en el sistema mediante el patrón Factory.</p>
            </div>

            <!-- Representación Visual de la Tarjeta Recién Creada -->
            <div class="w-full max-w-md">
                <div class="bg-transit-card rounded-xl p-lg text-white shadow-md text-left relative overflow-hidden group min-h-[180px] flex flex-col justify-between">
                    <div class="absolute -right-16 -top-16 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl"></div>
                    
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-label-caps text-label-caps text-blue-200 mb-xs">SALDO INICIAL</p>
                            <h1 class="font-numeric-display text-[36px] leading-none">${{ number_format($emittedCard->saldo_actual, 2) }}</h1>
                        </div>
                        
                        <div class="bg-white/10 text-white px-md py-xs rounded-full border border-white/20">
                            <span class="font-label-caps text-label-caps tracking-wider">{{ $emittedCard->tipo }}</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-end mt-md">
                        <div class="flex flex-col gap-xs">
                            <p class="font-label-caps text-label-caps text-blue-200">TITULAR</p>
                            <p class="font-medium text-lg">{{ $emittedCard->titular->nombre }}</p>
                        </div>
                        <div class="flex flex-col gap-xs text-right">
                            <p class="font-label-caps text-label-caps text-blue-200">NÚMERO DE TARJETA</p>
                            <p class="font-body-sm tracking-widest">#{{ $emittedCard->id_tarjeta }} ({{ $emittedCard->estado }})</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detalles Técnicos -->
            <div class="w-full text-left bg-surface-container-low p-md rounded-xl border border-outline-variant text-body-sm space-y-xs text-on-surface">
                <p><strong>ID de Tarjeta:</strong> #{{ $emittedCard->id_tarjeta }}</p>
                <p><strong>Titular:</strong> {{ $emittedCard->titular->nombre }} (CURP: {{ $emittedCard->titular->curp }}, Edad: {{ $emittedCard->titular->curp ? $emittedCard->titular->edad : 'N/A' }} años)</p>
                <p><strong>Tipo de Tarjeta:</strong> {{ $emittedCard->tipo }}</p>
                @if($emittedCard->tipo === 'ESTUDIANTE' && $emittedCard->tarjetaEstudiante)
                    <p><strong>Escuela:</strong> {{ $emittedCard->tarjetaEstudiante->institucion_educativa }}</p>
                    <p><strong>Vigencia:</strong> {{ $emittedCard->tarjetaEstudiante->vigencia_estudiante }}</p>
                @elseif($emittedCard->tipo === 'ADULTO_MAYOR' && $emittedCard->tarjetaAdultoMayor)
                    <p><strong>Folio INAPAM:</strong> {{ $emittedCard->tarjetaAdultoMayor->folio_inapam }}</p>
                @elseif($emittedCard->tipo === 'TURISTA' && $emittedCard->tarjetaTurista)
                    <p><strong>Vigencia de Turista:</strong> {{ $emittedCard->tarjetaTurista->fecha_vigencia_turista }}</p>
                @endif
                <p><strong>Estado Inicial:</strong> {{ $emittedCard->estado }}</p>
            </div>

            <div class="flex gap-md w-full max-w-md pt-sm">
                <button wire:click="resetEmission" class="w-1/2 border border-outline text-on-surface py-md rounded-lg hover:bg-surface-container-low transition-all cursor-pointer font-medium">
                    Emitir Otra Tarjeta
                </button>
                <a href="{{ route('show') }}" class="w-1/2 bg-primary text-white font-bold py-md rounded-lg text-center shadow-sm hover:bg-primary-container transition-all flex items-center justify-center">
                    Consultar Saldo
                </a>
            </div>
        </div>
    @else
        <!-- Formulario de Emisión -->
        <div class="bg-white border border-outline-variant rounded-xl shadow-sm p-lg">
            <div class="border-b border-outline-variant pb-md mb-lg">
                <h1 class="font-headline-lg text-primary">Emitir Nueva Tarjeta</h1>
                <p class="text-on-surface-variant text-body-sm mt-xs">Ingresa los datos para registrar un titular y generar una tarjeta de movilidad integrada.</p>
            </div>

            <!-- Error Global -->
            @if($errorMsg)
                <div class="bg-error-container text-on-error-container p-md rounded-lg text-body-sm flex items-start gap-sm mb-lg">
                    <span class="material-symbols-outlined text-error text-[18px]">info</span>
                    <span class="font-medium">{{ $errorMsg }}</span>
                </div>
            @endif

            <form wire:submit.prevent="emitir" class="space-y-xl">
                
                <!-- 2. DATOS DEL TITULAR (Subido para definir las restricciones antes) -->
                <div class="space-y-sm">
                    <div class="flex justify-between items-center border-b border-outline-variant pb-xs">
                        <h3 class="font-label-caps text-label-caps text-on-surface-variant tracking-wider">1. DATOS DEL TITULAR</h3>
                        
                        <!-- Toggle Titular Existente o Nuevo -->
                        <div class="flex items-center gap-sm bg-surface-container-low p-[3px] rounded-lg border border-outline-variant">
                            <button type="button" wire:click="$set('useExistingTitular', true)" class="px-sm py-xs text-[12px] font-bold rounded-md transition-all cursor-pointer {{ $useExistingTitular ? 'bg-white text-primary shadow-xs' : 'text-on-surface-variant hover:text-on-surface' }}">
                                Existente
                            </button>
                            <button type="button" wire:click="$set('useExistingTitular', false)" class="px-sm py-xs text-[12px] font-bold rounded-md transition-all cursor-pointer {{ !$useExistingTitular ? 'bg-white text-primary shadow-xs' : 'text-on-surface-variant hover:text-on-surface' }}">
                                Nuevo Titular
                            </button>
                        </div>
                    </div>

                    @if($useExistingTitular)
                        <!-- Seleccionar Titular Existente -->
                        <div class="space-y-sm pt-sm">
                            <label class="font-label-caps text-label-caps block text-on-surface-variant">SELECCIONAR TITULAR</label>
                            <select wire:model.live="selectedTitularId" class="w-full border border-outline rounded-lg p-md focus:ring-2 focus:ring-primary outline-none bg-white text-on-surface font-medium">
                                @forelse($titulares as $tit)
                                    <option value="{{ $tit->id_titular }}">
                                        {{ $tit->nombre }} (CURP: {{ $tit->curp }}, {{ $tit->edad }} años)
                                    </option>
                                @empty
                                    <option value="">No hay titulares registrados</option>
                                @endforelse
                            </select>
                            @error('selectedTitularId') <span class="text-error text-xs">{{ $message }}</span> @enderror

                            <!-- Información de Tarjeta Anterior y Restricciones -->
                            @if($tarjetaAnterior)
                                <div class="bg-surface-container-low border border-outline-variant p-md rounded-lg flex items-center justify-between mt-sm text-body-sm text-on-surface">
                                    <div class="flex items-center gap-sm">
                                        <span class="material-symbols-outlined text-primary text-[20px]">credit_card</span>
                                        <span>
                                            <strong>Tarjeta anterior:</strong> 
                                            <span class="font-bold text-primary">{{ $tarjetaAnterior->tipo }}</span> 
                                            (ID: #{{ $tarjetaAnterior->id_tarjeta }} - Estado: <span class="capitalize">{{ $tarjetaAnterior->estado }}</span>)
                                        </span>
                                    </div>
                                    <span class="bg-primary-fixed text-primary px-sm py-[2px] rounded text-[11px] font-bold uppercase tracking-wider">
                                        Restricciones Activas
                                    </span>
                                </div>
                            @else
                                <div class="bg-surface-container-low border border-outline-variant p-md rounded-lg flex items-center gap-sm mt-sm text-body-sm text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[20px]">info</span>
                                    <span>No tiene tarjetas previas. Todos los tipos de tarjeta están disponibles.</span>
                                </div>
                            @endif
                        </div>
                    @else
                        <!-- Registrar Nuevo Titular -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-md pt-sm">
                            <div class="space-y-sm">
                                <label class="font-label-caps text-label-caps block text-on-surface-variant">NOMBRE COMPLETO</label>
                                <input wire:model="nombre" class="w-full border border-outline rounded-lg p-md focus:ring-2 focus:ring-primary outline-none bg-white text-on-surface" placeholder="Ej. Juan Pérez" type="text">
                                @error('nombre') <span class="text-error text-xs block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-sm">
                                <label class="font-label-caps text-label-caps block text-on-surface-variant">CURP (18 dígitos)</label>
                                <input wire:model="curp" class="w-full border border-outline rounded-lg p-md focus:ring-2 focus:ring-primary outline-none bg-white text-on-surface uppercase" placeholder="ABCD123456HDFXXN01" type="text" maxlength="18">
                                @error('curp') <span class="text-error text-xs block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-sm">
                                <label class="font-label-caps text-label-caps block text-on-surface-variant">EDAD (Años)</label>
                                <input wire:model="edad" class="w-full border border-outline rounded-lg p-md focus:ring-2 focus:ring-primary outline-none bg-white text-on-surface" placeholder="Ej. 25" type="number">
                                @error('edad') <span class="text-error text-xs block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endif
                </div>

                <!-- 1. SELECCIONAR TIPO DE TARJETA -->
                <div class="space-y-sm">
                    <h3 class="font-label-caps text-label-caps text-on-surface-variant tracking-wider">2. SELECCIONAR TIPO DE TARJETA</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-md">
                        <!-- GENERAL -->
                        <button type="button" wire:click="setTipo('GENERAL')" 
                            @if($disabledTypes['GENERAL']) disabled @endif
                            class="border-2 {{ $tipo === 'GENERAL' ? 'border-primary bg-primary-fixed text-primary font-bold' : 'border-outline-variant hover:bg-surface-container-low bg-white text-on-surface' }} {{ $disabledTypes['GENERAL'] ? 'opacity-40 cursor-not-allowed bg-surface-container' : 'cursor-pointer' }} p-md rounded-xl text-center flex flex-col items-center gap-sm transition-all">
                            <span class="material-symbols-outlined text-[32px]">credit_card</span>
                            <span class="font-bold text-body-lg">General</span>
                            <span class="text-xs text-on-surface-variant">Tarifa: $10.00</span>
                        </button>
                        <!-- ESTUDIANTE -->
                        <button type="button" wire:click="setTipo('ESTUDIANTE')" 
                            @if($disabledTypes['ESTUDIANTE']) disabled @endif
                            class="border-2 {{ $tipo === 'ESTUDIANTE' ? 'border-primary bg-primary-fixed text-primary font-bold' : 'border-outline-variant hover:bg-surface-container-low bg-white text-on-surface' }} {{ $disabledTypes['ESTUDIANTE'] ? 'opacity-40 cursor-not-allowed bg-surface-container' : 'cursor-pointer' }} p-md rounded-xl text-center flex flex-col items-center gap-sm transition-all">
                            <span class="material-symbols-outlined text-[32px]">school</span>
                            <span class="font-bold text-body-lg">Estudiante</span>
                            <span class="text-xs text-on-surface-variant">Tarifa: $5.00</span>
                        </button>
                        <!-- ADULTO MAYOR -->
                        <button type="button" wire:click="setTipo('ADULTO_MAYOR')" 
                            @if($disabledTypes['ADULTO_MAYOR']) disabled @endif
                            class="border-2 {{ $tipo === 'ADULTO_MAYOR' ? 'border-primary bg-primary-fixed text-primary font-bold' : 'border-outline-variant hover:bg-surface-container-low bg-white text-on-surface' }} {{ $disabledTypes['ADULTO_MAYOR'] ? 'opacity-40 cursor-not-allowed bg-surface-container' : 'cursor-pointer' }} p-md rounded-xl text-center flex flex-col items-center gap-sm transition-all">
                            <span class="material-symbols-outlined text-[32px]">elderly</span>
                            <span class="font-bold text-body-lg">Adulto Mayor</span>
                            <span class="text-xs text-on-surface-variant">Tarifa: $1.00</span>
                        </button>
                        <!-- TURISTA -->
                        <button type="button" wire:click="setTipo('TURISTA')" 
                            @if($disabledTypes['TURISTA']) disabled @endif
                            class="border-2 {{ $tipo === 'TURISTA' ? 'border-primary bg-primary-fixed text-primary font-bold' : 'border-outline-variant hover:bg-surface-container-low bg-white text-on-surface' }} {{ $disabledTypes['TURISTA'] ? 'opacity-40 cursor-not-allowed bg-surface-container' : 'cursor-pointer' }} p-md rounded-xl text-center flex flex-col items-center gap-sm transition-all">
                            <span class="material-symbols-outlined text-[32px]">travel_explore</span>
                            <span class="font-bold text-body-lg">Turista</span>
                            <span class="text-xs text-on-surface-variant">Tarifa: $15.00</span>
                        </button>
                    </div>
                </div>

                <!-- 3. CONFIGURACIÓN Y DATOS ESPECÍFICOS -->
                <div class="space-y-sm">
                    <h3 class="font-label-caps text-label-caps text-on-surface-variant tracking-wider border-b border-outline-variant pb-xs">3. DETALLES Y CONFIGURACIÓN ESPECÍFICA</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-md pt-sm">
                        <!-- Saldo Inicial -->
                        <div class="space-y-sm">
                            <label class="font-label-caps text-label-caps block text-on-surface-variant">SALDO INICIAL DE APERTURA</label>
                            <input wire:model="saldoInicial" class="w-full border border-outline rounded-lg p-md focus:ring-2 focus:ring-primary outline-none bg-white text-on-surface font-bold" type="number" min="0" max="1000">
                            @error('saldoInicial') <span class="text-error text-xs block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Campos dinámicos por tipo -->
                        @if($tipo === 'ESTUDIANTE')
                            <div class="space-y-sm">
                                <label class="font-label-caps text-label-caps block text-on-surface-variant">INSTITUCIÓN EDUCATIVA</label>
                                <input wire:model="institucionEducativa" class="w-full border border-outline rounded-lg p-md focus:ring-2 focus:ring-primary outline-none bg-white text-on-surface" placeholder="Nombre de la escuela" type="text">
                                @error('institucionEducativa') <span class="text-error text-xs block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-sm">
                                <label class="font-label-caps text-label-caps block text-on-surface-variant">VIGENCIA DE ESTUDIANTE</label>
                                <input wire:model="vigenciaEstudiante" class="w-full border border-outline rounded-lg p-md focus:ring-2 focus:ring-primary outline-none bg-white text-on-surface" type="date">
                                @error('vigenciaEstudiante') <span class="text-error text-xs block">{{ $message }}</span> @enderror
                            </div>
                        @elseif($tipo === 'ADULTO_MAYOR')
                            <div class="space-y-sm col-span-1">
                                <label class="font-label-caps text-label-caps block text-on-surface-variant">FOLIO DE CREDENCIAL INAPAM</label>
                                <input wire:model="folioInapam" class="w-full border border-outline rounded-lg p-md focus:ring-2 focus:ring-primary outline-none bg-white text-on-surface" placeholder="Ej. INAPAM-123456" type="text">
                                @error('folioInapam') <span class="text-error text-xs block">{{ $message }}</span> @enderror
                            </div>
                        @elseif($tipo === 'TURISTA')
                            <div class="space-y-sm col-span-1">
                                <label class="font-label-caps text-label-caps block text-on-surface-variant">FECHA VIGENCIA TURISTA</label>
                                <input wire:model="fechaVigenciaTurista" class="w-full border border-outline rounded-lg p-md focus:ring-2 focus:ring-primary outline-none bg-white text-on-surface" type="date">
                                @error('fechaVigenciaTurista') <span class="text-error text-xs block">{{ $message }}</span> @enderror
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Botón de Envío -->
                <div class="pt-md border-t border-outline-variant flex justify-end gap-md">
                    <a href="{{ route('show') }}" class="px-lg py-md border border-outline text-on-surface rounded-lg hover:bg-surface-container-low transition-all font-medium text-center">
                        Cancelar
                    </a>
                    <button type="submit" class="px-xl py-md bg-primary text-white font-bold rounded-lg hover:bg-primary-container shadow-sm active:scale-95 transition-transform cursor-pointer">
                        Emitir Tarjeta
                    </button>
                </div>

            </form>
        </div>
    @endif
</div>
