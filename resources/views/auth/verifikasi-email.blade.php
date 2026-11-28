@extends('layouts.guest')

@section('title', 'Verifikasi email')

@section('content')
    <div class="text-center">
        <div class="mx-auto flex size-16 items-center justify-center rounded-2xl bg-secondary text-secondary-foreground neu-sm">
            <x-icon name="mail" :size="28" />
        </div>

        <h2 class="mt-6 text-2xl font-semibold tracking-tight">Cek email kamu</h2>
        <p class="mt-2 text-sm leading-relaxed text-muted-foreground">
            Kami sudah mengirim link verifikasi ke
            <span class="font-semibold text-foreground">{{ auth()->user()->email }}</span>.
            Klik link tersebut untuk membuka ruang aman Kawan Tenang.
        </p>

        @if (session('status'))
            <div class="mt-5 rounded-xl bg-success/10 p-3 text-left text-xs font-medium text-success">
                {{ session('status') }}
            </div>
        @endif

        <form class="mt-6" action="{{ route('verification.send') }}" method="POST">
            @csrf
            <button type="submit" class="btn-lime w-full py-3">
                Kirim ulang link verifikasi
                <x-icon name="arrow-right" :size="15" />
            </button>
        </form>

        <p class="mt-5 text-xs leading-relaxed text-muted-foreground">
            Tidak melihat emailnya? Cek folder spam, atau pastikan alamat email kamu sudah benar.
        </p>

        <form class="mt-5" action="{{ route('keluar') }}" method="POST">
            @csrf
            <button type="submit" class="text-xs font-semibold text-foreground hover:underline">
                Keluar dan gunakan akun lain
            </button>
        </form>
    </div>
@endsection
