<!DOCTYPE html>
<html class="light" lang="es">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Transit Flow - Sistema de Movilidad Integrada</title>
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
        body {
            background-color: #f9f9ff;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .transition-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -10px rgba(0, 32, 69, 0.15);
        }
    </style>
</head>
<body class="font-body-lg text-on-surface">
    <header class="fixed top-0 left-0 w-full z-50 bg-surface-container-lowest shadow-sm border-b border-outline-variant h-16">
        <div class="max-w-container-max mx-auto px-lg h-full flex justify-between items-center">
            <div class="flex items-center gap-xl">
                <span class="text-headline-md font-headline-md text-primary tracking-tight">Transit Flow</span>
                <nav class="hidden md:flex items-center gap-lg">
                    <a class="text-primary border-b-2 border-primary font-bold pb-1 font-label-caps text-label-caps" href="{{ route('home') }}">Inicio</a>
                    <a class="text-on-surface-variant font-medium hover:text-primary transition-all duration-200 font-label-caps text-label-caps" href="{{ route('create') }}">Emitir Tarjeta</a>
                    <a class="text-on-surface-variant font-medium hover:text-primary transition-all duration-200 font-label-caps text-label-caps" href="{{ route('show') }}">Consultar Saldo</a>
                </nav>
            </div>
        </div>
    </header>

    <main class="pt-16 min-h-screen flex flex-col items-center">
        <section class="w-full bg-surface-container-low py-xl px-lg">
            <div class="max-w-container-max mx-auto text-center">
                <h1 class="font-headline-lg text-headline-lg md:text-headline-lg text-primary mb-md">Simplificando tu trayecto diario</h1>
                <p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl mx-auto">Gestiona tus tarjetas de transporte de manera eficiente y segura. Recarga, consulta y viaja sin interrupciones con el sistema de movilidad integrada de Transit Flow.</p>
            </div>
        </section>

        <section class="max-w-container-max w-full px-lg py-xl grid grid-cols-1 md:grid-cols-2 gap-lg">
            <a class="transition-card bg-surface-container-lowest border border-outline-variant p-xl flex flex-col md:flex-row items-center gap-lg transition-all duration-300" href="{{ route('create') }}">
                <div class="w-20 h-20 md:w-24 md:h-24 bg-primary rounded-xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-white text-[48px]">add_card</span>
                </div>
                <div class="flex-1 text-center md:text-left">
                    <h2 class="font-headline-md text-headline-md text-primary mb-xs">Emitir Nueva Tarjeta</h2>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mb-md">Obtén tu pase de transporte digital o solicita uno físico en minutos. Configuración instantánea para que empieces a moverte hoy mismo.</p>
                    <span class="inline-flex items-center gap-xs font-label-caps text-label-caps text-primary group">
                        SOLICITAR AHORA
                        <span class="material-symbols-outlined text-[16px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </span>
                </div>
            </a>

            <a class="transition-card bg-surface-container-lowest border border-outline-variant p-xl flex flex-col md:flex-row items-center gap-lg transition-all duration-300" href="{{ route('show') }}">
                <div class="w-20 h-20 md:w-24 md:h-24 bg-secondary rounded-xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-white text-[48px]">account_balance_wallet</span>
                </div>
                <div class="flex-1 text-center md:text-left">
                    <h2 class="font-headline-md text-headline-md text-primary mb-xs">Consultar Saldo e Información</h2>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mb-md">Revisa tu saldo disponible, historial de viajes y facturación de forma transparente. Mantén el control total de tus gastos de transporte.</p>
                    <span class="inline-flex items-center gap-xs font-label-caps text-label-caps text-secondary group">
                        VER MI CUENTA
                        <span class="material-symbols-outlined text-[16px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </span>
                </div>
            </a>
        </section>

        <section class="max-w-container-max w-full px-lg py-xl">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-md">
                <div class="bg-surface-container-low p-lg border border-outline-variant">
                    <span class="material-symbols-outlined text-primary mb-sm">speed</span>
                    <h3 class="font-label-caps text-label-caps text-primary mb-xs uppercase">Eficiencia Total</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Acceso inmediato a validadores y torniquetes con tecnología NFC de última generación.</p>
                </div>
                <div class="bg-surface-container-low p-lg border border-outline-variant">
                    <span class="material-symbols-outlined text-primary mb-sm">security</span>
                    <h3 class="font-label-caps text-label-caps text-primary mb-xs uppercase">Seguridad Cifrada</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Tus transacciones y datos personales están protegidos por estándares bancarios internacionales.</p>
                </div>
                <div class="bg-surface-container-low p-lg border border-outline-variant">
                    <span class="material-symbols-outlined text-primary mb-sm">eco</span>
                    <h3 class="font-label-caps text-label-caps text-primary mb-xs uppercase">Sostenibilidad</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Reduce el uso de plásticos con nuestras tarjetas digitales integradas en tu dispositivo móvil.</p>
                </div>
            </div>
        </section>

        <section class="w-full h-64 md:h-96 relative overflow-hidden mt-xl">
            <img class="w-full h-full object-cover" alt="Modern blue city bus at a minimalist transit station" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDn0L6YEhIbpFOnqKU5iBgj0cG9LqeEchyeZXSX-bwnwWwk2DtKjg1XBLwoFvI8R1VnEVM_0UGsGvjo8KxzmwNvGbZqou4Y37U0uPJlLLBGmluO39IpW3fbAkTqx9z42WAFWcfKbjpph8dwpar1BhJvJG8noOuPwsWtBHa0O2vMGD2K9tv7z3SVn0qkG5wUwZxqOYUVhLaFIyd2fV-q8K8sJhvQJqie-qjQBGlBsWKc2_dCINFXN94P9NtEQmGoqBpXT0IN8rKFTQ1m">
            <div class="absolute inset-0 bg-primary/20 backdrop-blur-[2px] flex items-center justify-center text-center px-lg">
                <div class="bg-surface-container-lowest/90 p-lg max-w-xl border border-outline-variant">
                    <h4 class="font-headline-md text-headline-md text-primary">Red de Cobertura Total</h4>
                    <p class="font-body-sm text-body-sm text-on-surface mt-xs">Conectamos más de 45 líneas de metro, tren ligero y autobuses en toda el área metropolitana.</p>
                </div>
            </div>
        </section>
    </main>

    <footer class="w-full bg-surface-container border-t border-outline-variant">
        <div class="max-w-container-max mx-auto py-md px-lg flex flex-col md:flex-row justify-between items-center gap-md">
            <div class="flex flex-col items-center md:items-start gap-xs">
                <span class="font-label-caps text-label-caps text-on-surface-variant">SISTEMA DE MOVILIDAD INTEGRADA</span>
                <p class="font-body-sm text-body-sm text-on-surface-variant text-center md:text-left">© {{ date('Y') }} Transit Flow. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        document.querySelectorAll('.transition-card').forEach(card => {
            card.addEventListener('mousedown', () => card.classList.add('scale-95'));
            card.addEventListener('mouseup', () => card.classList.remove('scale-95'));
            card.addEventListener('mouseleave', () => card.classList.remove('scale-95'));
        });
    </script>
</body>
</html>
</content>
</invoke>
