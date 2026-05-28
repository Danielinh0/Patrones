<!DOCTYPE html>
<html class="light" lang="es">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Transit Flow - Consulta de Saldo</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-error-container": "#93000a",
                        "primary-container": "#1a365d",
                        "on-background": "#111c2c",
                        "outline-variant": "#c4c6cf",
                        "surface-container-high": "#dee8ff",
                        "surface-container-highest": "#d8e3fa",
                        "on-tertiary-fixed": "#271900",
                        "on-primary-fixed-variant": "#2d476f",
                        "on-surface": "#111c2c",
                        "on-secondary": "#ffffff",
                        "surface-variant": "#d8e3fa",
                        "tertiary-fixed": "#ffdeaa",
                        "tertiary-container": "#493100",
                        "on-secondary-container": "#007243",
                        "on-tertiary-fixed-variant": "#5f4100",
                        "outline": "#74777f",
                        "on-secondary-fixed-variant": "#00522f",
                        "surface-tint": "#455f88",
                        "secondary": "#006d40",
                        "surface-bright": "#f9f9ff",
                        "primary": "#002045",
                        "tertiary": "#2d1d00",
                        "surface-container-lowest": "#ffffff",
                        "secondary-container": "#8ef5b5",
                        "inverse-on-surface": "#ebf1ff",
                        "secondary-fixed": "#91f8b8",
                        "secondary-fixed-dim": "#74db9d",
                        "surface-container-low": "#f0f3ff",
                        "on-tertiary-container": "#cb9524",
                        "inverse-primary": "#adc7f7",
                        "on-tertiary": "#ffffff",
                        "error-container": "#ffdad6",
                        "on-primary-container": "#86a0cd",
                        "error": "#ba1a1a",
                        "primary-fixed": "#d6e3ff",
                        "on-primary": "#ffffff",
                        "on-surface-variant": "#43474e",
                        "tertiary-fixed-dim": "#f8bc4b",
                        "on-error": "#ffffff",
                        "on-primary-fixed": "#001b3c",
                        "inverse-surface": "#263142",
                        "surface-container": "#e7eeff",
                        "on-secondary-fixed": "#002110",
                        "background": "#f9f9ff",
                        "surface-dim": "#cfdaf1",
                        "primary-fixed-dim": "#adc7f7",
                        "surface": "#f9f9ff"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "md": "1rem",
                        "sm": "0.5rem",
                        "xs": "0.25rem",
                        "lg": "1.5rem",
                        "xl": "2.5rem",
                        "gutter": "1rem",
                        "container-max": "1280px",
                        "base": "4px"
                    },
                    "fontFamily": {
                        "body-sm": ["Inter"],
                        "headline-md": ["Inter"],
                        "body-lg": ["Inter"],
                        "label-caps": ["Inter"],
                        "numeric-display": ["Inter"],
                        "headline-lg": ["Inter"],
                        "headline-lg-mobile": ["Inter"]
                    },
                    "fontSize": {
                        "body-sm": ["14px", {"lineHeight": "20px", "fontWeight": "400"}],
                        "headline-md": ["20px", {"lineHeight": "28px", "fontWeight": "600"}],
                        "body-lg": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                        "label-caps": ["12px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600"}],
                        "numeric-display": ["28px", {"lineHeight": "32px", "letterSpacing": "-0.03em", "fontWeight": "700"}],
                        "headline-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "headline-lg-mobile": ["24px", {"lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "700"}]
                    }
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            vertical-align: middle;
        }
        .bg-transit-card {
            background: linear-gradient(135deg, #002045 0%, #1a365d 100%);
        }
    </style>
</head>
<body class="bg-background font-body-lg text-on-surface min-h-screen flex flex-col">
    <header class="fixed top-0 left-0 w-full z-50 bg-surface-container-lowest shadow-sm border-b border-outline-variant h-16">
        <div class="max-w-container-max mx-auto px-lg h-full flex justify-between items-center">
            <div class="flex items-center gap-xl">
                <span class="text-headline-md font-headline-md text-primary tracking-tight">Transit Flow</span>
                <nav class="hidden md:flex items-center gap-lg">
                    <a class="text-on-surface-variant font-medium hover:text-primary transition-all duration-200 font-label-caps text-label-caps" href="{{ route('home') }}">Inicio</a>
                    <a class="text-on-surface-variant font-medium hover:text-primary transition-all duration-200 font-label-caps text-label-caps" href="{{ route('create') }}">Emitir Tarjeta</a>
                    <a class="text-primary border-b-2 border-primary font-bold pb-1 font-label-caps text-label-caps" href="{{ route('show') }}">Consultar Saldo</a>
                </nav>
            </div>
        </div>
    </header>

    <main class="flex-grow pt-24 pb-xl px-gutter max-w-container-max mx-auto w-full">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-lg">
            <section class="md:col-span-8">
                <div class="bg-transit-card rounded-xl p-lg text-white shadow-lg relative overflow-hidden group">
                    <div class="absolute -right-16 -top-16 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl group-hover:opacity-10 transition-opacity"></div>
                    <div class="flex flex-col gap-xl">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-label-caps text-label-caps text-blue-200 mb-xs">SALDO ACTUAL</p>
                                <h1 class="font-numeric-display text-[48px] leading-none">$18.50</h1>
                            </div>
                            <div class="bg-error-container text-on-error-container px-md py-xs rounded-full flex items-center gap-xs animate-pulse">
                                <span class="material-symbols-outlined text-[16px]">warning</span>
                                <span class="font-label-caps text-label-caps">SALDO BAJO</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-end">
                            <div class="flex flex-col gap-xs">
                                <p class="font-label-caps text-label-caps text-blue-200">NÚMERO DE TARJETA</p>
                                <p class="font-body-sm tracking-widest">**** **** **** 4829</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="md:col-span-4 flex flex-col gap-md">
                <div class="bg-surface-container-lowest border border-outline-variant p-md rounded-xl flex items-center gap-md">
                    <div class="bg-primary-fixed p-sm rounded-lg">
                        <span class="material-symbols-outlined text-primary">directions_bus</span>
                    </div>
                    <div>
                        <p class="font-label-caps text-label-caps text-on-surface-variant">ÚLTIMO VIAJE</p>
                        <p class="font-headline-md text-headline-md text-primary">Línea 4 - Centro</p>
                    </div>
                </div>
                <div class="bg-surface-container-lowest border border-outline-variant p-md rounded-xl flex items-center gap-md">
                    <div class="bg-secondary-fixed p-sm rounded-lg">
                        <span class="material-symbols-outlined text-secondary">verified_user</span>
                    </div>
                    <div>
                        <p class="font-label-caps text-label-caps text-on-surface-variant">ESTADO TARJETA</p>
                        <p class="font-headline-md text-headline-md text-secondary">Activa</p>
                    </div>
                </div>
            </section>

            <section class="md:col-span-12 grid grid-cols-1 md:grid-cols-2 gap-lg my-md">
                <button class="bg-primary text-white p-lg rounded-xl flex items-center justify-between group hover:shadow-md transition-all active:scale-[0.98]" onclick="openModal('recharge-modal')">
                    <div class="flex items-center gap-md">
                        <span class="material-symbols-outlined text-[32px] bg-primary-container p-md rounded-xl">add_card</span>
                        <div class="text-left">
                            <p class="font-headline-md text-headline-md">Recargar Saldo</p>
                            <p class="font-body-sm text-on-primary-container">Añade fondos a tu cuenta al instante</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">chevron_right</span>
                </button>
                <button class="bg-white border-2 border-primary text-primary p-lg rounded-xl flex items-center justify-between group hover:bg-surface-container-low transition-all active:scale-[0.98]" onclick="openModal('simulate-modal')">
                    <div class="flex items-center gap-md">
                        <span class="material-symbols-outlined text-[32px] bg-surface-container p-md rounded-xl">route</span>
                        <div class="text-left">
                            <p class="font-headline-md text-headline-md">Simular Pago de Viaje</p>
                            <p class="font-body-sm text-on-surface-variant">Calcula el costo de tu próximo trayecto</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">chevron_right</span>
                </button>
            </section>

            <section class="md:col-span-12">
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
                    <div class="p-lg border-b border-outline-variant flex justify-between items-center">
                        <h2 class="font-headline-md text-headline-md text-primary">Historial de Transacciones</h2>
                        <button class="text-primary font-label-caps text-label-caps hover:underline">VER TODO</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-surface-container-low">
                                    <th class="p-md font-label-caps text-label-caps text-on-surface-variant">FECHA</th>
                                    <th class="p-md font-label-caps text-label-caps text-on-surface-variant">ESTACIÓN / RUTA</th>
                                    <th class="p-md font-label-caps text-label-caps text-on-surface-variant">TIPO</th>
                                    <th class="p-md font-label-caps text-label-caps text-on-surface-variant text-right">MONTO</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant">
                                <tr class="hover:bg-surface-container-low transition-colors">
                                    <td class="p-md text-body-sm">Hoy, 08:45 AM</td>
                                    <td class="p-md font-medium">Estación Hidalgo - L3</td>
                                    <td class="p-md"><span class="bg-surface-container-high text-primary px-sm py-xs rounded text-[12px] font-bold">VIAJE</span></td>
                                    <td class="p-md text-right font-bold text-error">-$6.00</td>
                                </tr>
                                <tr class="hover:bg-surface-container-low transition-colors">
                                    <td class="p-md text-body-sm">Ayer, 06:20 PM</td>
                                    <td class="p-md font-medium">Estación Insurgentes - L1</td>
                                    <td class="p-md"><span class="bg-surface-container-high text-primary px-sm py-xs rounded text-[12px] font-bold">VIAJE</span></td>
                                    <td class="p-md text-right font-bold text-error">-$6.00</td>
                                </tr>
                                <tr class="hover:bg-surface-container-low transition-colors">
                                    <td class="p-md text-body-sm">15 Oct, 11:30 AM</td>
                                    <td class="p-md font-medium">Terminal Central</td>
                                    <td class="p-md"><span class="bg-secondary-container text-on-secondary-container px-sm py-xs rounded text-[12px] font-bold">RECARGA</span></td>
                                    <td class="p-md text-right font-bold text-secondary">+$100.00</td>
                                </tr>
                                <tr class="hover:bg-surface-container-low transition-colors">
                                    <td class="p-md text-body-sm">14 Oct, 09:15 AM</td>
                                    <td class="p-md font-medium">Estación Pino Suárez - L2</td>
                                    <td class="p-md"><span class="bg-surface-container-high text-primary px-sm py-xs rounded text-[12px] font-bold">VIAJE</span></td>
                                    <td class="p-md text-right font-bold text-error">-$6.00</td>
                                </tr>
                                <tr class="hover:bg-surface-container-low transition-colors">
                                    <td class="p-md text-body-sm">14 Oct, 07:45 AM</td>
                                    <td class="p-md font-medium">Línea 7 Bus - Reforma</td>
                                    <td class="p-md"><span class="bg-surface-container-high text-primary px-sm py-xs rounded text-[12px] font-bold">VIAJE</span></td>
                                    <td class="p-md text-right font-bold text-error">-$4.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <footer class="w-full bg-surface-container border-t border-outline-variant">
        <div class="max-w-container-max mx-auto py-md px-lg flex flex-col md:flex-row justify-between items-center gap-md">
            <div class="flex flex-col items-center md:items-start gap-xs">
                <span class="font-label-caps text-label-caps text-on-surface-variant">SISTEMA DE MOVILIDAD INTEGRADA</span>
                <p class="font-body-sm text-body-sm text-on-surface-variant text-center md:text-left">© {{ date('Y') }} Transit Flow. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <div class="fixed inset-0 z-[100] hidden" id="recharge-modal">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeModal('recharge-modal')"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-lg">
            <div class="bg-white rounded-xl shadow-xl overflow-hidden">
                <div class="p-lg bg-primary text-white flex justify-between items-center">
                    <h3 class="font-headline-md">Recargar Saldo</h3>
                    <button class="material-symbols-outlined" onclick="closeModal('recharge-modal')">close</button>
                </div>
                <div class="p-lg flex flex-col gap-lg">
                    <div>
                        <label class="font-label-caps text-label-caps block mb-xs text-on-surface-variant">SELECCIONA MONTO</label>
                        <div class="grid grid-cols-3 gap-md">
                            <button class="border border-outline p-md rounded-lg font-bold hover:bg-surface-container-low transition-colors focus:ring-2 focus:ring-primary">$20</button>
                            <button class="border border-outline p-md rounded-lg font-bold hover:bg-surface-container-low transition-colors focus:ring-2 focus:ring-primary">$50</button>
                            <button class="border border-primary bg-primary-fixed p-md rounded-lg font-bold text-primary">$100</button>
                        </div>
                    </div>
                    <div class="space-y-sm">
                        <label class="font-label-caps text-label-caps block text-on-surface-variant">OTRO MONTO</label>
                        <input class="w-full border border-outline rounded-lg p-md focus:ring-2 focus:ring-primary outline-none transition-all" placeholder="$ 0.00" type="number">
                    </div>
                    <button class="bg-primary text-white font-bold py-md rounded-lg shadow-sm active:scale-95 transition-transform">Confirmar Pago</button>
                </div>
            </div>
        </div>
    </div>

    <div class="fixed inset-0 z-[100] hidden" id="simulate-modal">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeModal('simulate-modal')"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-lg">
            <div class="bg-white rounded-xl shadow-xl overflow-hidden">
                <div class="p-lg bg-surface-container border-b border-outline-variant flex justify-between items-center">
                    <h3 class="font-headline-md text-primary">Simular Viaje</h3>
                    <button class="material-symbols-outlined text-on-surface-variant" onclick="closeModal('simulate-modal')">close</button>
                </div>
                <div class="p-lg flex flex-col gap-lg">
                    <div class="flex items-center gap-md bg-surface-container-low p-md rounded-lg">
                        <div class="h-10 w-10 bg-primary flex items-center justify-center text-white rounded-full">
                            <span class="material-symbols-outlined">train</span>
                        </div>
                        <div>
                            <p class="font-label-caps text-label-caps text-on-surface-variant">TARIFA ESTÁNDAR</p>
                            <p class="font-numeric-display text-primary">$6.00 MXN</p>
                        </div>
                    </div>
                    <div class="space-y-md">
                        <div class="flex justify-between items-center text-body-sm">
                            <span class="text-on-surface-variant">Saldo después del viaje:</span>
                            <span class="font-bold text-error">$12.50</span>
                        </div>
                        <div class="bg-error-container/20 p-md rounded-lg border border-error/20 flex items-start gap-sm">
                            <span class="material-symbols-outlined text-error text-[20px]">info</span>
                            <p class="text-on-error-container text-body-sm font-medium">Atención: Tu saldo quedará por debajo del límite mínimo recomendado ($20.00) tras este viaje.</p>
                        </div>
                    </div>
                    <button class="bg-primary text-white font-bold py-md rounded-lg active:scale-95 transition-transform" onclick="closeModal('simulate-modal')">Entendido</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) {
            const modal = document.getElementById(id);
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            const content = modal.querySelector('div:last-child');
            content.style.opacity = '0';
            content.style.transform = 'translate(-50%, -40%) scale(0.95)';
            content.style.transition = 'all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1)';

            requestAnimationFrame(() => {
                content.style.opacity = '1';
                content.style.transform = 'translate(-50%, -50%) scale(1)';
            });
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            const content = modal.querySelector('div:last-child');

            content.style.opacity = '0';
            content.style.transform = 'translate(-50%, -40%) scale(0.95)';

            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 200);
        }

        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeModal('recharge-modal');
                closeModal('simulate-modal');
            }
        });
    </script>
</body>
</html>
</content>
</invoke>
