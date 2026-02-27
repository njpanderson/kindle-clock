<div
    class="flex items-center justify-center h-full"
    x-show="store.ui.mode === UIMode.full"
>
    <div class="grid grid-rows-[1fr_max-content] w-full h-full">
        <span
            style="background-image: url('/images/{{ $potd['src'] }}')"
            class="block w-full h-full rounded-lg overflow-hidden bg-cover bg-center"
        ></span>

        <p class="mt-2 leading-5">
            <b>{{ $potd['title'] }}</b><br>
            <i>{{ $potd['artist'] }} — {{ $potd['year'] }}</i>
        </p>
    </div>
</div>
