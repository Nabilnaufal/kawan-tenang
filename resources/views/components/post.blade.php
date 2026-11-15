@props([
    'time' => 'Baru saja',
    'likes' => 12,
    'replies' => 3,
    'tag' => null,
])

@php
    $id = 'post-' . uniqid();
@endphp

<article
    class="rounded-2xl bg-card p-4 neu-sm sm:p-5"
    x-data="{ liked: false, likes: {{ (int) $likes }}, showComments: false, report: false, sent: false, reason: 'Ujaran kebencian' }"
>
    <div class="flex items-start gap-3">
        <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-secondary/50 text-secondary-foreground" aria-hidden="true">
            <x-icon name="user" :size="18" />
        </span>

        <div class="min-w-0 flex-1">
            <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                <span class="text-sm font-semibold">Anonim</span>
                <span class="text-xs text-muted-foreground">{{ $time }}</span>
                @if ($tag)
                    <x-status tone="info">{{ $tag }}</x-status>
                @endif
            </div>
            <p class="mt-1 text-xs text-muted-foreground">Identitas disamarkan · hanya kamu yang tahu</p>
        </div>

        <button
            type="button"
            class="flex size-8 items-center justify-center rounded-lg text-muted-foreground transition hover:bg-muted hover:text-foreground"
            aria-label="Opsi postingan"
            @click="report = true"
        >
            <x-icon name="more" :size="18" />
        </button>
    </div>

    <div class="mt-3 text-sm leading-relaxed text-foreground/90">
        {{ $slot }}
    </div>

    <div class="mt-4 flex items-center gap-1 border-t border-border/60 pt-3 text-xs font-medium text-muted-foreground">
        <button
            type="button"
            class="flex items-center gap-1.5 rounded-lg px-2 py-1.5 transition hover:bg-muted hover:text-foreground"
            :class="liked ? 'text-destructive' : ''"
            :aria-pressed="liked"
            @click="liked = !liked; likes += liked ? 1 : -1"
        >
            <x-icon name="heart" :size="16" x-bind:fill="liked ? 'currentColor' : 'none'" />
            <span x-text="likes">{{ (int) $likes }}</span>
            <span class="sr-only">suka</span>
        </button>

        <button
            type="button"
            class="flex items-center gap-1.5 rounded-lg px-2 py-1.5 transition hover:bg-muted hover:text-foreground"
            :aria-expanded="showComments"
            @click="showComments = !showComments"
        >
            <x-icon name="chat" :size="16" />
            {{ (int) $replies }}
            <span class="sr-only">komentar</span>
        </button>

        <button
            type="button"
            class="ml-auto flex items-center gap-1.5 rounded-lg px-2 py-1.5 transition hover:bg-muted hover:text-foreground"
            @click="report = true"
        >
            <x-icon name="flag" :size="15" />
            Laporkan
        </button>
    </div>

    <div x-show="showComments" x-transition.opacity class="mt-3 space-y-3 border-t border-border/60 pt-3">
        @isset($comments)
            {{ $comments }}
        @else
            <p class="text-xs text-muted-foreground">Belum ada komentar. Jadi kawan pertama yang mendukung.</p>
        @endisset

        <form class="flex items-center gap-2" @submit.prevent="$el.reset()">
            <input
                type="text"
                placeholder="Tulis dukungan sebagai Anonim…"
                aria-label="Tulis komentar"
                class="min-w-0 flex-1 rounded-xl bg-input px-3 py-2 text-xs text-foreground placeholder:text-muted-foreground neu-inset focus:outline-none"
            >
            <button
                type="submit"
                class="flex size-9 items-center justify-center rounded-xl bg-primary text-primary-foreground"
                aria-label="Kirim komentar"
            >
                <x-icon name="send" :size="15" />
            </button>
        </form>
    </div>

    {{-- Report dialog --}}
    <div
        x-show="report"
        x-cloak
        x-transition.opacity
        class="fixed inset-0 z-50 flex items-end justify-center bg-slate-text/35 p-4 backdrop-blur-sm sm:items-center"
        role="dialog"
        aria-modal="true"
        aria-labelledby="{{ $id }}-title"
        @keydown.escape.window="report = false"
    >
        <div class="w-full max-w-md rounded-2xl bg-card p-5 neu" @click.outside="report = false">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 id="{{ $id }}-title" class="text-base font-semibold">Laporkan postingan</h2>
                    <p class="mt-1 text-xs text-muted-foreground">Laporanmu dikirim ke moderator secara anonim.</p>
                </div>
                <button type="button" class="rounded-lg p-1 text-muted-foreground hover:bg-muted" aria-label="Tutup" @click="report = false">
                    <x-icon name="x" :size="18" />
                </button>
            </div>

            <template x-if="! sent">
                <form class="mt-4 space-y-3" @submit.prevent="sent = true">
                    <label class="block text-xs font-medium text-muted-foreground" for="{{ $id }}-reason">Alasan</label>
                    <select
                        id="{{ $id }}-reason"
                        x-model="reason"
                        class="w-full rounded-xl bg-input px-3 py-2.5 text-sm neu-inset focus:outline-none"
                    >
                        <option>Ujaran kebencian</option>
                        <option>Pelecehan atau intimidasi</option>
                        <option>Membahayakan diri sendiri</option>
                        <option>Spam atau penipuan</option>
                    </select>

                    <label class="block text-xs font-medium text-muted-foreground" for="{{ $id }}-note">Catatan tambahan (opsional)</label>
                    <textarea id="{{ $id }}-note" rows="3" class="w-full rounded-xl bg-input px-3 py-2.5 text-sm neu-inset focus:outline-none" placeholder="Ceritakan singkat apa yang terjadi…"></textarea>

                    <div class="flex justify-end gap-2 pt-1">
                        <button type="button" class="rounded-xl px-4 py-2 text-sm font-medium text-muted-foreground hover:bg-muted" @click="report = false">Batal</button>
                        <button type="submit" class="rounded-xl bg-destructive px-4 py-2 text-sm font-semibold text-white">Kirim laporan</button>
                    </div>
                </form>
            </template>

            <div x-show="sent" x-cloak class="mt-5 flex items-start gap-3 rounded-xl bg-success/10 p-3">
                <span class="flex size-7 items-center justify-center rounded-full bg-success text-white">
                    <x-icon name="check" :size="15" />
                </span>
                <p class="text-xs leading-relaxed">Terima kasih. Laporanmu sudah masuk antrean moderasi.</p>
            </div>
        </div>
    </div>
</article>
