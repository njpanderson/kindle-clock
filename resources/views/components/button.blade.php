<button {{ $attributes->merge([
   'type' => 'button'
])->class([
    'flex items-center gap-2 px-3 py-3 bg-background text-lg',
    'border rounded-lg border-foreground' => $bordered
]) }}>
    @if(!empty($icon))
        @svg($icon, 'size-6')
    @endif
    {{ $slot }}
</button>
