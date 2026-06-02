<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'MyUOS') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    
    <!-- Background Gradient Sama dengan Dashboard -->
    <body class="font-sans text-gray-900 antialiased" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); min-height: 100vh;">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            
            <!-- Logo Aplikasi -->
            <div>
                <a href="/" wire:navigate class="flex flex-col items-center">
                 <img src="/images/logo.png" alt="Logo" class="w-12 h-12 rounded-lg object-cover" />
                    <span class="text-3xl font-black tracking-wide" style="background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        MyUOS
                    </span>
                </a>
            </div>

            <!-- Card Form Login / Register -->
            <div class="w-full sm:max-w-md mt-6 px-6 py-8 shadow-lg overflow-hidden sm:rounded-xl" style="background: #ffffff; border: 1px solid #e5e7eb;">
                {{ $slot }}
            </div>
            
        </div>
    </body>
</html>