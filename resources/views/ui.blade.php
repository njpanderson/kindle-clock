@inject('weather', 'App\Services\WeatherService')

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Kindle</title>

        <!-- Fonts -->
        <!-- <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" /> -->

        @vite(['resources/css/app.css'])
    </head>

    <body class="font-sans antialiased">
        <main
            x-data="UI('{{ config('kindle.location.lat') }}', '{{ config('kindle.location.lng') }}')"
            class="absolute flex flex-col justify-center items-center overflow-hidden inset-0 bg-background text-foreground"
            :class="{
                'dark': state.ui.darkMode
            }"
            @touchstart="onClockClick"
        >
            <div
                class="absolute bg-foreground left-0 top-0 bottom-0 right-0 z-50"
                :class="{
                    'invisible': !state.ui.refresh
                }"
            ></div>
            <div class="w-full">
                {{-- Main clock interface --}}
                <x-clock/>

                <div class="m-auto mt-6">
                    <x-weather/>

                    <div class="flex items-center justify-center mt-6 text-2xl">
                        <span class="flex items-center">
                            <x-wi-sunrise class="w-10 mr-1"/>
                            <span x-text="state.sun.rises"></span>
                        </span>

                        <span class="flex items-center ml-4">
                            <x-wi-sunset class="w-10 mr-1"/>
                            <span x-text="state.sun.sets"></span>
                        </span>
                    </div>
                </div>

                <div class="absolute z-10 right-0 bottom-0 p-2">
                    <x-button
                        @click="openToolbar"
                        :bordered="false"
                        icon="heroicon-o-arrow-left"
                    />
                </div>
            </div>

            {{-- Toolbar --}}
            <nav
                class="absolute right-0 bottom-0 w-full z-20 overflow-hidden border-t border-foreground p-2 flex justify-end bg-background"
                :class="{
                    'translate-x-full': !state.toolbar.open,
                    'translate-x-0': state.toolbar.open
                }"
            >
                <x-button @click="toggleDarkMode">
                    <x-heroicon-o-sun class="size-6"/>
                    <x-heroicon-o-moon class="size-6"/>
                </x-button>

                <x-button class="ml-2" @click="toggleFullScreen" icon="heroicon-o-arrows-pointing-out"/>

                <x-button class="ml-2" @click="reload" icon="heroicon-o-arrow-path"/>

                <x-button class="ml-2" @click="requestPointerLock" icon="heroicon-o-cursor-arrow-rays"/>

                <x-button class="ml-2" @click="frontLightBoost" icon="heroicon-o-light-bulb"/>

                <x-button class="ml-2" @click="frontLightOff" icon="heroicon-s-light-bulb"/>

                <x-button
                    class="ml-2"
                    @click="closeToolbar"
                    icon="heroicon-o-arrow-right"
                />
            </nav>
        </main>

        @vite(['resources/js/app.js'])

        <svg hidden class="hidden">
            @stack('bladeicons')
        </svg>
    </body>
</html>
