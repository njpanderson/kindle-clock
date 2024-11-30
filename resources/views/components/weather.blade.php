<div x-data="Weather">
    <ul
        class="flex items-start mx-auto w-min"
    >
        <template x-for="day in state.daily" x-key="day.time">
            <li class="flex flex-col items-center w-1/3 grow-0 shrink-0 px-6">
                <span class="block w-full text-center text-3xl" x-text="day.dayName"></span>

                <span
                    class="flex size-24 justify-center"
                    x-html="day.day_icon"
                ></span>

                <span class="block w-full flex flex-wrap justify-center items-center mt-1 text-2xl">
                    <span class="flex items-center bg-split rounded-full ring-2 ring-foreground">
                        <span x-text="`${Math.round(day.temperature_2m_min)}°`" class="px-[10px] min-w-[70px] text-center"></span>
                        <span x-text="`${Math.round(day.temperature_2m_max)}°`" class="pr-[10px] min-w-[60px] text-center text-background"></span>
                    </span>

                    <span class="flex items-center justify-center mt-1 w-full" x-show="day.precipitation_probability > 3">
                        <x-wi-showers class="size-10"/>
                        <span x-text="`${day.precipitation_probability}%`"></span>
                    </span>
                </span>
            </li>
        </template>
    </ul>
</div>
