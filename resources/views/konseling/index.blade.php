@extends('layouts.app')
@section('title', 'Konseling')
@section('content')
    <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
        <div>
            <h2 class="text-xl font-semibold tracking-tight sm:text-2xl">Konseling Privat</h2>
            <p class="mt-1 text-sm text-muted-foreground">Percakapan empat mata dengan konselor yang ditugaskan untukmu.</p>
        </div>

        <div x-data="{ open: {{ $errors->any() ? 'true' : 'false' }} }">
            <button type="button" class="btn-lime text-xs" @click="open = true" @if ($counselors->isEmpty()) disabled title="Belum ada konselor yang tersedia" @endif>
                <x-icon name="plus" :size="15" />
                Ajukan sesi baru
            </button>

            <div x-show="open" x-cloak x-transition.opacity class="fixed inset-0 z-50 flex items-end justify-center bg-foreground/40 p-4 backdrop-blur-sm sm:items-center" role="dialog" aria-modal="true" @keydown.escape.window="open = false">
                <div class="w-full max-w-md rounded-[1.4rem] bg-card p-5 neu" @click.outside="open = false">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-base font-semibold">Ajukan sesi konseling</h2>
                            <p class="mt-1 text-xs text-muted-foreground">Pilih konselor yang tersedia untuk memulai percakapan privat.</p>
                        </div>
                        <button type="button" class="rounded-lg p-1 text-muted-foreground hover:bg-muted" aria-label="Tutup" @click="open = false"><x-icon name="x" :size="18" /></button>
                    </div>

                    <form class="mt-4 space-y-3" method="POST" action="{{ route('konseling.store') }}">
                        @csrf
                        <div>
                            <label for="konselor" class="block text-xs font-medium text-muted-foreground">Pilih konselor</label>
                            <select id="konselor" name="counselor_id" required class="mt-1 w-full rounded-xl bg-input px-3 py-2.5 text-sm neu-inset focus:outline-none">
                                <option value="">Pilih konselor</option>
                                @foreach ($counselors as $counselor)
                                    <option value="{{ $counselor->id }}" @selected((string) old('counselor_id') === (string) $counselor->id)>
                                        {{ $counselor->name }}{{ $counselor->prodi ? ' · '.$counselor->prodi : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('counselor_id')<p class="mt-1 text-xs text-destructive">{{ $message }}</p>@enderror
                        </div>
                        <label for="topik" class="block text-xs font-medium text-muted-foreground">Topik utama</label>
                        <select id="topik" name="topic" required class="w-full rounded-xl bg-input px-3 py-2.5 text-sm neu-inset focus:outline-none">
                            <option value="Kecemasan" @selected(old('topic', 'Kecemasan') === 'Kecemasan')>Kecemasan</option>
                            <option>Tekanan akademik</option>
                            <option>Masalah keluarga</option>
                            <option>Kesepian</option>
                            <option>Lainnya</option>
                        </select>
                        <label for="catatan" class="block text-xs font-medium text-muted-foreground">Ceritakan singkat (opsional)</label>
                        <textarea id="catatan" name="note" maxlength="2000" rows="3" class="w-full rounded-xl bg-input px-3 py-2.5 text-sm neu-inset focus:outline-none" placeholder="Apa yang ingin kamu bahas?">{{ old('note') }}</textarea>
                        <p class="flex items-start gap-2 rounded-xl bg-muted/70 p-3 text-xs leading-relaxed text-muted-foreground"><x-icon name="lock" :size="13" class="mt-0.5" />Catatan dan chat hanya bisa dibaca oleh kamu dan konselor pilihanmu. Pesan tersimpan terenkripsi.</p>
                        <div class="flex justify-end gap-2 pt-1"><button type="button" class="rounded-xl px-4 py-2 text-sm font-medium text-muted-foreground hover:bg-muted" @click="open = false">Batal</button><button type="submit" class="btn-lime" @if ($counselors->isEmpty()) disabled @endif>Kirim pengajuan</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if (session('status'))
        <p class="mb-4 rounded-xl bg-success/10 p-3 text-xs font-medium text-success">{{ session('status') }}</p>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-xl bg-destructive/10 p-3 text-xs text-destructive">
            {{ $errors->first() }}
        </div>
    @endif

    @if ($counselors->isEmpty())
        <p class="mb-4 rounded-xl bg-muted p-3 text-xs text-muted-foreground">Belum ada konselor yang tersedia. Coba lagi nanti.</p>
    @endif

    <div class="grid gap-4 lg:grid-cols-[340px_1fr]">
        <div class="min-w-0 space-y-2.5">
            <div class="flex items-center justify-between px-1">
                <h3 class="text-xs font-semibold tracking-wide text-muted-foreground uppercase">Sesi kamu</h3>
                <span class="text-xs text-muted-foreground">{{ $sessions->count() }} sesi</span>
            </div>

            @forelse ($sessions as $sesi)
                @php($tone = ['aktif' => 'success', 'menunggu' => 'warning', 'selesai' => 'neutral'][$sesi->status] ?? 'neutral')
                <a href="{{ route('konseling.show', $sesi) }}" class="flex items-start gap-3 rounded-[1.4rem] bg-card p-3.5 neu-sm transition hover:bg-muted">
                    <span class="relative flex size-10 shrink-0 items-center justify-center rounded-full bg-primary/15 text-sm font-semibold text-foreground">
                        {{ mb_substr($sesi->counselor?->name ?? 'M', 0, 1) }}
                        @if ($sesi->status === 'aktif')<span class="absolute -right-0.5 -bottom-0.5 size-2.5 rounded-full border-2 border-card bg-success"></span>@endif
                    </span>
                    <span class="min-w-0 flex-1">
                        <span class="flex items-center gap-2">
                            <span class="truncate text-sm font-semibold">{{ $sesi->counselor?->name ?? 'Menunggu konselor' }}</span>
                            <span class="ml-auto shrink-0 text-xs text-muted-foreground">{{ $sesi->updated_at->diffForHumans() }}</span>
                        </span>
                        <span class="mt-0.5 block truncate text-xs text-muted-foreground">{{ $sesi->topic }} · {{ $sesi->messages_count }} pesan</span>
                        <span class="mt-1.5 flex items-center gap-2"><x-status :tone="$tone">{{ ucfirst($sesi->status) }}</x-status></span>
                    </span>
                </a>
            @empty
                <div class="rounded-[1.4rem] bg-card p-6 text-center neu-sm"><p class="text-sm font-semibold">Belum ada sesi</p><p class="mt-1 text-xs text-muted-foreground">Ajukan sesi konseling pertamamu.</p></div>
            @endforelse

            <p class="flex items-start gap-2 rounded-2xl bg-secondary/25 p-3.5 text-xs leading-relaxed text-muted-foreground"><x-icon name="shield" :size="14" class="mt-0.5 shrink-0" />Setiap percakapan memiliki kontrol akses sendiri. Konselor hanya bisa membuka sesi yang ditugaskan.</p>
        </div>

        <div class="hidden min-w-0 lg:block">
            @if ($sesi = $sessions->first())
                <x-chat-panel :session="$sesi" :name="$sesi->counselor?->name ?? 'Menunggu konselor'" role="Konselor" :state="ucfirst($sesi->status)" />
            @else
                <div class="flex h-full min-h-[420px] items-center justify-center rounded-[1.4rem] bg-card p-6 text-center neu-sm"><p class="text-xs text-muted-foreground">Ajukan sesi untuk memulai percakapan privat.</p></div>
            @endif
        </div>
    </div>
@endsection
