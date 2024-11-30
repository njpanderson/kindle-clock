<nav
    class="absolute flex flex-col right-0 bottom-0 items-end justify-end h-full z-20 overflow-hidden border-l border-foreground p-2 bg-background-300"
    :class="{
        'translate-x-full': !store.toolbar.open,
        'translate-x-0': store.toolbar.open
    }"
>
    <x-button class="mb-2" @click="setupKindle" icon="heroicon-o-cog-8-tooth">
        Init Kindle
    </x-button>

    <x-button @click="toggleDarkMode">
        <x-heroicon-o-sun class="size-6"/>
        <x-heroicon-o-moon class="size-6 mr-2"/>
        Toggle dark mode
    </x-button>

    <x-button class="mt-2" @click="toggleFullScreen" icon="heroicon-o-arrows-pointing-out">
        Full screen
    </x-button>

    <x-button class="mt-2" @click="reload" icon="heroicon-o-arrow-path">
        Reload
    </x-button>

    <x-button class="mt-2" @click="requestPointerLock" icon="heroicon-o-cursor-arrow-rays">
        Hide pointer
    </x-button>

    <x-button class="mt-2" @click="frontLightBoost" icon="heroicon-s-light-bulb">
        Front light boost
    </x-button>

    <x-button class="mt-2" @click="frontLightOff" icon="heroicon-o-light-bulb">
        Front light off
    </x-button>

    <div class="mt-2 w-full text-right px-2 pb-4">
        <label class="block mb-2 text-xl">Brightness</label>

        <div class="flex items-center">
            <input
                type="range"
                step="1"
                min="{{ config('kindle.brightness.min') }}"
                max="{{ config('kindle.brightness.max') }}"
                class="basis-full h-2 bg-foreground rounded-lg appearance-none cursor-pointer h-10 dark:bg-gray-700"
                x-model="store.ui.fields.brightness"
                @change.throttle.1000ms="brightness(parseInt(store.ui.fields.brightness, 10), true)"
            >
            <span class="grow-0 ml-2 text-xl w-[3ch]" x-text="store.ui.fields.brightness"></span>
        </div>
    </div>

    <x-button
        class="mt-2"
        @click="closeToolbar"
        icon="heroicon-o-arrow-right"
    />
</nav>
