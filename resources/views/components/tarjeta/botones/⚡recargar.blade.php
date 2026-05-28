<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Tarjeta;
use App\Models\Transaccion;

new class extends Component
{
    public $tarjetaId = null;
    public $monto = '';
    public $isOpen = false;
    public $errorMsg = null;
    public $successMsg = null;

    #[On('tarjetaSelected')]
    public function setTarjeta($id)
    {
        $this->tarjetaId = $id;
        $this->errorMsg = null;
        $this->successMsg = null;
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
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->monto = '';
        $this->errorMsg = null;
        $this->successMsg = null;
    }

    public function selectMonto($val)
    {
        $this->monto = $val;
        $this->errorMsg = null;
    }

    public function recargar()
    {
        if (!$this->tarjetaId) {
            $this->errorMsg = 'Debes seleccionar una tarjeta primero.';
            return;
        }

        if (empty($this->monto) || (float)$this->monto <= 0) {
            $this->errorMsg = 'Ingresa un monto válido mayor a 0.';
            return;
        }

        $tarjeta = Tarjeta::find($this->tarjetaId);
        if (!$tarjeta) {
            $this->errorMsg = 'La tarjeta seleccionada no existe.';
            return;
        }

        try {
            $montoFloat = (float)$this->monto;
            
            // Llama a la recarga delegada en el patrón State
            $tarjeta->recargarSaldo($montoFloat);

            // Registrar transacción en la base de datos
            Transaccion::create([
                'monto' => $montoFloat,
                'fecha' => now()->toDateString(),
                'tipo' => 'RECARGA',
                'id_tarjeta' => $tarjeta->id_tarjeta,
            ]);

            $this->successMsg = "¡Recarga exitosa! Se han abonado $" . number_format($montoFloat, 2) . " a la tarjeta.";
            $this->monto = '';
            $this->errorMsg = null;

            // Despacha evento para que se actualice la vista de saldo e historial
            $this->dispatch('tarjetaUpdated');
        } catch (\Exception $e) {
            $this->errorMsg = $e->getMessage();
            $this->successMsg = null;
        }
    }
};
?>

<div>
    <!-- Botón de Recarga -->
    <button wire:click="openModal" class="w-full bg-primary text-white p-lg rounded-xl flex items-center justify-between group hover:shadow-md transition-all active:scale-[0.98] cursor-pointer">
        <div class="flex items-center gap-md">
            <span class="material-symbols-outlined text-[32px] bg-primary-container p-md rounded-xl text-primary-fixed">add_card</span>
            <div class="text-left">
                <p class="font-headline-md text-headline-md">Recargar Saldo</p>
                <p class="font-body-sm text-blue-200">Añade fondos a tu cuenta al instante</p>
            </div>
        </div>
        <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">chevron_right</span>
    </button>

    <!-- Modal de Recarga -->
    <div class="fixed inset-0 z-[100] {{ $isOpen ? '' : 'hidden' }}">
        <!-- Fondo oscuro con desenfoque -->
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
        
        <!-- Contenedor del Modal -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-lg">
            <div class="bg-white rounded-xl shadow-xl overflow-hidden border border-outline-variant">
                <!-- Encabezado del Modal -->
                <div class="p-lg bg-primary text-white flex justify-between items-center">
                    <h3 class="font-headline-md">Recargar Saldo</h3>
                    <button class="material-symbols-outlined hover:text-outline-variant transition-colors cursor-pointer" wire:click="closeModal">close</button>
                </div>
                
                <!-- Cuerpo del Formulario -->
                <form wire:submit.prevent="recargar" class="p-lg flex flex-col gap-lg">
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

                    <!-- Botones Rápidos de Monto -->
                    <div>
                        <label class="font-label-caps text-label-caps block mb-xs text-on-surface-variant">SELECCIONA MONTO</label>
                        <div class="grid grid-cols-3 gap-md">
                            <button type="button" wire:click="selectMonto(20)" class="border {{ (float)$monto == 20 ? 'border-secondary bg-secondary-container text-on-secondary-container' : 'border-outline text-on-surface bg-white' }} p-md rounded-lg font-bold hover:bg-surface-container-low transition-colors cursor-pointer">$20</button>
                            <button type="button" wire:click="selectMonto(50)" class="border {{ (float)$monto == 50 ? 'border-secondary bg-secondary-container text-on-secondary-container' : 'border-outline text-on-surface bg-white' }} p-md rounded-lg font-bold hover:bg-surface-container-low transition-colors cursor-pointer">$50</button>
                            <button type="button" wire:click="selectMonto(100)" class="border {{ (float)$monto == 100 ? 'border-secondary bg-secondary-container text-on-secondary-container' : 'border-outline text-on-surface bg-white' }} p-md rounded-lg font-bold hover:bg-surface-container-low transition-colors cursor-pointer">$100</button>
                        </div>
                    </div>
                    
                    <!-- Entrada de Otro Monto -->
                    <div class="space-y-sm">
                        <label class="font-label-caps text-label-caps block text-on-surface-variant">OTRO MONTO</label>
                        <input wire:model.live="monto" class="w-full border border-outline rounded-lg p-md focus:ring-2 focus:ring-primary outline-none transition-all text-on-surface bg-white font-bold" placeholder="$ 0.00" type="number" step="0.50" min="1">
                    </div>
                    
                    <!-- Botones de Acción -->
                    <div class="flex gap-md pt-sm">
                        <button type="button" wire:click="closeModal" class="w-1/3 border border-outline text-on-surface py-md rounded-lg active:scale-95 transition-transform hover:bg-surface-container-low cursor-pointer">
                            Cancelar
                        </button>
                        <button type="submit" class="w-2/3 bg-primary text-white font-bold py-md rounded-lg shadow-sm active:scale-95 transition-transform hover:bg-primary-container hover:text-white cursor-pointer">
                            Confirmar Pago
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>