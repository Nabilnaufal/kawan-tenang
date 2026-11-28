@extends('layouts.app')
@section('title', 'Manajemen Pengguna')
@section('role', 'admin')
@section('content')
    @php
        $roleTone = ['pengguna' => 'neutral', 'konselor' => 'info', 'admin' => 'warning'];
        $roleLabel = ['pengguna' => 'Pengguna', 'konselor' => 'Konselor', 'admin' => 'Administrator'];
    @endphp

    <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
        <div>
            <h2 class="text-xl font-semibold tracking-tight sm:text-2xl">Manajemen pengguna</h2>
            <p class="mt-1 text-sm text-muted-foreground">Kelola akun pengguna, konselor, dan administrator.</p>
        </div>
        <span class="rounded-full bg-card px-3 py-1.5 text-xs font-semibold text-muted-foreground neu-sm">{{ $users->total() }} akun</span>
    </div>

    @if (session('status'))
        <p class="mb-4 rounded-xl bg-success/10 p-3 text-xs font-medium text-success">{{ session('status') }}</p>
    @endif

    <section class="min-w-0 rounded-[1.4rem] bg-card p-4 neu-sm sm:p-5">
        <div class="flex flex-wrap items-center gap-2">
            <div class="flex flex-wrap gap-2" aria-label="Filter peran">
                @foreach (['semua' => 'Semua', 'pengguna' => 'Pengguna', 'konselor' => 'Konselor', 'admin' => 'Administrator'] as $value => $label)
                    <a
                        href="{{ route('admin.pengguna', array_filter(['role' => $value, 'q' => $search])) }}"
                        @class([
                            'shrink-0 rounded-full px-3.5 py-1.5 text-xs font-semibold transition',
                            'bg-primary text-primary-foreground' => $role === $value,
                            'bg-muted text-muted-foreground hover:text-foreground' => $role !== $value,
                        ])
                        @if ($role === $value) aria-current="page" @endif
                    >{{ $label }}</a>
                @endforeach
            </div>

            <form method="GET" action="{{ route('admin.pengguna') }}" class="ml-auto flex w-full items-center gap-2 rounded-xl bg-input px-3 py-2 text-xs text-muted-foreground neu-inset sm:w-64">
                @if ($role !== 'semua')
                    <input type="hidden" name="role" value="{{ $role }}">
                @endif
                <x-icon name="search" :size="15" />
                <input type="search" name="q" value="{{ $search }}" placeholder="Cari nama, email, atau NIM" aria-label="Cari pengguna" class="min-w-0 flex-1 bg-transparent text-foreground placeholder:text-muted-foreground focus:outline-none">
            </form>
        </div>

        <div class="mt-4 overflow-x-auto">
            <table class="w-full min-w-[760px] text-left text-xs">
                <thead class="text-xs tracking-wide text-muted-foreground uppercase">
                    <tr class="border-b border-border/60">
                        <th class="py-2.5 pr-3 font-medium">Akun</th>
                        <th class="py-2.5 pr-3 font-medium">Peran</th>
                        <th class="py-2.5 pr-3 font-medium">Verifikasi</th>
                        <th class="py-2.5 pr-3 font-medium">Bergabung</th>
                        <th class="py-2.5 font-medium">Status akun</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border/60">
                    @forelse ($users as $user)
                        <tr class="align-middle">
                            <td class="py-3 pr-3">
                                <div class="flex items-center gap-2.5">
                                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-secondary text-secondary-foreground">
                                        <x-icon name="user" :size="14" />
                                    </span>
                                    <div class="min-w-0">
                                        <p class="truncate font-semibold">{{ $user->name }}</p>
                                        <p class="truncate text-xs text-muted-foreground">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 pr-3"><x-status :tone="$roleTone[$user->role] ?? 'neutral'">{{ $roleLabel[$user->role] ?? $user->role }}</x-status></td>
                            <td class="py-3 pr-3">
                                @if ($user->face_registered_at)
                                    <x-status tone="success">Terdaftar</x-status>
                                @else
                                    <x-status tone="danger">Belum</x-status>
                                @endif
                            </td>
                            <td class="py-3 pr-3 text-muted-foreground">{{ $user->created_at->translatedFormat('d M Y') }}</td>
                            <td class="py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if ($user->is_active)
                                        <x-status tone="success">Aktif</x-status>
                                    @else
                                        <x-status tone="danger">Nonaktif</x-status>
                                    @endif

                                    @if ($user->is(auth()->user()))
                                        <span class="text-xs text-muted-foreground">Akun sendiri</span>
                                    @else
                                        <form method="POST" action="{{ route('admin.pengguna.status', $user) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="rounded-lg px-2.5 py-1.5 text-xs font-semibold {{ $user->is_active ? 'text-destructive hover:bg-destructive/10' : 'text-success hover:bg-success/10' }}">
                                                {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-muted-foreground">Tidak ada akun yang cocok.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex flex-wrap items-center justify-between gap-3 border-t border-border/60 pt-3 text-xs text-muted-foreground">
            <p>Menampilkan {{ $users->count() }} dari {{ $users->total() }} akun</p>
            <div>{{ $users->links() }}</div>
        </div>
    </section>
@endsection
