<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="shortcut icon" href="{{ asset('vue.svg') }}">

        @vite('resources/js/src/main.js')

        <style>
            *,
            *::before,
            *::after {
                box-sizing: border-box;
            }

            body {
                margin: 0;
            }

            .app-container-spinner {
                position: absolute;
                z-index: 999999;
                width: 100vw;
                height: 100vh;
                display: grid;
                place-items: center;
            }

            body.light .app-container-spinner {
                background-color: #ffffff;
            }

            body.dark .app-container-spinner {
                background-color: #181818;
            }

            .app-spinner {
                
                width: 40px;
                height: 40px;
                border-radius: 50%;

                animation: spin 1s ease-in-out infinite;
            }

            body.light .app-spinner {
                border: 4px solid rgba(0, 0, 0, 0.1);
                border-left-color: #ce2d4f;
            }

            body.dark .app-spinner {
                border: 4px solid rgba(255, 255, 255, 0.1);
                border-left-color: #ffffff;
            }

            @keyframes spin {
                from {
                    transform: rotate(0deg);
                }

                to {
                    transform: rotate(360deg);
                }
            }
        </style>

    </head>
    
    <body>
        <noscript>
            <strong>Lo sentimos, pero para que la aplicación funcione correctamente, es necesario que habilites JavaScript en tu navegador.</strong>
        </noscript>

        <div
            id="app-loading"
            class="app-container-spinner"
        >
            <div class="app-spinner"></div>
        </div>

        <div id="app"></div>

        <script>
            function establecerTema() {
                const tema = localStorage.getItem('tema-actual') || 'temaClaro';
                const $appLoading = document.getElementById('app-loading');

                if (tema === 'temaOscuro') {
                    document.body.classList.remove('light');
                    document.body.classList.add('dark');
                } else {
                    document.body.classList.remove('dark');
                    document.body.classList.add('light');
                }
            }

            (function() {
                establecerTema();
            })();
        </script>
    </body>
</html>