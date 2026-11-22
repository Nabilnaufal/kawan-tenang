@extends('layouts.app')
@section('title', 'Verifikasi Akun')
@section('role', 'admin')
@section('content')
    <div class="mb-5">
        <h2 class="text-xl font-semibold tracking-tight sm:text-2xl">Status verifikasi akun</h2>
        <p class="mt-1 text-sm text-muted-foreground">Hasil verifikasi liveness dari penyedia. Data wajah tidak disimpan di sistem ini.</p>
    </div>

    <div class="grid grid-cols-2 gap-3 xl:grid-cols-4">
        @foreach ([['Berhasil', '612', 'check-circle', 'bg-success/15 text-success'], ['Menunggu', '9', 'clock', 'bg-accent/35 text-accent-foreground'], ['Gagal', '14', 'x', 'bg-destructive/15 text-destructive'], ['Belum verifikasi', '649', 'user', 'bg-muted text-muted-foreground']] as [$label, $value, $icon, $tone])
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
            <h3 class="text-sm font-semibold">Antrean verifikasi</h3>
            <div class="flex gap-2" x-data="{ tab: 0 }">
                @foreach (['Semua', 'Menunggu', 'Gagal'] as $i => $filter)
                    <button type="button" @click="tab = {{ $i }}" :class="tab === {{ $i }} ? 'bg-primary text-primary-foreground' : 'bg-muted text-muted-foreground'" class="rounded-full px-3 py-1.5 text-xs font-semibold">{{ $filter }}</button>
                @endforeach
            </div>
        </div>

        <div class="mt-4 overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="text-xs tracking-wide text-muted-foreground uppercase">
                    <tr class="border-b border-border/60">
                        <th class="hidden py-2.5 pr-3 font-medium sm:table-cell">ID sesi</th>
                        <th class="py-2.5 pr-3 font-medium">Akun</th>
                        <th class="hidden py-2.5 pr-3 font-medium sm:table-cell">Penyedia</th>
                        <th class="hidden py-2.5 pr-3 font-medium sm:table-cell">Metode</th>
                        <th class="hidden py-2.5 pr-3 font-medium sm:table-cell">Waktu</th>
                        <th class="py-2.5 pr-3 font-medium">Status</th>
                        <th class="py-2.5 font-medium"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border/60">
                    @foreach ([
                        ['#DVT-88214', 'Kawan Anonim', 'Didit', 'Passive liveness', '15 Nov, 09.12', 'Menunggu', 'warning'],
                        ['#DVT-88210', 'Bagas Nugroho', 'Didit', 'Passive liveness', '15 Nov, 08.40', 'Berhasil', 'success'],
                        ['#DVT-88201', 'Ayu Lestari', 'Didit', 'Face match 1:1', '14 Nov, 21.05', 'Gagal', 'danger'],
                        ['#DVT-88190', 'Sari Wulandari', 'Didit', 'Passive liveness', '14 Nov, 17.22', 'Berhasil', 'success'],
                        ['#DVT-88182', 'Fajar Hidayat', 'Didit', 'Passive liveness', '14 Nov, 11.03', 'Perlu ditinjau', 'info'],
                    ] as [$session, $account, $provider, $method, $time, $status, $tone])
                        <tr class="align-middle">
                            <td class="hidden py-3 pr-3 font-mono text-xs font-medium sm:table-cell">{{ $session }}</td>
                            <td class="py-3 pr-3 font-medium">{{ $account }}</td>
                            <td class="hidden py-3 pr-3 text-muted-foreground sm:table-cell">{{ $provider }}</td>
                            <td class="hidden py-3 pr-3 text-muted-foreground sm:table-cell">{{ $method }}</td>
                            <td class="hidden py-3 pr-3 text-muted-foreground sm:table-cell">{{ $time }}</td>
                            <td class="py-3 pr-3"><x-status :tone="$tone">{{ $status }}</x-status></td>
                            <td class="py-3 text-right">
                                <button type="button" class="rounded-lg px-2.5 py-1.5 text-xs font-semibold text-foreground hover:bg-muted">Tinjau</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <p class="mt-3 flex items-start gap-2 px-1 text-xs leading-relaxed text-muted-foreground">
        <x-icon name="lock" :size="13" class="mt-0.5 shrink-0" />
        Status hanya diperbarui dari webhook penyedia yang tervalidasi. Timeout atau kegagalan vendor tidak dianggap berhasil.
    </p>
@endsection
