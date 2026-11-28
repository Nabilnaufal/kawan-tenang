@extends('layouts.guest')
@section('title', 'Buat Sandi Baru')
@section('content')
    <div>
        <h2 class="text-2xl font-semibold tracking-tight">Buat kata sandi baru</h2>
        <p class="mt-1.5 text-sm text-muted-foreground">Gunakan kata sandi baru untuk mengamankan akun Kawan Tenang.</p>

        @if ($errors->any())
            <div class="mt-4 rounded-xl bg-destructive/10 p-3 text-xs font-medium text-destructive">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form class="mt-7 space-y-4" action="{{ route('password.store') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="email" class="mb-1.5 block text-xs font-medium text-muted-foreground">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $email) }}" required autocomplete="email" class="w-full rounded-xl bg-input px-3.5 py-3 text-sm neu-inset focus:outline-none">
            </div>

            <div>
                <label for="password" class="mb-1.5 block text-xs font-medium text-muted-foreground">Kata sandi baru</label>
                <input id="password" name="password" type="password" required minlength="8" autocomplete="new-password" placeholder="Min. 8 karakter" class="w-full rounded-xl bg-input px-3.5 py-3 text-sm neu-inset focus:outline-none">
            </div>

            <div>
                <label for="password_confirmation" class="mb-1.5 block text-xs font-medium text-muted-foreground">Ulangi kata sandi baru</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8" autocomplete="new-password" placeholder="Ulangi kata sandi" class="w-full rounded-xl bg-input px-3.5 py-3 text-sm neu-inset focus:outline-none">
            </div>

            <button type="submit" class="btn-lime w-full py-3">
                Simpan kata sandi
                <x-icon name="arrow-right" :size="15" />
            </button>
        </form>

        <p class="mt-5 text-center text-xs text-muted-foreground">
            Ingat sandimu?
            <a href="{{ route('masuk') }}" class="font-semibold text-foreground hover:underline">Kembali masuk</a>
        </p>
    </div>
@endsection
