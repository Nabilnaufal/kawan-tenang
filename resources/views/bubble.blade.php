<x-layouts.app title="Bubble Publik">
    <div class="mb-5">
        <h2 class="text-xl font-semibold tracking-tight sm:text-2xl">Bubble Publik</h2>
        <p class="mt-1 text-sm text-muted-foreground">Ruang berbagi cerita dengan identitas anonim. Nama dan emailmu tidak pernah ditampilkan.</p>
    </div>

    <div class="grid gap-5 xl:grid-cols-[2.4fr_1fr]">
        <div class="min-w-0">
            {{-- Composer --}}
            @auth
            <form
                class="rounded-[1.4rem] bg-card p-4 neu sm:p-5"
                x-data="{ text: '' }"
                action="{{ route('bubble.store') }}"
                method="POST"
            >
                @csrf
                <div class="flex gap-3">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-secondary text-secondary-foreground" aria-hidden="true">
                        <x-icon name="user" :size="18" />
                    </span>
                    <div class="min-w-0 flex-1">
                        <label for="cerita" class="sr-only">Ceritamu</label>
                        <textarea
                            id="cerita"
                            name="body"
                            rows="3"
                            maxlength="500"
                            x-model="text"
                            placeholder="Apa yang sedang kamu rasakan hari ini, Kawan?"
                            class="w-full resize-none rounded-xl bg-input px-4 py-3 text-sm leading-relaxed text-foreground placeholder:text-muted-foreground neu-inset focus:outline-none"
                        ></textarea>

                        <div class="mt-3 flex flex-wrap items-center gap-2">
                            <p class="flex items-center gap-1.5 text-xs text-muted-foreground">
                                <x-icon name="lock" :size="13" />
                                Diposting sebagai <span class="font-semibold text-foreground">Anonim</span>
                            </p>

                            <div class="ml-auto flex items-center gap-3">
                                <span class="text-xs tabular-nums text-muted-foreground" x-text="text.length + '/500'">0/500</span>
                                <button
                                    type="submit"
                                    :disabled="text.trim().length === 0"
                                    class="btn-lime px-4 py-2 text-xs disabled:opacity-40"
                                >
                                    <x-icon name="send" :size="14" />
                                    Kirim
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            @else
                <div class="rounded-[1.4rem] bg-card p-5 neu sm:p-6">
                    <p class="text-sm font-semibold">Ingin berbagi cerita?</p>
                    <p class="mt-1 text-xs text-muted-foreground">Masuk dulu agar ceritamu bisa tersimpan secara anonim.</p>
                    <a href="{{ route('masuk') }}" class="btn-lime mt-4 text-xs">Masuk untuk menulis</a>
                </div>
            @endauth

            {{-- Filters --}}
            <div class="mt-5 flex flex-wrap gap-2 pb-1" role="tablist" aria-label="Filter cerita" x-data="{ tab: 0 }">
                @foreach (['Semua', 'Terbaru', 'Populer', 'Perlu dukungan'] as $i => $filter)
                    <button
                        type="button"
                        role="tab"
                        @click="tab = {{ $i }}"
                        :aria-selected="tab === {{ $i }}"
                        :class="tab === {{ $i }} ? 'bg-primary text-primary-foreground' : 'bg-card text-muted-foreground hover:text-foreground'"
                        class="shrink-0 rounded-full px-4 py-2 text-xs font-semibold transition"
                    >{{ $filter }}</button>
                @endforeach
            </div>

            {{-- Feed --}}
            <div class="mt-4 space-y-3">
                @forelse ($posts as $post)
                    <x-post :post="$post" />
                @empty
                    <div class="rounded-[1.4rem] bg-card p-6 text-center neu-sm"><p class="text-sm font-semibold">Belum ada cerita</p><p class="mt-1 text-xs text-muted-foreground">Jadilah orang pertama yang berbagi hari ini.</p></div>
                @endforelse
            </div>

            <p class="mt-5 text-center text-xs text-muted-foreground">
                Menampilkan {{ $posts->count() }} dari {{ $posts->total() }} cerita.
            </p>
            <div class="mt-4">{{ $posts->links() }}</div>
        </div>

        {{-- Aside --}}
        <aside class="hidden space-y-3 xl:block">
            <div class="rounded-[1.4rem] bg-card p-5 neu-sm">
                <h2 class="text-sm font-semibold">Panduan Bubble</h2>
                <ul class="mt-3 space-y-2.5 text-xs leading-relaxed text-muted-foreground">
                    @foreach ([
                        'Jangan bagikan data pribadi siapa pun.',
                        'Kritik ide, bukan orangnya.',
                        'Jika melihat konten berbahaya, laporkan.',
                        'Jangan memberi diagnosis atau saran medis.',
                    ] as $rule)
                        <li class="flex gap-2">
                            <x-icon name="check" :size="14" class="mt-0.5 text-success" />
                            {{ $rule }}
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="rounded-2xl bg-secondary/30 p-5">
                <h2 class="text-sm font-semibold">Butuh teman bicara?</h2>
                <p class="mt-1 text-xs leading-relaxed text-muted-foreground">
                    Cerita yang terlalu berat bisa kamu lanjutkan secara privat dengan konselor.
                </p>
                <a href="{{ route('konseling.index') }}" class="mt-3 flex items-center justify-center gap-2 rounded-full bg-card px-4 py-2.5 text-xs font-semibold neu-sm transition hover:bg-muted">
                    <x-icon name="chat" :size="14" />
                    Mulai konseling
                </a>
            </div>

            <div class="rounded-2xl border border-accent/50 bg-accent/15 p-5">
                <h2 class="flex items-center gap-2 text-sm font-semibold">
                    <x-icon name="life-buoy" :size="15" />
                    Kondisi darurat
                </h2>
                <p class="mt-1 text-xs leading-relaxed text-muted-foreground">
                    Hubungi 119 ext. 8 atau 112. Tim Kawan Tenang tidak memantau Bubble 24 jam.
                </p>
            </div>
        </aside>
    </div>
</x-layouts.app>
