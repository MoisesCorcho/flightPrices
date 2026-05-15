<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Flight Alerts') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Material Symbols -->
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

        <!-- PWA -->
        <link rel="manifest" href="/manifest.json">
        <meta name="theme-color" content="#3525cd">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <script>
            // Theme initialization
            if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/sw.js');
                });
            }
        </script>

        <!-- Firebase SDKs -->
        <script src="https://www.gstatic.com/firebasejs/10.8.0/firebase-app-compat.js"></script>
        <script src="https://www.gstatic.com/firebasejs/10.8.0/firebase-messaging-compat.js"></script>

        <script>
            // 2. INICIALIZAR FIREBASE CON DATOS EXACTOS
            const firebaseConfig = {
                apiKey: "AIzaSyCl4f71qHNjH5v3ny4dDS7GmPAemRi6hAU",
                authDomain: "flightprices-4dfbf.firebaseapp.com",
                projectId: "flightprices-4dfbf",
                storageBucket: "flightprices-4dfbf.firebasestorage.app",
                messagingSenderId: "298414671888",
                appId: "1:298414671888:web:5beca7b05e92dc54f62295",
                measurementId: "G-B71QW442WB"
            };
            firebase.initializeApp(firebaseConfig);
            const messaging = firebase.messaging();

            // 3. SOLICITAR PERMISOS Y OBTENER TOKEN
            function requestPermission() {
                console.log('Solicitando permiso para notificaciones...');
                Notification.requestPermission().then((permission) => {
                    if (permission === 'granted') {
                        console.log('Permiso concedido.');

                        // Obtenemos el registro del Service Worker actual
                        navigator.serviceWorker.ready.then((registration) => {
                            // Obtener el token de registro de FCM
                            messaging.getToken({ 
                                vapidKey: 'BHQK5n-2nCUSozb2nLLbecQI2t1UE-7gPieh4cd1EN5aaTi57ymF6i1f3yJSl7GM-HNzaLlzRQ9xlo1Z5dOxqU4', 
                                serviceWorkerRegistration: registration 
                            }).then((currentToken) => {
                                if (currentToken) {
                                    console.log('Token FCM:', currentToken);
                                    sendTokenToServer(currentToken);
                                } else {
                                    console.log('No se pudo obtener el token. Asegúrate de tener configurado el VAPID key.');
                                }
                            }).catch((err) => {
                                console.log('Error al obtener el token:', err);
                            });
                        });
                    } else {
                        console.log('Permiso denegado.');
                    }
                });
            }
            function sendTokenToServer(token) {
                fetch('/fcm-token', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ token: token })
                })
                .then(response => response.json())
                .then(data => console.log('Token guardado en el servidor:', data))
                .catch(error => console.error('Error al guardar el token:', error));
            }

            // Iniciar el proceso de solicitud de permiso
            window.addEventListener('load', () => {
                // Pequeño delay para no interrumpir la carga inicial
                setTimeout(requestPermission, 2000);
            });
        </script>

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="bg-background text-on-surface min-h-screen flex flex-col font-sans">
        {{ $slot }}

        @persist('flux')
            @fluxScripts
        @endpersist
        @livewireScripts
    </body>
</html>
