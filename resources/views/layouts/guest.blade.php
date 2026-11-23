@php($title = $__env->yieldContent('title'))

<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#ECEEE9">
    <title>{{ $title ? $title . ' — Kawan Tenang' : 'Kawan Tenang' }}</title>
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" type="image/png" href="/favicon-32.png" sizes="32x32">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <script>
        if (localStorage.getItem('kt-theme') === 'dark' || (!localStorage.getItem('kt-theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full">
    <div class="flex min-h-screen items-center justify-center p-3 sm:p-6">
        <div class="grid w-full max-w-5xl overflow-hidden rounded-[2rem] bg-card lg:grid-cols-[1.02fr_1fr] neu">
            {{-- Brand panel --}}
            <aside class="relative hidden flex-col justify-between bg-primary p-8 text-primary-foreground lg:flex">
                <a href="{{ route('home') }}" class="inline-flex flex-col items-start gap-2">
                    <img src="/assets/kawan-tenang-logo.png" alt="Kawan Tenang" width="160" height="78" class="h-auto w-40">
                    <span class="text-[0.7rem] tracking-[0.16em] uppercase opacity-60">Ruang aman</span>
                </a>

                <div class="mt-10">
                    <span class="inline-flex items-center gap-2 rounded-full border border-primary-foreground/20 px-3 py-1.5 text-[0.75rem] font-medium">
                        <x-icon name="lock" :size="13" />
                        Anonim secara default
                    </span>

                    <h1 class="mt-5 text-3xl leading-tight font-semibold tracking-tight">
                        Bercerita tanpa takut dikenali.
                    </h1>
                    <p class="mt-3 max-w-sm text-sm leading-relaxed text-primary-foreground/70">
                        Bagikan cerita secara anonim, atau bicara empat mata dengan konselor yang siap mendengarkan.
                    </p>

                    <a href="{{ route('home') }}" class="mt-7 inline-flex items-center justify-center gap-2 rounded-full bg-secondary px-5 py-2.5 text-sm font-bold text-secondary-foreground transition hover:opacity-90">
                        Lihat beranda
                        <x-icon name="arrow-right" :size="16" />
                    </a>
                </div>

                <div class="mt-10 grid grid-cols-3 gap-2 rounded-2xl border border-primary-foreground/15 p-4">
                    @foreach ([['Anonim', 'cerita'], ['Privat', 'konseling'], ['Lokal', 'proses']] as [$value, $label])
                        <div>
                            <p class="text-lg font-semibold">{{ $value }}</p>
                            <p class="text-[0.72rem] uppercase tracking-[0.12em] text-primary-foreground/60">{{ $label }}</p>
                        </div>
                    @endforeach
                </div>

                <p class="mt-6 text-[0.72rem] leading-relaxed text-primary-foreground/50">
                    Kawan Tenang bukan layanan gawat darurat. Jika kamu dalam bahaya, hubungi 119 ext. 8 atau 112.
                </p>
            </aside>

            {{-- Form panel --}}
            <main class="flex min-w-0 flex-col px-5 py-6 sm:px-9 sm:py-8">
                <div class="flex items-center justify-between gap-3">
                    <a href="{{ route('home') }}" class="flex items-center lg:hidden">
                        <img src="/assets/kawan-tenang-logo.png" alt="Kawan Tenang" width="76" height="37" class="h-10 w-auto">
                    </a>

                    <div class="ml-auto flex items-center gap-2">
                        <a href="{{ route('home') }}" class="hidden text-xs font-medium text-muted-foreground hover:text-foreground sm:block">Kembali ke beranda</a>
                        <button type="button" class="icon-btn" onclick="window.toggleTheme()" aria-label="Ganti tema">
                            <x-icon name="sun" :size="18" class="hidden dark:block" />
                            <x-icon name="moon" :size="18" class="dark:hidden" />
                        </button>
                    </div>
                </div>

                <div class="mx-auto flex w-full max-w-md flex-1 flex-col justify-center py-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>
</html>
