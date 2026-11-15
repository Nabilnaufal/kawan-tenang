<x-layouts.app title="Bubble Publik">
    <div class="mb-5">
        <h2 class="text-xl font-semibold tracking-tight sm:text-2xl">Bubble Publik</h2>
        <p class="mt-1 text-sm text-muted-foreground">Ruang berbagi cerita dengan identitas anonim. Nama dan emailmu tidak pernah ditampilkan.</p>
    </div>

    <div class="grid gap-5 xl:grid-cols-[2.4fr_1fr]">
        <div class="min-w-0">
            {{-- Composer --}}
            <form
                class="rounded-2xl bg-card p-4 neu sm:p-5"
                x-data="{ text: '' }"
                @submit.prevent="text = ''"
            >
                <div class="flex gap-3">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-secondary/50 text-secondary-foreground" aria-hidden="true">
                        <x-icon name="user" :size="18" />
                    </span>
                    <div class="min-w-0 flex-1">
                        <label for="cerita" class="sr-only">Ceritamu</label>
                        <textarea
                            id="cerita"
                            rows="3"
                            maxlength="500"
                            x-model="text"
                            placeholder="Apa yang sedang kamu rasakan hari ini, Kawan?"
                            class="w-full resize-none rounded-xl bg-input px-4 py-3 text-sm leading-relaxed text-foreground placeholder:text-muted-foreground neu-inset focus:outline-none"
                        ></textarea>

                        <div class="mt-3 flex flex-wrap items-center gap-2">
                            <p class="flex items-center gap-1.5 text-[11px] text-muted-foreground">
                                <x-icon name="lock" :size="13" />
                                Diposting sebagai <span class="font-semibold text-foreground">Anonim</span>
                            </p>

                            <div class="ml-auto flex items-center gap-3">
                                <span class="text-[11px] tabular-nums text-muted-foreground" x-text="text.length + '/500'">0/500</span>
                                <button
                                    type="submit"
                                    :disabled="text.trim().length === 0"
                                    class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2 text-xs font-semibold text-primary-foreground transition enabled:hover:bg-brand-deep disabled:opacity-40"
                                >
                                    <x-icon name="send" :size="14" />
                                    Kirim
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Filters --}}
            <div class="mt-5 flex gap-2 overflow-x-auto pb-1 no-scrollbar" role="tablist" aria-label="Filter cerita">
                @foreach (['Semua', 'Terbaru', 'Populer', 'Perlu dukungan'] as $i => $filter)
                    <button
                        type="button"
                        role="tab"
                        @class([
                            'shrink-0 rounded-full px-4 py-2 text-xs font-semibold transition',
                            'bg-primary text-primary-foreground' => $i === 0,
                            'bg-card text-muted-foreground hover:text-foreground' => $i !== 0,
                        ])
                        aria-selected="{{ $i === 0 ? 'true' : 'false' }}"
                    >{{ $filter }}</button>
                @endforeach
            </div>

            {{-- Feed --}}
            <div class="mt-4 space-y-3">
                <x-post :likes="37" :replies="6" time="8 menit lalu" tag="Perlu dukungan">
                    Aku takut banget besok presentasi. Sudah seminggu nggak bisa tidur nyenyak dan kepala terus penuh. Ada yang punya cara biar lebih tenang?
                </x-post>

                <x-post :likes="24" :replies="4" time="42 menit lalu">
                    Hari ini aku berhasil bangun pagi dan keluar rumah walau rasanya berat. Kecil, tapi aku mau mengapresiasi diriku sendiri.
                    <x-slot:comments>
                        <div class="flex items-start gap-2.5">
                            <span class="flex size-7 shrink-0 items-center justify-center rounded-full bg-secondary/50 text-secondary-foreground">
                                <x-icon name="user" :size="14" />
                            </span>
                            <div class="rounded-xl bg-muted/70 px-3 py-2">
                                <p class="text-[11px] font-semibold">Anonim <span class="ml-1 font-normal text-muted-foreground">20 menit lalu</span></p>
                                <p class="mt-0.5 text-xs leading-relaxed">Bangga banget sama kamu. Langkah kecil tetap langkah. 🤍</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <span class="flex size-7 shrink-0 items-center justify-center rounded-full bg-secondary/50 text-secondary-foreground">
                                <x-icon name="user" :size="14" />
                            </span>
                            <div class="rounded-xl bg-muted/70 px-3 py-2">
                                <p class="text-[11px] font-semibold">Anonim <span class="ml-1 font-normal text-muted-foreground">12 menit lalu</span></p>
                                <p class="mt-0.5 text-xs leading-relaxed">Aku juga sedang di fase yang sama. Kita pelan-pelan ya.</p>
                            </div>
                        </div>
                    </x-slot:comments>
                </x-post>

                <x-post :likes="58" :replies="12" time="2 jam lalu">
                    Mengingatkan diri sendiri: kamu tidak harus punya alasan besar untuk merasa lelah. Istirahat itu bukan kemalasan.
                </x-post>
            </div>

            <p class="mt-5 text-center text-[11px] text-muted-foreground">
                Kamu sudah melihat 3 dari 24 cerita.
            </p>
        </div>

        {{-- Aside --}}
        <aside class="hidden space-y-3 xl:block">
            <div class="rounded-2xl bg-card p-5 neu-sm">
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
                <a href="{{ route('konseling.index') }}" class="mt-3 flex items-center justify-center gap-2 rounded-xl bg-card px-4 py-2.5 text-xs font-semibold neu-sm transition hover:bg-muted">
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
