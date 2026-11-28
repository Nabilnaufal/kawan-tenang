@extends('layouts.guest')
@section('title', 'Lupa Sandi')
@section('content')
    <div>
        <h2 class="text-2xl font-semibold tracking-tight">Lupa kata sandi</h2>
        <p class="mt-1.5 text-sm text-muted-foreground">Masukkan email akunmu. Kami kirim tautan untuk membuat sandi baru.</p>

        @if (session('status'))
            <p class="mt-4 rounded-xl bg-success/10 p-3 text-xs font-medium text-success">{{ session('status') }}</p>
        @endif

        @error('email')
            <p class="mt-4 rounded-xl bg-destructive/10 p-3 text-xs font-medium text-destructive">{{ $message }}</p>
        @enderror

        <form class="mt-7 space-y-4" action="{{ route('lupa-sandi.store') }}" method="POST">
            @csrf

            <div>
                <label for="email" class="mb-1.5 block text-xs font-medium text-muted-foreground">Email</label>
                <div class="flex items-center gap-2 rounded-xl bg-input px-3.5 py-3 neu-inset">
                    <x-icon name="mail" :size="16" class="text-muted-foreground" />
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" placeholder="nama@email.com" class="min-w-0 flex-1 bg-transparent text-sm placeholder:text-muted-foreground focus:outline-none">
                </div>
            </div>

            <button type="submit" class="btn-lime w-full py-3">
                <x-icon name="send" :size="15" />
                Kirim tautan reset
            </button>
        </form>

        <p class="mt-5 text-center text-xs text-muted-foreground">
            Ingat sandimu?
            <a href="{{ route('masuk') }}" class="font-semibold text-foreground hover:underline">Kembali masuk</a>
        </p>
    </div>
@endsection
