<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Tarjeta;

new class extends Component
{
    public $tarjetas = [];
    public $tarjetaId = null;
    public ?Tarjeta $tarjeta = null;

    public function mount()
    {
        $this->tarjetas = Tarjeta::with('titular')->get();
        if ($this->tarjetas->isNotEmpty()) {
            $this->tarjetaId = $this->tarjetas->first()->id_tarjeta;
            $this->loadTarjeta();
        }
    }

    public function updatedTarjetaId()
    {
        $this->loadTarjeta();
    }

    public function loadTarjeta()
    {
        if ($this->tarjetaId) {
            $this->tarjeta = Tarjeta::with(['titular', 'transacciones.ruta'])->find($this->tarjetaId);
            $this->dispatch('tarjetaSelected', id: $this->tarjetaId);
        } else {
            $this->tarjeta = null;
        }
    }

    #[On('tarjetaUpdated')]
    public function reloadTarjeta()
    {
        if ($this->tarjetaId) {
            $this->tarjeta = Tarjeta::with(['titular', 'transacciones.ruta'])->find($this->tarjetaId);
        }
        $this->tarjetas = Tarjeta::with('titular')->get(); // Refresh list in case status/balance changes
    }
};
?>

<div class="flex flex-col gap-md">
    <!-- Selector de Tarjeta -->
    <div class="bg-white border border-outline-variant p-md rounded-xl shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-md">
        <div>
            <h2 class="font-headline-md text-headline-md text-primary">Consulta de Tarjetas</h2>
            <p class="font-body-sm text-on-surface-variant">Selecciona una tarjeta para ver detalles y realizar operaciones</p>
        </div>
        <div class="w-full md:w-80">
            <select wire:model.live="tarjetaId" class="w-full border border-outline rounded-lg p-md focus:ring-2 focus:ring-primary outline-none transition-all bg-white text-on-surface font-medium">
                <option value="">-- Seleccionar Tarjeta --</option>
                @foreach($tarjetas as $t)
                    <option value="{{ $t->id_tarjeta }}">
                        Tar. #{{ $t->id_tarjeta }} - {{ $t->titular->nombre }} ({{ $t->tipo }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    @if($tarjeta)
        <div class="grid grid-cols-1 md:grid-cols-12 gap-lg">
            <!-- Tarjeta Visual (Columna de 8) -->
            <div class="md:col-span-8">
                <div class="bg-transit-card rounded-xl p-lg text-white shadow-lg relative overflow-hidden group min-h-[180px] flex flex-col justify-between">
                    <div class="absolute -right-16 -top-16 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl group-hover:opacity-10 transition-opacity"></div>
                    
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-label-caps text-label-caps text-blue-200 mb-xs">SALDO ACTUAL</p>
                            <h1 class="font-numeric-display text-[48px] leading-none">${{ number_format($tarjeta->saldo_actual, 2) }}</h1>
                        </div>
                        
                        <div class="flex flex-col items-end gap-sm">
                            <!-- Alerta de Saldo Bajo -->
                            @if($tarjeta->saldo_actual < 20 && strtolower($tarjeta->estado) === 'activa')
                                <div class="bg-error-container text-on-error-container px-md py-xs rounded-full flex items-center gap-xs animate-pulse">
                                    <span class="material-symbols-outlined text-[16px]">warning</span>
                                    <span class="font-label-caps text-label-caps">SALDO BAJO</span>
                                </div>
                            @endif

                            <!-- Insignia de Tipo de Tarjeta -->
                            <div class="bg-white/10 text-white px-md py-xs rounded-full border border-white/20">
                                <span class="font-label-caps text-label-caps tracking-wider">{{ $tarjeta->tipo }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-end mt-md">
                        <div class="flex flex-col gap-xs">
                            <p class="font-label-caps text-label-caps text-blue-200">TITULAR</p>
                            <p class="font-medium text-lg">{{ $tarjeta->titular->nombre }}</p>
                        </div>
                        <div class="flex flex-col gap-xs text-right">
                            <p class="font-label-caps text-label-caps text-blue-200">NÚMERO DE TARJETA</p>
                            <p class="font-body-sm tracking-widest">**** **** **** {{ str_pad($tarjeta->id_tarjeta, 4, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detalles del Estado y Último Viaje (Columna de 4) -->
            <div class="md:col-span-4 flex flex-col gap-md">
                <!-- Tarjeta de Último Viaje -->
                <div class="bg-white border border-outline-variant p-md rounded-xl flex items-center gap-md shadow-sm">
                    <div class="bg-primary-fixed p-sm rounded-lg">
                        <span class="material-symbols-outlined text-primary">directions_bus</span>
                    </div>
                    <div>
                        <p class="font-label-caps text-label-caps text-on-surface-variant">ÚLTIMO VIAJE</p>
                        @php
                            $ultimoViaje = $tarjeta->transacciones()->where('tipo', 'VIAJE')->with('ruta')->latest()->first();
                        @endphp
                        <p class="font-headline-md text-headline-md text-primary">
                            {{ $ultimoViaje ? ($ultimoViaje->ruta ? $ultimoViaje->ruta->nombre : 'Viaje Registrado') : 'Sin viajes registrados' }}
                        </p>
                    </div>
                </div>

                <!-- Tarjeta de Estado -->
                <div class="bg-white border border-outline-variant p-md rounded-xl flex items-center gap-md shadow-sm">
                    @php
                        $estadoLower = strtolower($tarjeta->estado);
                        $isActiva = $estadoLower === 'activa';
                        $isBloqueada = $estadoLower === 'bloqueada';
                    @endphp
                    <div class="{{ $isActiva ? 'bg-secondary-fixed' : 'bg-error-container' }} p-sm rounded-lg">
                        <span class="material-symbols-outlined {{ $isActiva ? 'text-secondary' : 'text-error' }}">
                            {{ $isActiva ? 'verified_user' : ($isBloqueada ? 'block' : 'lock_clock') }}
                        </span>
                    </div>
                    <div>
                        <p class="font-label-caps text-label-caps text-on-surface-variant">ESTADO TARJETA</p>
                        <p class="font-headline-md text-headline-md {{ $isActiva ? 'text-secondary' : 'text-error' }} capitalize">
                            {{ $tarjeta->estado }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white border border-outline-variant p-xl rounded-xl shadow-sm text-center">
            <span class="material-symbols-outlined text-[48px] text-outline mb-md">credit_card</span>
            <p class="text-on-surface-variant font-medium">No se ha seleccionado ninguna tarjeta.</p>
            <p class="text-body-sm text-outline">Por favor selecciona una tarjeta en el menú superior para comenzar.</p>
        </div>
    @endif
</div>