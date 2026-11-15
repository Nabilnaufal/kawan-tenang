@props(['title' => null, 'role' => 'pengguna'])

@php
    $menus = [
        'pengguna' => [
            ['label' => 'Beranda', 'href' => route('home'), 'icon' => 'home', 'active' => request()->routeIs('home')],
            ['label' => 'Bubble Publik', 'href' => route('bubble'), 'icon' => 'bubble', 'active' => request()->routeIs('bubble')],
            ['label' => 'Konseling', 'href' => route('konseling.index'), 'icon' => 'chat', 'active' => request()->routeIs('konseling.*')],
            ['label' => 'Verifikasi', 'href' => route('verifikasi'), 'icon' => 'shield', 'active' => request()->routeIs('verifikasi')],
            ['label' => 'Dasbor', 'href' => route('dasbor'), 'icon' => 'chart', 'active' => request()->routeIs('dasbor')],
        ],
        'konselor' => [
            ['label' => 'Dasbor', 'href' => route('konselor.dasbor'), 'icon' => 'chart', 'active' => request()->routeIs('konselor.dasbor')],
            ['label' => 'Sesi', 'href' => route('konselor.sesi'), 'icon' => 'chat', 'active' => request()->routeIs('konselor.sesi*')],
            ['label' => 'Profil', 'href' => route('konselor.profil'), 'icon' => 'user', 'active' => request()->routeIs('konselor.profil')],
        ],
        'admin' => [
            ['label' => 'Dasbor', 'href' => route('admin.dasbor'), 'icon' => 'chart', 'active' => request()->routeIs('admin.dasbor')],
            ['label' => 'Pengguna', 'href' => route('admin.pengguna'), 'icon' => 'users', 'active' => request()->routeIs('admin.pengguna')],
            ['label' => 'Laporan', 'href' => route('admin.laporan'), 'icon' => 'flag', 'active' => request()->routeIs('admin.laporan')],
            ['label' => 'Verifikasi', 'href' => route('admin.verifikasi'), 'icon' => 'shield', 'active' => request()->routeIs('admin.verifikasi')],
            ['label' => 'Profil', 'href' => route('admin.profil'), 'icon' => 'user', 'active' => request()->routeIs('admin.profil')],
        ],
    ];

    $nav = $menus[$role] ?? $menus['pengguna'];
    $roleLabel = ['pengguna' => 'Pengguna', 'konselor' => 'Konselor', 'admin' => 'Administrator'][$role] ?? 'Pengguna';
    $profileUrl = ['pengguna' => route('profil'), 'konselor' => route('konselor.profil'), 'admin' => route('admin.profil')][$role];
@endphp

<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#F3F1EA">
    <title>{{ $title ? $title . ' — Kawan Tenang' : 'Kawan Tenang' }}</title>
    <meta name="description" content="Kawan Tenang adalah ruang aman untuk bercerita secara anonim dan berkonseling privat dengan konselor.">
    <link rel="icon" href="/favicon.ico">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full">
    <a href="#konten" class="sr-only rounded-lg bg-primary px-4 py-2 font-semibold text-primary-foreground focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50">
        Lewati ke konten utama
    </a>

    <div class="flex min-h-screen w-full">
        {{-- Desktop sidebar --}}
        <aside class="sticky top-0 hidden h-screen w-[236px] shrink-0 flex-col gap-7 px-5 py-7 xl:w-[264px] xl:px-6 lg:flex">
            <a href="{{ $role === 'admin' ? route('admin.dasbor') : ($role === 'konselor' ? route('konselor.dasbor') : route('home')) }}" class="flex items-center gap-3">
                <span class="flex size-10 items-center justify-center rounded-2xl bg-primary text-primary-foreground neu-primary">
                    <x-icon name="heart" :size="20" />
                </span>
                <span class="leading-tight">
                    <span class="block font-semibold tracking-tight">Kawan Tenang</span>
                    <span class="block text-[11px] text-muted-foreground">{{ $roleLabel }}</span>
                </span>
            </a>

            <nav class="flex flex-col gap-1.5" aria-label="Navigasi utama">
                @foreach ($nav as $item)
                    <a
                        href="{{ $item['href'] }}"
                        @class([
                            'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                            'bg-primary text-primary-foreground neu-primary' => $item['active'],
                            'text-muted-foreground hover:bg-card hover:text-foreground' => ! $item['active'],
                        ])
                        @if ($item['active']) aria-current="page" @endif
                    >
                        <x-icon :name="$item['icon']" :size="19" />
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="mt-auto">
                <a href="{{ $profileUrl }}" class="flex items-center gap-3 rounded-xl px-2 py-2 hover:bg-card">
                    <span class="flex size-9 items-center justify-center rounded-full bg-secondary/60 text-secondary-foreground">
                        <x-icon name="user" :size="17" />
                    </span>
                    <span class="min-w-0 leading-tight">
                        <span class="block truncate text-sm font-medium">Kawan Anonim</span>
                        <span class="block text-[11px] text-muted-foreground">{{ $roleLabel }}</span>
                    </span>
                    <x-icon name="chevron-right" :size="15" class="ml-auto text-muted-foreground" />
                </a>
            </div>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            {{-- Top bar --}}
            <header class="sticky top-0 z-30 border-b border-border/60 bg-background/85 backdrop-blur-xl">
                <div class="flex items-center gap-3 px-4 py-3 sm:px-6 xl:px-10">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 lg:hidden">
                        <span class="flex size-9 items-center justify-center rounded-xl bg-primary text-primary-foreground">
                            <x-icon name="heart" :size="18" />
                        </span>
                        <span class="text-sm font-semibold tracking-tight">Kawan Tenang</span>
                    </a>

                    <h1 class="hidden text-base font-semibold tracking-tight lg:block">{{ $title ?? 'Kawan Tenang' }}</h1>

                    <div class="ml-auto flex items-center gap-2">
                        <label class="hidden items-center gap-2 rounded-xl bg-input px-3 py-2 text-sm text-muted-foreground neu-inset sm:flex">
                            <x-icon name="search" :size="17" />
                            <input
                                type="search"
                                placeholder="{{ $role === 'admin' ? 'Cari pengguna' : 'Cari cerita atau konselor' }}"
                                aria-label="Cari"
                                class="w-44 bg-transparent text-sm text-foreground placeholder:text-muted-foreground focus:outline-none lg:w-56"
                            >
                        </label>

                        <button
                            type="button"
                            class="relative flex size-10 items-center justify-center rounded-xl bg-card text-muted-foreground neu-sm transition hover:text-foreground"
                            aria-label="Notifikasi"
                        >
                            <x-icon name="bell" :size="18" />
                            <span class="absolute top-2.5 right-3 size-1.5 rounded-full bg-destructive"></span>
                        </button>

                        <a
                            href="{{ $profileUrl }}"
                            class="flex size-10 items-center justify-center rounded-xl bg-secondary/60 text-secondary-foreground transition hover:bg-secondary"
                            aria-label="Profil saya"
                        >
                            <x-icon name="user" :size="18" />
                        </a>
                    </div>
                </div>
            </header>

            <main id="konten" class="w-full flex-1 px-4 pt-5 pb-28 sm:px-6 lg:px-8 lg:pt-7 lg:pb-10 xl:px-10">
                {{ $slot }}
            </main>
        </div>
    </div>

    {{-- Mobile bottom navigation --}}
    <nav
        class="fixed inset-x-0 bottom-0 z-40 border-t border-border/70 bg-card/95 backdrop-blur-xl lg:hidden"
        style="padding-bottom: env(safe-area-inset-bottom)"
        aria-label="Navigasi bawah"
    >
        <div class="grid" style="grid-template-columns: repeat({{ count($nav) }}, minmax(0, 1fr))">
            @foreach ($nav as $item)
                <a
                    href="{{ $item['href'] }}"
                    @class([
                        'flex flex-col items-center gap-1 py-2 text-[10px] font-medium transition',
                        'text-foreground' => $item['active'],
                        'text-muted-foreground' => ! $item['active'],
                    ])
                    @if ($item['active']) aria-current="page" @endif
                >
                    <span @class([
                        'flex h-7 w-11 items-center justify-center rounded-full transition',
                        'bg-primary text-primary-foreground neu-primary' => $item['active'],
                    ])>
                        <x-icon :name="$item['icon']" :size="18" />
                    </span>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>
    </nav>
</body>
</html>
