@extends('layouts.app')
@section('title', 'Dasbor Konselor')
@section('role', 'konselor')
@section('content')
    <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
        <div>
            <div class="flex flex-wrap items-center gap-2">
                <h2 class="text-xl font-semibold tracking-tight sm:text-2xl">Halo, Kak</h2>
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
                <span data-count="37">0</span>
            </p>
            <p class="mt-1.5 text-xs text-primary-foreground/60">sesi ditangani bulan ini</p>

            <div class="mt-3 flex flex-wrap gap-2">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-secondary px-3 py-1 text-[0.72rem] font-bold text-secondary-foreground">
                    <x-icon name="clock" :size="12" />
                    9 mnt rata-rata
                </span>
                <span class="inline-flex items-center gap-1.5 rounded-full border border-primary-foreground/20 px-3 py-1 text-[0.72rem] font-medium">
                    98% tepat waktu
                </span>
            </div>

            <dl class="mt-auto grid grid-cols-3 gap-2 pt-5">
                @foreach ([
                    ['label' => 'Aktif', 'value' => '6'],
                    ['label' => 'Menunggu', 'value' => '3'],
                    ['label' => 'Selesai', 'value' => '28'],
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
                <canvas id="chartSesiKonselor" role="img" aria-label="Diagram batang jumlah sesi konseling per hari"></canvas>
            </div>
        </section>

        <section class="rounded-[1.4rem] bg-card p-4 neu-sm sm:p-5" x-data="{ available: true }">
            <div class="flex items-center justify-between gap-3">
                <h3 class="text-sm font-semibold">Ketersediaan</h3>
                <span class="flex items-center gap-1.5 text-xs text-muted-foreground">
                    <span class="size-2 rounded-full bg-success" x-show="available"></span>
                    <span x-text="available ? 'Online' : 'Offline'">Online</span>
                </span>
            </div>

            <div class="mt-3 flex items-center justify-between gap-3 rounded-xl bg-muted/60 p-3">
                <span class="text-xs font-medium" x-text="available ? 'Menerima sesi baru' : 'Sedang tidak tersedia'">Menerima sesi baru</span>
                <button
                    type="button"
                    role="switch"
                    :aria-checked="available"
                    @click="available = !available"
                    class="relative h-6 w-11 shrink-0 rounded-full transition"
                    :class="available ? 'bg-primary' : 'bg-muted-foreground/30'"
                    aria-label="Ketersediaan konselor"
                >
                    <span class="absolute top-0.5 left-0.5 size-5 rounded-full bg-card shadow transition" :class="available ? 'translate-x-5' : ''"></span>
                </button>
            </div>

            <p class="label-cap mt-4 mb-2 block">Jadwal hari ini</p>
            <ul class="space-y-2.5 text-xs">
                @foreach ([['10.00', 'Sesi lanjutan · Anonim #2418'], ['13.30', 'Sesi baru · Anonim #2420'], ['16.00', 'Evaluasi tim konselor']] as [$time, $label])
                    <li class="flex gap-3">
                        <span class="w-10 shrink-0 font-semibold tabular-nums text-foreground">{{ $time }}</span>
                        <span class="text-muted-foreground">{{ $label }}</span>
                    </li>
                @endforeach
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

            <form class="mt-4 space-y-3" @submit.prevent="$el.reset()">
                <div>
                    <label for="sesi-catatan" class="label-cap mb-1.5 block">Sesi</label>
                    <select id="sesi-catatan" class="w-full rounded-xl bg-input px-3 py-2.5 text-sm neu-inset focus:outline-none">
                        <option>Anonim #2418 · Kecemasan</option>
                        <option>Anonim #2411 · Tekanan akademik</option>
                        <option>Anonim #2396 · Masalah keluarga</option>
                    </select>
                </div>

                <div>
                    <label for="isi-catatan" class="label-cap mb-1.5 block">Catatan</label>
                    <textarea id="isi-catatan" rows="4" placeholder="Ringkasan sesi, rencana tindak lanjut…" class="w-full resize-none rounded-xl bg-input px-3 py-2.5 text-sm placeholder:text-muted-foreground neu-inset focus:outline-none"></textarea>
                </div>

                <div class="flex items-center justify-between gap-3">
                    <p class="flex items-center gap-1.5 text-[0.7rem] text-muted-foreground">
                        <x-icon name="lock" :size="12" />
                        Catatan internal
                    </p>
                    <button type="submit" class="btn-lime">
                        Simpan catatan
                        <x-icon name="arrow-right" :size="14" />
                    </button>
                </div>
            </form>
        </section>

        <div class="min-w-0">
            <div class="flex flex-wrap items-end justify-between gap-2">
                <h3 class="text-base font-semibold tracking-tight">
                    Sesi terbaru
                    <span class="text-xs font-normal text-muted-foreground">(2 sorotan)</span>
                </h3>
                <a href="{{ route('konselor.sesi') }}" class="text-xs font-semibold text-foreground hover:underline">Lihat semua</a>
            </div>

            <div class="mt-3 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ([
                    ['Anonim #2418', 'Kecemasan', '5 menit lalu', 'Aktif', 'success'],
                    ['Anonim #2411', 'Tekanan akademik', '22 menit lalu', 'Menunggu', 'warning'],
                ] as [$user, $topic, $time, $status, $tone])
                    <a href="{{ route('konselor.sesi.show', 1) }}" class="group flex flex-col rounded-[1.4rem] bg-card p-4 neu-sm transition hover:-translate-y-0.5">
                        <div class="flex items-center justify-between gap-3">
                            <span class="flex size-8 items-center justify-center rounded-lg bg-muted text-muted-foreground">
                                <x-icon name="chat" :size="15" />
                            </span>
                            <span @class(['size-2 rounded-full', 'bg-success' => $tone === 'success', 'bg-[#c9971f]' => $tone === 'warning'])></span>
                        </div>
                        <p class="mt-4 text-[0.68rem] tracking-[0.1em] uppercase text-muted-foreground">{{ $topic }}</p>
                        <p class="mt-1 text-sm font-semibold">{{ $user }}</p>
                        <p class="mt-0.5 text-xs text-muted-foreground">{{ $time }}</p>
                        <span class="mt-auto flex items-center justify-between gap-2 pt-3">
                            <x-status :tone="$tone">{{ $status }}</x-status>
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
                    <span class="text-xs text-muted-foreground">4 sesi ditugaskan</span>
                </a>
            </div>

            <section class="mt-3 rounded-[1.4rem] bg-card p-4 neu-sm">
                <div class="flex items-center justify-between gap-3">
                    <h3 class="text-sm font-semibold">Menunggu balasanmu</h3>
                    <a href="{{ route('konselor.sesi') }}" class="text-xs font-semibold text-foreground hover:underline">Buka sesi</a>
                </div>
                <ul class="mt-3 space-y-2.5">
                    @foreach ([['Anonim #2411', 'Aku bingung harus mulai dari mana…', '22 mnt'], ['Anonim #2420', 'Besok aku ada presentasi, aku takut…', '41 mnt'], ['Anonim #2415', 'Makasih sarannya, aku coba ya.', '1 jam']] as [$user, $preview, $time])
                        <li>
                            <a href="{{ route('konselor.sesi.show', 1) }}" class="block rounded-xl bg-muted/60 p-3 transition hover:bg-muted">
                                <span class="flex items-center justify-between gap-2">
                                    <span class="text-xs font-semibold">{{ $user }}</span>
                                    <span class="text-xs text-muted-foreground">{{ $time }}</span>
                                </span>
                                <span class="mt-0.5 block truncate text-xs text-muted-foreground">{{ $preview }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>
        </div>
    </div>
@endsection
