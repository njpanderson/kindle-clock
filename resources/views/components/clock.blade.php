<div
    x-data="Clock"
    class="flex flex-col items-center justify-center"
>
    <div class="relative leading-none text-center">
        <span class="text-[200px] tracking-wider font-bold" x-text="state.clock.time.digits"></span>
        {{-- <span class="absolute overflow-hidden -right-[40px] -top-[20px] text-[24px] text-background bg-background bg-foreground-700 rounded-full w-[50px] h-[50px] flex items-center justify-center p-1.5" x-text="state.clock.time.meridiem">pm</span> --}}
    </div>
    <div x-html="state.clock.date" class="text-center text-[30px]"></div>
</div>
