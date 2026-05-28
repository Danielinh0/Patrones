<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Tarjeta;
use App\Models\Ruta;
use App\Models\Transaccion;

new class extends Component
{
    public $tarjetaId = null;
    public ?Tarjeta $tarjeta = null;
    public $rutaId = null;
    public $rutas = [];
    public $fare = 0.0;
    public $isOpen = false;
    public $errorMsg = null;
    public $successMsg = null;

    public function mount()
    {
        $this->rutas = Ruta::all();
        if ($this->rutas->isNotEmpty()) {
            $this->rutaId = $this->rutas->first()->id_ruta;
        }
        $this->loadFare();
    }

    #[On('tarjetaSelected')]
    public function setTarjeta($id)
    {
        $this->tarjetaId = $id;
        $this->errorMsg = null;
        $this->successMsg = null;
        $this->loadFare();
    }

    #[On('tarjetaUpdated')]
    public function onTarjetaUpdated()
    {
        $this->loadFare();
    }

    public function loadFare()
    {
        if ($this->tarjetaId) {
            $this->tarjeta = Tarjeta::find($this->tarjetaId);
            if ($this->tarjeta) {
                // Selecciona la estrategia y calcula la tarifa
                $strategy = $this->tarjeta->getTarifaStrategy();
                $this->fare = $strategy->calcularTarifa($this->tarjeta);
            }
        } else {
            $this->tarjeta = null;
            $this->fare = 0.0;
        }
    }

    public function openModal()
    {
        if (!$this->tarjetaId) {
            session()->flash('warning', 'Por favor selecciona una tarjeta primero en el buscador superior.');
            $this->dispatch('showWarningAlert');
            return;
        }
        $this->isOpen = true;
        $this->errorMsg = null;
        $this->successMsg = null;
        $this->loadFare();
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->errorMsg = null;
        $this->successMsg = null;
    }

    public function pagarViaje()
    {
        if (!$this->tarjetaId || !$this->tarjeta) {
            $this->errorMsg = 'Debes seleccionar una tarjeta primero.';
            return;
        }

        if (!$this->rutaId) {
            $this->errorMsg = 'Debes seleccionar una ruta de viaje.';
            return;
        }

        try {
            // Llama a pagar viaje delegando al estado de la tarjeta (Patrón State)
            $this->tarjeta->pagarViaje((float)$this->fare);

            // Registrar transacción en la base de datos
            Transaccion::create([
                'monto' => $this->fare,
                'fecha' => now()->toDateString(),
                'tipo' => 'VIAJE',
                'id_tarjeta' => $this->tarjeta->id_tarjeta,
                'id_ruta' => $this->rutaId,
            ]);

            $this->successMsg = "¡Buen viaje! Se cobró una tarifa de $" . number_format($this->fare, 2) . " MXN.";
            $this->errorMsg = null;

            // Notifica cambios al resto de componentes
            $this->dispatch('tarjetaUpdated');
        } catch (\Exception $e) {
            $this->errorMsg = $e->getMessage();
            $this->successMsg = null;
        }
    }
};
?>

<div>
    <!-- Botón de Simular Pago -->
    <button wire:click="openModal" class="w-full bg-white border-2 border-primary text-primary p-lg rounded-xl flex items-center justify-between group hover:bg-surface-container-low transition-all active:scale-[0.98] cursor-pointer">
        <div class="flex items-center gap-md">
            <span class="material-symbols-outlined text-[32px] bg-surface-container p-md rounded-xl text-primary">route</span>
            <div class="text-left">
                <p class="font-headline-md text-headline-md">Simular Pago de Viaje</p>
                <p class="font-body-sm text-on-surface-variant">Calcula el costo de tu próximo trayecto</p>
            </div>
        </div>
        <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">chevron_right</span>
    </button>

    <!-- Modal de Simulación -->
    <div class="fixed inset-0 z-[100] {{ $isOpen ? '' : 'hidden' }}">
        <!-- Fondo oscuro -->
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
        
        <!-- Contenedor -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-lg">
            <div class="bg-white rounded-xl shadow-xl overflow-hidden border border-outline-variant">
                <div class="p-lg bg-surface-container border-b border-outline-variant flex justify-between items-center">
                    <h3 class="font-headline-md text-primary">Simular Viaje</h3>
                    <button class="material-symbols-outlined text-on-surface-variant hover:text-primary transition-colors cursor-pointer" wire:click="closeModal">close</button>
                </div>
                
                <form wire:submit.prevent="pagarViaje" class="p-lg flex flex-col gap-lg">
                    <!-- Mensaje de Error -->
                    @if($errorMsg)
                        <div class="bg-error-container text-on-error-container p-md rounded-lg text-body-sm flex items-start gap-sm">
                            <span class="material-symbols-outlined text-error text-[18px]">info</span>
                            <span class="font-medium">{{ $errorMsg }}</span>
                        </div>
                    @endif

                    <!-- Mensaje de Éxito -->
                    @if($successMsg)
                        <div class="bg-secondary-container text-on-secondary-container p-md rounded-lg text-body-sm flex items-start gap-sm">
                            <span class="material-symbols-outlined text-secondary text-[18px]">check_circle</span>
                            <span class="font-medium">{{ $successMsg }}</span>
                        </div>
                    @endif

                    <!-- Tarjeta de Tarifa Calculada -->
                    <div class="flex items-center gap-md bg-surface-container-low p-md rounded-lg">
                        <div class="h-10 w-10 bg-primary flex items-center justify-center text-white rounded-full">
                            <span class="material-symbols-outlined">train</span>
                        </div>
                        <div>
                            <p class="font-label-caps text-label-caps text-on-surface-variant">TARIFA CALCULADA ({{ $tarjeta ? $tarjeta->tipo : 'N/A' }})</p>
                            <p class="font-numeric-display text-primary">${{ number_format($fare, 2) }} MXN</p>
                        </div>
                    </div>

                    <!-- Selección de Ruta -->
                    <div class="space-y-sm">
                        <label class="font-label-caps text-label-caps block text-on-surface-variant">SELECCIONAR RUTA DE VIAJE</label>
                        <select wire:model="rutaId" class="w-full border border-outline rounded-lg p-md focus:ring-2 focus:ring-primary outline-none transition-all bg-white text-on-surface font-medium">
                            @foreach($rutas as $r)
                                <option value="{{ $r->id_ruta }}">{{ $r->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Estimaciones de Saldo -->
                    @if($tarjeta)
                        <div class="space-y-md">
                            <div class="flex justify-between items-center text-body-sm">
                                <span class="text-on-surface-variant">Saldo actual:</span>
                                <span class="font-bold text-primary">${{ number_format($tarjeta->saldo_actual, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center text-body-sm">
                                <span class="text-on-surface-variant">Saldo después del viaje:</span>
                                @php
                                    $estimado = $tarjeta->saldo_actual - $fare;
                                @endphp
                                <span class="font-bold {{ $estimado < 0 ? 'text-error' : ($estimado < 20 ? 'text-yellow-600' : 'text-secondary') }}">
                                    ${{ number_format($estimado, 2) }}
                                </span>
                            </div>

                            @if($estimado < 20 && $estimado >= 0 && strtolower($tarjeta->estado) === 'activa')
                                <div class="bg-error-container/20 p-md rounded-lg border border-error/20 flex items-start gap-sm">
                                    <span class="material-symbols-outlined text-error text-[20px]">info</span>
                                    <p class="text-on-error-container text-body-sm font-medium">Atención: Tu saldo quedará por debajo del límite mínimo recomendado ($20.00) tras este viaje.</p>
                                </div>
                            @elseif($estimado < 0 && strtolower($tarjeta->estado) === 'activa')
                                <div class="bg-error-container/20 p-md rounded-lg border border-error/20 flex items-start gap-sm">
                                    <span class="material-symbols-outlined text-error text-[20px]">info</span>
                                    <p class="text-on-error-container text-body-sm font-medium">Atención: Saldo insuficiente para realizar este viaje.</p>
                                </div>
                            @endif
                        </div>
                    @endif
                    
                    <!-- Botones de Acción -->
                    <div class="flex gap-md pt-sm">
                        <button type="button" wire:click="closeModal" class="w-1/3 border border-outline text-on-surface py-md rounded-lg hover:bg-surface-container-low active:scale-95 transition-transform cursor-pointer">
                            Cancelar
                        </button>
                        <button type="submit" class="w-2/3 bg-primary text-white font-bold py-md rounded-lg shadow-sm active:scale-95 transition-transform hover:bg-primary-container cursor-pointer">
                            Registrar Viaje
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>