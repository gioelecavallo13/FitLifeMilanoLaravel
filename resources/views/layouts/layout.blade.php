<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'FitLife')</title>

    <!--Import BOOTSTRAP CSS-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('styles')
</head>
<body>

@include('layouts.header')

<main>
    @yield('content')
</main>

@include('layouts.footer')

<!-- JS di Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

<!-- Pusher e Laravel Echo (per chat in tempo reale) -->
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.min.js"></script>
<script>
(function() {
    if (typeof Pusher === 'undefined' || typeof Echo === 'undefined') return;
    var csrf = document.querySelector('meta[name="csrf-token"]');
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: "{{ config('broadcasting.connections.pusher.key') }}",
        cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
        forceTLS: true,
        authEndpoint: "{{ url('/broadcasting/auth') }}",
        auth: {
            headers: {
                'X-CSRF-TOKEN': (csrf && csrf.getAttribute('content')) || '',
                'Accept': 'application/json'
            }
        }
    });
})();
</script>

<!-- JS specifici delle pagine -->
@stack('scripts')
</body>
</html>
