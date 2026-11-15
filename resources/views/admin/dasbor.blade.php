<x-layouts.app title="Dasbor Admin" role="admin">
    <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
        <div>
            <h2 class="text-xl font-semibold tracking-tight sm:text-2xl">Dasbor administrator</h2>
            <p class="mt-1 text-sm text-muted-foreground">Ringkasan pengguna, sesi, dan moderasi komunitas.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.laporan') }}" class="inline-flex items-center gap-2 rounded-xl bg-background px-4 py-2.5 text-xs font-semibold neu-sm hover:bg-muted">
                <x-icon name="flag" :size="14" />
                Antrean laporan
            </a>
            <a href="{{ route('admin.pengguna') }}" class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-xs font-semibold text-primary-foreground neu-primary hover:bg-brand-deep">
                <x-icon name="users" :size="14" />
                Kelola pengguna
            </a>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-3 xl:grid-cols-4">
        @foreach ([
            ['label' => 'Pengguna terdaftar', 'value' => '1284', 'delta' => '+8,2%', 'icon' => 'users', 'tone' => 'bg-secondary/45 text-secondary-foreground'],
            ['label' => 'Sesi aktif', 'value' => '32', 'delta' => '+3', 'icon' => 'chat', 'tone' => 'bg-primary/15 text-brand-deep'],
            ['label' => 'Cerita dibagikan', 'value' => '486', 'delta' => '+21', 'icon' => 'bubble', 'tone' => 'bg-accent/35 text-accent-foreground'],
            ['label' => 'Laporan terbuka', 'value' => '7', 'delta' => '-2', 'icon' => 'flag', 'tone' => 'bg-destructive/12 text-[#8d4a46]'],
        ] as $stat)
            <div class="rounded-2xl bg-card p-4 neu-sm">
                <div class="flex items-center justify-between gap-2">
                    <span class="flex size-8 items-center justify-center rounded-lg {{ $stat['tone'] }}">
                        <x-icon :name="$stat['icon']" :size="16" />
                    </span>
                    <span class="text-xs font-semibold text-muted-foreground">{{ $stat['delta'] }}</span>
                </div>
                <p class="mt-3 text-xl font-semibold tracking-tight tabular-nums" data-count="{{ $stat['value'] }}">0</p>
                <p class="mt-0.5 text-xs leading-tight text-muted-foreground">{{ $stat['label'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-4 grid gap-4 xl:grid-cols-[1.8fr_1fr]">
        <section class="min-w-0 rounded-2xl bg-card p-4 neu-sm sm:p-5">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h3 class="text-sm font-semibold">Aktivitas 7 hari terakhir</h3>
                    <p class="mt-0.5 text-xs text-muted-foreground">Cerita baru dan pesan konseling per hari.</p>
                </div>
                <div class="flex items-center gap-3 text-xs font-medium text-muted-foreground">
                    <span class="flex items-center gap-1.5"><span class="size-2 rounded-full bg-primary"></span>Cerita</span>
                    <span class="flex items-center gap-1.5"><span class="size-2 rounded-full bg-secondary"></span>Pesan</span>
                </div>
            </div>
            <div class="mt-4 h-56 sm:h-64">
                <canvas id="chartAktivitas" role="img" aria-label="Grafik garis aktivitas cerita dan pesan selama tujuh hari"></canvas>
            </div>
        </section>

        <section class="min-w-0 rounded-2xl bg-card p-4 neu-sm sm:p-5">
            <h3 class="text-sm font-semibold">Status sesi konseling</h3>
            <p class="mt-0.5 text-xs text-muted-foreground">Distribusi seluruh sesi bulan ini.</p>
            <div class="relative mx-auto mt-3 h-40 w-40">
                <canvas id="chartSesi" role="img" aria-label="Diagram donat distribusi status sesi konseling"></canvas>
                <div class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-xl font-semibold tracking-tight tabular-nums" data-count="86">0</span>
                    <span class="text-xs text-muted-foreground">total sesi</span>
                </div>
            </div>
            <ul class="mt-4 space-y-2">
                @foreach ([['Aktif', '32', 'bg-primary'], ['Selesai', '41', 'bg-success'], ['Menunggu', '9', 'bg-accent'], ['Ditutup', '4', 'bg-muted-foreground/50']] as [$label, $total, $dot])
                    <li class="flex items-center gap-2 text-xs">
                        <span class="size-2 rounded-full {{ $dot }}"></span>
                        <span class="text-muted-foreground">{{ $label }}</span>
                        <span class="ml-auto font-semibold tabular-nums">{{ $total }}</span>
                    </li>
                @endforeach
            </ul>
        </section>
    </div>

    <div class="mt-4 grid gap-4 xl:grid-cols-2">
        <section class="rounded-2xl bg-card p-4 neu-sm sm:p-5">
            <div class="flex items-center justify-between gap-3">
                <h3 class="text-sm font-semibold">Antrean laporan</h3>
                <a href="{{ route('admin.laporan') }}" class="text-xs font-semibold text-brand-deep hover:underline">Kelola</a>
            </div>
            <ul class="mt-3 divide-y divide-border/60">
                @foreach ([
                    ['Ujaran kebencian', 'Cerita #4821', '2 jam lalu', 'warning'],
                    ['Spam berulang', 'Komentar #1290', '4 jam lalu', 'neutral'],
                    ['Membahayakan diri', 'Cerita #4817', '6 jam lalu', 'danger'],
                ] as [$reason, $target, $time, $tone])
                    <li class="flex items-center gap-3 py-2.5">
                        <span class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-muted text-muted-foreground">
                            <x-icon name="flag" :size="15" />
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-xs font-semibold">{{ $reason }}</p>
                            <p class="text-xs text-muted-foreground">{{ $target }} · {{ $time }}</p>
                        </div>
                        <x-status :tone="$tone">Baru</x-status>
                    </li>
                @endforeach
            </ul>
        </section>

        <section class="rounded-2xl bg-card p-4 neu-sm sm:p-5">
            <h3 class="text-sm font-semibold">Aktivitas terbaru</h3>
            <ol class="mt-3 space-y-3">
                @foreach ([
                    ['Konselor Rani menyelesaikan sesi #2418', '12 menit lalu', 'chat'],
                    ['Verifikasi berhasil untuk 1 akun', '38 menit lalu', 'shield'],
                    ['Moderator menyembunyikan 1 komentar', '1 jam lalu', 'flag'],
                    ['7 cerita baru dipublikasikan', '2 jam lalu', 'bubble'],
                ] as [$text, $time, $icon])
                    <li class="flex items-start gap-3">
                        <span class="flex size-7 shrink-0 items-center justify-center rounded-full bg-muted text-muted-foreground">
                            <x-icon :name="$icon" :size="13" />
                        </span>
                        <div class="min-w-0">
                            <p class="text-xs leading-snug">{{ $text }}</p>
                            <p class="text-xs text-muted-foreground">{{ $time }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </section>
    </div>
</x-layouts.app>
