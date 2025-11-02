<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white shadow-lg rounded-lg p-8 w-96">
    <h1 class="text-2xl font-bold mb-6 text-center">Login Akun</h1>

    @if ($errors->has('login_error'))
        <p class="text-red-500 text-center mb-4">{{ $errors->first('login_error') }}</p>
    @endif

    <form action="{{ route('login.post') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="username" class="block font-medium">Username</label>
            <input type="text" name="username" id="username" class="w-full p-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label for="password" class="block font-medium">Password</label>
            <input type="password" name="password" id="password" class="w-full p-2 border rounded" required>
        </div>

        <button type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white p-2 rounded transition">Login</button>
    </form>

    <p class="mt-4 text-center text-sm">
        Belum punya akun?
        <a href="{{ url('/register') }}" class="text-blue-500 hover:underline">Daftar disini</a>
    </p>
</div>

</body>
</html>
