@extends('layouts.app')
@section('title', 'Dasbor Admin')
@section('role', 'admin')
@section('content')
    <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
        <div>
            <div class="flex flex-wrap items-center gap-2">
                <h2 class="text-xl font-semibold tracking-tight sm:text-2xl">Halo, Admin</h2>
                <span class="rounded-full bg-card px-3 py-1 text-[0.72rem] font-semibold text-muted-foreground neu-sm">Administrator</span>
            </div>
            <p class="mt-1 text-sm text-muted-foreground">Pantau pengguna, sesi, dan moderasi komunitas secara real-time.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.laporan') }}" class="btn-soft">
                <x-icon name="flag" :size="15" />
                Antrean laporan
            </a>
            <a href="{{ route('admin.pengguna') }}" class="btn-lime">
                <x-icon name="users" :size="15" />
                Kelola pengguna
            </a>
        </div>
    </div>

    {{-- Ringkasan, grafik, distribusi --}}
    <div class="grid gap-3 xl:grid-cols-[1.32fr_1fr_1fr]">
        <section class="card-ink flex flex-col rounded-[1.5rem] p-5">
            <div class="flex items-start justify-between gap-3">
                <p class="label-cap text-primary-foreground/60">Ringkasan platform</p>
                <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-primary-foreground/10">
                    <x-icon name="chart" :size="16" />
                </span>
            </div>

            <p class="mt-3 text-3xl leading-none font-semibold tracking-tight tabular-nums">
                <span data-count="1284">0</span>
            </p>
            <p class="mt-1.5 text-xs text-primary-foreground/60">pengguna terdaftar</p>

            <div class="mt-3 flex flex-wrap gap-2">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-secondary px-3 py-1 text-[0.72rem] font-bold text-secondary-foreground">
                    <x-icon name="arrow-right" :size="12" class="-rotate-45" />
                    +8,2% bulan ini
                </span>
                <span class="inline-flex items-center gap-1.5 rounded-full border border-primary-foreground/20 px-3 py-1 text-[0.72rem] font-medium">
                    7 laporan terbuka
                </span>
            </div>

            <dl class="mt-auto grid grid-cols-3 gap-2 pt-5">
                @foreach ([
                    ['label' => 'Sesi aktif', 'value' => '32'],
                    ['label' => 'Cerita', 'value' => '486'],
                    ['label' => 'Konselor', 'value' => '32'],
                ] as $stat)
                    <div class="rounded-2xl bg-primary-foreground/10 p-3">
                        <dt class="text-[0.62rem] tracking-[0.1em] uppercase text-primary-foreground/60">{{ $stat['label'] }}</dt>
                        <dd class="mt-1.5 text-lg font-semibold tabular-nums"><span data-count="{{ $stat['value'] }}">0</span></dd>
                    </div>
                @endforeach
            </dl>
        </section>

        <section class="flex min-w-0 flex-col rounded-[1.4rem] bg-card p-4 neu-sm sm:p-5">
            <div class="flex flex-wrap items-start justify-between gap-2">
                <div>
                    <h3 class="text-sm font-semibold">Aktivitas 7 hari</h3>
                    <p class="mt-0.5 text-xs text-muted-foreground">Cerita baru dan pesan per hari.</p>
                </div>
                <div class="flex items-center gap-3 text-xs text-muted-foreground">
                    <span class="flex items-center gap-1.5"><span class="size-2 rounded-full bg-primary"></span>Cerita</span>
                    <span class="flex items-center gap-1.5"><span class="size-2 rounded-full bg-secondary"></span>Pesan</span>
                </div>
            </div>
            <div class="relative mt-3 min-h-[168px] flex-1">
                <canvas id="chartAktivitas" role="img" aria-label="Grafik garis aktivitas cerita dan pesan selama tujuh hari"></canvas>
            </div>
        </section>

        <section class="min-w-0 rounded-[1.4rem] bg-card p-4 neu-sm sm:p-5">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h3 class="text-sm font-semibold">Distribusi sesi</h3>
                    <p class="mt-0.5 text-xs text-muted-foreground">Seluruh sesi bulan ini.</p>
                </div>
                <x-icon name="chart" :size="17" class="text-muted-foreground" />
            </div>

            <div class="mt-4 flex items-center gap-3">
                <ul class="min-w-0 flex-1 space-y-2.5">
                    @foreach ([['Aktif', '32', 'bg-primary'], ['Selesai', '41', 'bg-secondary'], ['Menunggu', '9', 'bg-[#b9b29c]'], ['Ditutup', '4', 'bg-muted-foreground/50']] as [$label, $total, $dot])
                        <li class="flex items-center gap-2 text-xs">
                            <span class="size-2 shrink-0 rounded-full {{ $dot }}"></span>
                            <span class="truncate text-muted-foreground">{{ $label }}</span>
                            <span class="ml-auto font-semibold tabular-nums">{{ $total }}</span>
                        </li>
                    @endforeach
                </ul>

                <div class="relative size-28 shrink-0">
                    <canvas id="chartSesi" role="img" aria-label="Diagram donat distribusi status sesi konseling"></canvas>
                    <div class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-lg font-semibold tracking-tight tabular-nums" data-count="86">0</span>
                        <span class="text-[0.65rem] text-muted-foreground">total sesi</span>
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.laporan') }}" class="btn-soft mt-4 w-full">
                <x-icon name="flag" :size="14" />
                Tinjau 7 laporan
            </a>
        </section>
    </div>

    {{-- Form tindakan + laporan terbaru --}}
    <div class="mt-3 grid gap-3 xl:grid-cols-[1fr_1.55fr]">
        <section class="rounded-[1.4rem] bg-card p-4 neu-sm sm:p-5">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h3 class="text-sm font-semibold">Catat tindakan</h3>
                    <p class="mt-0.5 text-xs text-muted-foreground">Tindakan moderasi langsung ke audit log.</p>
                </div>
                <span class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-muted text-muted-foreground">
                    <x-icon name="clipboard" :size="15" />
                </span>
            </div>

            <form class="mt-4 space-y-3" @submit.prevent="$el.reset()">
                <div class="grid grid-cols-2 gap-2.5">
                    <div>
                        <label for="jenis-laporan" class="label-cap mb-1.5 block">Jenis</label>
                        <select id="jenis-laporan" class="w-full rounded-xl bg-input px-3 py-2.5 text-sm neu-inset focus:outline-none">
                            <option>Kebencian</option>
                            <option>Spam</option>
                            <option>Bahaya diri</option>
                        </select>
                    </div>
                    <div>
                        <label for="target-laporan" class="label-cap mb-1.5 block">Target</label>
                        <select id="target-laporan" class="w-full rounded-xl bg-input px-3 py-2.5 text-sm neu-inset focus:outline-none">
                            <option>Cerita #4821</option>
                            <option>Komentar #1290</option>
                            <option>Cerita #4817</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="tindakan" class="label-cap mb-1.5 block">Tindakan</label>
                    <select id="tindakan" class="w-full rounded-xl bg-input px-3 py-2.5 text-sm neu-inset focus:outline-none">
                        <option>Sembunyikan konten</option>
                        <option>Kirim peringatan</option>
                        <option>Tangguhkan akun</option>
                        <option>Tolak laporan</option>
                    </select>
                </div>

                <div>
                    <label for="catatan-tindakan" class="label-cap mb-1.5 block">Catatan</label>
                    <textarea id="catatan-tindakan" rows="3" placeholder="Alasan singkat untuk audit log…" class="w-full resize-none rounded-xl bg-input px-3 py-2.5 text-sm placeholder:text-muted-foreground neu-inset focus:outline-none"></textarea>
                </div>

                <div class="flex items-center justify-between gap-3">
                    <p class="flex items-center gap-1.5 text-[0.7rem] text-muted-foreground">
                        <x-icon name="lock" :size="12" />
                        Hanya moderator
                    </p>
                    <button type="submit" class="btn-lime">
                        Simpan tindakan
                        <x-icon name="arrow-right" :size="14" />
                    </button>
                </div>
            </form>
        </section>

        <div class="min-w-0">
            <div class="flex flex-wrap items-end justify-between gap-2">
                <h3 class="text-base font-semibold tracking-tight">
                    Laporan terbaru
                    <span class="text-xs font-normal text-muted-foreground">(2 sorotan)</span>
                </h3>
                <a href="{{ route('admin.laporan') }}" class="text-xs font-semibold text-foreground hover:underline">Lihat semua</a>
            </div>

            <div class="mt-3 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ([
                    ['Ujaran kebencian', 'Cerita #4821', '2 jam lalu · Anonim', 'danger'],
                    ['Membahayakan diri', 'Cerita #4817', '6 jam lalu · Anonim', 'warning'],
                ] as [$reason, $target, $meta, $tone])
                    <a href="{{ route('admin.laporan') }}" class="group flex flex-col rounded-[1.4rem] bg-card p-4 neu-sm transition hover:-translate-y-0.5">
                        <div class="flex items-center justify-between gap-3">
                            <span class="flex size-8 items-center justify-center rounded-lg bg-muted text-muted-foreground">
                                <x-icon name="flag" :size="15" />
                            </span>
                            <span @class(['size-2 rounded-full', 'bg-destructive' => $tone === 'danger', 'bg-[#c9971f]' => $tone === 'warning'])></span>
                        </div>
                        <p class="mt-4 text-[0.68rem] tracking-[0.1em] uppercase text-muted-foreground">{{ $reason }}</p>
                        <p class="mt-1 text-sm font-semibold">{{ $target }}</p>
                        <p class="mt-0.5 text-xs text-muted-foreground">{{ $meta }}</p>
                        <span class="mt-auto inline-flex items-center gap-1 pt-3 text-xs font-semibold text-foreground">
                            Tinjau
                            <x-icon name="arrow-right" :size="12" />
                        </span>
                    </a>
                @endforeach

                <a href="{{ route('admin.laporan') }}" class="flex min-h-[186px] flex-col items-center justify-center gap-2 rounded-[1.4rem] border-2 border-dashed border-foreground/30 bg-card/40 p-4 text-center transition hover:border-foreground/70">
                    <span class="flex size-10 items-center justify-center rounded-full bg-card text-foreground neu-sm">
                        <x-icon name="plus" :size="17" />
                    </span>
                    <span class="text-sm font-semibold">Buka antrean</span>
                    <span class="text-xs text-muted-foreground">7 laporan menunggu</span>
                </a>
            </div>

            <section class="mt-3 rounded-[1.4rem] bg-card p-4 neu-sm">
                <div class="flex items-center justify-between gap-3">
                    <h3 class="text-sm font-semibold">Aktivitas terbaru</h3>
                    <a href="{{ route('admin.laporan') }}" class="text-xs font-semibold text-foreground hover:underline">Kelola</a>
                </div>
                <ol class="mt-3 grid gap-2.5 sm:grid-cols-2">
                    @foreach ([
                        ['Konselor Rani menyelesaikan sesi #2418', '12 menit lalu', 'chat'],
                        ['Verifikasi berhasil untuk 1 akun', '38 menit lalu', 'shield'],
                        ['Moderator menyembunyikan 1 komentar', '1 jam lalu', 'flag'],
                        ['7 cerita baru dipublikasikan', '2 jam lalu', 'bubble'],
                    ] as [$text, $time, $icon])
                        <li class="flex items-start gap-2.5 rounded-xl bg-muted/60 p-2.5">
                            <span class="flex size-7 shrink-0 items-center justify-center rounded-full bg-card text-foreground">
                                <x-icon :name="$icon" :size="13" />
                            </span>
                            <div class="min-w-0">
                                <p class="text-xs leading-snug">{{ $text }}</p>
                                <p class="mt-0.5 text-xs text-muted-foreground">{{ $time }}</p>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </section>
        </div>
    </div>
@endsection
