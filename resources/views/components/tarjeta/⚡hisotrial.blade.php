<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Transaccion;

new class extends Component
{
    public $tarjetaId = null;
    public $transacciones = [];

    public function mount()
    {
        $this->loadTransacciones();
    }

    #[On('tarjetaSelected')]
    public function setTarjeta($id)
    {
        $this->tarjetaId = $id;
        $this->loadTransacciones();
    }

    #[On('tarjetaUpdated')]
    public function onTarjetaUpdated()
    {
        $this->loadTransacciones();
    }

    public function loadTransacciones()
    {
        if ($this->tarjetaId) {
            $this->transacciones = Transaccion::where('id_tarjeta', $this->tarjetaId)
                ->with('ruta')
                ->latest()
                ->get();
        } else {
            $this->transacciones = collect();
        }
    }
};
?>

<div class="bg-white border border-outline-variant rounded-xl overflow-hidden shadow-sm">
    <div class="p-lg border-b border-outline-variant flex justify-between items-center">
        <h2 class="font-headline-md text-headline-md text-primary">Historial de Transacciones</h2>
        <button class="text-primary font-label-caps text-label-caps hover:underline cursor-pointer" wire:click="loadTransacciones">RECARGAR</button>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-container-low border-b border-outline-variant">
                    <th class="p-md font-label-caps text-label-caps text-on-surface-variant">FECHA</th>
                    <th class="p-md font-label-caps text-label-caps text-on-surface-variant">ESTACIÓN / DETALLE</th>
                    <th class="p-md font-label-caps text-label-caps text-on-surface-variant">TIPO</th>
                    <th class="p-md font-label-caps text-label-caps text-on-surface-variant text-right">MONTO</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($transacciones as $t)
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="p-md text-body-sm">
                            {{ \Carbon\Carbon::parse($t->created_at)->timezone('America/Mexico_City')->format('d M Y, h:i A') }}
                        </td>
                        <td class="p-md font-medium text-on-surface">
                            @if($t->tipo === 'VIAJE')
                                {{ $t->ruta ? $t->ruta->nombre : 'Viaje Integrado' }}
                            @else
                                Recarga de Saldo (Ventanilla/App)
                            @endif
                        </td>
                        <td class="p-md">
                            @if($t->tipo === 'VIAJE')
                                <span class="bg-surface-container-high text-primary px-sm py-xs rounded text-[12px] font-bold">VIAJE</span>
                            @else
                                <span class="bg-secondary-container text-on-secondary-container px-sm py-xs rounded text-[12px] font-bold">RECARGA</span>
                            @endif
                        </td>
                        <td class="p-md text-right font-bold {{ $t->tipo === 'VIAJE' ? 'text-error' : 'text-secondary' }}">
                            {{ $t->tipo === 'VIAJE' ? '-' : '+' }}${{ number_format($t->monto, 2) }} MXN
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-lg text-center text-on-surface-variant text-body-sm">
                            No hay transacciones registradas para esta tarjeta.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>