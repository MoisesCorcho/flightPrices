<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>
    {{ filled($title ?? null) ? $title.' - '.config('app.name', 'Laravel') : config('app.name', 'Laravel') }}
</title>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">
<link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="#3525cd">

<!-- OpenGraph / Social Sharing -->
<meta property="og:type" content="website">
<meta property="og:title" content="SkyTrack — Monitoreo Inteligente de Vuelos">
<meta property="og:description" content="Rastreo automático de precios de vuelos en tiempo real con alertas instantáneas cuando bajan las tarifas.">
<meta property="og:image" content="{{ asset('og-image.png') }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="SkyTrack — Monitoreo Inteligente de Vuelos">
<meta name="twitter:description" content="Rastreo automático de precios de vuelos en tiempo real con alertas instantáneas cuando bajan las tarifas.">
<meta name="twitter:image" content="{{ asset('og-image.png') }}">

@fonts

<script>
    /**
     * Theme initialization based on user preference or system settings.
     */
    function initializeTheme() {
        const theme = localStorage.getItem('theme');
        const isDark = theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches);
        
        if (isDark) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }

    initializeTheme();
    document.addEventListener('livewire:navigated', initializeTheme);
</script>

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
