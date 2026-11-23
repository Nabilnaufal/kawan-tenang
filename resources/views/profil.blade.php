@extends('layouts.app')
@section('title', 'Profil')
@section('role', $role ?? 'pengguna')
@section('content')
    @if (session('status'))
        <p class="mb-4 rounded-xl bg-success/10 p-3 text-xs font-medium text-success">{{ session('status') }}</p>
    @endif
    <div class="mb-5">
        <h2 class="text-xl font-semibold tracking-tight sm:text-2xl">Profil & Privasi</h2>
        <p class="mt-1 text-sm text-muted-foreground">Kelola akun, identitas publik, dan izin data.</p>
    </div>

    <div class="grid gap-4 lg:grid-cols-[1fr_1.5fr]">
        {{-- Identity --}}
        <section class="self-start rounded-[1.4rem] bg-card p-5 neu-sm">
            <div class="flex items-center gap-4">
                <span class="flex size-16 items-center justify-center rounded-full bg-secondary text-secondary-foreground">
                    <x-icon name="user" :size="28" />
                </span>
                <div class="min-w-0">
                    <h3 class="truncate text-base font-semibold">{{ $user->name }}</h3>
                    <p class="text-xs text-muted-foreground">Bergabung sejak {{ $user->created_at->translatedFormat('F Y') }}</p>
                </div>
            </div>

            <div class="mt-4 flex flex-wrap gap-2">
                <x-status tone="success">
                    <x-icon name="check" :size="12" />
                    Email terverifikasi
                </x-status>
                    @if ($user->face_registered_at)
                        <x-status tone="success">Wajah terdaftar</x-status>
                    @else
                        <x-status tone="warning">Wajah belum terdaftar</x-status>
                    @endif
            </div>

            <dl class="mt-5 space-y-3 border-t border-border/60 pt-4 text-xs">
                <div class="flex items-center justify-between gap-3">
                    <dt class="text-muted-foreground">Email</dt>
                    <dd class="truncate font-medium">{{ $user->email }}</dd>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <dt class="text-muted-foreground">Peran</dt>
                    <dd class="font-medium">{{ ucfirst($user->role) }}</dd>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <dt class="text-muted-foreground">Nama publik</dt>
                    <dd class="flex items-center gap-1.5 font-medium">
                        <x-icon name="lock" :size="12" />
                        Selalu Anonim
                    </dd>
                </div>
            </dl>

            @if (($role ?? 'pengguna') === 'pengguna')
                <a href="{{ route('verifikasi') }}" class="mt-5 flex items-center justify-center gap-2 rounded-full bg-background px-4 py-2.5 text-xs font-semibold neu-sm transition hover:bg-muted">
                    <x-icon name="face" :size="14" />
                    Verifikasi wajah
                </a>
            @endif
        </section>

        <div class="space-y-4">
            {{-- Account form --}}
            <section class="rounded-[1.4rem] bg-card p-5 neu-sm">
                <h3 class="text-sm font-semibold">Informasi akun</h3>
                <form class="mt-4 grid gap-3 sm:grid-cols-2" method="POST" action="{{ route('profil.update') }}">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label for="nama" class="mb-1.5 block text-xs font-medium text-muted-foreground">Nama</label>
                        <input id="nama" name="name" type="text" value="{{ old('name', $user->name) }}" required class="w-full rounded-xl bg-input px-3.5 py-2.5 text-sm neu-inset focus:outline-none">
                    </div>
                    <div>
                        <label for="email" class="mb-1.5 block text-xs font-medium text-muted-foreground">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-xl bg-input px-3.5 py-2.5 text-sm neu-inset focus:outline-none">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="sandi" class="mb-1.5 block text-xs font-medium text-muted-foreground">Kata sandi baru</label>
                        <input id="sandi" name="password" type="password" placeholder="Kosongkan jika tidak diubah" class="w-full rounded-xl bg-input px-3.5 py-2.5 text-sm neu-inset focus:outline-none">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="sandi_confirmation" class="mb-1.5 block text-xs font-medium text-muted-foreground">Ulangi kata sandi baru</label>
                        <input id="sandi_confirmation" name="password_confirmation" type="password" class="w-full rounded-xl bg-input px-3.5 py-2.5 text-sm neu-inset focus:outline-none">
                    </div>
                    <div class="flex items-center justify-end gap-2 sm:col-span-2">
                        <button type="button" class="rounded-xl px-4 py-2 text-xs font-medium text-muted-foreground hover:bg-muted">Batal</button>
                        <button type="submit" class="rounded-full bg-primary px-4 py-2 text-xs font-semibold text-primary-foreground">Simpan perubahan</button>
                    </div>
                </form>
            </section>

            {{-- Privacy --}}
            <section class="rounded-[1.4rem] bg-card p-5 neu-sm">
                <h3 class="text-sm font-semibold">Privasi</h3>
                <div class="mt-3 divide-y divide-border/60">
                    <div class="flex items-center gap-3 py-3">
                        <span class="min-w-0 flex-1">
                            <span class="block text-xs font-medium">Selalu tampil anonim</span>
                            <span class="block text-xs text-muted-foreground">Nama asli tidak pernah ditampilkan di ruang publik.</span>
                        </span>
                        <x-status tone="success">Aktif</x-status>
                    </div>
                </div>

                @if ($user->face_registered_at)
                    <form method="POST" action="{{ route('profil.wajah.destroy') }}" class="mt-3" onsubmit="return confirm('Hapus data wajah dari akun ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-destructive/10 px-4 py-2.5 text-xs font-semibold text-destructive hover:bg-destructive/15">
                            <x-icon name="x" :size="14" />
                            Hapus data wajah
                        </button>
                    </form>
                @endif
            </section>

        </div>
    </div>
@endsection
