@props(['title' => null])

<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#F3F1EA">
    <title>{{ $title ? $title . ' — Kawan Tenang' : 'Kawan Tenang' }}</title>
    <link rel="icon" href="/favicon.ico">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full">
    <div class="flex min-h-screen w-full">
        {{-- Brand panel --}}
        <aside class="relative hidden w-[38%] max-w-[520px] flex-col justify-between bg-brand-deep p-10 text-primary-foreground lg:flex">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <span class="flex size-10 items-center justify-center rounded-2xl bg-primary-foreground/15">
                    <x-icon name="heart" :size="20" />
                </span>
                <span class="text-sm font-semibold tracking-tight">Kawan Tenang</span>
            </a>

            <div class="max-w-sm">
                <h1 class="text-3xl leading-tight font-semibold tracking-tight">
                    Ruang aman untuk bercerita, tanpa takut dikenali.
                </h1>
                <p class="mt-4 text-sm leading-relaxed text-primary-foreground/80">
                    Bagikan cerita secara anonim, atau bicara empat mata dengan konselor yang siap mendengarkan.
                </p>

                <dl class="mt-8 grid grid-cols-3 gap-4 border-t border-primary-foreground/20 pt-6">
                    @foreach ([['1.284', 'cerita'], ['32', 'konselor'], ['4.8', 'rating']] as [$value, $label])
                        <div>
                            <dt class="text-xs text-primary-foreground/70">{{ $label }}</dt>
                            <dd class="mt-0.5 text-lg font-semibold">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            <p class="text-xs leading-relaxed text-primary-foreground/70">
                Kawan Tenang bukan layanan gawat darurat. Jika kamu dalam bahaya, hubungi 119 ext. 8 atau 112.
            </p>
        </aside>

        {{-- Form panel --}}
        <main class="flex min-w-0 flex-1 flex-col px-5 py-6 sm:px-8 lg:px-12">
            <div class="flex items-center justify-between lg:justify-end">
                <a href="{{ route('home') }}" class="flex items-center gap-2 lg:hidden">
                    <span class="flex size-9 items-center justify-center rounded-xl bg-primary text-primary-foreground">
                        <x-icon name="heart" :size="18" />
                    </span>
                    <span class="text-sm font-semibold tracking-tight">Kawan Tenang</span>
                </a>
                <a href="{{ route('home') }}" class="text-xs font-medium text-muted-foreground hover:text-foreground">Kembali ke beranda</a>
            </div>

            <div class="mx-auto flex w-full max-w-md flex-1 flex-col justify-center py-10">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>
