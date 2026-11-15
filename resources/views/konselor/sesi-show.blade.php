<x-layouts.app title="Sesi Konseling" role="konselor">
    <div class="mb-4 flex items-center gap-3">
        <a href="{{ route('konselor.sesi') }}" class="flex size-9 items-center justify-center rounded-xl bg-card text-muted-foreground neu-sm transition hover:text-foreground" aria-label="Kembali ke daftar sesi">
            <x-icon name="chevron-right" :size="18" class="rotate-180" />
        </a>
        <div class="min-w-0">
            <h2 class="truncate text-lg font-semibold tracking-tight">Sesi dengan Anonim #2418</h2>
            <p class="text-[11px] text-muted-foreground">Topik: Kecemasan · ID sesi #KT-2418</p>
        </div>
        <x-status tone="success" class="ml-auto">Aktif</x-status>
    </div>

    <x-chat-panel name="Anonim #2418" role="Topik: Kecemasan" state="Sesi aktif" :anonymous="true" />

    <p class="mt-3 flex items-start gap-2 px-1 text-[11px] leading-relaxed text-muted-foreground">
        <x-icon name="lock" :size="13" class="mt-0.5 shrink-0" />
        Identitas asli pengguna tidak ditampilkan. Catatan konseling internal terpisah dari percakapan ini.
    </p>
</x-layouts.app>
