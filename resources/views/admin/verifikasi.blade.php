@extends('layouts.app')
@section('title', 'Verifikasi Akun')
@section('role', 'admin')
@section('content')
    <div class="mb-5">
        <h2 class="text-xl font-semibold tracking-tight sm:text-2xl">Status verifikasi akun</h2>
        <p class="mt-1 text-sm text-muted-foreground">Descriptor wajah disimpan terenkripsi. Foto asli tidak disimpan.</p>
    </div>

    @if (session('status'))
        <p class="mb-4 rounded-xl bg-success/10 p-3 text-xs font-medium text-success">{{ session('status') }}</p>
    @endif

    <div class="grid grid-cols-2 gap-3 xl:grid-cols-4">
        @foreach ([
            ['Terdaftar', $counts['terdaftar'], 'check-circle', 'bg-success/15 text-success'],
            ['Belum terdaftar', $counts['belum'], 'user', 'bg-muted text-muted-foreground'],
        ] as [$label, $value, $icon, $tone])
            <div class="rounded-[1.4rem] bg-card p-4 neu-sm">
                <span class="flex size-8 items-center justify-center rounded-lg {{ $tone }}">
                    <x-icon :name="$icon" :size="16" />
                </span>
                <p class="mt-3 text-xl font-semibold tracking-tight tabular-nums" data-count="{{ $value }}">0</p>
                <p class="mt-0.5 text-xs text-muted-foreground">{{ $label }}</p>
            </div>
        @endforeach
    </div>

    <section class="mt-4 min-w-0 rounded-[1.4rem] bg-card p-4 neu-sm sm:p-5">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h3 class="text-sm font-semibold">Data verifikasi wajah</h3>
            <span class="text-xs text-muted-foreground">{{ $users->total() }} akun</span>
        </div>

        <div class="mt-4 overflow-x-auto">
            <table class="w-full min-w-[620px] text-left text-xs">
                <thead class="text-xs tracking-wide text-muted-foreground uppercase">
                    <tr class="border-b border-border/60">
                        <th class="py-2.5 pr-3 font-medium">Akun</th>
                        <th class="hidden py-2.5 pr-3 font-medium sm:table-cell">NIM</th>
                        <th class="py-2.5 pr-3 font-medium">Status</th>
                        <th class="hidden py-2.5 pr-3 font-medium sm:table-cell">Waktu daftar wajah</th>
                        <th class="py-2.5 font-medium"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border/60">
                    @forelse ($users as $user)
                        <tr class="align-middle">
                            <td class="py-3 pr-3">
                                <p class="font-medium">{{ $user->name }}</p>
                                <p class="text-xs text-muted-foreground">{{ $user->email }}</p>
                            </td>
                            <td class="hidden py-3 pr-3 text-muted-foreground sm:table-cell">{{ $user->nim ?: '—' }}</td>
                            <td class="py-3 pr-3">
                                @if ($user->face_registered_at)
                                    <x-status tone="success">Terdaftar</x-status>
                                @else
                                    <x-status tone="warning">Belum</x-status>
                                @endif
                            </td>
                            <td class="hidden py-3 pr-3 text-muted-foreground sm:table-cell">{{ $user->face_registered_at?->translatedFormat('d M Y, H.i') ?? '—' }}</td>
                            <td class="py-3 text-right">
                                @if ($user->face_registered_at && ! $user->is(auth()->user()))
                                    <form method="POST" action="{{ route('admin.verifikasi.reset', $user) }}" onsubmit="return confirm('Hapus data wajah pengguna ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg px-2.5 py-1.5 text-xs font-semibold text-destructive hover:bg-destructive/10">Hapus wajah</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-muted-foreground">Belum ada akun.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 border-t border-border/60 pt-3 text-xs text-muted-foreground">{{ $users->links() }}</div>
    </section>

    <p class="mt-3 flex items-start gap-2 px-1 text-xs leading-relaxed text-muted-foreground">
        <x-icon name="lock" :size="13" class="mt-0.5 shrink-0" />
        Penghapusan wajah membuat pengguna tidak bisa login dengan wajah sampai memindai ulang.
    </p>
@endsection
