@props(['tone' => 'neutral'])

@php
    $tones = [
        'neutral' => 'bg-muted text-muted-foreground',
        'success' => 'bg-success/15 text-[#44624e]',
        'warning' => 'bg-accent/35 text-accent-foreground',
        'danger' => 'bg-destructive/15 text-[#8d4a46]',
        'info' => 'bg-secondary/45 text-secondary-foreground',
    ];
@endphp

<span {{ $attributes->class(['inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-semibold', $tones[$tone] ?? $tones['neutral']]) }}>
    <span class="size-1.5 rounded-full bg-current opacity-70" aria-hidden="true"></span>
    {{ $slot }}
</span>
