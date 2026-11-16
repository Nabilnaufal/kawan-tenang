<x-layouts.app title="Beranda">
    {{-- Hero --}}
    <section class="card-ink relative overflow-hidden rounded-[1.75rem] p-5 sm:p-8">
        <div class="flex flex-col gap-7 xl:flex-row xl:items-end xl:justify-between">
            <div class="max-w-2xl">
                <span class="inline-flex items-center gap-2 rounded-full border border-primary-foreground/20 px-3 py-1.5 text-[0.75rem] font-medium">
                    <span class="size-1.5 rounded-full bg-secondary"></span>
                    Ruang aman & anonim
                </span>

                <h2 class="mt-4 text-2xl leading-tight font-semibold tracking-tight sm:text-3xl">
                    Halo, Kawan. Tidak apa-apa untuk tidak baik-baik saja.
                </h2>
                <p class="mt-2.5 max-w-xl text-sm leading-relaxed text-primary-foreground/70">
                    Ceritakan apa yang sedang kamu rasakan tanpa takut dikenali, atau ngobrol privat dengan konselor yang siap mendengarkan.
                </p>

                <div class="mt-6 flex flex-col gap-2 sm:flex-row">
                    <a href="{{ route('bubble') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-secondary px-5 py-2.5 text-sm font-semibold text-secondary-foreground transition hover:opacity-90">
                        Mulai bercerita
                        <x-icon name="arrow-right" :size="16" />
                    </a>
                    <a href="{{ route('konseling.index') }}" class="inline-flex items-center justify-center gap-2 rounded-full border border-primary-foreground/25 px-5 py-2.5 text-sm font-semibold text-primary-foreground transition hover:bg-primary-foreground/10">
                        <x-icon name="chat" :size="16" />
                        Cari konselor
                    </a>
                </div>
            </div>

            <dl class="grid grid-cols-3 gap-4 rounded-2xl border border-primary-foreground/15 p-4 sm:gap-6 sm:p-5 xl:min-w-[360px]">
                @foreach ([['1.284', 'cerita', 'dibagikan'], ['32', 'konselor', 'aktif'], ['98%', 'merasa', 'didengar']] as [$value, $line1, $line2])
                    <div>
                        <dd class="text-xl font-semibold tracking-tight tabular-nums" data-count="{{ $value }}">0</dd>
                        <dt class="mt-1 text-[0.72rem] leading-tight tracking-[0.1em] text-primary-foreground/60 uppercase">
                            {{ $line1 }}<br>{{ $line2 }}
                        </dt>
                    </div>
                @endforeach
            </dl>
        </div>
    </section>

    {{-- Emergency notice --}}
    <section class="mt-3 flex items-start gap-3 rounded-2xl border-2 border-[var(--card-border)] bg-secondary/30 px-4 py-3.5">
        <x-icon name="life-buoy" :size="18" class="mt-0.5 shrink-0" />
        <p class="text-xs leading-relaxed">
            <span class="font-semibold">Butuh bantuan segera?</span>
            Kawan Tenang bukan layanan darurat. Hubungi
            <a href="tel:119" class="font-semibold underline underline-offset-2">119 ext. 8</a> atau
            <a href="tel:112" class="font-semibold underline underline-offset-2">112</a>.
        </p>
    </section>

    {{-- Areas --}}
    <section class="mt-8">
        <div class="flex items-end justify-between gap-4">
            <h2 class="text-lg font-semibold tracking-tight">Tiga ruang untukmu</h2>
            <span class="label-cap hidden sm:block">Pilih ruang</span>
        </div>

        <div class="mt-3 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ([
                ['icon' => 'bubble', 'title' => 'Bubble Publik', 'text' => 'Bagikan cerita dan saling menguatkan dengan identitas anonim.', 'href' => route('bubble'), 'tone' => 'bg-secondary text-secondary-foreground'],
                ['icon' => 'chat', 'title' => 'Konseling Privat', 'text' => 'Ngobrol empat mata dengan konselor, hanya kalian yang bisa membaca.', 'href' => route('konseling.index'), 'tone' => 'bg-muted text-foreground'],
                ['icon' => 'shield', 'title' => 'Verifikasi Akun', 'text' => 'Bantu jaga komunitas tetap aman lewat verifikasi liveness.', 'href' => route('verifikasi'), 'tone' => 'bg-muted text-foreground'],
            ] as $card)
                <a href="{{ $card['href'] }}" class="group flex items-start gap-3 rounded-[1.4rem] bg-card p-4 transition hover:bg-muted sm:p-5 neu-sm">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-xl {{ $card['tone'] }}">
                        <x-icon :name="$card['icon']" :size="19" />
                    </span>
                    <span class="min-w-0">
                        <span class="block text-sm font-semibold">{{ $card['title'] }}</span>
                        <span class="mt-1 block text-xs leading-relaxed text-muted-foreground">{{ $card['text'] }}</span>
                    </span>
                    <span class="ml-auto flex size-8 shrink-0 items-center justify-center rounded-full bg-muted text-muted-foreground transition group-hover:bg-primary group-hover:text-primary-foreground">
                        <x-icon name="chevron-right" :size="15" />
                    </span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Feed + aside --}}
    <div class="mt-8 grid gap-5 xl:grid-cols-[2.2fr_1fr]">
        <section class="min-w-0">
            <div class="flex items-end justify-between gap-4">
                <h2 class="text-lg font-semibold tracking-tight">Cerita terbaru</h2>
                <a href="{{ route('bubble') }}" class="text-xs font-semibold text-foreground hover:underline">Lihat semua</a>
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
            <div class="rounded-[1.4rem] bg-card p-4 neu-sm sm:p-5">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-sm font-semibold">Konselor tersedia</h2>
                    <span class="text-xs text-muted-foreground">3 orang</span>
                </div>
                <ul class="mt-4 space-y-3.5">
                    @foreach ([['Rani', 'Konselor keluarga', true], ['Dimas', 'Konselor kecemasan', true], ['Sari', 'Konselor remaja', false]] as [$name, $role, $online])
                        <li class="flex items-center gap-3">
                            <span class="relative flex size-10 items-center justify-center rounded-full bg-muted text-sm font-semibold">
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
                <a href="{{ route('konseling.index') }}" class="btn-lime mt-5 w-full text-xs">
                    Mulai sesi konseling
                </a>
            </div>

            <div class="card-ink rounded-[1.4rem] p-5">
                <h2 class="flex items-center gap-2 text-sm font-semibold">
                    <x-icon name="lock" :size="15" />
                    Privasi adalah default
                </h2>
                <p class="mt-2 text-xs leading-relaxed text-primary-foreground/70">
                    Nama, email, dan ID kamu tidak pernah ditampilkan di ruang publik. Percakapan konseling hanya bisa dibuka olehmu dan konselormu.
                </p>
            </div>
        </aside>
    </div>
</x-layouts.app>
