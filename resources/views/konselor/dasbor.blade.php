<x-layouts.app title="Dasbor Konselor" role="konselor">
    <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
        <div>
            <h2 class="text-xl font-semibold tracking-tight sm:text-2xl">Dasbor konselor</h2>
            <p class="mt-1 text-sm text-muted-foreground">Sesi yang ditugaskan kepadamu dan ringkasan beban kerja.</p>
        </div>

        <div class="flex items-center gap-2" x-data="{ available: true }">
            <span class="text-xs text-muted-foreground" x-text="available ? 'Menerima sesi baru' : 'Sedang tidak tersedia'">Menerima sesi baru</span>
            <button
                type="button"
                role="switch"
                :aria-checked="available"
                @click="available = !available"
                class="relative h-6 w-11 rounded-full transition"
                :class="available ? 'bg-primary' : 'bg-muted'"
                aria-label="Ketersediaan konselor"
            >
                <span class="absolute top-0.5 left-0.5 size-5 rounded-full bg-card shadow transition" :class="available ? 'translate-x-5' : ''"></span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-3 xl:grid-cols-4">
        @foreach ([
            ['label' => 'Sesi aktif', 'value' => '6', 'icon' => 'chat', 'tone' => 'bg-primary/15 text-brand-deep'],
            ['label' => 'Menunggu balasan', 'value' => '3', 'icon' => 'inbox', 'tone' => 'bg-accent/35 text-accent-foreground'],
            ['label' => 'Selesai bulan ini', 'value' => '28', 'icon' => 'check-circle', 'tone' => 'bg-success/15 text-success'],
            ['label' => 'Rata-rata respons', 'value' => '9', 'suffix' => ' mnt', 'icon' => 'clock', 'tone' => 'bg-secondary/45 text-secondary-foreground'],
        ] as $stat)
            <div class="rounded-2xl bg-card p-4 neu-sm">
                <span class="flex size-8 items-center justify-center rounded-lg {{ $stat['tone'] }}">
                    <x-icon :name="$stat['icon']" :size="16" />
                </span>
                <p class="mt-3 text-xl font-semibold tracking-tight tabular-nums">
                    <span data-count="{{ $stat['value'] }}">0</span>{{ $stat['suffix'] ?? '' }}
                </p>
                <p class="mt-0.5 text-[11px] leading-tight text-muted-foreground">{{ $stat['label'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-4 grid gap-4 xl:grid-cols-[1.8fr_1fr]">
        <div class="min-w-0 space-y-4">
            <section class="rounded-2xl bg-card p-4 neu-sm sm:p-5">
                <div class="flex items-center justify-between gap-3">
                    <h3 class="text-sm font-semibold">Sesi yang ditugaskan</h3>
                    <a href="{{ route('konselor.sesi') }}" class="text-[11px] font-semibold text-brand-deep hover:underline">Lihat semua</a>
                </div>

                <div class="mt-3 overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="text-[10px] tracking-wide text-muted-foreground uppercase">
                            <tr>
                                <th class="py-2 pr-3 font-medium">Pengguna</th>
                                <th class="hidden py-2 pr-3 font-medium sm:table-cell">Topik</th>
                                <th class="py-2 pr-3 font-medium">Status</th>
                                <th class="hidden py-2 pr-3 font-medium sm:table-cell">Aktivitas</th>
                                <th class="py-2 font-medium"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border/60">
                            @foreach ([
                                ['Anonim #2418', 'Kecemasan', 'Aktif', 'success', '5 menit lalu'],
                                ['Anonim #2411', 'Tekanan akademik', 'Menunggu', 'warning', '22 menit lalu'],
                                ['Anonim #2396', 'Masalah keluarga', 'Aktif', 'success', '1 jam lalu'],
                                ['Anonim #2374', 'Kesepian', 'Selesai', 'neutral', 'Kemarin'],
                            ] as [$user, $topic, $status, $tone, $time])
                                <tr class="align-middle">
                                    <td class="py-2.5 pr-3 font-medium">{{ $user }}</td>
                                    <td class="hidden py-2.5 pr-3 text-muted-foreground sm:table-cell">{{ $topic }}</td>
                                    <td class="py-2.5 pr-3"><x-status :tone="$tone">{{ $status }}</x-status></td>
                                    <td class="hidden py-2.5 pr-3 text-muted-foreground sm:table-cell">{{ $time }}</td>
                                    <td class="py-2.5 text-right">
                                        <a href="{{ route('konselor.sesi.show', 1) }}" class="inline-flex items-center gap-1 font-semibold text-brand-deep hover:underline">
                                            Buka
                                            <x-icon name="chevron-right" :size="12" />
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="min-w-0 rounded-2xl bg-card p-4 neu-sm sm:p-5">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h3 class="text-sm font-semibold">Sesi per hari</h3>
                        <p class="mt-0.5 text-[11px] text-muted-foreground">Tujuh hari terakhir.</p>
                    </div>
                    <div class="flex items-center gap-3 text-[10px] font-medium text-muted-foreground">
                        <span class="flex items-center gap-1.5"><span class="size-2 rounded-full bg-primary"></span>Selesai</span>
                        <span class="flex items-center gap-1.5"><span class="size-2 rounded-full bg-secondary"></span>Baru</span>
                    </div>
                </div>
                <div class="mt-4 h-52 sm:h-60">
                    <canvas id="chartSesiKonselor" role="img" aria-label="Diagram batang jumlah sesi konseling per hari"></canvas>
                </div>
            </section>
        </div>

        <aside class="space-y-4">
            <section class="rounded-2xl bg-card p-4 neu-sm sm:p-5">
                <h3 class="text-sm font-semibold">Menunggu balasanmu</h3>
                <ul class="mt-3 space-y-2.5">
                    @foreach ([['Anonim #2411', 'Aku bingung harus mulai dari mana…', '22 mnt'], ['Anonim #2420', 'Besok aku ada presentasi, aku takut…', '41 mnt'], ['Anonim #2415', 'Makasih sarannya, aku coba ya.', '1 jam']] as [$user, $preview, $time])
                        <li>
                            <a href="{{ route('konselor.sesi.show', 1) }}" class="block rounded-xl bg-muted/60 p-3 transition hover:bg-muted">
                                <span class="flex items-center justify-between gap-2">
                                    <span class="text-xs font-semibold">{{ $user }}</span>
                                    <span class="text-[10px] text-muted-foreground">{{ $time }}</span>
                                </span>
                                <span class="mt-0.5 block truncate text-[11px] text-muted-foreground">{{ $preview }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>

            <section class="rounded-2xl bg-card p-4 neu-sm sm:p-5">
                <h3 class="text-sm font-semibold">Jadwal hari ini</h3>
                <ul class="mt-3 space-y-2.5 text-xs">
                    @foreach ([['10.00', 'Sesi lanjutan · Anonim #2418'], ['13.30', 'Sesi baru · Anonim #2420'], ['16.00', 'Evaluasi mingguan tim konselor']] as [$time, $label])
                        <li class="flex gap-3">
                            <span class="w-10 shrink-0 font-semibold tabular-nums text-brand-deep">{{ $time }}</span>
                            <span class="text-muted-foreground">{{ $label }}</span>
                        </li>
                    @endforeach
                </ul>
            </section>

            <section class="rounded-2xl bg-secondary/25 p-4">
                <h3 class="flex items-center gap-2 text-sm font-semibold">
                    <x-icon name="lock" :size="15" />
                    Catatan internal
                </h3>
                <p class="mt-1.5 text-xs leading-relaxed text-muted-foreground">
                    Catatan konseling dipisahkan dari pesan yang bisa dibaca pengguna dan hanya terlihat oleh konselor yang ditugaskan.
                </p>
            </section>
        </aside>
    </div>
</x-layouts.app>
