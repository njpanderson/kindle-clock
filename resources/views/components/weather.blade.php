<div x-data="Weather">
    <ul
        class="flex items-center mx-auto w-max"
    >
        <template x-for="day in state.daily" x-key="day.time">
            <li class="flex flex-col items-center min-w-1/3 max-w-1/3 grow-0 shrink-0 mx-6 text-2xl">
                <span class="block w-full text-center" x-text="day.dayName"></span>

                <span
                    class="flex size-24 justify-center"
                    x-html="day.day_icon"
                ></span>

                <span class="block w-full flex justify-center items-center">
                    <span class="flex items-center mr-2">
                        <x-heroicon-o-arrow-down class="size-4"/>
                        <span x-text="day.temperature_2m_min"></span>
                    </span>

                    <span class="flex items-center">
                        <x-heroicon-o-arrow-up class="size-4"/>
                        <span x-text="day.temperature_2m_max"></span>
                    </span>
                </span>
            </li>
        </template>
    </ul>
</div>
