@extends('layouts.app')
@section('title', 'Sesi Konseling')
@section('role', $role)
@section('content')
    <div class="mb-4 flex items-center gap-3">
        <a href="{{ $back }}" class="flex size-9 items-center justify-center rounded-xl bg-card text-muted-foreground neu-sm transition hover:text-foreground" aria-label="Kembali ke daftar sesi">
            <x-icon name="chevron-right" :size="18" class="rotate-180" />
        </a>
        <div>
            <h2 class="text-lg font-semibold tracking-tight">
                @if ($role === 'konselor')
                    Sesi dengan Anonim #{{ $session->user_id }}
                @else
                    Sesi {{ $session->counselor?->name ?? 'menunggu konselor' }}
                @endif
            </h2>
            <p class="text-xs text-muted-foreground">{{ $session->topic }} · ID sesi #KT-{{ $session->id }}</p>
        </div>
        <x-status :tone="$session->status === 'aktif' ? 'success' : 'warning'" class="ml-auto">{{ ucfirst($session->status) }}</x-status>
    </div>

    <div>
        <x-chat-panel
            :session="$session"
            :name="$role === 'konselor' ? 'Anonim #'.$session->user_id : ($session->counselor?->name ?? 'Menunggu konselor')"
            :role="$role === 'konselor' ? 'Pengguna' : 'Konselor'"
            :state="ucfirst($session->status)"
            :anonymous="$role === 'konselor'"
        />
    </div>

    @if ($role === 'konselor')
        <section class="mt-3 rounded-[1.4rem] bg-card p-4 neu-sm">
            <h3 class="text-sm font-semibold">Catatan sesi</h3>
            <p class="mt-0.5 text-xs text-muted-foreground">Hanya terlihat oleh konselor yang ditugaskan.</p>

            @if (session('status'))
                <p class="mt-3 rounded-xl bg-success/10 p-3 text-xs font-medium text-success">{{ session('status') }}</p>
            @endif

            <form method="POST" action="{{ route('konselor.sesi.catatan', $session) }}" class="mt-3 space-y-3">
                @csrf
                <textarea name="counselor_note" rows="4" maxlength="2000" placeholder="Ringkasan sesi, rencana tindak lanjut…" class="w-full resize-none rounded-xl bg-input px-3 py-2.5 text-sm placeholder:text-muted-foreground neu-inset focus:outline-none">{{ old('counselor_note', $session->counselor_note) }}</textarea>
                <div class="flex items-center justify-between gap-3">
                    <p class="flex items-center gap-1.5 text-[0.7rem] text-muted-foreground">
                        <x-icon name="lock" :size="12" />
                        Catatan terenkripsi
                    </p>
                    <button type="submit" class="btn-lime text-xs">
                        Simpan catatan
                        <x-icon name="arrow-right" :size="14" />
                    </button>
                </div>
            </form>
        </section>
    @endif

    <p class="mt-3 flex items-start gap-2 px-1 text-xs leading-relaxed text-muted-foreground">
        <x-icon name="alert" :size="13" class="mt-0.5 shrink-0" />
        Jika kamu dalam bahaya atau berpikir untuk menyakiti diri, segera hubungi 119 ext. 8 atau 112. Konselor tidak selalu online 24 jam.
    </p>
@endsection
