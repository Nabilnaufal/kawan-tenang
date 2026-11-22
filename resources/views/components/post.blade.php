@props([
    'post' => null,
    'time' => 'Baru saja',
    'likes' => 12,
    'replies' => 3,
    'tag' => null,
])

@php
    if (! $post) {
        $post = new \Illuminate\Support\Fluent([
            'id' => uniqid(),
            'body' => trim((string) $slot),
            'tag' => $tag,
            'likes_count' => $likes,
            'comments_count' => $replies,
            'liked' => false,
            'comments' => collect(),
            'created_at' => now(),
        ]);
    }

    $persistent = $post instanceof \App\Models\Post;
    $waktu = $post->exists ?? false ? $post->created_at->diffForHumans() : $time;
    $id = 'post-' . $post->id;
@endphp

<article class="rounded-[1.4rem] bg-card p-4 neu-sm sm:p-5" x-data="{ liked: {{ ($post->liked ?? false) ? 'true' : 'false' }}, likes: {{ $post->likes_count }}, showComments: false, report: false, sent: false, reason: 'Ujaran kebencian' }">
    <div class="flex items-start gap-3">
        <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-secondary text-secondary-foreground" aria-hidden="true"><x-icon name="user" :size="18" /></span>
        <div class="min-w-0 flex-1">
            <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                <span class="text-sm font-semibold">Anonim</span>
                <span class="text-xs text-muted-foreground">{{ $waktu }}</span>
                @if ($post->tag)<x-status tone="info">{{ $post->tag }}</x-status>@endif
            </div>
            <p class="mt-1 text-xs text-muted-foreground">Identitas disamarkan · hanya kamu yang tahu</p>
        </div>
        <button type="button" class="flex size-8 items-center justify-center rounded-lg text-muted-foreground hover:bg-muted" aria-label="Opsi postingan" @click="report = true"><x-icon name="more" :size="18" /></button>
    </div>

    <div class="mt-3 text-sm leading-relaxed text-foreground/90">{{ $post->body }}</div>

    <div class="mt-4 flex items-center gap-1 border-t border-border/60 pt-3 text-xs font-medium text-muted-foreground">
        @auth
        @if ($persistent)
            <button type="button" class="flex items-center gap-1.5 rounded-lg px-2 py-1.5 hover:bg-muted" :class="liked ? 'text-destructive' : ''" :aria-pressed="liked" @click="fetch('{{ route('bubble.suka', $post) }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' } }).then(r => r.json()).then(data => { liked = data.liked; likes = data.likes })">
                <x-icon name="heart" :size="16" x-bind:fill="liked ? 'currentColor' : 'none'" /><span x-text="likes">{{ $post->likes_count }}</span><span class="sr-only">suka</span>
            </button>
        @else
            <span class="flex items-center gap-1.5 px-2 py-1.5"><x-icon name="heart" :size="16" />{{ $post->likes_count }}</span>
        @endif
        @else
            <span class="flex items-center gap-1.5 px-2 py-1.5"><x-icon name="heart" :size="16" />{{ $post->likes_count }}</span>
        @endauth
        <button type="button" class="flex items-center gap-1.5 rounded-lg px-2 py-1.5 hover:bg-muted" :aria-expanded="showComments" @click="showComments = !showComments"><x-icon name="chat" :size="16" />{{ $post->comments_count }}<span class="sr-only">komentar</span></button>
        @auth
            <button type="button" class="ml-auto flex items-center gap-1.5 rounded-lg px-2 py-1.5 hover:bg-muted" @click="report = true"><x-icon name="flag" :size="15" />Laporkan</button>
        @endauth
    </div>

    <div x-show="showComments" x-cloak x-transition.opacity class="mt-3 space-y-3 border-t border-border/60 pt-3">
        @forelse ($post->comments as $comment)
            <div class="flex items-start gap-2.5"><span class="flex size-7 shrink-0 items-center justify-center rounded-full bg-secondary text-secondary-foreground"><x-icon name="user" :size="14" /></span><div class="rounded-xl bg-muted/70 px-3 py-2"><p class="text-xs font-semibold">Anonim <span class="ml-1 font-normal text-muted-foreground">{{ $comment->created_at->diffForHumans() }}</span></p><p class="mt-0.5 text-xs leading-relaxed">{{ $comment->body }}</p></div></div>
        @empty
            <p class="text-xs text-muted-foreground">Belum ada komentar. Jadi kawan pertama yang mendukung.</p>
        @endforelse
        @if ($persistent)
        @auth
            <form class="flex items-center gap-2" method="POST" action="{{ route('bubble.komentar', $post) }}">@csrf<input type="text" name="body" required maxlength="300" placeholder="Tulis dukungan sebagai Anonim…" aria-label="Tulis komentar" class="min-w-0 flex-1 rounded-xl bg-input px-3 py-2 text-xs neu-inset focus:outline-none"><button type="submit" class="flex size-9 items-center justify-center rounded-xl bg-primary text-primary-foreground" aria-label="Kirim komentar"><x-icon name="send" :size="15" /></button></form>
        @endauth
        @endif
    </div>

    @if ($persistent)
    @auth
    <div x-show="report" x-cloak x-transition.opacity class="fixed inset-0 z-50 flex items-end justify-center bg-foreground/40 p-4 backdrop-blur-sm sm:items-center" role="dialog" aria-modal="true" @keydown.escape.window="report = false">
        <div class="w-full max-w-md rounded-[1.4rem] bg-card p-5 neu" @click.outside="report = false">
            <div class="flex items-start justify-between gap-4"><div><h2 class="text-base font-semibold">Laporkan postingan</h2><p class="mt-1 text-xs text-muted-foreground">Laporanmu dikirim ke moderator.</p></div><button type="button" class="rounded-lg p-1 text-muted-foreground hover:bg-muted" aria-label="Tutup" @click="report = false"><x-icon name="x" :size="18" /></button></div>
            <template x-if="! sent"><form class="mt-4 space-y-3" method="POST" action="{{ route('bubble.lapor', $post) }}" @submit="sent = true">@csrf<label class="block text-xs font-medium text-muted-foreground">Alasan</label><select name="reason" x-model="reason" required class="w-full rounded-xl bg-input px-3 py-2.5 text-sm neu-inset focus:outline-none"><option>Ujaran kebencian</option><option>Pelecehan atau intimidasi</option><option>Membahayakan diri sendiri</option><option>Spam atau penipuan</option></select><label class="block text-xs font-medium text-muted-foreground">Catatan tambahan (opsional)</label><textarea name="note" rows="3" maxlength="500" class="w-full rounded-xl bg-input px-3 py-2.5 text-sm neu-inset focus:outline-none" placeholder="Ceritakan singkat apa yang terjadi…"></textarea><div class="flex justify-end gap-2"><button type="button" class="rounded-xl px-4 py-2 text-sm text-muted-foreground hover:bg-muted" @click="report = false">Batal</button><button type="submit" class="rounded-full bg-destructive px-4 py-2 text-sm font-semibold text-white">Kirim laporan</button></div></form></template>
            <div x-show="sent" x-cloak class="mt-5 flex items-start gap-3 rounded-xl bg-success/10 p-3"><span class="flex size-7 items-center justify-center rounded-full bg-success text-white"><x-icon name="check" :size="15" /></span><p class="text-xs leading-relaxed">Terima kasih. Laporanmu sudah masuk antrean moderasi.</p></div>
        </div>
    </div>
    @endauth
    @endif
</article>
