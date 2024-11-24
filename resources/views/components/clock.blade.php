<div
    x-data="Clock"
    class="flex flex-col items-center justify-center"
>
    <div class="relative text-center">
        <span class="flex items-center leading-none text-[200px] tracking-wider font-bold">
            <span x-text="state.clock.time.hours"></span>
            <span><svg viewBox="0 0 134.775 134.775" class="w-[60px] h-[120px] fill-current"><path d="m67.58 44.795c13.569 0 22.201-11.101 22.201-22.598 0-11.096-8.632-22.197-22.201-22.197-14.367 0-22.586 11.101-22.586 22.197 0 11.497 8.219 22.598 22.586 22.598z"/><path d="m67.58 134.775c13.569 0 22.201-11.101 22.201-22.602 0-11.101-8.632-22.193-22.201-22.193-14.367 0-22.586 11.093-22.586 22.193-0 11.502 8.219 22.602 22.586 22.602z"/></svg></span>
            <span x-text="state.clock.time.minutes"></span>
        </span>
        {{-- <span class="absolute overflow-hidden -right-[40px] -top-[20px] text-[24px] text-background bg-background bg-foreground-700 rounded-full w-[50px] h-[50px] flex items-center justify-center p-1.5" x-text="state.clock.time.meridiem">pm</span> --}}
    </div>
    <div x-html="state.clock.date" class="text-center text-[30px]"></div>
</div>
