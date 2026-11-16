<x-layouts.app title="Manajemen Pengguna" role="admin">
@php
    $accounts = [
        ['Kawan Anonim', 'kawan@mail.com', 'Pengguna', 'neutral', 'Berhasil', 'success', '12 Nov 2026', 'Aktif', 'success'],
        ['Rani Puspita', 'rani@kawan.id', 'Konselor', 'info', 'Berhasil', 'success', '4 Okt 2026', 'Aktif', 'success'],
        ['Dimas Ardi', 'dimas@kawan.id', 'Konselor', 'info', 'Berhasil', 'success', '4 Okt 2026', 'Aktif', 'success'],
        ['Sari Wulandari', 'sari@kawan.id', 'Konselor', 'info', 'Menunggu', 'warning', '9 Nov 2026', 'Aktif', 'success'],
        ['Bagas Nugroho', 'bagas@mail.com', 'Pengguna', 'neutral', 'Belum', 'danger', '10 Nov 2026', 'Aktif', 'success'],
        ['Ayu Lestari', 'ayu@mail.com', 'Pengguna', 'neutral', 'Berhasil', 'success', '2 Nov 2026', 'Nonaktif', 'neutral'],
        ['Fajar Hidayat', 'fajar@kawan.id', 'Administrator', 'warning', 'Berhasil', 'success', '1 Sep 2026', 'Aktif', 'success'],
    ];
@endphp

    <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
        <div>
            <h2 class="text-xl font-semibold tracking-tight sm:text-2xl">Manajemen pengguna</h2>
            <p class="mt-1 text-sm text-muted-foreground">Kelola akun pengguna, konselor, dan administrator.</p>
        </div>
        <button type="button" class="btn-lime text-xs">
            <x-icon name="plus" :size="14" />
            Tambah akun
        </button>
    </div>

    <section class="min-w-0 rounded-[1.4rem] bg-card p-4 neu-sm sm:p-5">
        <div class="flex flex-wrap items-center gap-2">
            <div class="flex flex-wrap gap-2" role="tablist" aria-label="Filter peran" x-data="{ tab: 0 }">
                @foreach (['Semua', 'Pengguna', 'Konselor', 'Administrator'] as $i => $filter)
                    <button
                        type="button"
                        role="tab"
                        @click="tab = {{ $i }}"
                        :aria-selected="tab === {{ $i }}"
                        :class="tab === {{ $i }} ? 'bg-primary text-primary-foreground' : 'bg-muted text-muted-foreground hover:text-foreground'"
                        class="shrink-0 rounded-full px-3.5 py-1.5 text-xs font-semibold transition"
                    >{{ $filter }}</button>
                @endforeach
            </div>

            <label class="ml-auto flex w-full items-center gap-2 rounded-xl bg-input px-3 py-2 text-xs text-muted-foreground neu-inset sm:w-64">
                <x-icon name="search" :size="15" />
                <input type="search" placeholder="Cari nama atau email" aria-label="Cari pengguna" class="min-w-0 flex-1 bg-transparent text-foreground placeholder:text-muted-foreground focus:outline-none">
            </label>
        </div>

        {{-- Mobile list --}}
        <ul class="mt-4 divide-y divide-border/60 md:hidden">
            @foreach ($accounts as [$name, $email, $role, $roleTone, $verif, $verifTone, $joined, $status, $statusTone])
                <li class="py-3">
                    <div class="flex items-center gap-2.5">
                        <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-secondary text-secondary-foreground">
                            <x-icon name="user" :size="15" />
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold">{{ $name }}</p>
                            <p class="truncate text-xs text-muted-foreground">{{ $email }}</p>
                        </div>
                        <x-status tone="{{ $statusTone }}">{{ $status }}</x-status>
                    </div>
                    <div class="mt-2 flex flex-wrap items-center gap-2 pl-11">
                        <x-status :tone="$roleTone">{{ $role }}</x-status>
                        <x-status :tone="$verifTone">Verifikasi: {{ $verif }}</x-status>
                        <button type="button" class="ml-auto rounded-lg bg-muted px-2.5 py-1.5 text-xs font-semibold text-foreground hover:bg-muted/70">Kelola</button>
                    </div>
                </li>
            @endforeach
        </ul>

        {{-- Desktop table --}}
        <div class="mt-4 hidden overflow-x-auto md:block">
            <table class="w-full min-w-[720px] text-left text-xs">
                <thead class="text-xs tracking-wide text-muted-foreground uppercase">
                    <tr class="border-b border-border/60">
                        <th class="py-2.5 pr-3 font-medium">Akun</th>
                        <th class="py-2.5 pr-3 font-medium">Peran</th>
                        <th class="py-2.5 pr-3 font-medium">Verifikasi</th>
                        <th class="py-2.5 pr-3 font-medium">Bergabung</th>
                        <th class="py-2.5 pr-3 font-medium">Status</th>
                        <th class="py-2.5 font-medium"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border/60">
                    @foreach ($accounts as [$name, $email, $role, $roleTone, $verif, $verifTone, $joined, $status, $statusTone])
                        <tr class="align-middle">
                            <td class="py-3 pr-3">
                                <div class="flex items-center gap-2.5">
                                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-secondary text-secondary-foreground">
                                        <x-icon name="user" :size="14" />
                                    </span>
                                    <div class="min-w-0">
                                        <p class="truncate font-semibold">{{ $name }}</p>
                                        <p class="truncate text-xs text-muted-foreground">{{ $email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 pr-3"><x-status :tone="$roleTone">{{ $role }}</x-status></td>
                            <td class="py-3 pr-3"><x-status :tone="$verifTone">{{ $verif }}</x-status></td>
                            <td class="py-3 pr-3 text-muted-foreground">{{ $joined }}</td>
                            <td class="py-3 pr-3"><x-status :tone="$statusTone">{{ $status }}</x-status></td>
                            <td class="py-3 text-right">
                                <button type="button" class="rounded-lg px-2.5 py-1.5 text-xs font-semibold text-foreground hover:bg-muted">Kelola</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex flex-wrap items-center justify-between gap-3 border-t border-border/60 pt-3 text-xs text-muted-foreground">
            <p>Menampilkan 7 dari 1.284 akun</p>
            <div class="flex items-center gap-1">
                <button type="button" class="rounded-lg px-2.5 py-1.5 font-medium hover:bg-muted" disabled>Sebelumnya</button>
                <button type="button" class="rounded-lg bg-primary px-2.5 py-1.5 font-semibold text-primary-foreground">1</button>
                <button type="button" class="rounded-lg px-2.5 py-1.5 font-medium hover:bg-muted">2</button>
                <button type="button" class="rounded-lg px-2.5 py-1.5 font-medium hover:bg-muted">3</button>
                <button type="button" class="rounded-lg px-2.5 py-1.5 font-medium hover:bg-muted">Berikutnya</button>
            </div>
        </div>
    </section>
</x-layouts.app>
