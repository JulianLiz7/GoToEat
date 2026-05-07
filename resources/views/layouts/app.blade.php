<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'GoToEat') }}</title>
    @include('partials.head-assets')
    @livewireStyles
</head>
<body class="bg-background font-body text-on-background min-h-screen">

    @include('layouts.navigation')

    @isset($header)
        <div class="bg-white border-b border-outline-variant">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
                {{ $header }}
            </div>
        </div>
    @endisset

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
