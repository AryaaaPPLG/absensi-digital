<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Absensi Digital</title>
</head>
<body>
    @yield('content')

    {{-- Tambahkan di bawah sebelum script JS utama --}}
    @if(Auth::check())
        <script>
            const user_id = {{ Auth::id() }};
            const username = "{{ Auth::user()->username }}";
        </script>
    @else
        <script>
            const user_id = null;
            const username = null;
        </script>
    @endif

    {{-- Script utama JS --}}
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
