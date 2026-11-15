<x-layouts.app title="Sesi Konseling">
    <div class="mb-4 flex items-center gap-3">
        <a href="{{ route('konseling.index') }}" class="flex size-9 items-center justify-center rounded-xl bg-card text-muted-foreground neu-sm transition hover:text-foreground" aria-label="Kembali ke daftar sesi">
            <x-icon name="chevron-right" :size="18" class="rotate-180" />
        </a>
        <div>
            <h2 class="text-lg font-semibold tracking-tight">Sesi dengan Kak Rani</h2>
            <p class="text-xs text-muted-foreground">Dimulai hari ini · ID sesi #KT-2418</p>
        </div>
        <x-status tone="success" class="ml-auto">Aktif</x-status>
    </div>

    <div>
        <x-chat-panel name="Kak Rani" role="Konselor keluarga" state="Sesi aktif" />
    </div>

    <p class="mt-3 flex items-start gap-2 px-1 text-xs leading-relaxed text-muted-foreground">
        <x-icon name="alert" :size="13" class="mt-0.5 shrink-0" />
        Jika kamu dalam bahaya atau berpikir untuk menyakiti diri, segera hubungi 119 ext. 8 atau 112. Konselor tidak selalu online 24 jam.
    </p>
</x-layouts.app>
