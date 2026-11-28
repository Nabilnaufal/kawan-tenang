@php
    $awal = $errors->has('setuju') || $errors->has('wajah') ? 3 : ($errors->hasAny(['email', 'password']) ? 2 : 1);
@endphp

@extends('layouts.guest')
@section('title', 'Daftar')
@section('content')
    <div x-data="daftarWizard({{ $awal }})" @wajah-reset.window="wajahSiap = false">
        <h2 class="text-2xl font-semibold tracking-tight">Buat akun Kawan Tenang</h2>
        <p class="mt-1.5 text-sm text-muted-foreground">Tiga langkah singkat, kurang dari satu menit.</p>

        {{-- Langkah --}}
        <ol class="relative mt-6 flex items-start" aria-label="Langkah pendaftaran">
            <span class="pointer-events-none absolute top-3 left-[16%] right-[16%] h-px bg-border" aria-hidden="true"></span>
            <span class="pointer-events-none absolute top-3 left-[16%] h-px bg-primary transition-all duration-300" :style="`width: ${Math.max(0, Math.min(1, (step - 1) / 2)) * 68}%`" aria-hidden="true"></span>
            @foreach ([['Data diri', 'Nama, NIM, prodi'], ['Akun', 'Email & sandi'], ['Wajah', 'Pindai wajah']] as $i => [$judul, $ket])
                <li class="relative z-10 flex min-w-0 flex-1 flex-col items-center text-center" :class="step < {{ $i + 1 }} ? 'opacity-45' : ''">
                    <span class="flex size-6 items-center justify-center rounded-full border border-border bg-background text-[0.7rem] font-bold" :class="step > {{ $i + 1 }} ? 'border-success bg-success text-white' : (step === {{ $i + 1 }} ? 'border-primary bg-primary text-primary-foreground' : 'text-muted-foreground')">
                        <span x-show="step <= {{ $i + 1 }}">{{ $i + 1 }}</span>
                        <span x-show="step > {{ $i + 1 }}" x-cloak>✓</span>
                    </span>
                    <span class="mt-2 block text-[0.68rem] font-semibold">{{ $judul }}</span>
                    <span class="mt-0.5 block text-[0.58rem] leading-tight text-muted-foreground">{{ $ket }}</span>
                </li>
            @endforeach
        </ol>

        @if ($errors->any())
            <div class="mt-4 rounded-xl bg-destructive/10 p-3 text-xs font-medium text-destructive">
                @foreach ($errors->all() as $error)
                    <p class="flex items-start gap-1.5">
                        <x-icon name="alert" :size="13" class="mt-0.5 shrink-0" />
                        {{ $error }}
                    </p>
                @endforeach
            </div>
        @endif

        <p x-show="pesan" x-cloak class="mt-4 rounded-xl bg-destructive/10 p-3 text-xs font-medium text-destructive" x-text="pesan"></p>

        <form class="mt-5 space-y-4" action="{{ route('daftar.store') }}" method="POST" @submit="kirim($event)">
            @csrf

            {{-- Langkah 1: data diri --}}
            <div data-langkah="1" x-show="step === 1" class="space-y-4">
                <fieldset>
                    <legend class="mb-2 text-xs font-medium text-muted-foreground">Daftar sebagai</legend>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach ([['pengguna', 'Pengguna', 'Mencari dukungan'], ['konselor', 'Konselor', 'Memberi dukungan']] as [$value, $label, $hint])
                            <label class="flex cursor-pointer items-start gap-2.5 rounded-xl bg-input p-3 text-xs neu-inset has-[:checked]:ring-2 has-[:checked]:ring-primary">
                                <input type="radio" name="role" value="{{ $value }}" @checked(old('role', 'pengguna') === $value) class="mt-0.5 size-4 border-border text-primary">
                                <span>
                                    <span class="block font-semibold">{{ $label }}</span>
                                    <span class="block text-xs text-muted-foreground">{{ $hint }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>

                <div>
                    <label for="nama" class="mb-1.5 block text-xs font-medium text-muted-foreground">Nama lengkap</label>
                    <div class="flex items-center gap-2 rounded-xl bg-input px-3.5 py-3 neu-inset">
                        <x-icon name="user" :size="16" class="text-muted-foreground" />
                        <input id="nama" name="nama" type="text" value="{{ old('nama') }}" required autocomplete="name" placeholder="Nama sesuai data kampus" class="min-w-0 flex-1 bg-transparent text-sm placeholder:text-muted-foreground focus:outline-none">
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="nim" class="mb-1.5 block text-xs font-medium text-muted-foreground">NIM</label>
                        <div class="flex items-center gap-2 rounded-xl bg-input px-3.5 py-3 neu-inset">
                            <x-icon name="clipboard" :size="16" class="text-muted-foreground" />
                            <input id="nim" name="nim" type="text" value="{{ old('nim') }}" required inputmode="numeric" placeholder="2024xxxx" class="min-w-0 flex-1 bg-transparent text-sm placeholder:text-muted-foreground focus:outline-none">
                        </div>
                    </div>
                    <div>
                        <label for="prodi" class="mb-1.5 block text-xs font-medium text-muted-foreground">Program studi</label>
                        <div class="flex items-center gap-2 rounded-xl bg-input px-3.5 py-3 neu-inset">
                            <x-icon name="file" :size="16" class="text-muted-foreground" />
                            <input id="prodi" name="prodi" type="text" value="{{ old('prodi') }}" required placeholder="Bimbingan Konseling" class="min-w-0 flex-1 bg-transparent text-sm placeholder:text-muted-foreground focus:outline-none">
                        </div>
                    </div>
                </div>

                <button type="button" class="btn-lime w-full py-3" @click="lanjut()">
                    Lanjut
                    <x-icon name="arrow-right" :size="15" />
                </button>
            </div>

            {{-- Langkah 2: akun --}}
            <div data-langkah="2" x-show="step === 2" x-cloak class="space-y-4">
                <div>
                    <label for="email" class="mb-1.5 block text-xs font-medium text-muted-foreground">Email</label>
                    <div class="flex items-center gap-2 rounded-xl bg-input px-3.5 py-3 neu-inset">
                        <x-icon name="mail" :size="16" class="text-muted-foreground" />
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" placeholder="nama@email.com" class="min-w-0 flex-1 bg-transparent text-sm placeholder:text-muted-foreground focus:outline-none">
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="password" class="mb-1.5 block text-xs font-medium text-muted-foreground">Kata sandi</label>
                        <div class="flex items-center gap-2 rounded-xl bg-input px-3.5 py-3 neu-inset">
                            <x-icon name="lock" :size="16" class="text-muted-foreground" />
                            <input id="password" name="password" type="password" x-model="password" required minlength="8" autocomplete="new-password" placeholder="Min. 8 karakter" class="min-w-0 flex-1 bg-transparent text-sm placeholder:text-muted-foreground focus:outline-none">
                        </div>
                    </div>
                    <div>
                        <label for="password_confirmation" class="mb-1.5 block text-xs font-medium text-muted-foreground">Ulangi sandi</label>
                        <div class="flex items-center gap-2 rounded-xl bg-input px-3.5 py-3 neu-inset">
                            <x-icon name="lock" :size="16" class="text-muted-foreground" />
                            <input id="password_confirmation" name="password_confirmation" type="password" x-model="konfirmasi" required autocomplete="new-password" placeholder="••••••••" class="min-w-0 flex-1 bg-transparent text-sm placeholder:text-muted-foreground focus:outline-none">
                        </div>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button type="button" class="rounded-full bg-background px-5 py-3 text-sm font-semibold neu-sm" @click="kembali()">Kembali</button>
                    <button type="button" class="btn-lime flex-1 py-3" @click="lanjut()">
                        Lanjut
                        <x-icon name="arrow-right" :size="15" />
                    </button>
                </div>
            </div>

            {{-- Langkah 3: wajah --}}
            <div data-langkah="3" x-show="step === 3" x-cloak class="space-y-4">
                <x-pemindai-wajah @wajah-siap.window="wajahSiap = true" />

                <label class="flex items-start gap-2.5 text-xs leading-relaxed text-muted-foreground">
                    <input type="checkbox" name="setuju" value="1" required class="mt-0.5 size-4 rounded border-border text-primary">
                    <span>
                        Saya menyetujui <a href="#" class="font-semibold text-foreground hover:underline">Kebijakan Privasi</a> dan
                        <a href="#" class="font-semibold text-foreground hover:underline">Aturan Komunitas</a>.
                    </span>
                </label>

                <div class="flex gap-2">
                    <button type="button" class="rounded-full bg-background px-5 py-3 text-sm font-semibold neu-sm" @click="kembali()">Kembali</button>
                    <button type="submit" class="btn-lime flex-1 py-3 disabled:opacity-40" :disabled="! wajahSiap">
                        Buat akun
                        <x-icon name="check" :size="15" />
                    </button>
                </div>
            </div>
        </form>

        <p class="mt-5 text-center text-xs text-muted-foreground">
            Sudah punya akun?
            <a href="{{ route('masuk') }}" class="font-semibold text-foreground hover:underline">Masuk</a>
        </p>
    </div>
@endsection
