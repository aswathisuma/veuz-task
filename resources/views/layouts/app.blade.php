<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veuz Interview</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
    <div class="app-wrapper">
        @auth
        @include('layouts.sidebar')
        @endauth

        <main class="app-main">
            {{ $slot ?? '' }}
            @yield('content')
        </main>

        @auth
        @include('layouts.footer')
        @endauth
    </div>
</body>

</html>