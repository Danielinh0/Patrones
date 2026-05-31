<!DOCTYPE html>
<html class="light" lang="es">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Transit Flow - Emitir Tarjeta</title>
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
                }
            }
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
                    <a class="text-primary border-b-2 border-primary font-bold pb-1 font-label-caps text-label-caps" href="{{ route('create') }}">Emitir Tarjeta</a>
                    <a class="text-on-surface-variant font-medium hover:text-primary transition-all duration-200 font-label-caps text-label-caps" href="{{ route('show') }}">Consultar Saldo</a>
                </nav>
            </div>
        </div>
    </header>

    <main class="flex-grow pt-24 pb-xl px-gutter max-w-container-max mx-auto w-full">
        <livewire:tarjeta.emitir />
    </main>

    <footer class="w-full bg-surface-container border-t border-outline-variant">
        <div class="max-w-container-max mx-auto py-md px-lg flex flex-col md:flex-row justify-between items-center gap-md">
            <div class="flex flex-col items-center md:items-start gap-xs">
                <span class="font-label-caps text-label-caps text-on-surface-variant">SISTEMA DE MOVILIDAD INTEGRADA</span>
                <p class="font-body-sm text-body-sm text-on-surface-variant text-center md:text-left">© {{ date('Y') }} Transit Flow. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
</body>
</html>