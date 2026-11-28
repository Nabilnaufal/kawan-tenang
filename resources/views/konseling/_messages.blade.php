@forelse ($session->messages as $message)
    <div @class(['flex justify-end' => $message->sender_id === auth()->id(), 'flex items-start gap-2' => $message->sender_id !== auth()->id()])>
        @if ($message->sender_id !== auth()->id())
            <span class="flex size-7 shrink-0 items-center justify-center rounded-full bg-primary/15 text-xs font-semibold text-foreground">{{ mb_substr($name, 0, 1) }}</span>
        @endif
        <div class="max-w-[80%]">
            <p @class(['rounded-2xl px-3.5 py-2.5 text-xs leading-relaxed', 'rounded-tr-sm bg-primary text-primary-foreground' => $message->sender_id === auth()->id(), 'rounded-tl-sm bg-muted' => $message->sender_id !== auth()->id()])>{{ $message->body }}</p>
            <p @class(['mt-1 text-xs text-muted-foreground', 'text-right' => $message->sender_id === auth()->id()])>{{ $message->created_at->format('H.i') }}</p>
        </div>
    </div>
@empty
    <p class="text-center text-xs text-muted-foreground">Belum ada pesan. Mulai percakapan dengan aman.</p>
@endforelse