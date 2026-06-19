{{-- <div>
    <!-- Simplicity is the ultimate sophistication. - Leonardo da Vinci -->
</div> --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        {{ config('app.name', 'Echo') }}
        {{ isset($title) ? ' - ' . $title : '' }}
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body class="min-h-screen bg-slate-100 text-slate-900 antialiased">
    {{ $slot }}
</body>
</html>
