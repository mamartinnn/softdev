<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'MyUOS') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-200 antialiased" style="background: linear-gradient(135deg, #0a0f1e 0%, #0d1b3e 40%, #0a1628 100%);">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div>
                <a href="/" wire:navigate class="flex flex-col items-center">
                    <div class="w-16 h-16 flex items-center justify-center text-white text-3xl font-black rounded-2xl shadow-lg mb-2" style="background: linear-gradient(135deg, #1d4ed8, #7c3aed);">
                        🔧
                    </div>
                    <span class="text-2xl font-black tracking-wide text-transparent bg-clip-text" style="background-image: linear-gradient(135deg, #60a5fa, #eab308);">
                        MyUOS
                    </span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-8 shadow-2xl overflow-hidden sm:rounded-xl border" style="background: linear-gradient(135deg, rgba(15,23,42,0.95), rgba(30,27,75,0.9)); border-color: rgba(234,179,8,0.2); backdrop-filter: blur(12px);">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>