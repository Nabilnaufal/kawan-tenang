<x-layouts.app title="Konseling">
    <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
        <div>
            <h2 class="text-xl font-semibold tracking-tight sm:text-2xl">Konseling Privat</h2>
            <p class="mt-1 text-sm text-muted-foreground">Percakapan empat mata dengan konselor yang ditugaskan untukmu.</p>
        </div>

        <div x-data="{ open: false, sent: false, topic: 'Kecemasan' }">
            <button
                type="button"
                class="btn-lime text-xs"
                @click="open = true"
            >
                <x-icon name="plus" :size="15" />
                Ajukan sesi baru
            </button>

            <div
                x-show="open"
                x-cloak
                x-transition.opacity
                class="fixed inset-0 z-50 flex items-end justify-center bg-foreground/40 p-4 backdrop-blur-sm sm:items-center"
                role="dialog"
                aria-modal="true"
                aria-labelledby="sesi-title"
                @keydown.escape.window="open = false"
            >
                <div class="w-full max-w-md rounded-[1.4rem] bg-card p-5 neu" @click.outside="open = false">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 id="sesi-title" class="text-base font-semibold">Ajukan sesi konseling</h2>
                            <p class="mt-1 text-xs text-muted-foreground">Konselor akan ditugaskan dalam 1×24 jam.</p>
                        </div>
                        <button type="button" class="rounded-lg p-1 text-muted-foreground hover:bg-muted" aria-label="Tutup" @click="open = false">
                            <x-icon name="x" :size="18" />
                        </button>
                    </div>

                    <template x-if="! sent">
                        <form class="mt-4 space-y-3" method="POST" action="{{ route('konseling.store') }}" @submit="sent = true">
                            @csrf
                            <label for="topik" class="block text-xs font-medium text-muted-foreground">Topik utama</label>
                            <select id="topik" name="topic" x-model="topic" required class="w-full rounded-xl bg-input px-3 py-2.5 text-sm neu-inset focus:outline-none">
                                <option>Kecemasan</option>
                                <option>Tekanan akademik</option>
                                <option>Masalah keluarga</option>
                                <option>Kesepian</option>
                                <option>Lainnya</option>
                            </select>

                            <label for="catatan" class="block text-xs font-medium text-muted-foreground">Ceritakan singkat (opsional)</label>
                            <textarea id="catatan" name="note" maxlength="2000" rows="3" class="w-full rounded-xl bg-input px-3 py-2.5 text-sm neu-inset focus:outline-none" placeholder="Apa yang ingin kamu bahas?"></textarea>

                            <p class="flex items-start gap-2 rounded-xl bg-muted/70 p-3 text-xs leading-relaxed text-muted-foreground">
                                <x-icon name="lock" :size="13" class="mt-0.5" />
                                Catatan ini hanya dibaca oleh konselor yang ditugaskan.
                            </p>

                            <div class="flex justify-end gap-2 pt-1">
                                <button type="button" class="rounded-xl px-4 py-2 text-sm font-medium text-muted-foreground hover:bg-muted" @click="open = false">Batal</button>
                                <button type="submit" class="btn-lime">Kirim pengajuan</button>
                            </div>
                        </form>
                    </template>

                    <div x-show="sent" x-cloak class="mt-5 space-y-3">
                        <div class="flex items-start gap-3 rounded-xl bg-success/10 p-3">
                            <span class="flex size-7 shrink-0 items-center justify-center rounded-full bg-success text-white">
                                <x-icon name="check" :size="15" />
                            </span>
                            <p class="text-xs leading-relaxed">Pengajuanmu terkirim. Kami akan memberi kabar lewat notifikasi.</p>
                        </div>
                        <button type="button" class="w-full rounded-xl bg-muted px-4 py-2.5 text-sm font-semibold" @click="open = false; sent = false">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-4 lg:grid-cols-[340px_1fr]">
        {{-- Session list --}}
        <div class="min-w-0 space-y-2.5">
            <div class="flex items-center justify-between px-1">
                <h3 class="text-xs font-semibold tracking-wide text-muted-foreground uppercase">Sesi kamu</h3>
                <span class="text-xs text-muted-foreground">3 sesi</span>
            </div>

            <a href="{{ route('konseling.show', 1) }}" class="flex items-start gap-3 rounded-[1.4rem] bg-card p-3.5 neu-sm ring-1 ring-primary/25">
                <span class="relative flex size-10 shrink-0 items-center justify-center rounded-full bg-primary/15 text-sm font-semibold text-foreground">
                    R
                    <span class="absolute -right-0.5 -bottom-0.5 size-2.5 rounded-full border-2 border-card bg-success"></span>
                </span>
                <span class="min-w-0 flex-1">
                    <span class="flex items-center gap-2">
                        <span class="truncate text-sm font-semibold">Kak Rani</span>
                        <span class="ml-auto shrink-0 text-xs text-muted-foreground">09.20</span>
                    </span>
                    <span class="mt-0.5 block truncate text-xs text-muted-foreground">Boleh, aku coba. Makasih sudah dengerin.</span>
                    <span class="mt-1.5 flex items-center gap-2">
                        <x-status tone="success">Aktif</x-status>
                        <span class="flex size-4 items-center justify-center rounded-full bg-primary text-xs font-bold text-primary-foreground">1</span>
                    </span>
                </span>
            </a>

            <a href="{{ route('konseling.show', 2) }}" class="flex items-start gap-3 rounded-[1.4rem] bg-card p-3.5 neu-sm transition hover:bg-muted">
                <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-secondary text-sm font-semibold text-secondary-foreground">D</span>
                <span class="min-w-0 flex-1">
                    <span class="flex items-center gap-2">
                        <span class="truncate text-sm font-semibold">Kak Dimas</span>
                        <span class="ml-auto shrink-0 text-xs text-muted-foreground">Kemarin</span>
                    </span>
                    <span class="mt-0.5 block truncate text-xs text-muted-foreground">Sampai jumpa di sesi berikutnya ya.</span>
                    <span class="mt-1.5 flex items-center gap-2">
                        <x-status>Selesai</x-status>
                    </span>
                </span>
            </a>

            <div class="flex items-start gap-3 rounded-2xl border border-dashed border-border bg-card/50 p-3.5">
                <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-muted text-muted-foreground">
                    <x-icon name="clock" :size="17" />
                </span>
                <span class="min-w-0 flex-1">
                    <span class="block text-sm font-semibold">Menunggu konselor</span>
                    <span class="mt-0.5 block text-xs text-muted-foreground">Pengajuanmu sedang ditinjau.</span>
                    <span class="mt-1.5 inline-flex"><x-status tone="warning">Menunggu</x-status></span>
                </span>
            </div>

            <p class="flex items-start gap-2 rounded-2xl bg-secondary/25 p-3.5 text-xs leading-relaxed text-muted-foreground">
                <x-icon name="shield" :size="14" class="mt-0.5 shrink-0" />
                Setiap percakapan memiliki kontrol akses sendiri. Konselor hanya bisa membuka sesi yang ditugaskan.
            </p>
        </div>

        {{-- Desktop chat preview --}}
        <div class="hidden min-w-0 lg:block">
            <x-chat-panel name="Kak Rani" role="Konselor keluarga" state="Sesi aktif" />
        </div>
    </div>
</x-layouts.app>
