@php
    $title = $__env->yieldContent('title');
    $role = $__env->yieldContent('role') ?: 'pengguna';
@endphp

@php
    $primary = [
        'pengguna' => [
            ['label' => 'Beranda', 'href' => route('home'), 'icon' => 'home', 'active' => request()->routeIs('home')],
            ['label' => 'Bubble', 'href' => route('bubble'), 'icon' => 'bubble', 'active' => request()->routeIs('bubble')],
            ['label' => 'Konseling', 'href' => route('konseling.index'), 'icon' => 'chat', 'active' => request()->routeIs('konseling.*')],
            ['label' => 'Dasbor', 'href' => route('dasbor'), 'icon' => 'chart', 'active' => request()->routeIs('dasbor')],
        ],
        'konselor' => [
            ['label' => 'Dasbor', 'href' => route('konselor.dasbor'), 'icon' => 'chart', 'active' => request()->routeIs('konselor.dasbor')],
            ['label' => 'Sesi', 'href' => route('konselor.sesi'), 'icon' => 'chat', 'active' => request()->routeIs('konselor.sesi*')],
        ],
        'admin' => [
            ['label' => 'Dasbor', 'href' => route('admin.dasbor'), 'icon' => 'chart', 'active' => request()->routeIs('admin.dasbor')],
            ['label' => 'Pengguna', 'href' => route('admin.pengguna'), 'icon' => 'users', 'active' => request()->routeIs('admin.pengguna')],
            ['label' => 'Laporan', 'href' => route('admin.laporan'), 'icon' => 'flag', 'active' => request()->routeIs('admin.laporan')],
            ['label' => 'Verifikasi', 'href' => route('admin.verifikasi'), 'icon' => 'shield', 'active' => request()->routeIs('admin.verifikasi')],
        ],
    ][$role];

    $profileUrl = ['pengguna' => route('profil'), 'konselor' => route('konselor.profil'), 'admin' => route('admin.profil')][$role];

    $account = $role === 'pengguna'
        ? [
            ['label' => 'Verifikasi', 'href' => route('verifikasi'), 'icon' => 'shield', 'active' => request()->routeIs('verifikasi')],
            ['label' => 'Profil', 'href' => $profileUrl, 'icon' => 'user', 'active' => request()->routeIs('profil')],
        ]
        : [
            ['label' => 'Profil', 'href' => $profileUrl, 'icon' => 'user', 'active' => request()->routeIs('*.profil')],
        ];

    $dock = [...$primary, ['label' => 'Profil', 'href' => $profileUrl, 'icon' => 'user', 'active' => request()->routeIs('*.profil') ?: request()->routeIs('profil')]];
    $dock = array_slice($dock, 0, 5);

    $roleLabel = ['pengguna' => 'Pengguna', 'konselor' => 'Konselor', 'admin' => 'Administrator'][$role] ?? 'Pengguna';
    $homeUrl = ['pengguna' => route('home'), 'konselor' => route('konselor.dasbor'), 'admin' => route('admin.dasbor')][$role];
@endphp

<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#ECEEE9">
    <title>{{ $title ? $title . ' — Kawan Tenang' : 'Kawan Tenang' }}</title>
    <meta name="description" content="Kawan Tenang adalah ruang aman untuk bercerita secara anonim dan berkonseling privat dengan konselor.">
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
    <a href="#konten" class="sr-only rounded-full bg-primary px-4 py-2 font-semibold text-primary-foreground focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50">
        Lewati ke konten utama
    </a>

    <div class="mx-auto flex min-h-screen w-full items-start gap-4 p-3 sm:p-4 xl:gap-5 xl:p-5">
        {{-- Desktop sidebar --}}
        <aside class="sticky top-5 hidden h-[calc(100dvh-2.5rem)] w-[268px] shrink-0 flex-col overflow-y-auto rounded-[1.75rem] bg-card px-4 py-5 lg:flex no-scrollbar neu">
            <a href="{{ $homeUrl }}" class="flex flex-col gap-2 px-2">
                <img src="/assets/kawan-tenang-logo.png" alt="Kawan Tenang" width="144" height="70" class="h-auto w-36">
                <span class="label-cap block text-[0.65rem]">{{ $roleLabel }}</span>
            </a>

            <p class="label-cap mt-7 mb-2 px-3">Menu utama</p>

            <nav class="flex flex-col gap-1" aria-label="Navigasi utama">
                @foreach ($primary as $item)
                    <a
                        href="{{ $item['href'] }}"
                        @class([
                            'flex items-center gap-3 rounded-2xl px-3.5 py-2.5 text-sm font-medium transition',
                            'bg-primary text-primary-foreground' => $item['active'],
                            'text-muted-foreground hover:bg-muted hover:text-foreground' => ! $item['active'],
                        ])
                        @if ($item['active']) aria-current="page" @endif
                    >
                        <x-icon :name="$item['icon']" :size="18" />
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <p class="label-cap mt-6 mb-2 px-3">Akun</p>

            <nav class="flex flex-col gap-1" aria-label="Navigasi akun">
                @foreach ($account as $item)
                    <a
                        href="{{ $item['href'] }}"
                        @class([
                            'flex items-center gap-3 rounded-2xl px-3.5 py-2.5 text-sm font-medium transition',
                            'bg-primary text-primary-foreground' => $item['active'],
                            'text-muted-foreground hover:bg-muted hover:text-foreground' => ! $item['active'],
                        ])
                        @if ($item['active']) aria-current="page" @endif
                    >
                        <x-icon :name="$item['icon']" :size="18" />
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <a href="{{ $profileUrl }}" class="card-ink mt-auto flex items-center gap-3 rounded-[1.2rem] px-3.5 py-3.5">
                <span class="flex size-10 items-center justify-center rounded-full bg-secondary text-secondary-foreground">
                    <x-icon name="user" :size="18" />
                </span>
                <span class="min-w-0 leading-tight">
                    <span class="block truncate text-sm font-semibold">{{ auth()->user()?->name }}</span>
                    <span class="block text-[0.75rem] text-primary-foreground/60">{{ $roleLabel }}</span>
                </span>
            </a>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col pb-24 lg:pb-0">
            {{-- Top bar --}}
            <header class="sticky top-0 z-30 -mx-3 mb-4 px-3 pt-2 pb-2 sm:-mx-4 sm:px-4 xl:-mx-5 xl:px-5">
                <div class="flex items-center gap-3 rounded-full bg-background/80 py-1 backdrop-blur-xl">
                    <a href="{{ $homeUrl }}" class="flex items-center lg:hidden">
                        <img src="/assets/kawan-tenang-logo.png" alt="Kawan Tenang" width="84" height="41" class="h-11 w-auto">
                    </a>

                    <span class="hidden items-center gap-2 rounded-full bg-card px-3 py-1.5 text-[0.75rem] font-semibold text-muted-foreground lg:inline-flex">
                        <span class="size-1.5 rounded-full bg-secondary-foreground/60"></span>
                        {{ $roleLabel }}
                    </span>

                    <div class="ml-auto flex items-center gap-2">
                        <label class="hidden items-center gap-2 rounded-full bg-card px-4 py-2 text-sm text-muted-foreground md:flex">
                            <x-icon name="search" :size="17" />
                            <input
                                type="search"
                                placeholder="{{ ['admin' => 'Cari pengguna', 'konselor' => 'Cari sesi'][$role] ?? 'Cari cerita' }}"
                                aria-label="Cari"
                                class="w-40 bg-transparent text-sm text-foreground placeholder:text-muted-foreground focus:outline-none lg:w-52"
                            >
                        </label>

                        <button type="button" class="icon-btn" onclick="window.toggleTheme()" aria-label="Ganti tema">
                            <x-icon name="sun" :size="18" class="hidden dark:block" />
                            <x-icon name="moon" :size="18" class="dark:hidden" />
                        </button>

                        <button type="button" class="icon-btn relative" aria-label="Notifikasi">
                            <x-icon name="bell" :size="18" />
                            <span class="absolute top-2.5 right-2.5 size-2 rounded-full bg-destructive"></span>
                        </button>

                        <a href="{{ $profileUrl }}" class="flex size-11 items-center justify-center rounded-full bg-secondary text-secondary-foreground" aria-label="Profil saya">
                            <x-icon name="user" :size="19" />
                        </a>

                        @auth
                            <form method="POST" action="{{ route('keluar') }}">
                                @csrf
                                <button type="submit" class="icon-btn" aria-label="Keluar">
                                    <x-icon name="logout" :size="18" />
                                </button>
                            </form>
                        @endauth
                    </div>
                </div>
            </header>

            <main id="konten" class="w-full flex-1">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Mobile dock --}}
    <nav class="fixed inset-x-3 bottom-3 z-40 lg:hidden" style="padding-bottom: env(safe-area-inset-bottom)" aria-label="Navigasi bawah">
        <div class="rounded-[1.5rem] bg-card/95 p-1.5 backdrop-blur-xl neu">
            <div class="grid" style="grid-template-columns: repeat({{ count($dock) }}, minmax(0, 1fr))">
                @foreach ($dock as $item)
                    <a
                        href="{{ $item['href'] }}"
                        @class([
                            'flex flex-col items-center gap-0.5 rounded-[1.15rem] px-1 py-2 text-[0.7rem] font-medium transition',
                            'bg-primary text-primary-foreground' => $item['active'],
                            'text-muted-foreground' => ! $item['active'],
                        ])
                        @if ($item['active']) aria-current="page" @endif
                    >
                        <x-icon :name="$item['icon']" :size="20" />
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </nav>
</body>
</html>
