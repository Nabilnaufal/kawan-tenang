@extends('layouts.app')
@section('title', 'Dasbor')
@section('content')
    <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
        <div>
            <div class="flex flex-wrap items-center gap-2">
                <h2 class="text-xl font-semibold tracking-tight sm:text-2xl">Halo, {{ $user->name }}</h2>
                <span class="rounded-full bg-card px-3 py-1 text-[0.72rem] font-semibold text-muted-foreground neu-sm">Pengguna</span>
            </div>
            <p class="mt-1 text-sm text-muted-foreground">Ringkasan aktivitas, status akun, dan sesi konselingmu.</p>
        </div>
        <a href="{{ route('bubble') }}" class="btn-lime">
            <x-icon name="plus" :size="15" />
            Tulis cerita
        </a>
    </div>

    <div class="grid gap-3 xl:grid-cols-[1.32fr_1fr_1fr]">
        <section class="card-ink flex flex-col rounded-[1.5rem] p-5">
            <div class="flex items-start justify-between gap-3">
                <p class="label-cap text-primary-foreground/60">Ringkasan aktivitas</p>
                <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-primary-foreground/10">
                    <x-icon name="chart" :size="16" />
                </span>
            </div>

            <p class="mt-3 text-3xl leading-none font-semibold tracking-tight tabular-nums">
                <span data-count="{{ $interaksi }}">0</span>
            </p>
            <p class="mt-1.5 text-xs text-primary-foreground/60">interaksi tercatat</p>

            <div class="mt-3 flex flex-wrap gap-2">
                <span class="inline-flex items-center gap-1.5 rounded-full border border-primary-foreground/20 px-3 py-1 text-[0.72rem] font-medium">
                    {{ $sessions->where('status', 'aktif')->count() }} sesi aktif
                </span>
            </div>

            <dl class="mt-auto grid grid-cols-3 gap-2 pt-5">
                @foreach ([
                    ['label' => 'Cerita', 'value' => $stats['cerita']],
                    ['label' => 'Komentar', 'value' => $stats['komentar']],
                    ['label' => 'Dukungan', 'value' => $stats['dukungan']],
                ] as $stat)
                    <div class="rounded-2xl bg-primary-foreground/10 p-3">
                        <dt class="text-[0.62rem] tracking-[0.1em] uppercase text-primary-foreground/60">{{ $stat['label'] }}</dt>
                        <dd class="mt-1.5 text-lg font-semibold tabular-nums"><span data-count="{{ $stat['value'] }}">0</span></dd>
                    </div>
                @endforeach
            </dl>
        </section>

        <section class="flex flex-col rounded-[1.4rem] bg-card p-4 neu-sm sm:p-5">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h3 class="text-sm font-semibold">Status akun</h3>
                    <p class="mt-0.5 text-xs text-muted-foreground">Identitas publikmu tetap anonim.</p>
                </div>
                <span class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-muted text-muted-foreground">
                    <x-icon name="shield" :size="15" />
                </span>
            </div>

            <dl class="mt-4 space-y-2.5 text-xs">
                <div class="flex items-center justify-between gap-3">
                    <dt class="text-muted-foreground">Email</dt>
                    <dd class="truncate font-medium">{{ $user->email }}</dd>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <dt class="text-muted-foreground">Nama publik</dt>
                    <dd class="font-medium">Anonim</dd>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <dt class="text-muted-foreground">Verifikasi wajah</dt>
                    <dd>
                        @if ($user->face_registered_at)
                            <x-status tone="success">Terdaftar</x-status>
                        @else
                            <x-status tone="warning">Belum</x-status>
                        @endif
                    </dd>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <dt class="text-muted-foreground">Email aktif</dt>
                    <dd>
                        @if ($user->email_verified_at)
                            <x-status tone="success">Terverifikasi</x-status>
                        @else
                            <x-status tone="warning">Belum</x-status>
                        @endif
                    </dd>
                </div>
            </dl>

            <a href="{{ route('verifikasi') }}" class="btn-soft mt-auto w-full">
                <x-icon name="face" :size="14" />
                {{ $user->face_registered_at ? 'Perbarui wajah' : 'Mulai verifikasi' }}
            </a>
        </section>

        <section class="flex flex-col rounded-[1.4rem] bg-card p-4 neu-sm sm:p-5">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h3 class="text-sm font-semibold">Konselor kamu</h3>
                    <p class="mt-0.5 text-xs text-muted-foreground">{{ $counselors->count() }} konselor terhubung.</p>
                </div>
                <span class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-muted text-muted-foreground">
                    <x-icon name="users" :size="15" />
                </span>
            </div>

            <ul class="mt-4 space-y-3">
                @forelse ($counselors as $counselor)
                    <li class="flex items-center gap-3">
                        <span class="relative flex size-9 shrink-0 items-center justify-center rounded-full bg-secondary text-sm font-semibold text-secondary-foreground">
                            {{ mb_substr($counselor->name, 0, 1) }}
                        </span>
                        <span class="min-w-0 flex-1 leading-tight">
                            <span class="block truncate text-sm font-medium">Kak {{ $counselor->name }}</span>
                            <span class="block truncate text-xs text-muted-foreground">{{ $counselor->prodi ?: 'Konselor' }}</span>
                        </span>
                        <span class="text-xs font-medium text-success">Terhubung</span>
                    </li>
                @empty
                    <li class="text-xs text-muted-foreground">Belum ada konselor. Ajukan sesi untuk terhubung.</li>
                @endforelse
            </ul>

            <a href="{{ route('konseling.index') }}" class="btn-soft mt-auto w-full">
                <x-icon name="chat" :size="14" />
                Cari konselor
            </a>
        </section>
    </div>

    <div class="mt-3 grid gap-3 xl:grid-cols-[1fr_1.55fr]">
        <section class="rounded-[1.4rem] bg-card p-4 neu-sm sm:p-5">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h3 class="text-sm font-semibold">Tulis cerita</h3>
                    <p class="mt-0.5 text-xs text-muted-foreground">Bagikan apa yang sedang kamu rasakan.</p>
                </div>
                <span class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-muted text-muted-foreground">
                    <x-icon name="bubble" :size="15" />
                </span>
            </div>

            <form class="mt-4 space-y-3" method="POST" action="{{ route('bubble.store') }}">
                @csrf
                <div>
                    <label for="kategori-cerita" class="label-cap mb-1.5 block">Kategori</label>
                    <select id="kategori-cerita" name="tag" class="w-full rounded-xl bg-input px-3 py-2.5 text-sm neu-inset focus:outline-none">
                        <option value="">Cerita harian</option>
                        <option value="Perlu dukungan">Perlu dukungan</option>
                        <option value="Tips & pengalaman">Tips &amp; pengalaman</option>
                        <option value="Perayaan kecil">Perayaan kecil</option>
                    </select>
                </div>

                <div>
                    <label for="isi-cerita" class="label-cap mb-1.5 block">Ceritamu</label>
                    <textarea id="isi-cerita" name="body" rows="4" required maxlength="500" placeholder="Apa yang sedang kamu rasakan hari ini, Kawan?" class="w-full resize-none rounded-xl bg-input px-3 py-2.5 text-sm leading-relaxed placeholder:text-muted-foreground neu-inset focus:outline-none"></textarea>
                </div>

                <div class="flex items-center justify-between gap-3">
                    <p class="flex items-center gap-1.5 text-[0.7rem] text-muted-foreground">
                        <x-icon name="lock" :size="12" />
                        Diposting sebagai Anonim
                    </p>
                    <button type="submit" class="btn-lime">
                        Bagikan
                        <x-icon name="arrow-right" :size="14" />
                    </button>
                </div>
            </form>
        </section>

        <div class="min-w-0">
            <div class="flex flex-wrap items-end justify-between gap-2">
                <h3 class="text-base font-semibold tracking-tight">
                    Terbaru untukmu
                    <span class="text-xs font-normal text-muted-foreground">({{ $sessions->count() + $posts->count() }} aktivitas)</span>
                </h3>
                <a href="{{ route('bubble') }}" class="text-xs font-semibold text-foreground hover:underline">Lihat semua</a>
            </div>

            <div class="mt-3 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                @if ($sesi = $sessions->first())
                    @php($tone = ['aktif' => 'success', 'menunggu' => 'warning'][$sesi->status] ?? 'neutral')
                    <a href="{{ route('konseling.show', $sesi) }}" class="group flex flex-col rounded-[1.4rem] bg-card p-4 neu-sm transition hover:-translate-y-0.5">
                        <div class="flex items-center justify-between gap-3">
                            <span class="flex size-8 items-center justify-center rounded-lg bg-muted text-muted-foreground">
                                <x-icon name="chat" :size="15" />
                            </span>
                            <span class="size-2 rounded-full {{ ['success' => 'bg-success', 'warning' => 'bg-[#c9971f]'][$tone] ?? 'bg-muted-foreground/50' }}"></span>
                        </div>
                        <p class="mt-4 text-[0.68rem] tracking-[0.1em] uppercase text-muted-foreground">Sesi konseling</p>
                        <p class="mt-1 text-sm font-semibold">{{ $sesi->counselor?->name ?? 'Menunggu konselor' }}</p>
                        <p class="mt-0.5 truncate text-xs text-muted-foreground">{{ $sesi->topic }} · {{ $sesi->messages_count }} pesan</p>
                        <span class="mt-auto flex items-center justify-between gap-2 pt-3">
                            <x-status :tone="$tone">{{ ucfirst($sesi->status) }}</x-status>
                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-foreground">
                                Lanjut
                                <x-icon name="arrow-right" :size="12" />
                            </span>
                        </span>
                    </a>
                @else
                    <div class="flex min-h-[186px] flex-col items-center justify-center gap-2 rounded-[1.4rem] border-2 border-dashed border-foreground/30 bg-card/40 p-4 text-center">
                        <span class="flex size-10 items-center justify-center rounded-full bg-card text-foreground neu-sm">
                            <x-icon name="chat" :size="17" />
                        </span>
                        <span class="text-sm font-semibold">Belum ada sesi</span>
                        <span class="text-xs text-muted-foreground">Ajukan sesi konseling pertamamu.</span>
                    </div>
                @endif

                @if ($post = $posts->first())
                    <a href="{{ route('bubble') }}" class="group flex flex-col rounded-[1.4rem] bg-card p-4 neu-sm transition hover:-translate-y-0.5">
                        <div class="flex items-center justify-between gap-3">
                            <span class="flex size-8 items-center justify-center rounded-lg bg-muted text-muted-foreground">
                                <x-icon name="bubble" :size="15" />
                            </span>
                            <span class="flex items-center gap-1 text-xs text-muted-foreground">
                                <x-icon name="heart" :size="12" />
                                {{ $post->likes_count }}
                            </span>
                        </div>
                        <p class="mt-4 text-[0.68rem] tracking-[0.1em] uppercase text-muted-foreground">Cerita terakhir</p>
                        <p class="mt-1 line-clamp-2 text-sm font-semibold">{{ $post->body }}</p>
                        <p class="mt-0.5 text-xs text-muted-foreground">{{ $post->created_at->diffForHumans() }} · Anonim</p>
                        <span class="mt-auto inline-flex items-center gap-1 pt-3 text-xs font-semibold text-foreground">
                            Baca
                            <x-icon name="arrow-right" :size="12" />
                        </span>
                    </a>
                @else
                    <div class="flex min-h-[186px] flex-col items-center justify-center gap-2 rounded-[1.4rem] border-2 border-dashed border-foreground/30 bg-card/40 p-4 text-center">
                        <span class="flex size-10 items-center justify-center rounded-full bg-card text-foreground neu-sm">
                            <x-icon name="bubble" :size="17" />
                        </span>
                        <span class="text-sm font-semibold">Belum ada cerita</span>
                        <span class="text-xs text-muted-foreground">Tulis cerita pertamamu di atas.</span>
                    </div>
                @endif

                <a href="{{ route('konseling.index') }}" class="flex min-h-[186px] flex-col items-center justify-center gap-2 rounded-[1.4rem] border-2 border-dashed border-foreground/30 bg-card/40 p-4 text-center transition hover:border-foreground/70">
                    <span class="flex size-10 items-center justify-center rounded-full bg-card text-foreground neu-sm">
                        <x-icon name="plus" :size="17" />
                    </span>
                    <span class="text-sm font-semibold">Ajukan sesi</span>
                    <span class="text-xs text-muted-foreground">Konselor membalas 1×24 jam</span>
                </a>
            </div>

            <div class="mt-3 flex flex-col gap-3 rounded-[1.4rem] bg-secondary/30 p-4 sm:flex-row sm:items-center">
                <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-card text-foreground">
                    <x-icon name="lock" :size="16" />
                </span>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold">Kendali privasi</p>
                    <p class="text-xs text-muted-foreground">Unduh datamu atau hapus data verifikasi kapan saja.</p>
                </div>
                <a href="{{ route('profil') }}" class="btn-soft shrink-0 px-4 py-2 text-xs">Buka pengaturan</a>
            </div>
        </div>
    </div>
@endsection
