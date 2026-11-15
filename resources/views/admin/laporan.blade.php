<x-layouts.app title="Laporan & Moderasi" role="admin">
    <div class="mb-5">
        <h2 class="text-xl font-semibold tracking-tight sm:text-2xl">Laporan & moderasi</h2>
        <p class="mt-1 text-sm text-muted-foreground">Tinjau laporan komunitas. Identitas pelapor tetap anonim.</p>
    </div>

    <div class="grid grid-cols-2 gap-3 xl:grid-cols-4">
        @foreach ([['Baru', '7', 'flag', 'bg-accent/35 text-accent-foreground'], ['Ditinjau', '4', 'eye', 'bg-secondary/45 text-secondary-foreground'], ['Ditindaklanjuti', '18', 'check-circle', 'bg-success/15 text-success'], ['Ditolak', '6', 'x', 'bg-muted text-muted-foreground']] as [$label, $value, $icon, $tone])
            <div class="rounded-2xl bg-card p-4 neu-sm">
                <span class="flex size-8 items-center justify-center rounded-lg {{ $tone }}">
                    <x-icon :name="$icon" :size="16" />
                </span>
                <p class="mt-3 text-xl font-semibold tracking-tight tabular-nums">{{ $value }}</p>
                <p class="mt-0.5 text-xs text-muted-foreground">{{ $label }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-4 grid gap-4 xl:grid-cols-[1.8fr_1fr]">
        <section class="min-w-0 rounded-2xl bg-card p-4 neu-sm sm:p-5">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <h3 class="text-sm font-semibold">Antrean laporan</h3>
                <div class="flex gap-2">
                    @foreach (['Baru', 'Ditinjau'] as $i => $filter)
                        <button type="button" @class(['rounded-full px-3 py-1.5 text-xs font-semibold', 'bg-primary text-primary-foreground' => $i === 0, 'bg-muted text-muted-foreground' => $i !== 0])>{{ $filter }}</button>
                    @endforeach
                </div>
            </div>

            <div class="mt-3 space-y-2.5">
                @foreach ([
                    ['Ujaran kebencian', 'Cerita #4821', 'Anonim', '2 jam lalu', 'Baru', 'warning'],
                    ['Membahayakan diri', 'Cerita #4817', 'Anonim', '6 jam lalu', 'Baru', 'danger'],
                    ['Spam berulang', 'Komentar #1290', 'Anonim', '4 jam lalu', 'Ditinjau', 'info'],
                    ['Pelecehan', 'Komentar #1284', 'Anonim', 'Kemarin', 'Baru', 'warning'],
                    ['Penipuan', 'Cerita #4790', 'Anonim', 'Kemarin', 'Ditolak', 'neutral'],
                ] as [$reason, $target, $reporter, $time, $status, $tone])
                    <article class="rounded-xl bg-muted/50 p-3.5">
                        <div class="flex flex-wrap items-start justify-between gap-2">
                            <div class="min-w-0">
                                <p class="text-xs font-semibold">{{ $reason }}</p>
                                <p class="mt-0.5 text-xs text-muted-foreground">{{ $target }} · dilaporkan {{ $reporter }} · {{ $time }}</p>
                            </div>
                            <x-status :tone="$tone">{{ $status }}</x-status>
                        </div>
                        <p class="mt-2 line-clamp-2 rounded-lg bg-card px-3 py-2 text-xs leading-relaxed text-muted-foreground">
                            "Aku benar-benar tidak sanggup lagi dengan semua ini, rasanya ingin menghilang saja."
                        </p>
                        <div class="mt-2.5 flex flex-wrap gap-2">
                            <button type="button" class="rounded-lg bg-primary px-3 py-1.5 text-xs font-semibold text-primary-foreground">Tinjau</button>
                            <button type="button" class="rounded-lg bg-background px-3 py-1.5 text-xs font-semibold neu-sm hover:bg-muted">Sembunyikan konten</button>
                            <button type="button" class="rounded-lg px-3 py-1.5 text-xs font-semibold text-muted-foreground hover:bg-muted">Tolak laporan</button>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>

        <aside class="space-y-4">
            <section class="rounded-2xl bg-card p-4 neu-sm sm:p-5">
                <h3 class="text-sm font-semibold">Panduan tindakan</h3>
                <ul class="mt-3 space-y-2.5 text-xs leading-relaxed text-muted-foreground">
                    @foreach ([
                        'Sembunyikan konten yang melanggar, jangan hapus permanen sebelum selesai tinjauan.',
                        'Sertakan alasan tindakan agar tercatat pada audit log.',
                        'Laporan yang melibatkan risiko menyakiti diri diteruskan ke tim konseling.',
                        'Jangan membuka isi pesan privat saat meninjau laporan.',
                    ] as $rule)
                        <li class="flex gap-2">
                            <x-icon name="check" :size="13" class="mt-0.5 shrink-0 text-success" />
                            {{ $rule }}
                        </li>
                    @endforeach
                </ul>
            </section>

            <section class="rounded-2xl bg-secondary/25 p-4">
                <h3 class="flex items-center gap-2 text-sm font-semibold">
                    <x-icon name="clipboard" :size="15" />
                    Audit log
                </h3>
                <ul class="mt-3 space-y-2 text-xs">
                    @foreach ([['Moderator Rani menyembunyikan komentar #1284', '1 jam lalu'], ['Admin Fajar menolak laporan #4790', '3 jam lalu'], ['Admin Fajar mengubah peran akun #2210', 'Kemarin']] as [$entry, $time])
                        <li class="rounded-lg bg-card/70 px-3 py-2">
                            <p class="text-muted-foreground">{{ $entry }}</p>
                            <p class="mt-0.5 text-xs text-muted-foreground/80">{{ $time }}</p>
                        </li>
                    @endforeach
                </ul>
            </section>
        </aside>
    </div>
</x-layouts.app>
