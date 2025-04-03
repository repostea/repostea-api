<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Login - {{ config('app.name') }}</title>


    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
<div class="max-w-md w-full">

    <div class="text-center mb-8">
        <div class="flex items-center justify-center">
            <i class="fas fa-share-alt text-blue-600 text-3xl mr-2"></i>
            <span class="text-2xl font-semibold text-gray-900">{{ config('app.name') }}</span>
        </div>
        <h2 class="mt-2 text-xl font-semibold text-gray-700">Panel de Administración</h2>
    </div>


    <div class="bg-white py-8 px-6 shadow rounded-lg">
        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf

            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
                <div class="mt-1">
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                           class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('email') border-red-500 @enderror">

                    @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                <div class="mt-1">
                    <input id="password" name="password" type="password" required
                           class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('password') border-red-500 @enderror">

                    @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox"
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-900">
                        Recordarme
                    </label>
                </div>
            </div>

            <div>
                <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Iniciar sesión
                </button>
            </div>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('home') }}" class="text-sm text-blue-600 hover:text-blue-500">
                Volver al sitio principal
            </a>
        </div>
    </div>
</div>
</body>
</html>
