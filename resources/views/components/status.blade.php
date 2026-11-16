@props(['tone' => 'neutral'])

@php
    $tones = [
        'neutral' => 'bg-muted-foreground/50',
        'success' => 'bg-success',
        'warning' => 'bg-[#c9971f]',
        'danger' => 'bg-destructive',
        'info' => 'bg-[#4c7dbf]',
    ];
@endphp

<span {{ $attributes->class(['inline-flex items-center gap-1.5 rounded-full border border-border bg-card px-2.5 py-1 text-[0.78rem] font-medium text-foreground']) }}>
    <span class="size-1.5 rounded-full {{ $tones[$tone] ?? $tones['neutral'] }}" aria-hidden="true"></span>
    {{ $slot }}
</span>
