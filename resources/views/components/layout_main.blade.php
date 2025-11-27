<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/app.css', 'resources/ts/app.ts', 'resources/css/treejs.css', 'resources/css/treejs-custom.css'])
    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>
    <script src="https://unpkg.com/tippy.js@6/dist/tippy-bundle.umd.js"></script>
    <title>{{ config('app.name') }} - @yield('title')</title>
</head>

<body class="flex flex-col min-h-screen text-primary-900">

    <x-notifications.modals />

    <nav>
        @include('partials._navbar')
    </nav>

    {{-- no general class attached due to landing page --}}
    <main class="">
        {{ $slot }}
    </main>

    @include('partials._footer')
    @stack('vite')
</body>

</html>
