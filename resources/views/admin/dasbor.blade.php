@extends('layouts.app')
@section('title', 'Dasbor Admin')
@section('role', 'admin')
@section('content')
    @php
        $totalSesi = array_sum($statusSesi);
        $statusList = [
            ['label' => 'Aktif', 'value' => $statusSesi['aktif'], 'dot' => 'bg-primary'],
            ['label' => 'Selesai', 'value' => $statusSesi['selesai'], 'dot' => 'bg-secondary'],
            ['label' => 'Menunggu', 'value' => $statusSesi['menunggu'], 'dot' => 'bg-[#b9b29c]'],
        ];
    @endphp

    <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
        <div>
            <div class="flex flex-wrap items-center gap-2">
                <h2 class="text-xl font-semibold tracking-tight sm:text-2xl">Halo, {{ auth()->user()->name }}</h2>
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
                <span data-count="{{ $counts['pengguna'] }}">0</span>
            </p>
            <p class="mt-1.5 text-xs text-primary-foreground/60">pengguna terdaftar</p>

            <div class="mt-3 flex flex-wrap gap-2">
                <span class="inline-flex items-center gap-1.5 rounded-full border border-primary-foreground/20 px-3 py-1 text-[0.72rem] font-medium">
                    {{ $counts['laporan_baru'] }} laporan terbuka
                </span>
            </div>

            <dl class="mt-auto grid grid-cols-3 gap-2 pt-5">
                @foreach ([
                    ['label' => 'Sesi aktif', 'value' => $counts['sesi_aktif']],
                    ['label' => 'Cerita', 'value' => $counts['cerita']],
                    ['label' => 'Konselor', 'value' => $counts['konselor']],
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
                <canvas
                    id="chartAktivitas"
                    role="img"
                    aria-label="Grafik garis aktivitas cerita dan pesan selama tujuh hari"
                    data-labels='@json($chartLabels)'
                    data-cerita='@json($chartCerita)'
                    data-pesan='@json($chartPesan)'
                ></canvas>
            </div>
        </section>

        <section class="min-w-0 rounded-[1.4rem] bg-card p-4 neu-sm sm:p-5">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h3 class="text-sm font-semibold">Distribusi sesi</h3>
                    <p class="mt-0.5 text-xs text-muted-foreground">Seluruh sesi konseling.</p>
                </div>
                <x-icon name="chart" :size="17" class="text-muted-foreground" />
            </div>

            <div class="mt-4 flex items-center gap-3">
                <ul class="min-w-0 flex-1 space-y-2.5">
                    @foreach ($statusList as $status)
                        <li class="flex items-center gap-2 text-xs">
                            <span class="size-2 shrink-0 rounded-full {{ $status['dot'] }}"></span>
                            <span class="truncate text-muted-foreground">{{ $status['label'] }}</span>
                            <span class="ml-auto font-semibold tabular-nums">{{ $status['value'] }}</span>
                        </li>
                    @endforeach
                </ul>

                <div class="relative size-28 shrink-0">
                    <canvas
                        id="chartSesi"
                        role="img"
                        aria-label="Diagram donat distribusi status sesi konseling"
                        data-labels='@json($statusList)'
                        data-values='@json(array_column($statusList, "value"))'
                    ></canvas>
                    <div class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-lg font-semibold tracking-tight tabular-nums" data-count="{{ $totalSesi }}">0</span>
                        <span class="text-[0.65rem] text-muted-foreground">total sesi</span>
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.laporan') }}" class="btn-soft mt-4 w-full">
                <x-icon name="flag" :size="14" />
                Tinjau {{ $counts['laporan_baru'] }} laporan
            </a>
        </section>
    </div>

    {{-- Verifikasi + laporan terbaru --}}
    <div class="mt-3 grid gap-3 xl:grid-cols-[1fr_1.55fr]">
        <section class="flex h-full flex-col rounded-[1.4rem] bg-card p-4 neu-sm sm:p-5">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h3 class="text-sm font-semibold">Status verifikasi wajah</h3>
                    <p class="mt-0.5 text-xs text-muted-foreground">Descriptor wajah tersimpan terenkripsi.</p>
                </div>
                <span class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-muted text-muted-foreground">
                    <x-icon name="shield" :size="15" />
                </span>
            </div>

            <dl class="mt-4 grid grid-cols-2 gap-2.5">
                <div class="rounded-xl bg-muted/60 p-3">
                    <dt class="text-xs text-muted-foreground">Terdaftar</dt>
                    <dd class="mt-1 text-lg font-semibold tabular-nums">{{ $counts['terverifikasi'] }}</dd>
                </div>
                <div class="rounded-xl bg-muted/60 p-3">
                    <dt class="text-xs text-muted-foreground">Belum</dt>
                    <dd class="mt-1 text-lg font-semibold tabular-nums">{{ $counts['belum_verifikasi'] }}</dd>
                </div>
            </dl>

            <a href="{{ route('admin.verifikasi') }}" class="btn-soft mt-auto w-full">
                <x-icon name="face" :size="14" />
                Kelola verifikasi
            </a>
        </section>

        <div class="min-w-0">
            <div class="flex flex-wrap items-end justify-between gap-2">
                <h3 class="text-base font-semibold tracking-tight">
                    Laporan terbaru
                    <span class="text-xs font-normal text-muted-foreground">({{ $reports->count() }} baru)</span>
                </h3>
                <a href="{{ route('admin.laporan') }}" class="text-xs font-semibold text-foreground hover:underline">Lihat semua</a>
            </div>

            <div class="mt-3 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                @forelse ($reports as $report)
                    <a href="{{ route('admin.laporan') }}" class="group flex flex-col rounded-[1.4rem] bg-card p-4 neu-sm transition hover:-translate-y-0.5">
                        <div class="flex items-center justify-between gap-3">
                            <span class="flex size-8 items-center justify-center rounded-lg bg-muted text-muted-foreground">
                                <x-icon name="flag" :size="15" />
                            </span>
                            <span class="size-2 rounded-full bg-destructive"></span>
                        </div>
                        <p class="mt-4 text-[0.68rem] tracking-[0.1em] uppercase text-muted-foreground">{{ $report->reason }}</p>
                        <p class="mt-1 text-sm font-semibold">{{ class_basename($report->reportable_type) }} #{{ $report->reportable_id }}</p>
                        <p class="mt-0.5 text-xs text-muted-foreground">{{ $report->created_at->diffForHumans() }}</p>
                        <span class="mt-auto inline-flex items-center gap-1 pt-3 text-xs font-semibold text-foreground">
                            Tinjau
                            <x-icon name="arrow-right" :size="12" />
                        </span>
                    </a>
                @empty
                    <div class="flex min-h-[186px] flex-col items-center justify-center gap-2 rounded-[1.4rem] border-2 border-dashed border-foreground/30 bg-card/40 p-4 text-center">
                        <span class="flex size-10 items-center justify-center rounded-full bg-card text-foreground neu-sm">
                            <x-icon name="check" :size="17" />
                        </span>
                        <span class="text-sm font-semibold">Antrean bersih</span>
                        <span class="text-xs text-muted-foreground">Tidak ada laporan baru.</span>
                    </div>
                @endforelse

                <a href="{{ route('admin.laporan') }}" class="flex min-h-[186px] flex-col items-center justify-center gap-2 rounded-[1.4rem] border-2 border-dashed border-foreground/30 bg-card/40 p-4 text-center transition hover:border-foreground/70">
                    <span class="flex size-10 items-center justify-center rounded-full bg-card text-foreground neu-sm">
                        <x-icon name="plus" :size="17" />
                    </span>
                    <span class="text-sm font-semibold">Buka antrean</span>
                    <span class="text-xs text-muted-foreground">{{ $counts['laporan_baru'] }} laporan menunggu</span>
                </a>
            </div>

            <section class="mt-3 rounded-[1.4rem] bg-card p-4 neu-sm">
                <div class="flex items-center justify-between gap-3">
                    <h3 class="text-sm font-semibold">Aktivitas terbaru</h3>
                    <a href="{{ route('admin.laporan') }}" class="text-xs font-semibold text-foreground hover:underline">Kelola</a>
                </div>
                <ol class="mt-3 grid gap-2.5 sm:grid-cols-2">
                    @forelse ($aktivitas as $item)
                        <li class="flex items-start gap-2.5 rounded-xl bg-muted/60 p-2.5">
                            <span class="flex size-7 shrink-0 items-center justify-center rounded-full bg-card text-foreground">
                                <x-icon :name="$item['icon']" :size="13" />
                            </span>
                            <div class="min-w-0">
                                <p class="text-xs leading-snug">{{ $item['text'] }}</p>
                                <p class="mt-0.5 text-xs text-muted-foreground">{{ $item['time']->diffForHumans() }}</p>
                            </div>
                        </li>
                    @empty
                        <li class="rounded-xl bg-muted/60 p-3 text-xs text-muted-foreground">Belum ada aktivitas.</li>
                    @endforelse
                </ol>
            </section>
        </div>
    </div>
@endsection
