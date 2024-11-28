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
        class="bg-background font-sans antialiased"
        {{-- style="background-image: url('images/potd/night/32305g1.jpg')" --}}
    >
        <main
            x-data="UI('{{ config('kindle.location.lat') }}', '{{ config('kindle.location.lng') }}')"
            class="absolute flex flex-col justify-center items-center overflow-hidden inset-0 bg-background text-foreground"
            :class="{
                'dark': state.ui.darkMode
            }"
        >
            {{-- refresh layer, nothing to see here --}}
            <div
                class="absolute bg-foreground left-0 top-0 bottom-0 right-0 z-50"
                :class="{
                    'invisible': !state.ui.refresh
                }"
            ></div>

            {{-- main interface --}}
            <div
                class="w-full"
                @touchstart="onClockClick"
            >
                {{-- Main clock interface --}}
                <x-clock/>

                <div class="m-auto mt-8">
                    <x-weather/>

                    <div class="flex items-center justify-center mt-4 text-3xl">
                        <span
                            class="flex items-center"
                            x-show="state.sun.rises"
                        >
                            <x-wi-sunrise class="size-12 mr-1"/>
                            <span x-text="state.sun.rises"></span>
                        </span>

                        <span
                            class="flex items-center ml-4"
                            x-show="state.sun.sets"
                        >
                            <x-wi-sunset class="size-12 mr-1"/>
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
            <x-toolbar/>
        </main>

        @vite(['resources/js/app.js'])

        <svg hidden class="hidden">
            @stack('bladeicons')
        </svg>
    </body>
</html>
