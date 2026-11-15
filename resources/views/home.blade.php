<x-layouts.app title="Beranda">
    {{-- Welcome --}}
    <section class="rounded-2xl bg-card p-5 neu-sm sm:p-7">
        <div class="flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
            <div class="max-w-2xl">
                <h2 class="text-2xl leading-tight font-semibold tracking-tight sm:text-3xl">
                    Halo, Kawan. Tidak apa-apa untuk tidak baik-baik saja.
                </h2>
                <p class="mt-2.5 max-w-xl text-sm leading-relaxed text-muted-foreground">
                    Ceritakan apa yang sedang kamu rasakan tanpa takut dikenali, atau ngobrol privat dengan konselor yang siap mendengarkan.
                </p>

                <div class="mt-5 flex flex-col gap-2 sm:flex-row">
                    <a href="{{ route('bubble') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-primary px-5 py-2.5 text-sm font-semibold text-primary-foreground neu-primary transition hover:bg-brand-deep">
                        Mulai bercerita
                        <x-icon name="arrow-right" :size="16" />
                    </a>
                    <a href="{{ route('konseling.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-background px-5 py-2.5 text-sm font-semibold neu-sm transition hover:bg-muted">
                        <x-icon name="chat" :size="16" />
                        Cari konselor
                    </a>
                </div>
            </div>

            <dl class="grid grid-cols-3 gap-5 border-t border-border/60 pt-5 xl:min-w-[340px] xl:border-t-0 xl:pt-0">
                @foreach ([['1.284', 'cerita dibagikan'], ['32', 'konselor aktif'], ['98%', 'merasa didengar']] as [$value, $label])
                    <div>
                        <dt class="text-xs leading-tight text-muted-foreground">{{ $label }}</dt>
                        <dd class="mt-0.5 text-lg font-semibold tracking-tight tabular-nums" data-count="{{ $value }}">0</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </section>

    {{-- Emergency notice --}}
    <section class="mt-3 flex items-start gap-3 rounded-xl border border-accent/50 bg-accent/15 px-4 py-3">
        <x-icon name="life-buoy" :size="16" class="mt-0.5 text-accent-foreground" />
        <p class="text-xs leading-relaxed">
            <span class="font-semibold">Butuh bantuan segera?</span>
            Kawan Tenang bukan layanan darurat. Hubungi <a href="tel:119" class="font-semibold underline underline-offset-2">119 ext. 8</a> atau <a href="tel:112" class="font-semibold underline underline-offset-2">112</a>.
        </p>
    </section>

    {{-- Areas --}}
    <section class="mt-7">
        <h2 class="text-base font-semibold tracking-tight">Tiga ruang untukmu</h2>

        <div class="mt-3 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ([
                ['icon' => 'bubble', 'title' => 'Bubble Publik', 'text' => 'Bagikan cerita dan saling menguatkan dengan identitas anonim.', 'href' => route('bubble'), 'tone' => 'bg-secondary/40 text-secondary-foreground'],
                ['icon' => 'chat', 'title' => 'Konseling Privat', 'text' => 'Ngobrol empat mata dengan konselor, hanya kalian yang bisa membaca.', 'href' => route('konseling.index'), 'tone' => 'bg-primary/15 text-brand-deep'],
                ['icon' => 'shield', 'title' => 'Verifikasi Akun', 'text' => 'Bantu jaga komunitas tetap aman lewat verifikasi liveness.', 'href' => route('verifikasi'), 'tone' => 'bg-accent/35 text-accent-foreground'],
            ] as $card)
                <a href="{{ $card['href'] }}" class="group flex items-start gap-3 rounded-2xl bg-card p-4 neu-sm transition hover:bg-cream">
                    <span class="flex size-9 shrink-0 items-center justify-center rounded-lg {{ $card['tone'] }}">
                        <x-icon :name="$card['icon']" :size="18" />
                    </span>
                    <span class="min-w-0">
                        <span class="block text-sm font-semibold">{{ $card['title'] }}</span>
                        <span class="mt-0.5 block text-xs leading-relaxed text-muted-foreground">{{ $card['text'] }}</span>
                    </span>
                    <x-icon name="chevron-right" :size="16" class="ml-auto mt-1 shrink-0 text-muted-foreground transition group-hover:translate-x-0.5" />
                </a>
            @endforeach
        </div>
    </section>

    {{-- Feed + aside --}}
    <div class="mt-7 grid gap-5 xl:grid-cols-[2.2fr_1fr]">
        <section class="min-w-0">
            <div class="flex items-center justify-between gap-4">
                <h2 class="text-base font-semibold tracking-tight">Cerita terbaru</h2>
                <a href="{{ route('bubble') }}" class="text-xs font-semibold text-brand-deep hover:underline">Lihat semua</a>
            </div>

            <div class="mt-3 space-y-3">
                <x-post :likes="24" :replies="5" time="12 menit lalu" tag="Hari ini berat">
                    Hari ini rasanya capek banget, tapi aku nggak tahu harus cerita ke siapa. Makasih sudah jadi ruang yang tenang.
                </x-post>
                <x-post :likes="41" :replies="9" time="1 jam lalu">
                    Tips kecil buat kamu yang lagi cemas: coba tarik napas 4 detik, tahan 4 detik, buang 6 detik. Aku sudah coba dan lumayan membantu.
                </x-post>
            </div>
        </section>

        <aside class="space-y-3">
            <div class="rounded-2xl bg-card p-4 neu-sm">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold">Konselor tersedia</h2>
                    <span class="text-xs text-muted-foreground">3 orang</span>
                </div>
                <ul class="mt-3 space-y-3">
                    @foreach ([['Rani', 'Konselor keluarga', true], ['Dimas', 'Konselor kecemasan', true], ['Sari', 'Konselor remaja', false]] as [$name, $role, $online])
                        <li class="flex items-center gap-3">
                            <span class="relative flex size-9 items-center justify-center rounded-full bg-primary/15 text-sm font-semibold text-brand-deep">
                                {{ $name[0] }}
                                <span @class(['absolute -right-0.5 -bottom-0.5 size-2.5 rounded-full border-2 border-card', 'bg-success' => $online, 'bg-muted-foreground/50' => ! $online])></span>
                            </span>
                            <span class="min-w-0 flex-1 leading-tight">
                                <span class="block truncate text-sm font-medium">Kak {{ $name }}</span>
                                <span class="block text-xs text-muted-foreground">{{ $role }}</span>
                            </span>
                            <span class="text-xs font-medium {{ $online ? 'text-success' : 'text-muted-foreground' }}">{{ $online ? 'Online' : 'Offline' }}</span>
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('konseling.index') }}" class="mt-4 flex items-center justify-center gap-2 rounded-xl bg-background px-4 py-2.5 text-xs font-semibold neu-sm transition hover:bg-muted">
                    Mulai sesi konseling
                </a>
            </div>

            <div class="rounded-2xl bg-secondary/25 p-4">
                <h2 class="flex items-center gap-2 text-sm font-semibold">
                    <x-icon name="lock" :size="15" />
                    Privasi adalah default
                </h2>
                <p class="mt-1.5 text-xs leading-relaxed text-muted-foreground">
                    Nama, email, dan ID kamu tidak pernah ditampilkan di ruang publik. Percakapan konseling hanya bisa dibuka olehmu dan konselormu.
                </p>
            </div>
        </aside>
    </div>
</x-layouts.app>
