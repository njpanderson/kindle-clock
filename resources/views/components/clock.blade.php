<div
    x-data="Clock"
    class="flex flex-col items-center justify-center bg-cover mx-4"
>
    <div
        class="flex items-start justify-center text-2xl text-foreground-500 gap-4"
        x-show="store.ui.mode === UIMode.full"
    >
        <span
            class="flex items-center w-max"
            x-show="state.sun.rises"
        >
            <x-wi-sunrise class="size-12 mr-1"/>
            <span x-text="state.sun.rises"></span>
        </span>

        <span
            class="flex items-center w-max"
            x-show="state.sun.sets"
        >
            <x-wi-sunset class="size-12 mr-1"/>
            <span x-text="state.sun.sets"></span>
        </span>
    </div>

    <div class="relative text-center">
        <span
            class="clock-text flex items-center leading-none tracking-wide font-display font-medium"
            :class="{
                'text-[220px] my-4': store.ui.mode === UIMode.full,
                'text-[140px]': store.ui.mode === UIMode.clock
            }"
        >
            <span x-text="state.time.hours"></span>
            <span>
                <svg
                    viewBox="0 0 79 235"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                    :class="{
                        'w-7': store.ui.mode === UIMode.full,
                        'w-6': store.ui.mode === UIMode.clock
                    }"
                ><circle cx="39.5" cy="39.5" r="35.5" fill="currentColor" style="stroke: var(--color-background-primary)" stroke-width="10"/><circle cx="39.5" cy="195.5" r="35.5" fill="currentColor" style="stroke: var(--color-background-primary)" stroke-width="10"/></svg>
            </span>
            <span x-text="state.time.minutes"></span>
        </span>
    </div>

    <div
        class="text-center bg-fore-back rounded-full ring-2 ring-foreground px-2.5 my-2"
        :class="{
            'py-1 text-[36px]': store.ui.mode === UIMode.full,
            'py-0 text-[32px]': store.ui.mode === UIMode.clock,
        }"
        :style="`--fore-width: ${state.phasePercentage}%;`"
    >
        <span
            class="flex items-center mix-blend-difference text-difference"
            :class="{
                'flex-row-reverse': store.sun.isNight
            }"
        >
            <template x-if="store.weather.daily && store.weather.daily.length > 1">
                <span class="flex size-16 justify-center" x-html="store.sun.isNight ? store.weather.daily[1].night_icon : store.weather.daily[1].day_icon"></span>
            </template>
            <template x-if="!(store.weather.daily && store.weather.daily.length > 1)">
                <x-heroicon-s-sun class="w-10"/>
            </template>
            <span x-html="state.date" class="mx-4" x-show="store.ui.mode === UIMode.full"></span>
            <span x-html="state.dateShort" class="mx-4" x-show="store.ui.mode === UIMode.clock"></span>
            <x-heroicon-s-moon class="w-10"/>
        </span>
    </div>
</div>
