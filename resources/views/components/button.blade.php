<button {{ $attributes->merge([
   'type' => 'button'
])->class([
    'flex items-center px-4 py-3 bg-background text-xl',
    'border rounded-lg border-foreground' => $bordered
]) }}>
    @if(!empty($icon))
        @svg($icon, 'size-6' . ($slot->hasActualContent() ? ' mr-2' : ''))
    @endif
    {{ $slot }}
</button>
