<div
    x-data="Clock"
    class="flex flex-col items-center justify-center"
>
    <div class="relative text-center">
        <span class="flex items-center leading-none text-[230px] tracking-wide font-display font-medium">
            <span x-text="state.time.hours"></span>
            <span><svg viewBox="0 0 79 235" class="w-6 mx-3"><g><circle cx="39.5" cy="39.5" r="39.5" fill="currentColor"/><circle cx="39.5" cy="195.5" r="39.5" fill="currentColor"/></g></svg></span>
            <span x-text="state.time.minutes"></span>
        </span>
        {{-- <span class="absolute overflow-hidden -right-[40px] -top-[20px] text-[24px] text-background bg-background bg-foreground-700 rounded-full w-[50px] h-[50px] flex items-center justify-center p-1.5" x-text="state.time.meridiem">pm</span> --}}
    </div>
    <div x-html="state.date" class="text-center text-[30px] bg-foreground text-background rounded-full px-4"></div>
</div>
