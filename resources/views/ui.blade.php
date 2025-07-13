@inject('weather', 'App\Services\WeatherService')

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Kindle Clock</title>

        <script>
            window.config = {
                tick: 20000, //ms
                ...{!! json_encode(config('kindle')) !!}
            }
        </script>

        @vite(['resources/css/app.css'])
    </head>

    <body
        class="font-sans antialiased"
    >
        <main
            x-data="UI('{{ config('kindle.location.lat') }}', '{{ config('kindle.location.lng') }}')"
            class="absolute select-none overflow-hidden inset-0 bg-background text-foreground max-h-full"
            :class="{
                'dark': store.ui.darkMode
            }"
            @touchstart="onUIClick"
            @keyup.window.ctrl.r="reload"
        >
            <!-- Main UI -->
            <div
                class="grid w-full h-full p-3"
                :class="{
                    'grid-cols-[45%_1fr]': store.ui.mode === UIMode.clock,
                    'grid-cols-1': store.ui.mode === UIMode.full,
                }"
            >
                <!-- Image -->
                <x-potd-image :$potd/>

                <div class="flex flex-col items-center justify-center">
                    <!-- Clock -->
                    <x-clock/>

                    <!-- Weather -->
                    <div class="mx-auto mt-4">
                        <x-weather/>
                    </div>
                </div>
            </div>

            <div class="absolute z-10 right-0 top-0 p-2 flex">
                <span x-show="store.ui.lux !== null" class="flex items-center text-gray-500">
                    <x-heroicon-s-light-bulb class="size-6"/>
                    <span x-text="store.ui.lux"></span>
                </span>

                <x-button
                    @click="toggleUIMode"
                    :bordered="false"
                >
                    <x-heroicon-s-clock class="size-6" x-show="store.ui.mode === UIMode.clock"/>
                    <x-heroicon-s-photo class="size-6" x-show="store.ui.mode === UIMode.full"/>
                </x-button>
            </div>

            <div class="absolute z-10 right-0 bottom-0 p-2">
                <x-button
                    @click="openToolbar"
                    :bordered="false"
                    icon="heroicon-o-arrow-left"
                />
            </div>

            {{-- Toolbar --}}
            <x-toolbar/>

            {{-- refresh layer, nothing to see here --}}
            <div
                class="absolute bg-foreground left-0 top-0 bottom-0 right-0 z-50"
                :class="{
                    'invisible': !store.ui.refresh
                }"
            ></div>
        </main>

        @vite(['resources/js/app.js'])

        <svg hidden class="hidden">
            @stack('bladeicons')
        </svg>
    </body>
</html>
