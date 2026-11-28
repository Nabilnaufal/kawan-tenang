@php($title = $__env->yieldContent('title'))

<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#ECEEE9">
    <meta name="description" content="Kawan Tenang adalah ruang aman untuk bercerita secara anonim dan berkonseling privat dengan konselor.">
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
<body class="min-h-full overflow-x-hidden">
    <a href="#konten" class="sr-only rounded-full bg-primary px-4 py-2 font-semibold text-primary-foreground focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-[60]">
        Lewati ke konten utama
    </a>

    <div class="relative min-h-screen">
        <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-[44rem] overflow-hidden" aria-hidden="true">
            <div class="absolute -top-48 left-1/2 size-[34rem] -translate-x-1/2 rounded-full bg-secondary/20 blur-3xl"></div>
            <div class="absolute top-48 -right-36 size-72 rounded-full bg-accent/80 blur-3xl"></div>
            <div class="absolute top-72 -left-32 size-72 rounded-full bg-secondary/10 blur-3xl"></div>
        </div>

        <header x-data="{ open: false }" class="fixed inset-x-0 top-0 z-50 px-3 pt-3 sm:px-6 sm:pt-5">
            <nav class="relative mx-auto flex max-w-6xl items-center gap-3 rounded-full bg-card/85 px-3 py-2.5 backdrop-blur-xl neu-sm sm:px-4" aria-label="Navigasi utama">
                <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-2.5" aria-label="Kawan Tenang">
                    <img src="/assets/kawan-tenang-logo.png" alt="Kawan Tenang" width="118" height="58" class="h-10 w-auto sm:h-11">
                </a>

                <div class="hidden items-center gap-6 pl-5 text-sm font-medium text-muted-foreground md:flex">
                    <a href="#fitur" class="transition hover:text-foreground">Ruang kami</a>
                    <a href="#cara-kerja" class="transition hover:text-foreground">Cara kerja</a>
                    <a href="#privasi" class="transition hover:text-foreground">Privasi</a>
                </div>

                <div class="ml-auto flex items-center gap-2">
                    <button type="button" class="icon-btn" onclick="window.toggleTheme()" aria-label="Ganti tema">
                        <x-icon name="sun" :size="18" class="hidden dark:block" />
                        <x-icon name="moon" :size="18" class="dark:hidden" />
                    </button>
                    <a href="{{ route('masuk') }}" class="hidden rounded-full px-4 py-2 text-sm font-semibold text-muted-foreground transition hover:bg-muted hover:text-foreground sm:inline-flex">Masuk</a>
                    <a href="{{ route('daftar') }}" class="btn-lime hidden px-4 py-2 text-sm sm:inline-flex">Mulai sekarang <x-icon name="arrow-right" :size="15" /></a>
                    <button type="button" class="flex size-11 items-center justify-center rounded-full bg-primary text-primary-foreground md:hidden" @click="open = ! open" :aria-expanded="open.toString()" aria-label="Buka menu">
                        <span class="flex w-4 flex-col gap-1" aria-hidden="true">
                            <span class="h-0.5 w-full rounded-full bg-current transition" :class="open ? 'translate-y-1.5 rotate-45' : ''"></span>
                            <span class="h-0.5 w-full rounded-full bg-current transition" :class="open ? 'opacity-0' : ''"></span>
                            <span class="h-0.5 w-full rounded-full bg-current transition" :class="open ? '-translate-y-1.5 -rotate-45' : ''"></span>
                        </span>
                    </button>
                </div>

                <div x-show="open" x-cloak x-transition.opacity class="absolute inset-x-0 top-[calc(100%+0.65rem)] rounded-[1.35rem] bg-card p-2 neu-sm md:hidden">
                    <div class="grid gap-1 text-sm font-semibold">
                        <a href="#fitur" @click="open = false" class="rounded-xl px-4 py-3 text-muted-foreground hover:bg-muted hover:text-foreground">Ruang kami</a>
                        <a href="#cara-kerja" @click="open = false" class="rounded-xl px-4 py-3 text-muted-foreground hover:bg-muted hover:text-foreground">Cara kerja</a>
                        <a href="#privasi" @click="open = false" class="rounded-xl px-4 py-3 text-muted-foreground hover:bg-muted hover:text-foreground">Privasi</a>
                    </div>
                    <div class="mt-2 grid grid-cols-2 gap-2 border-t border-border/60 pt-2">
                        <a href="{{ route('masuk') }}" class="btn-soft px-3 py-2.5 text-sm">Masuk</a>
                        <a href="{{ route('daftar') }}" class="btn-lime px-3 py-2.5 text-sm">Daftar</a>
                    </div>
                </div>
            </nav>
        </header>

        <main id="konten">
            @yield('content')
        </main>

        <footer class="mx-auto max-w-6xl px-5 pb-8 pt-14 sm:px-8 lg:px-10">
            <div class="flex flex-col gap-5 border-t border-border/70 pt-5 text-xs text-muted-foreground sm:flex-row sm:items-center sm:justify-between">
                <p>© {{ date('Y') }} Kawan Tenang. Ruang aman untuk bertumbuh.</p>
                <p class="flex items-center gap-1.5"><x-icon name="lock" :size="13" /> Anonim secara default</p>
            </div>
        </footer>
    </div>
</body>
</html>
