<x-layouts.app title="Dasbor">
    <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
        <div>
            <div class="flex flex-wrap items-center gap-2">
                <h2 class="text-xl font-semibold tracking-tight sm:text-2xl">Halo, Kawan</h2>
                <span class="rounded-full bg-card px-3 py-1 text-[0.72rem] font-semibold text-muted-foreground neu-sm">Pengguna</span>
            </div>
            <p class="mt-1 text-sm text-muted-foreground">Ringkasan aktivitas, status akun, dan sesi konselingmu.</p>
        </div>
        <a href="{{ route('bubble') }}" class="btn-lime">
            <x-icon name="plus" :size="15" />
            Tulis cerita
        </a>
    </div>

    <div class="grid gap-3 xl:grid-cols-[1.32fr_1fr_1fr]">
        <section class="card-ink flex flex-col rounded-[1.5rem] p-5">
            <div class="flex items-start justify-between gap-3">
                <p class="label-cap text-primary-foreground/60">Ringkasan aktivitas</p>
                <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-primary-foreground/10">
                    <x-icon name="chart" :size="16" />
                </span>
            </div>

            <p class="mt-3 text-3xl leading-none font-semibold tracking-tight tabular-nums">
                <span data-count="189">0</span>
            </p>
            <p class="mt-1.5 text-xs text-primary-foreground/60">interaksi dalam 30 hari terakhir</p>

            <div class="mt-3 flex flex-wrap gap-2">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-secondary px-3 py-1 text-[0.72rem] font-bold text-secondary-foreground">
                    <x-icon name="arrow-right" :size="12" class="-rotate-45" />
                    +12% bulan ini
                </span>
                <span class="inline-flex items-center gap-1.5 rounded-full border border-primary-foreground/20 px-3 py-1 text-[0.72rem] font-medium">
                    3 sesi aktif
                </span>
            </div>

            <dl class="mt-auto grid grid-cols-3 gap-2 pt-5">
                @foreach ([
                    ['label' => 'Cerita', 'value' => '12'],
                    ['label' => 'Komentar', 'value' => '48'],
                    ['label' => 'Dukungan', 'value' => '126'],
                ] as $stat)
                    <div class="rounded-2xl bg-primary-foreground/10 p-3">
                        <dt class="text-[0.62rem] tracking-[0.1em] uppercase text-primary-foreground/60">{{ $stat['label'] }}</dt>
                        <dd class="mt-1.5 text-lg font-semibold tabular-nums"><span data-count="{{ $stat['value'] }}">0</span></dd>
                    </div>
                @endforeach
            </dl>
        </section>

        <section class="flex flex-col rounded-[1.4rem] bg-card p-4 neu-sm sm:p-5">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h3 class="text-sm font-semibold">Status akun</h3>
                    <p class="mt-0.5 text-xs text-muted-foreground">Identitas publikmu tetap anonim.</p>
                </div>
                <span class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-muted text-muted-foreground">
                    <x-icon name="shield" :size="15" />
                </span>
            </div>

            <dl class="mt-4 space-y-2.5 text-xs">
                <div class="flex items-center justify-between gap-3">
                    <dt class="text-muted-foreground">Email</dt>
                    <dd class="truncate font-medium">kawan•••@mail.com</dd>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <dt class="text-muted-foreground">Nama publik</dt>
                    <dd class="font-medium">Anonim</dd>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <dt class="text-muted-foreground">Verifikasi wajah</dt>
                    <dd><x-status tone="warning">Belum</x-status></dd>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <dt class="text-muted-foreground">Email aktif</dt>
                    <dd><x-status tone="success">Terverifikasi</x-status></dd>
                </div>
            </dl>

            <a href="{{ route('verifikasi') }}" class="btn-soft mt-auto w-full">
                <x-icon name="face" :size="14" />
                Mulai verifikasi
            </a>
        </section>

        <section class="flex flex-col rounded-[1.4rem] bg-card p-4 neu-sm sm:p-5">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h3 class="text-sm font-semibold">Konselor kamu</h3>
                    <p class="mt-0.5 text-xs text-muted-foreground">2 konselor terhubung.</p>
                </div>
                <span class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-muted text-muted-foreground">
                    <x-icon name="users" :size="15" />
                </span>
            </div>

            <ul class="mt-4 space-y-3">
                @foreach ([['Rani', 'Konselor keluarga', true], ['Dimas', 'Konselor kecemasan', false]] as [$name, $role, $online])
                    <li class="flex items-center gap-3">
                        <span class="relative flex size-9 shrink-0 items-center justify-center rounded-full bg-secondary text-sm font-semibold text-secondary-foreground">
                            {{ $name[0] }}
                            <span @class(['absolute -right-0.5 -bottom-0.5 size-2.5 rounded-full border-2 border-card', 'bg-success' => $online, 'bg-muted-foreground/50' => ! $online])></span>
                        </span>
                        <span class="min-w-0 flex-1 leading-tight">
                            <span class="block truncate text-sm font-medium">Kak {{ $name }}</span>
                            <span class="block truncate text-xs text-muted-foreground">{{ $role }}</span>
                        </span>
                        <span class="text-xs font-medium {{ $online ? 'text-success' : 'text-muted-foreground' }}">{{ $online ? 'Online' : 'Offline' }}</span>
                    </li>
                @endforeach
            </ul>

            <a href="{{ route('konseling.index') }}" class="btn-soft mt-auto w-full">
                <x-icon name="chat" :size="14" />
                Cari konselor
            </a>
        </section>
    </div>

    <div class="mt-3 grid gap-3 xl:grid-cols-[1fr_1.55fr]">
        <section class="rounded-[1.4rem] bg-card p-4 neu-sm sm:p-5">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h3 class="text-sm font-semibold">Tulis cerita</h3>
                    <p class="mt-0.5 text-xs text-muted-foreground">Bagikan apa yang sedang kamu rasakan.</p>
                </div>
                <span class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-muted text-muted-foreground">
                    <x-icon name="bubble" :size="15" />
                </span>
            </div>

            <form class="mt-4 space-y-3" @submit.prevent="$el.reset()">
                <div>
                    <label for="kategori-cerita" class="label-cap mb-1.5 block">Kategori</label>
                    <select id="kategori-cerita" class="w-full rounded-xl bg-input px-3 py-2.5 text-sm neu-inset focus:outline-none">
                        <option>Perlu dukungan</option>
                        <option>Cerita harian</option>
                        <option>Tips & pengalaman</option>
                        <option>Perayaan kecil</option>
                    </select>
                </div>

                <div>
                    <label for="isi-cerita" class="label-cap mb-1.5 block">Ceritamu</label>
                    <textarea id="isi-cerita" rows="4" maxlength="500" placeholder="Apa yang sedang kamu rasakan hari ini, Kawan?" class="w-full resize-none rounded-xl bg-input px-3 py-2.5 text-sm leading-relaxed placeholder:text-muted-foreground neu-inset focus:outline-none"></textarea>
                </div>

                <div class="flex items-center justify-between gap-3">
                    <p class="flex items-center gap-1.5 text-[0.7rem] text-muted-foreground">
                        <x-icon name="lock" :size="12" />
                        Diposting sebagai Anonim
                    </p>
                    <button type="submit" class="btn-lime">
                        Bagikan
                        <x-icon name="arrow-right" :size="14" />
                    </button>
                </div>
            </form>
        </section>

        <div class="min-w-0">
            <div class="flex flex-wrap items-end justify-between gap-2">
                <h3 class="text-base font-semibold tracking-tight">
                    Terbaru untukmu
                    <span class="text-xs font-normal text-muted-foreground">(2 sorotan)</span>
                </h3>
                <a href="{{ route('bubble') }}" class="text-xs font-semibold text-foreground hover:underline">Lihat semua</a>
            </div>

            <div class="mt-3 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                <a href="{{ route('konseling.show', 1) }}" class="group flex flex-col rounded-[1.4rem] bg-card p-4 neu-sm transition hover:-translate-y-0.5">
                    <div class="flex items-center justify-between gap-3">
                        <span class="flex size-8 items-center justify-center rounded-lg bg-muted text-muted-foreground">
                            <x-icon name="chat" :size="15" />
                        </span>
                        <span class="size-2 rounded-full bg-success"></span>
                    </div>
                    <p class="mt-4 text-[0.68rem] tracking-[0.1em] uppercase text-muted-foreground">Sesi konseling</p>
                    <p class="mt-1 text-sm font-semibold">Kak Rani</p>
                    <p class="mt-0.5 truncate text-xs text-muted-foreground">Boleh, aku coba. Makasih sudah dengerin.</p>
                    <span class="mt-auto flex items-center justify-between gap-2 pt-3">
                        <x-status tone="success">Aktif</x-status>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-foreground">
                            Lanjut
                            <x-icon name="arrow-right" :size="12" />
                        </span>
                    </span>
                </a>

                <a href="{{ route('bubble') }}" class="group flex flex-col rounded-[1.4rem] bg-card p-4 neu-sm transition hover:-translate-y-0.5">
                    <div class="flex items-center justify-between gap-3">
                        <span class="flex size-8 items-center justify-center rounded-lg bg-muted text-muted-foreground">
                            <x-icon name="bubble" :size="15" />
                        </span>
                        <span class="flex items-center gap-1 text-xs text-muted-foreground">
                            <x-icon name="heart" :size="12" />
                            37
                        </span>
                    </div>
                    <p class="mt-4 text-[0.68rem] tracking-[0.1em] uppercase text-muted-foreground">Cerita terakhir</p>
                    <p class="mt-1 line-clamp-2 text-sm font-semibold">Aku takut banget besok presentasi. Sudah seminggu nggak bisa tidur nyenyak…</p>
                    <p class="mt-0.5 text-xs text-muted-foreground">8 menit lalu · Anonim</p>
                    <span class="mt-auto inline-flex items-center gap-1 pt-3 text-xs font-semibold text-foreground">
                        Baca
                        <x-icon name="arrow-right" :size="12" />
                    </span>
                </a>

                <a href="{{ route('konseling.index') }}" class="flex min-h-[186px] flex-col items-center justify-center gap-2 rounded-[1.4rem] border-2 border-dashed border-foreground/30 bg-card/40 p-4 text-center transition hover:border-foreground/70">
                    <span class="flex size-10 items-center justify-center rounded-full bg-card text-foreground neu-sm">
                        <x-icon name="plus" :size="17" />
                    </span>
                    <span class="text-sm font-semibold">Ajukan sesi</span>
                    <span class="text-xs text-muted-foreground">Konselor membalas 1×24 jam</span>
                </a>
            </div>

            <div class="mt-3 flex flex-col gap-3 rounded-[1.4rem] bg-secondary/30 p-4 sm:flex-row sm:items-center">
                <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-card text-foreground">
                    <x-icon name="lock" :size="16" />
                </span>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold">Kendali privasi</p>
                    <p class="text-xs text-muted-foreground">Unduh datamu atau hapus data verifikasi kapan saja.</p>
                </div>
                <a href="{{ route('profil') }}" class="btn-soft shrink-0 px-4 py-2 text-xs">Buka pengaturan</a>
            </div>
        </div>
    </div>
</x-layouts.app>
