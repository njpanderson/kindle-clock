<ul
    class="flex items-center"
    x-ref="weatherList"
>
    @foreach($forecast['daily'] as $day)
        <li class="mx-6">
            <span class="block w-full text-center">{{ $day['dayName'] }}</span>

            <span class="flex w-full justify-center">
                @svg($day['image']['day']['icon'], 'size-20 text-foreground-700"')
            </span>

            <span class="block w-full flex justify-center items-center">
                <span class="flex items-center mr-2"><x-heroicon-o-arrow-up class="size-4"/>{{ $day['temperature_2m_max'] }}°</span>
                <span class="flex items-center"><x-heroicon-o-arrow-down class="size-4"/>{{ $day['temperature_2m_min'] }}°</span>
            </span>
        </li>
    @endforeach
</ul>
