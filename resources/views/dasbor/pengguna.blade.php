<x-layouts.app title="Dasbor">
    <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
        <div>
            <h2 class="text-xl font-semibold tracking-tight sm:text-2xl">Dasbor pengguna</h2>
            <p class="mt-1 text-sm text-muted-foreground">Ringkasan aktivitas, status akun, dan sesi konselingmu.</p>
        </div>
        <a href="{{ route('bubble') }}" class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-xs font-semibold text-primary-foreground neu-primary transition hover:bg-brand-deep">
            <x-icon name="plus" :size="15" />
            Tulis cerita
        </a>
    </div>

    <div class="grid grid-cols-2 gap-3 xl:grid-cols-4">
        @foreach ([
            ['label' => 'Cerita dibagikan', 'value' => '12', 'icon' => 'bubble', 'tone' => 'bg-secondary/45 text-secondary-foreground'],
            ['label' => 'Komentar', 'value' => '48', 'icon' => 'chat', 'tone' => 'bg-primary/15 text-brand-deep'],
            ['label' => 'Sesi konseling', 'value' => '3', 'icon' => 'calendar', 'tone' => 'bg-accent/35 text-accent-foreground'],
            ['label' => 'Dukungan diterima', 'value' => '126', 'icon' => 'heart', 'tone' => 'bg-destructive/12 text-[#8d4a46]'],
        ] as $stat)
            <div class="rounded-2xl bg-card p-4 neu-sm">
                <span class="flex size-8 items-center justify-center rounded-lg {{ $stat['tone'] }}">
                    <x-icon :name="$stat['icon']" :size="16" />
                </span>
                <p class="mt-3 text-xl font-semibold tracking-tight tabular-nums" data-count="{{ $stat['value'] }}">0</p>
                <p class="mt-0.5 text-xs leading-tight text-muted-foreground">{{ $stat['label'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-5 grid gap-4 xl:grid-cols-[1.7fr_1fr]">
        <div class="min-w-0 space-y-4">
            {{-- Active session --}}
            <section class="rounded-2xl bg-card p-4 neu-sm sm:p-5">
                <div class="flex items-center justify-between gap-3">
                    <h3 class="text-sm font-semibold">Sesi konseling aktif</h3>
                    <a href="{{ route('konseling.index') }}" class="text-xs font-semibold text-brand-deep hover:underline">Semua sesi</a>
                </div>

                <a href="{{ route('konseling.show', 1) }}" class="mt-3 flex items-center gap-3 rounded-xl bg-muted/60 p-3 transition hover:bg-muted">
                    <span class="relative flex size-10 shrink-0 items-center justify-center rounded-full bg-primary/15 text-sm font-semibold text-brand-deep">
                        R
                        <span class="absolute -right-0.5 -bottom-0.5 size-2.5 rounded-full border-2 border-card bg-success"></span>
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold">Kak Rani</p>
                        <p class="truncate text-xs text-muted-foreground">Boleh, aku coba. Makasih sudah dengerin.</p>
                    </div>
                    <x-status tone="success">Aktif</x-status>
                </a>

                <div class="mt-3 flex flex-col gap-2 sm:flex-row">
                    <a href="{{ route('konseling.show', 1) }}" class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-xs font-semibold text-primary-foreground">
                        <x-icon name="chat" :size="14" />
                        Lanjutkan percakapan
                    </a>
                    <a href="{{ route('konseling.index') }}" class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-background px-4 py-2.5 text-xs font-semibold neu-sm hover:bg-muted">
                        <x-icon name="plus" :size="14" />
                        Ajukan sesi baru
                    </a>
                </div>
            </section>

            {{-- Post history --}}
            <section class="rounded-2xl bg-card p-4 neu-sm sm:p-5">
                <h3 class="text-sm font-semibold">Riwayat postingan</h3>
                <ul class="mt-3 divide-y divide-border/60">
                    @foreach ([
                        ['Aku takut banget besok presentasi. Sudah seminggu nggak bisa tidur nyenyak…', '8 menit lalu', '37', '6'],
                        ['Hari ini aku berhasil bangun pagi dan keluar rumah walau rasanya berat.', '42 menit lalu', '24', '4'],
                        ['Mengingatkan diri sendiri: istirahat itu bukan kemalasan.', '2 hari lalu', '58', '12'],
                    ] as [$excerpt, $time, $likes, $replies])
                        <li class="flex items-start gap-3 py-3">
                            <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-secondary/50 text-secondary-foreground">
                                <x-icon name="user" :size="14" />
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-xs">{{ $excerpt }}</p>
                                <p class="mt-1 flex items-center gap-3 text-xs text-muted-foreground">
                                    <span>{{ $time }}</span>
                                    <span class="flex items-center gap-1"><x-icon name="heart" :size="11" />{{ $likes }}</span>
                                    <span class="flex items-center gap-1"><x-icon name="chat" :size="11" />{{ $replies }}</span>
                                </p>
                            </div>
                            <span class="rounded-full bg-muted px-2 py-0.5 text-xs font-medium text-muted-foreground">Anonim</span>
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('bubble') }}" class="mt-3 flex items-center justify-center gap-2 rounded-xl bg-background px-4 py-2.5 text-xs font-semibold neu-sm hover:bg-muted">
                    Lihat Bubble Publik
                </a>
            </section>
        </div>

        <aside class="space-y-4">
            {{-- Verification --}}
            <section class="rounded-2xl bg-card p-4 neu-sm sm:p-5">
                <div class="flex items-center justify-between gap-3">
                    <h3 class="text-sm font-semibold">Status akun</h3>
                    <x-status tone="success">
                        <x-icon name="check" :size="12" />
                        Email aktif
                    </x-status>
                </div>
                <dl class="mt-3 space-y-2.5 text-xs">
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-muted-foreground">Email</dt>
                        <dd class="truncate font-medium">kawan•••@mail.com</dd>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-muted-foreground">Nama publik</dt>
                        <dd class="font-medium">Anonim</dd>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-muted-foreground">Verifikasi wajah</dt>
                        <dd><x-status tone="warning">Belum</x-status></dd>
                    </div>
                </dl>
                <a href="{{ route('verifikasi') }}" class="mt-4 flex items-center justify-center gap-2 rounded-xl bg-background px-4 py-2.5 text-xs font-semibold neu-sm hover:bg-muted">
                    <x-icon name="face" :size="14" />
                    Mulai verifikasi
                </a>
            </section>

            <section class="rounded-2xl bg-card p-4 neu-sm sm:p-5">
                <h3 class="text-sm font-semibold">Konselor kamu</h3>
                <ul class="mt-3 space-y-3">
                    @foreach ([['Rani', 'Konselor keluarga', true], ['Dimas', 'Konselor kecemasan', false]] as [$name, $role, $online])
                        <li class="flex items-center gap-3">
                            <span class="flex size-9 items-center justify-center rounded-full bg-primary/15 text-sm font-semibold text-brand-deep">{{ $name[0] }}</span>
                            <span class="min-w-0 flex-1 leading-tight">
                                <span class="block text-sm font-medium">Kak {{ $name }}</span>
                                <span class="block text-xs text-muted-foreground">{{ $role }}</span>
                            </span>
                            <span class="text-xs font-medium {{ $online ? 'text-success' : 'text-muted-foreground' }}">{{ $online ? 'Online' : 'Offline' }}</span>
                        </li>
                    @endforeach
                </ul>
            </section>

            <section class="rounded-2xl bg-secondary/25 p-4">
                <h3 class="flex items-center gap-2 text-sm font-semibold">
                    <x-icon name="lock" :size="15" />
                    Kendali privasi
                </h3>
                <p class="mt-1.5 text-xs leading-relaxed text-muted-foreground">
                    Kamu bisa mengunduh datamu atau meminta penghapusan data verifikasi kapan saja dari halaman profil.
                </p>
                <a href="{{ route('profil') }}" class="mt-3 inline-flex items-center gap-2 text-xs font-semibold text-brand-deep hover:underline">
                    Buka pengaturan privasi
                    <x-icon name="arrow-right" :size="13" />
                </a>
            </section>
        </aside>
    </div>
</x-layouts.app>
