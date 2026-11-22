@extends('layouts.guest')
@section('title', 'Masuk')
@section('content')
    <div x-data="{ faceOpen: false, tutup() { this.$dispatch('tutup-kamera'); this.faceOpen = false; } }" @keydown.escape.window="tutup()">
        <h2 class="text-2xl font-semibold tracking-tight">Masuk ke akunmu</h2>
        <p class="mt-1.5 text-sm text-muted-foreground">Ruang amanmu tetap anonim dan privat.</p>

        @if (session('status'))
            <p class="mt-4 rounded-xl bg-secondary/30 p-3 text-xs font-medium">{{ session('status') }}</p>
        @endif

        @error('wajah')
            <p class="mt-4 flex items-start gap-2 rounded-xl bg-destructive/10 p-3 text-xs font-medium text-destructive">
                <x-icon name="alert" :size="14" class="mt-0.5" />
                {{ $message }}
            </p>
        @enderror

        <form class="mt-7 space-y-4" action="{{ route('masuk.store') }}" method="POST">
            @csrf

            <div>
                <label for="email" class="mb-1.5 block text-xs font-medium text-muted-foreground">Email</label>
                <div class="flex items-center gap-2 rounded-xl bg-input px-3.5 py-3 neu-inset">
                    <x-icon name="mail" :size="16" class="text-muted-foreground" />
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        placeholder="nama@email.com"
                        class="min-w-0 flex-1 bg-transparent text-sm text-foreground placeholder:text-muted-foreground focus:outline-none"
                    >
                </div>
                @error('email')
                    <p class="mt-1.5 text-xs font-medium text-destructive">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div class="mb-1.5 flex items-center justify-between">
                    <label for="password" class="block text-xs font-medium text-muted-foreground">Kata sandi</label>
                    <a href="{{ route('lupa-sandi') }}" class="text-xs font-semibold text-foreground hover:underline">Lupa sandi?</a>
                </div>
                <div x-data="{ show: false }" class="flex items-center gap-2 rounded-xl bg-input px-3.5 py-3 neu-inset">
                    <x-icon name="lock" :size="16" class="text-muted-foreground" />
                    <input
                        id="password"
                        name="password"
                        :type="show ? 'text' : 'password'"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="min-w-0 flex-1 bg-transparent text-sm text-foreground placeholder:text-muted-foreground focus:outline-none"
                    >
                    <button type="button" class="text-muted-foreground hover:text-foreground" @click="show = !show" :aria-label="show ? 'Sembunyikan sandi' : 'Tampilkan sandi'">
                        <x-icon name="eye" :size="16" />
                    </button>
                </div>
            </div>

            <label class="flex items-center gap-2 text-xs text-muted-foreground">
                <input type="checkbox" name="remember" class="size-4 rounded border-border text-primary focus:ring-ring">
                Ingat saya di perangkat ini
            </label>

            <button type="submit" class="btn-lime w-full py-3">
                Masuk
                <x-icon name="arrow-right" :size="15" />
            </button>
        </form>

        <div class="my-5 flex items-center gap-3 text-[0.7rem] tracking-wider text-muted-foreground uppercase">
            <span class="h-px flex-1 bg-border"></span>
            atau
            <span class="h-px flex-1 bg-border"></span>
        </div>

        <button type="button" class="flex w-full items-center justify-center gap-2 rounded-full bg-background py-3 text-sm font-semibold neu-sm transition hover:bg-muted" @click="faceOpen = true">
            <x-icon name="face" :size="18" />
            Masuk dengan wajah
        </button>

        <p class="mt-5 text-center text-xs text-muted-foreground">
            Belum punya akun?
            <a href="{{ route('daftar') }}" class="font-semibold text-foreground hover:underline">Daftar sekarang</a>
        </p>

        <p class="mt-6 flex items-start gap-2 rounded-xl bg-muted/60 p-3 text-xs leading-relaxed text-muted-foreground">
            <x-icon name="lock" :size="13" class="mt-0.5" />
            Nama dan email tidak pernah ditampilkan di Bubble Publik. Identitas publik selalu "Anonim".
        </p>

        <p class="mt-6 border-t border-border/60 pt-4 text-center text-xs text-muted-foreground">
            Tampilan peran:
            <a href="{{ route('konselor.dasbor') }}" class="font-semibold text-foreground hover:underline">Konselor</a>
            <span class="mx-1">·</span>
            <a href="{{ route('admin.dasbor') }}" class="font-semibold text-foreground hover:underline">Administrator</a>
        </p>

        {{-- Dialog masuk dengan wajah --}}
        <div
            x-show="faceOpen"
            x-cloak
            x-transition.opacity
            class="fixed inset-0 z-50 flex items-end justify-center bg-foreground/40 p-4 backdrop-blur-sm sm:items-center"
            role="dialog"
            aria-modal="true"
            aria-labelledby="wajah-title"
        >
            <div class="w-full max-w-lg rounded-[1.4rem] bg-card p-5 neu" @click.outside="tutup()">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 id="wajah-title" class="text-base font-semibold">Masuk dengan wajah</h2>
                        <p class="mt-1 text-xs text-muted-foreground">Hadapkan wajah ke kamera. Pencocokan berjalan di server.</p>
                    </div>
                    <button type="button" class="rounded-lg p-1 text-muted-foreground hover:bg-muted" aria-label="Tutup" @click="tutup()">
                        <x-icon name="x" :size="18" />
                    </button>
                </div>

                <form class="mt-4" action="{{ route('masuk.wajah') }}" method="POST" x-ref="faceForm" @wajah-siap.window="$refs.faceForm.requestSubmit()">
                    @csrf
                    <x-pemindai-wajah />
                </form>
            </div>
        </div>
    </div>
@endsection
