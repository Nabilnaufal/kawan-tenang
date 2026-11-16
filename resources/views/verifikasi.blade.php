<x-layouts.app title="Verifikasi Akun">
    <div class="mb-5">
        <h2 class="text-xl font-semibold tracking-tight sm:text-2xl">Verifikasi Akun</h2>
        <p class="mt-1 text-sm text-muted-foreground">Pemeriksaan liveness untuk membantu menjaga komunitas tetap aman.</p>
    </div>

    <div class="grid gap-4 lg:grid-cols-[1.4fr_1fr]">
        {{-- Status --}}
        <section class="relative overflow-hidden rounded-[1.4rem] bg-card p-5 neu sm:p-6" x-data="{ open: false, waiting: false, agree: false }">
            <div class="pointer-events-none absolute -top-10 -right-8 size-40 rounded-full bg-secondary/25 blur-2xl" aria-hidden="true"></div>

            <div class="relative flex flex-wrap items-start gap-4">
                <span class="flex size-12 items-center justify-center rounded-2xl bg-accent/35 text-accent-foreground">
                    <x-icon name="face" :size="24" />
                </span>
                <div class="min-w-0 flex-1">
                    <x-status tone="warning">Belum diverifikasi</x-status>
                    <h3 class="mt-2 text-lg font-semibold tracking-tight">Satu langkah kecil untuk kepercayaan bersama</h3>
                    <p class="mt-1.5 max-w-md text-xs leading-relaxed text-muted-foreground">
                        Verifikasi dilakukan sekali oleh penyedia pihak ketiga (Didit). Kamu tetap bisa membaca Bubble Publik dan mengakses bantuan darurat tanpa verifikasi.
                    </p>
                </div>
            </div>

            <template x-if="! waiting">
                <div class="relative mt-5 flex flex-col gap-2 sm:flex-row">
                    <button
                        type="button"
                        class="btn-lime text-xs"
                        @click="open = true"
                    >
                        <x-icon name="face" :size="15" />
                        Mulai verifikasi
                    </button>
                    <a href="#privasi" class="inline-flex items-center justify-center gap-2 rounded-full bg-background px-4 py-2.5 text-xs font-semibold neu-sm transition hover:bg-muted">
                        Pelajari prosesnya
                    </a>
                </div>
            </template>

            <div x-show="waiting" x-cloak class="relative mt-5 flex items-center gap-3 rounded-xl bg-secondary/30 p-3">
                <span class="flex size-8 items-center justify-center rounded-full bg-card text-foreground">
                    <x-icon name="clock" :size="16" />
                </span>
                <div>
                    <p class="text-xs font-semibold">Menunggu hasil pemeriksaan</p>
                    <p class="text-xs text-muted-foreground">Biasanya selesai dalam 1–2 menit. Halaman ini akan diperbarui otomatis.</p>
                </div>
            </div>

            <dl class="relative mt-6 grid grid-cols-2 gap-4 border-t border-border/60 pt-4 sm:grid-cols-3">
                @foreach ([['Penyedia', 'Didit'], ['Metode', 'Passive liveness'], ['Data disimpan', 'Status & waktu']] as [$label, $value])
                    <div>
                        <dt class="text-xs tracking-wide text-muted-foreground uppercase">{{ $label }}</dt>
                        <dd class="mt-0.5 text-xs font-semibold">{{ $value }}</dd>
                    </div>
                @endforeach
            </dl>

            {{-- Consent dialog --}}
            <div
                x-show="open"
                x-cloak
                x-transition.opacity
                class="fixed inset-0 z-50 flex items-end justify-center bg-foreground/40 p-4 backdrop-blur-sm sm:items-center"
                role="dialog"
                aria-modal="true"
                aria-labelledby="verif-title"
                @keydown.escape.window="open = false"
            >
                <div class="w-full max-w-lg rounded-[1.4rem] bg-card p-5 neu" @click.outside="open = false">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 id="verif-title" class="text-base font-semibold">Persetujuan pemrosesan data</h2>
                            <p class="mt-1 text-xs text-muted-foreground">Baca ringkasan ini sebelum melanjutkan.</p>
                        </div>
                        <button type="button" class="rounded-lg p-1 text-muted-foreground hover:bg-muted" aria-label="Tutup" @click="open = false">
                            <x-icon name="x" :size="18" />
                        </button>
                    </div>

                    <ul class="mt-4 space-y-2.5 text-xs leading-relaxed text-muted-foreground">
                        @foreach ([
                            'Tujuan: memastikan akun dijalankan oleh orang hidup, bukan foto atau rekaman.',
                            'Data: gambar wajah diproses oleh Didit sebagai penyedia pihak ketiga.',
                            'Penyimpanan: kami hanya menyimpan status, ID sesi, dan waktu — bukan foto selfie.',
                            'Hak kamu: menarik persetujuan dan meminta penghapusan data kapan saja.',
                        ] as $point)
                            <li class="flex gap-2">
                                <x-icon name="check" :size="13" class="mt-0.5 shrink-0 text-success" />
                                {{ $point }}
                            </li>
                        @endforeach
                    </ul>

                    <label class="mt-4 flex items-start gap-2.5 rounded-xl bg-input p-3 text-xs leading-relaxed neu-inset">
                        <input type="checkbox" x-model="agree" class="mt-0.5 size-4 rounded border-border text-primary focus:ring-ring">
                        <span>Aku memahami tujuan, penyedia, dan cara data wajah diproses.</span>
                    </label>

                    <div class="mt-4 flex justify-end gap-2">
                        <button type="button" class="rounded-xl px-4 py-2 text-sm font-medium text-muted-foreground hover:bg-muted" @click="open = false">Nanti saja</button>
                        <button
                            type="button"
                            class="rounded-full bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground transition disabled:opacity-40"
                            :disabled="! agree"
                            @click="open = false; waiting = true"
                        >Setuju & lanjutkan</button>
                    </div>
                </div>
            </div>
        </section>

        {{-- Steps --}}
        <section class="rounded-3xl bg-card p-5 neu-sm sm:p-6">
            <h3 class="text-sm font-semibold">Alur verifikasi</h3>
            <ol class="mt-4 space-y-4">
                @foreach ([
                    ['Persetujuan', 'Kamu membaca dan menyetujui pemrosesan data.'],
                    ['Sesi verifikasi', 'Backend membuat sesi aman bersama penyedia.'],
                    ['Pemeriksaan liveness', 'Kamera memastikan ada orang hidup di depan layar.'],
                    ['Hasil', 'Status diperbarui lewat webhook yang tervalidasi.'],
                ] as $i => [$step, $detail])
                    <li class="flex gap-3">
                        <span @class([
                            'flex size-7 shrink-0 items-center justify-center rounded-full text-xs font-bold',
                            'bg-primary text-primary-foreground' => $i === 0,
                            'bg-muted text-muted-foreground' => $i > 0,
                        ])>{{ $i + 1 }}</span>
                        <div class="min-w-0">
                            <p class="text-xs font-semibold">{{ $step }}</p>
                            <p class="mt-0.5 text-xs leading-relaxed text-muted-foreground">{{ $detail }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </section>
    </div>

    <div id="privasi" class="mt-4 grid gap-4 lg:grid-cols-2">
        <section class="rounded-2xl bg-secondary/25 p-5">
            <h3 class="flex items-center gap-2 text-sm font-semibold">
                <x-icon name="lock" :size="15" />
                Apa yang tidak kami simpan
            </h3>
            <ul class="mt-3 space-y-2 text-xs leading-relaxed text-muted-foreground">
                <li>Foto selfie mentah dan template biometrik.</li>
                <li>Data wajah di luar kebutuhan verifikasi.</li>
                <li>Isi percakapan konseling di log sistem.</li>
            </ul>
            <p class="mt-3 text-xs leading-relaxed text-muted-foreground">
                Kamu dapat mengajukan penghapusan data verifikasi melalui halaman pengaturan profil.
            </p>
        </section>

        <section class="rounded-2xl border border-accent/50 bg-accent/15 p-5">
            <h3 class="flex items-center gap-2 text-sm font-semibold">
                <x-icon name="alert" :size="15" />
                Verifikasi bukan syarat bantuan
            </h3>
            <p class="mt-2 text-xs leading-relaxed">
                Kamu tetap dapat memakai Bubble Publik dan menghubungi layanan darurat tanpa verifikasi wajah. Jangan menunda bantuan hanya karena proses ini.
            </p>
            <a href="tel:119" class="mt-3 inline-flex items-center gap-2 rounded-full bg-card px-4 py-2 text-xs font-semibold">
                <x-icon name="life-buoy" :size="13" />
                Hubungi 119 ext. 8
            </a>
        </section>
    </div>
</x-layouts.app>
