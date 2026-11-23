@extends('layouts.app')
@section('title', 'Dasbor Konselor')
@section('role', 'konselor')
@section('content')
    @php
        $tones = ['aktif' => 'success', 'menunggu' => 'warning', 'selesai' => 'neutral'];
        $perluDitangani = $sessions->whereIn('status', ['aktif', 'menunggu'])->take(3);
    @endphp

    <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
        <div>
            <div class="flex flex-wrap items-center gap-2">
                <h2 class="text-xl font-semibold tracking-tight sm:text-2xl">Halo, {{ $user->name }}</h2>
                <span class="rounded-full bg-card px-3 py-1 text-[0.72rem] font-semibold text-muted-foreground neu-sm">Konselor</span>
            </div>
            <p class="mt-1 text-sm text-muted-foreground">Sesi yang ditugaskan kepadamu dan ringkasan beban kerja.</p>
        </div>

        <a href="{{ route('konselor.sesi') }}" class="btn-lime">
            <x-icon name="chat" :size="15" />
            Buka sesi
        </a>
    </div>

    <div class="grid gap-3 xl:grid-cols-[1.32fr_1fr_1fr]">
        <section class="card-ink flex flex-col rounded-[1.5rem] p-5">
            <div class="flex items-start justify-between gap-3">
                <p class="label-cap text-primary-foreground/60">Beban kerja</p>
                <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-primary-foreground/10">
                    <x-icon name="chart" :size="16" />
                </span>
            </div>

            <p class="mt-3 text-3xl leading-none font-semibold tracking-tight tabular-nums">
                <span data-count="{{ $sessions->count() }}">0</span>
            </p>
            <p class="mt-1.5 text-xs text-primary-foreground/60">total sesi ditugaskan</p>

            <div class="mt-3 flex flex-wrap gap-2">
                <span class="inline-flex items-center gap-1.5 rounded-full border border-primary-foreground/20 px-3 py-1 text-[0.72rem] font-medium">
                    {{ $counts['aktif'] }} aktif
                </span>
                <span class="inline-flex items-center gap-1.5 rounded-full border border-primary-foreground/20 px-3 py-1 text-[0.72rem] font-medium">
                    {{ $counts['menunggu'] }} menunggu
                </span>
            </div>

            <dl class="mt-auto grid grid-cols-3 gap-2 pt-5">
                @foreach ([
                    ['label' => 'Aktif', 'value' => $counts['aktif']],
                    ['label' => 'Menunggu', 'value' => $counts['menunggu']],
                    ['label' => 'Selesai', 'value' => $counts['selesai']],
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
                    <h3 class="text-sm font-semibold">Sesi per hari</h3>
                    <p class="mt-0.5 text-xs text-muted-foreground">Tujuh hari terakhir.</p>
                </div>
                <div class="flex items-center gap-3 text-xs text-muted-foreground">
                    <span class="flex items-center gap-1.5"><span class="size-2 rounded-full bg-primary"></span>Selesai</span>
                    <span class="flex items-center gap-1.5"><span class="size-2 rounded-full bg-secondary"></span>Baru</span>
                </div>
            </div>
            <div class="relative mt-3 min-h-[168px] flex-1">
                <canvas
                    id="chartSesiKonselor"
                    role="img"
                    aria-label="Diagram batang jumlah sesi konseling per hari"
                    data-labels='@json($chartLabels)'
                    data-selesai='@json($chartSelesai)'
                    data-baru='@json($chartBaru)'
                ></canvas>
            </div>
        </section>

        <section class="rounded-[1.4rem] bg-card p-4 neu-sm sm:p-5">
            <div class="flex items-center justify-between gap-3">
                <h3 class="text-sm font-semibold">Ketersediaan</h3>
                <span class="flex items-center gap-1.5 text-xs text-muted-foreground">
                    <span @class(['size-2 rounded-full', 'bg-success' => $user->is_available, 'bg-muted-foreground/50' => ! $user->is_available])></span>
                    {{ $user->is_available ? 'Online' : 'Offline' }}
                </span>
            </div>

            <form method="POST" action="{{ route('konselor.ketersediaan') }}" class="mt-3">
                @csrf
                @method('PATCH')
                <input type="hidden" name="is_available" value="{{ $user->is_available ? 0 : 1 }}">
                <button type="submit" class="flex w-full items-center justify-between gap-3 rounded-xl bg-muted/60 p-3 text-left">
                    <span class="text-xs font-medium">{{ $user->is_available ? 'Menerima sesi baru' : 'Sedang tidak tersedia' }}</span>
                    <span @class(['relative h-6 w-11 shrink-0 rounded-full transition', 'bg-primary' => $user->is_available, 'bg-muted-foreground/30' => ! $user->is_available]) aria-hidden="true">
                        <span @class(['absolute top-0.5 left-0.5 size-5 rounded-full bg-card shadow transition', 'translate-x-5' => $user->is_available])></span>
                    </span>
                </button>
            </form>

            <p class="label-cap mt-4 mb-2 block">Perlu ditangani</p>
            <ul class="space-y-2.5 text-xs">
                @forelse ($perluDitangani as $sesi)
                    <li class="flex gap-3">
                        <span class="w-10 shrink-0 font-semibold tabular-nums text-foreground">{{ $sesi->created_at->format('H.i') }}</span>
                        <a href="{{ route('konselor.sesi.show', $sesi) }}" class="min-w-0 text-muted-foreground hover:text-foreground">
                            <span class="block truncate">{{ $sesi->topic }} · Anonim #{{ $sesi->user_id }}</span>
                            <span class="block text-[0.7rem]">{{ $sesi->messages_count }} pesan · {{ ucfirst($sesi->status) }}</span>
                        </a>
                    </li>
                @empty
                    <li class="text-muted-foreground">Tidak ada sesi yang menunggu.</li>
                @endforelse
            </ul>
        </section>
    </div>

    <div class="mt-3 grid gap-3 xl:grid-cols-[1fr_1.55fr]">
        <section class="rounded-[1.4rem] bg-card p-4 neu-sm sm:p-5">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h3 class="text-sm font-semibold">Catatan sesi</h3>
                    <p class="mt-0.5 text-xs text-muted-foreground">Hanya terlihat oleh konselor yang ditugaskan.</p>
                </div>
                <span class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-muted text-muted-foreground">
                    <x-icon name="clipboard" :size="15" />
                </span>
            </div>

            <ul class="mt-4 space-y-2.5">
                @forelse ($notes as $sesi)
                    <li>
                        <a href="{{ route('konselor.sesi.show', $sesi) }}" class="block rounded-xl bg-muted/60 p-3 transition hover:bg-muted">
                            <span class="flex items-center justify-between gap-2">
                                <span class="text-xs font-semibold">Anonim #{{ $sesi->user_id }} · {{ $sesi->topic }}</span>
                                <span class="text-xs text-muted-foreground">{{ $sesi->updated_at->diffForHumans() }}</span>
                            </span>
                            <span class="mt-1 block line-clamp-2 text-xs text-muted-foreground">{{ $sesi->counselor_note }}</span>
                        </a>
                    </li>
                @empty
                    <li class="rounded-xl bg-muted/60 p-3 text-xs text-muted-foreground">Belum ada catatan. Buka sesi untuk menulis catatan.</li>
                @endforelse
            </ul>
        </section>

        <div class="min-w-0">
            <div class="flex flex-wrap items-end justify-between gap-2">
                <h3 class="text-base font-semibold tracking-tight">
                    Sesi terbaru
                    <span class="text-xs font-normal text-muted-foreground">({{ $sessions->count() }} sesi)</span>
                </h3>
                <a href="{{ route('konselor.sesi') }}" class="text-xs font-semibold text-foreground hover:underline">Lihat semua</a>
            </div>

            <div class="mt-3 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($sessions->take(2) as $sesi)
                    <a href="{{ route('konselor.sesi.show', $sesi) }}" class="group flex flex-col rounded-[1.4rem] bg-card p-4 neu-sm transition hover:-translate-y-0.5">
                        <div class="flex items-center justify-between gap-3">
                            <span class="flex size-8 items-center justify-center rounded-lg bg-muted text-muted-foreground">
                                <x-icon name="chat" :size="15" />
                            </span>
                            <span @class(['size-2 rounded-full', 'bg-success' => $sesi->status === 'aktif', 'bg-[#c9971f]' => $sesi->status === 'menunggu', 'bg-muted-foreground/50' => $sesi->status === 'selesai'])></span>
                        </div>
                        <p class="mt-4 text-[0.68rem] tracking-[0.1em] uppercase text-muted-foreground">{{ $sesi->topic }}</p>
                        <p class="mt-1 text-sm font-semibold">Anonim #{{ $sesi->user_id }}</p>
                        <p class="mt-0.5 text-xs text-muted-foreground">{{ $sesi->created_at->diffForHumans() }} · {{ $sesi->messages_count }} pesan</p>
                        <span class="mt-auto flex items-center justify-between gap-2 pt-3">
                            <x-status :tone="$tones[$sesi->status] ?? 'neutral'">{{ ucfirst($sesi->status) }}</x-status>
                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-foreground">
                                Buka
                                <x-icon name="arrow-right" :size="12" />
                            </span>
                        </span>
                    </a>
                @endforeach

                <a href="{{ route('konselor.sesi') }}" class="flex min-h-[186px] flex-col items-center justify-center gap-2 rounded-[1.4rem] border-2 border-dashed border-foreground/30 bg-card/40 p-4 text-center transition hover:border-foreground/70">
                    <span class="flex size-10 items-center justify-center rounded-full bg-card text-foreground neu-sm">
                        <x-icon name="plus" :size="17" />
                    </span>
                    <span class="text-sm font-semibold">Semua sesi</span>
                    <span class="text-xs text-muted-foreground">{{ $sessions->count() }} sesi ditugaskan</span>
                </a>
            </div>

            <section class="mt-3 rounded-[1.4rem] bg-card p-4 neu-sm">
                <div class="flex items-center justify-between gap-3">
                    <h3 class="text-sm font-semibold">Menunggu balasanmu</h3>
                    <a href="{{ route('konselor.sesi') }}" class="text-xs font-semibold text-foreground hover:underline">Buka sesi</a>
                </div>
                <ul class="mt-3 space-y-2.5">
                    @forelse ($unreadSessions as $sesi)
                        <li>
                            <a href="{{ route('konselor.sesi.show', $sesi) }}" class="block rounded-xl bg-muted/60 p-3 transition hover:bg-muted">
                                <span class="flex items-center justify-between gap-2">
                                    <span class="text-xs font-semibold">Anonim #{{ $sesi->user_id }} · {{ $sesi->topic }}</span>
                                    <span class="text-xs text-muted-foreground">{{ $sesi->updated_at->diffForHumans() }}</span>
                                </span>
                                <span class="mt-0.5 block text-xs text-muted-foreground">{{ $sesi->unread_count }} pesan belum dibaca</span>
                            </a>
                        </li>
                    @empty
                        <li class="rounded-xl bg-muted/60 p-3 text-xs text-muted-foreground">Tidak ada pesan yang menunggu balasan.</li>
                    @endforelse
                </ul>
            </section>
        </div>
    </div>
@endsection
