<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Reservo</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @inertiaHead
    @routes

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            body {
                font-family: "Poppins", sans-serif;
                color: #003366;
            }
        </style>
        <div style="text-align:center; font-size: 1.25rem;">
            ⚠️ Vite dev server is not running.<br>
            Please run <code>npm run dev</code>.
        </div>
    @endif
</head>
<body class="antialiased">
@inertia
</body>
</html>
