@extends('layouts.app')
@section('title', 'Profil')
@section('role', $role ?? 'pengguna')
@section('content')
    @if (session('status'))
        <p class="mb-4 rounded-xl bg-success/10 p-3 text-xs font-medium text-success">{{ session('status') }}</p>
    @endif
    <div class="mb-5">
        <h2 class="text-xl font-semibold tracking-tight sm:text-2xl">Profil</h2>
        <p class="mt-1 text-sm text-muted-foreground">Kelola akun dan identitas publikmu.</p>
    </div>

    <div class="grid items-stretch gap-4 lg:grid-cols-2">
        {{-- Identity --}}
        <section class="h-full rounded-[1.4rem] bg-card p-5 neu-sm">
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

        {{-- Account form --}}
        <section class="h-full rounded-[1.4rem] bg-card p-5 neu-sm">
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
    </div>
@endsection
