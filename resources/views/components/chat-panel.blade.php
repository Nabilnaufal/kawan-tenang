@props([
    'name' => 'Kak Rani',
    'role' => 'Konselor keluarga',
    'state' => 'Sesi aktif',
    'anonymous' => false,
])

@php
    $initial = mb_substr(preg_replace('/^Anonim\s*/', '', str_replace('Kak ', '', $name)), 0, 1);
@endphp

<section class="flex h-[calc(100dvh-13.5rem)] min-h-[420px] flex-col overflow-hidden rounded-[1.4rem] bg-card neu-sm lg:h-[calc(100dvh-13rem)]">
    <header class="flex shrink-0 items-center gap-3 border-b border-border/60 px-4 py-3">
        <span class="relative flex size-10 shrink-0 items-center justify-center rounded-full bg-primary/15 text-sm font-semibold text-foreground">
            @if ($anonymous)
                <x-icon name="user" :size="17" />
            @else
                {{ $initial }}
            @endif
            <span class="absolute -right-0.5 -bottom-0.5 size-2.5 rounded-full border-2 border-card bg-success"></span>
        </span>

        <div class="min-w-0 flex-1 leading-tight">
            <p class="truncate text-sm font-semibold">{{ $name }}</p>
            <p class="truncate text-xs text-muted-foreground">{{ $role }} · <span class="text-success">{{ $state }}</span></p>
        </div>

        <button type="button" class="rounded-lg px-2.5 py-1.5 text-xs font-semibold text-destructive transition hover:bg-destructive/10">
            {{ $anonymous ? 'Tandai selesai' : 'Akhiri' }}
        </button>
    </header>

    <div class="flex-1 space-y-3 overflow-y-auto px-4 py-4" aria-live="polite" aria-label="Riwayat pesan">
        <p class="text-center text-xs text-muted-foreground">Hari ini</p>

        <div class="flex items-start gap-2">
            <span class="flex size-7 shrink-0 items-center justify-center rounded-full bg-primary/15 text-xs font-semibold text-foreground">
                @if ($anonymous)
                    <x-icon name="user" :size="13" />
                @else
                    {{ $initial }}
                @endif
            </span>
            <div class="max-w-[80%]">
                <p class="rounded-2xl rounded-tl-sm bg-muted px-3.5 py-2.5 text-xs leading-relaxed">
                    Halo, terima kasih sudah mau cerita. Apa yang paling mengganggu pikiranmu belakangan ini?
                </p>
                <p class="mt-1 pl-1 text-xs text-muted-foreground">09.12</p>
            </div>
        </div>

        <div class="flex justify-end">
            <div class="max-w-[80%]">
                <p class="rounded-2xl rounded-tr-sm bg-primary px-3.5 py-2.5 text-xs leading-relaxed text-primary-foreground">
                    Aku susah tidur beberapa hari ini, kepikiran tugas terus. Rasanya nggak pernah cukup.
                </p>
                <p class="mt-1 pr-1 text-right text-xs text-muted-foreground">09.15 · Terkirim</p>
            </div>
        </div>

        <div class="flex items-start gap-2">
            <span class="flex size-7 shrink-0 items-center justify-center rounded-full bg-primary/15 text-xs font-semibold text-foreground">
                @if ($anonymous)
                    <x-icon name="user" :size="13" />
                @else
                    {{ $initial }}
                @endif
            </span>
            <div class="max-w-[80%]">
                <p class="rounded-2xl rounded-tl-sm bg-muted px-3.5 py-2.5 text-xs leading-relaxed">
                    Itu melelahkan ya. Coba kita mulai dari hal kecil: sebelum tidur, tuliskan tiga hal yang sudah kamu selesaikan hari ini. Mau dicoba malam ini?
                </p>
                <p class="mt-1 pl-1 text-xs text-muted-foreground">09.18</p>
            </div>
        </div>

        <div class="flex justify-end">
            <div class="max-w-[80%]">
                <p class="rounded-2xl rounded-tr-sm bg-primary px-3.5 py-2.5 text-xs leading-relaxed text-primary-foreground">
                    Boleh, aku coba. Makasih sudah dengerin.
                </p>
                <p class="mt-1 pr-1 text-right text-xs text-muted-foreground">09.20 · Dibaca</p>
            </div>
        </div>
    </div>

    <div class="shrink-0 border-t border-border/60 px-4 py-3">
        <div class="mb-2 flex gap-2 overflow-x-auto no-scrollbar">
            @foreach (['Aku merasa lebih tenang', 'Boleh lanjut besok?', 'Terima kasih'] as $chip)
                <button type="button" class="shrink-0 rounded-full bg-muted px-3 py-1.5 text-xs font-medium text-muted-foreground transition hover:text-foreground">
                    {{ $chip }}
                </button>
            @endforeach
        </div>

        <form class="flex items-center gap-2" @submit.prevent="$el.reset()">
            <label for="pesan" class="sr-only">Tulis pesan</label>
            <input
                id="pesan"
                type="text"
                placeholder="Tulis pesan privat…"
                class="min-w-0 flex-1 rounded-xl bg-input px-3.5 py-2.5 text-xs text-foreground placeholder:text-muted-foreground neu-inset focus:outline-none"
            >
            <button type="submit" class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-primary text-primary-foreground" aria-label="Kirim pesan">
                <x-icon name="send" :size="16" />
            </button>
        </form>
        <p class="mt-2 flex items-center justify-center gap-1.5 text-xs text-muted-foreground">
            <x-icon name="lock" :size="11" />
            {{ $anonymous ? 'Pesan ini hanya terlihat olehmu dan pengguna ini.' : 'Hanya kamu dan konselormu yang bisa membaca pesan ini.' }}
        </p>
    </div>
</section>
