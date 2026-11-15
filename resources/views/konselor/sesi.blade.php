<x-layouts.app title="Sesi Konseling" role="konselor">
    <div class="mb-5">
        <h2 class="text-xl font-semibold tracking-tight sm:text-2xl">Sesi saya</h2>
        <p class="mt-1 text-sm text-muted-foreground">Hanya sesi yang ditugaskan kepadamu yang tampil di sini.</p>
    </div>

    <div class="grid gap-4 xl:grid-cols-[360px_1fr]">
        <div class="min-w-0 space-y-2.5">
            <div class="flex gap-2 overflow-x-auto pb-1 no-scrollbar" role="tablist" aria-label="Filter sesi">
                @foreach (['Semua', 'Aktif', 'Menunggu', 'Selesai'] as $i => $filter)
                    <button
                        type="button"
                        role="tab"
                        @class([
                            'shrink-0 rounded-full px-3.5 py-1.5 text-xs font-semibold transition',
                            'bg-primary text-primary-foreground' => $i === 0,
                            'bg-card text-muted-foreground hover:text-foreground' => $i !== 0,
                        ])
                        aria-selected="{{ $i === 0 ? 'true' : 'false' }}"
                    >{{ $filter }}</button>
                @endforeach
            </div>

            @foreach ([
                ['Anonim #2418', 'Kecemasan', '5 menit lalu', 'Boleh, aku coba. Makasih sudah dengerin.', 'Aktif', 'success', true, 1],
                ['Anonim #2411', 'Tekanan akademik', '22 menit lalu', 'Aku bingung harus mulai dari mana…', 'Menunggu', 'warning', true, 1],
                ['Anonim #2396', 'Masalah keluarga', '1 jam lalu', 'Aku sudah coba ngomong ke ibu, lumayan lega.', 'Aktif', 'success', false, 1],
                ['Anonim #2374', 'Kesepian', 'Kemarin', 'Sampai jumpa di sesi berikutnya ya.', 'Selesai', 'neutral', false, 1],
            ] as [$user, $topic, $time, $preview, $status, $tone, $unread, $id])
                <a
                    href="{{ route('konselor.sesi.show', $id) }}"
                    @class([
                        'flex items-start gap-3 rounded-2xl bg-card p-3.5 neu-sm transition hover:bg-cream',
                        'ring-1 ring-primary/25' => $loop->first,
                    ])
                >
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-secondary/50 text-secondary-foreground">
                        <x-icon name="user" :size="17" />
                    </span>
                    <span class="min-w-0 flex-1">
                        <span class="flex items-center gap-2">
                            <span class="truncate text-sm font-semibold">{{ $user }}</span>
                            <span class="ml-auto shrink-0 text-[10px] text-muted-foreground">{{ $time }}</span>
                        </span>
                        <span class="mt-0.5 block truncate text-[11px] text-muted-foreground">{{ $topic }} · {{ $preview }}</span>
                        <span class="mt-1.5 flex items-center gap-2">
                            <x-status :tone="$tone">{{ $status }}</x-status>
                            @if ($unread)
                                <span class="size-1.5 rounded-full bg-destructive" aria-label="Pesan belum dibaca"></span>
                            @endif
                        </span>
                    </span>
                </a>
            @endforeach
        </div>

        <div class="hidden min-w-0 xl:block">
            <x-chat-panel name="Anonim #2418" role="Topik: Kecemasan" state="Sesi aktif" :anonymous="true" />
        </div>
    </div>
</x-layouts.app>
