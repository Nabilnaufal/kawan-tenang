<x-layouts.app title="Profil" :role="$role ?? 'pengguna'">
    <div class="mb-5">
        <h2 class="text-xl font-semibold tracking-tight sm:text-2xl">Profil & Privasi</h2>
        <p class="mt-1 text-sm text-muted-foreground">Kelola akun, identitas publik, dan izin data.</p>
    </div>

    <div class="grid gap-4 lg:grid-cols-[1fr_1.5fr]">
        {{-- Identity --}}
        <section class="self-start rounded-2xl bg-card p-5 neu-sm">
            <div class="flex items-center gap-4">
                <span class="flex size-16 items-center justify-center rounded-full bg-secondary/50 text-secondary-foreground">
                    <x-icon name="user" :size="28" />
                </span>
                <div class="min-w-0">
                    <h3 class="truncate text-base font-semibold">Kawan Anonim</h3>
                    <p class="text-xs text-muted-foreground">Bergabung sejak November 2026</p>
                </div>
            </div>

            <div class="mt-4 flex flex-wrap gap-2">
                <x-status tone="success">
                    <x-icon name="check" :size="12" />
                    Email terverifikasi
                </x-status>
                <x-status tone="warning">Wajah belum diverifikasi</x-status>
            </div>

            <dl class="mt-5 space-y-3 border-t border-border/60 pt-4 text-xs">
                <div class="flex items-center justify-between gap-3">
                    <dt class="text-muted-foreground">Email</dt>
                    <dd class="truncate font-medium">kawan•••@mail.com</dd>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <dt class="text-muted-foreground">Peran</dt>
                    <dd class="font-medium">Pengguna</dd>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <dt class="text-muted-foreground">Nama publik</dt>
                    <dd class="flex items-center gap-1.5 font-medium">
                        <x-icon name="lock" :size="12" />
                        Selalu Anonim
                    </dd>
                </div>
            </dl>

            @if (($role ?? 'pengguna') === 'pengguna')
                <a href="{{ route('verifikasi') }}" class="mt-5 flex items-center justify-center gap-2 rounded-xl bg-background px-4 py-2.5 text-xs font-semibold neu-sm transition hover:bg-muted">
                    <x-icon name="face" :size="14" />
                    Verifikasi wajah
                </a>
            @endif
        </section>

        <div class="space-y-4">
            {{-- Account form --}}
            <section class="rounded-2xl bg-card p-5 neu-sm">
                <h3 class="text-sm font-semibold">Informasi akun</h3>
                <form class="mt-4 grid gap-3 sm:grid-cols-2" @submit.prevent>
                    <div>
                        <label for="nama" class="mb-1.5 block text-xs font-medium text-muted-foreground">Nama</label>
                        <input id="nama" type="text" value="Kawan Anonim" class="w-full rounded-xl bg-input px-3.5 py-2.5 text-sm neu-inset focus:outline-none">
                    </div>
                    <div>
                        <label for="email" class="mb-1.5 block text-xs font-medium text-muted-foreground">Email</label>
                        <input id="email" type="email" value="kawan@mail.com" class="w-full rounded-xl bg-input px-3.5 py-2.5 text-sm neu-inset focus:outline-none">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="sandi" class="mb-1.5 block text-xs font-medium text-muted-foreground">Kata sandi baru</label>
                        <input id="sandi" type="password" placeholder="••••••••" class="w-full rounded-xl bg-input px-3.5 py-2.5 text-sm neu-inset focus:outline-none">
                    </div>
                    <div class="flex items-center justify-end gap-2 sm:col-span-2">
                        <button type="button" class="rounded-xl px-4 py-2 text-xs font-medium text-muted-foreground hover:bg-muted">Batal</button>
                        <button type="submit" class="rounded-xl bg-primary px-4 py-2 text-xs font-semibold text-primary-foreground">Simpan perubahan</button>
                    </div>
                </form>
            </section>

            {{-- Privacy --}}
            <section class="rounded-2xl bg-card p-5 neu-sm" x-data="{ anon: true, notify: true }">
                <h3 class="text-sm font-semibold">Privasi & notifikasi</h3>
                <div class="mt-3 divide-y divide-border/60">
                    <label class="flex cursor-pointer items-center gap-3 py-3">
                        <span class="min-w-0 flex-1">
                            <span class="block text-xs font-medium">Selalu tampil anonim</span>
                            <span class="block text-xs text-muted-foreground">Nama asli tidak pernah ditampilkan di ruang publik.</span>
                        </span>
                        <input type="checkbox" x-model="anon" checked class="size-4 shrink-0 rounded border-border text-primary focus:ring-ring">
                    </label>
                    <label class="flex cursor-pointer items-center gap-3 py-3">
                        <span class="min-w-0 flex-1">
                            <span class="block text-xs font-medium">Notifikasi sesi konseling</span>
                            <span class="block text-xs text-muted-foreground">Beri tahu saat konselor membalas pesan.</span>
                        </span>
                        <input type="checkbox" x-model="notify" checked class="size-4 shrink-0 rounded border-border text-primary focus:ring-ring">
                    </label>
                </div>

                <div class="mt-3 flex flex-col gap-2 sm:flex-row">
                    <button type="button" class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-background px-4 py-2.5 text-xs font-semibold neu-sm hover:bg-muted">
                        <x-icon name="download" :size="14" />
                        Unduh data saya
                    </button>
                    <button type="button" class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-destructive/10 px-4 py-2.5 text-xs font-semibold text-destructive hover:bg-destructive/15">
                        <x-icon name="x" :size="14" />
                        Hapus data verifikasi
                    </button>
                </div>
            </section>

            {{-- Sessions --}}
            <section class="rounded-2xl bg-card p-5 neu-sm">
                <h3 class="text-sm font-semibold">Perangkat yang masuk</h3>
                <ul class="mt-3 space-y-2.5">
                    @foreach ([['Chrome · Linux', 'Aktif sekarang · Jakarta', true], ['Safari · iPhone', '2 hari lalu · Bandung', false]] as [$device, $meta, $current])
                        <li class="flex items-center gap-3 rounded-xl bg-muted/50 px-3 py-2.5">
                            <span class="flex size-8 items-center justify-center rounded-lg bg-card text-muted-foreground">
                                <x-icon name="lock-open" :size="15" />
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="block truncate text-xs font-medium">{{ $device }}</span>
                                <span class="block text-xs text-muted-foreground">{{ $meta }}</span>
                            </span>
                            @if ($current)
                                <x-status tone="success">Ini</x-status>
                            @else
                                <button type="button" class="text-xs font-semibold text-destructive">Keluar</button>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </section>
        </div>
    </div>
</x-layouts.app>
