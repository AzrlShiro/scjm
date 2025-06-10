<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk Sistem - Supply Chain Jamu Madura</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Optional: Custom styling for the background image overlay */
        .bg-overlay {
            background-color: rgba(0, 0, 0, 0.4); /* Dark overlay for better text readability */
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100 min-h-screen flex items-center justify-center relative"
      style="background-image: url('{{ asset('images/bg-jamu-madura.png') }}'); background-size: cover; background-position: center;">

    {{-- Overlay untuk membuat teks lebih jelas --}}
    <div class="absolute inset-0 bg-overlay"></div>

    <div class="relative z-10 w-full max-w-md bg-white rounded-lg shadow-xl p-8 transform transition-all duration-300 hover:scale-105">
        <div class="text-center mb-8">
            {{-- Logo Perusahaan Jamu Madura --}}
            <img src="{{ asset('images/logo-jamu-madura.png') }}" alt="Logo Jamu Madura" class="mx-auto h-24 w-auto mb-4">
            <h2 class="text-3xl font-extrabold text-gray-900 leading-tight">Masuk Sistem</h2>
            <p class="text-gray-600 mt-1 text-lg">Manajemen Supply Chain Jamu Madura</p>
        </div>

        {{-- Tampilkan pesan error umum --}}
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Tampilkan pesan sukses --}}
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        {{-- Form Login Laravel Breeze --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Input Email --}}
            <div class="mb-5">
                <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="shadow-sm appearance-none border rounded-lg w-full py-3 px-4 text-gray-800 leading-tight focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent @error('email') border-red-500 @enderror"
                       placeholder="contoh@jamumadura.com">
                @error('email')
                    <p class="text-red-600 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Input Password --}}
            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">Kata Sandi</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                       class="shadow-sm appearance-none border rounded-lg w-full py-3 px-4 text-gray-800 mb-3 leading-tight focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent @error('password') border-red-500 @enderror"
                       placeholder="••••••••">
                @error('password')
                    <p class="text-red-600 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember Me & Lupa Password --}}
            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center text-sm">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} class="form-checkbox h-4 w-4 text-green-600 rounded focus:ring-green-500">
                    <span class="ml-2 text-gray-700">Ingat Saya</span>
                </label>
                @if (Route::has('password.request'))
                    <a class="inline-block align-baseline font-semibold text-sm text-green-600 hover:text-green-800 transition-colors duration-200" href="{{ route('password.request') }}">
                        Lupa Kata Sandi?
                    </a>
                @endif
            </div>

            {{-- Tombol Login --}}
            <div class="flex items-center justify-center">
                <button type="submit"
                        class="bg-green-700 hover:bg-green-800 text-white font-bold py-3 px-6 rounded-lg focus:outline-none focus:shadow-outline transition-colors duration-200 w-full text-lg">
                    Masuk
                </button>
            </div>
        </form>

        {{-- Link ke Registrasi --}}
        <div class="text-center mt-6">
            <p class="text-gray-600">Belum punya akun?
                <a href="{{ route('register') }}" class="font-semibold text-green-600 hover:text-green-800 transition-colors duration-200">
                    Daftar di sini
                </a>
            </p>
        </div>

        {{-- Footer Informasi --}}
        <div class="text-center mt-8 text-sm text-gray-500">
            &copy; {{ date('Y') }} Supply Chain Jamu Madura. Hak Cipta Dilindungi.
        </div>
    </div>
</body>
</html>
