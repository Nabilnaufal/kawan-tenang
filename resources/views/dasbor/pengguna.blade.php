@extends('layouts.app')
@section('title', 'Dasbor')
@section('content')
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
                @foreach ($platformStats as $stat)
                    <div>
                        <dd class="text-xl font-semibold tracking-tight tabular-nums" data-count="{{ $stat['value'] }}">0</dd>
                        <dt class="mt-1 text-[0.72rem] leading-tight tracking-[0.1em] text-primary-foreground/60 uppercase">
                            {{ $stat['label'] }}
                        </dt>
                    </div>
                @endforeach
            </dl>
        </div>
    </section>

    <section class="mt-8">
        <div class="flex items-end justify-between gap-4">
            <h2 class="text-lg font-semibold tracking-tight">Cerita terbaru</h2>
            <a href="{{ route('bubble') }}" class="text-xs font-semibold text-foreground hover:underline">Lihat semua</a>
        </div>

        <div class="mt-3 space-y-3">
            @forelse ($latestPosts as $post)
                <x-post :post="$post" />
            @empty
                <div class="rounded-[1.4rem] bg-card p-6 text-center neu-sm">
                    <p class="text-sm font-semibold">Belum ada cerita</p>
                    <p class="mt-1 text-xs text-muted-foreground">Jadilah orang pertama yang berbagi hari ini.</p>
                </div>
            @endforelse
        </div>
    </section>

    <div class="mt-8 mb-4 flex flex-wrap items-end justify-between gap-3">
        <div>
            <div class="flex flex-wrap items-center gap-2">
                <h2 class="text-xl font-semibold tracking-tight sm:text-2xl">Aktivitasmu</h2>
                <span class="rounded-full bg-card px-3 py-1 text-[0.72rem] font-semibold text-muted-foreground neu-sm">Pengguna</span>
            </div>
            <p class="mt-1 text-sm text-muted-foreground">Cerita dan sesi konselingmu di Kawan Tenang.</p>
        </div>
        <a href="{{ route('bubble') }}" class="btn-lime">
            <x-icon name="plus" :size="15" />
            Tulis cerita
        </a>
    </div>

    <div class="mt-3 grid gap-3 xl:grid-cols-[1.25fr_0.85fr_1.5fr]">
        <section class="flex h-full flex-col rounded-[1.4rem] bg-card p-4 neu-sm sm:p-5">
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
                    <textarea id="isi-cerita" name="body" rows="5" required maxlength="500" placeholder="Apa yang sedang kamu rasakan hari ini, Kawan?" class="min-h-[180px] w-full resize-none rounded-xl bg-input px-3 py-2.5 text-sm leading-relaxed placeholder:text-muted-foreground neu-inset focus:outline-none"></textarea>
                </div>

                <div class="flex items-center justify-between gap-3">
                    <p class="flex items-center gap-1.5 text-[0.7rem] text-muted-foreground">
                        <x-icon name="lock" :size="12" />
                        Diposting sebagai Saya
                    </p>
                    <button type="submit" class="btn-lime">
                        Bagikan
                        <x-icon name="arrow-right" :size="14" />
                    </button>
                </div>
            </form>
        </section>

        <section class="flex h-full flex-col rounded-[1.4rem] bg-card p-4 neu-sm sm:p-5">
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
                        <span class="relative flex size-8 shrink-0 items-center justify-center rounded-full bg-secondary text-sm font-semibold text-secondary-foreground">
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
                        <p class="mt-0.5 text-xs text-muted-foreground">{{ $post->created_at->diffForHumans() }} · Saya</p>
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

        </div>
    </div>
@endsection
