@inject('weather', 'App\Services\WeatherService')

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Kindle Clock</title>

        <script>
            window.config = {
                tick: 20000, // ms
                brightness: {
                    initial: parseInt('{{ config('kindle.brightness.initial')}}', 10),
                    min: parseInt('{{ config('kindle.brightness.min')}}', 10),
                    max: parseInt('{{ config('kindle.brightness.max')}}', 10),
                }
            }
        </script>

        @vite(['resources/css/app.css'])
    </head>

    <body
        class="font-sans antialiased"
    >
        <main
            x-data="UI('{{ config('kindle.location.lat') }}', '{{ config('kindle.location.lng') }}')"
            class="absolute select-none overflow-hidden inset-0 bg-background text-foreground"
            :class="{
                'dark': store.ui.darkMode
            }"
        >
            {{-- main interface --}}
            <div
                class="grid w-full h-full bg-cover bg-center p-2"
                :class="{
                    'grid-cols-[1fr_max-content]': store.ui.mode === UIMode.clock,
                    'grid-cols-1': store.ui.mode === UIMode.full,
                }"
                @touchstart="onUIClick"
            >
                <div
                    class="flex items-center justify-center"
                    x-show="store.ui.mode === UIMode.clock"
                >
                    <div class="flex flex-col">
                        <img
                            src="/images/{{ $potd['src'] }}"
                            width="{{ $potd['width'] }}"
                            height="{{ $potd['height'] }}"
                            class="max-w-full h-auto rounded-lg"
                        >
                        <p class="mt-2">
                            {{ $potd['title'] }}
                            <i>{{ $potd['artist'] }} — {{ $potd['year'] }}</i>
                        </p>
                    </div>
                </div>

                {{-- Main clock interface --}}
                <x-clock/>

                <div
                    class="mx-auto mt-8"
                    x-show="store.ui.mode === UIMode.full"
                >
                    <x-weather/>
                </div>
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
