@extends('layouts.landing')
@section('title', 'Ruang aman untuk ceritamu')
@section('content')
    <section class="mx-auto max-w-6xl scroll-mt-28 px-5 pb-14 pt-36 sm:px-8 sm:pb-20 sm:pt-44 lg:px-10 lg:pt-48">
        <div class="grid items-center gap-12 lg:grid-cols-[1.02fr_.98fr] lg:gap-16">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full border border-border bg-card/75 px-3.5 py-2 text-xs font-semibold text-muted-foreground neu-inset">
                    <span class="size-2 rounded-full bg-success"></span>
                    Ruang aman & anonim
                </span>

                <h1 class="mt-6 max-w-3xl text-4xl leading-[1.08] font-semibold tracking-tight sm:text-5xl lg:text-[4.15rem]">
                    Kamu tidak harus terlihat kuat <span class="text-success">setiap hari.</span>
                </h1>
                <p class="mt-5 max-w-xl text-base leading-relaxed text-muted-foreground sm:text-lg">
                    Kawan Tenang adalah tempat untuk bercerita tanpa takut dikenali, menemukan dukungan, dan bicara privat dengan konselor yang siap mendengarkan.
                </p>

                <div class="mt-7 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('daftar') }}" class="btn-lime w-full px-6 py-3 text-sm sm:w-auto">
                        Mulai bercerita
                        <x-icon name="arrow-right" :size="16" />
                    </a>
                    <a href="{{ route('masuk') }}" class="btn-soft w-full px-6 py-3 text-sm sm:w-auto">
                        Sudah punya akun? Masuk
                    </a>
                </div>

                <div class="mt-8 flex flex-wrap gap-x-5 gap-y-2 text-xs font-medium text-muted-foreground">
                    <span class="flex items-center gap-1.5"><x-icon name="check-circle" :size="15" class="text-success" /> Identitas publik anonim</span>
                    <span class="flex items-center gap-1.5"><x-icon name="check-circle" :size="15" class="text-success" /> Percakapan privat</span>
                    <span class="flex items-center gap-1.5"><x-icon name="check-circle" :size="15" class="text-success" /> Kamu tetap memegang kendali</span>
                </div>
            </div>

            <div class="relative mx-auto w-full max-w-[31rem] lg:ml-auto">
                <div class="absolute -top-7 -right-3 z-10 rounded-full bg-secondary px-4 py-2 text-xs font-bold text-secondary-foreground neu-sm sm:-right-8">
                    Kamu aman di sini <span class="ml-1">✦</span>
                </div>
                <div class="card-ink relative overflow-hidden rounded-[2rem] p-5 neu sm:p-7">
                    <div class="pointer-events-none absolute -top-20 -right-16 size-64 rounded-full bg-secondary/20 blur-3xl" aria-hidden="true"></div>
                    <div class="pointer-events-none absolute -bottom-24 -left-16 size-64 rounded-full bg-white/10 blur-3xl" aria-hidden="true"></div>

                    <div class="relative flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold tracking-[0.15em] text-primary-foreground/50 uppercase">Kawan Tenang</p>
                            <p class="mt-1 text-lg font-semibold">Hari ini, pelan-pelan saja.</p>
                        </div>
                        <span class="flex size-11 items-center justify-center rounded-full bg-secondary text-secondary-foreground">
                            <x-icon name="heart" :size="19" />
                        </span>
                    </div>

                    <div class="relative mt-8 space-y-3">
                        <div class="ml-auto max-w-[82%] rounded-[1.35rem] rounded-br-md bg-secondary px-4 py-3 text-sm font-medium text-secondary-foreground">
                            Aku sedang belajar menerima perasaanku.
                        </div>
                        <div class="flex max-w-[88%] items-start gap-2.5 rounded-[1.35rem] rounded-bl-md bg-primary-foreground/10 px-4 py-3 text-sm leading-relaxed text-primary-foreground/85">
                            <span class="mt-0.5 flex size-6 shrink-0 items-center justify-center rounded-full bg-primary-foreground/15">
                                <x-icon name="chat" :size="13" />
                            </span>
                            Tidak apa-apa. Ceritamu boleh dimulai dari mana saja.
                        </div>
                    </div>

                    <div class="relative mt-8 grid grid-cols-3 gap-2 border-t border-primary-foreground/15 pt-4">
                        @foreach ([['value' => 'Anonim', 'label' => 'identitas'], ['value' => 'Privat', 'label' => 'konseling'], ['value' => 'Lokal', 'label' => 'pemrosesan'] ] as $item)
                            <div>
                                <p class="text-sm font-semibold">{{ $item['value'] }}</p>
                                <p class="mt-0.5 text-[0.65rem] tracking-[0.12em] text-primary-foreground/50 uppercase">{{ $item['label'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="absolute -bottom-6 -left-3 hidden rounded-2xl bg-card px-4 py-3 text-xs font-semibold neu-sm sm:flex sm:items-center sm:gap-2 sm:-left-8">
                    <span class="flex size-7 items-center justify-center rounded-full bg-secondary text-secondary-foreground"><x-icon name="shield" :size="14" /></span>
                    Privasi adalah default
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-5 pb-16 sm:px-8 lg:px-10">
        <div class="grid overflow-hidden rounded-[1.7rem] bg-card neu-sm sm:grid-cols-3">
            @foreach ([['value' => '3', 'label' => 'ruang pendampingan'], ['value' => '0', 'label' => 'identitas publik'], ['value' => '1×1', 'label' => 'dukungan yang lebih personal']] as $item)
                <div class="border-b border-border/70 px-5 py-5 last:border-0 sm:border-r sm:border-b-0 sm:px-7">
                    <p class="text-2xl font-semibold tracking-tight">{{ $item['value'] }}</p>
                    <p class="mt-1 text-xs text-muted-foreground">{{ $item['label'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <section id="fitur" class="scroll-mt-28 bg-card/55 py-16 sm:py-20">
        <div class="mx-auto max-w-6xl px-5 sm:px-8 lg:px-10">
            <div class="max-w-2xl">
                <span class="label-cap">Ruang untukmu</span>
                <h2 class="mt-3 text-3xl leading-tight font-semibold tracking-tight sm:text-4xl">Pilih cara yang paling nyaman untuk mulai.</h2>
                <p class="mt-3 text-sm leading-relaxed text-muted-foreground sm:text-base">Tidak ada satu cara yang paling benar untuk mencari dukungan. Mulai dari yang terasa paling ringan.</p>
            </div>

            <div class="mt-8 grid gap-4 md:grid-cols-3">
                @foreach ([
                    ['icon' => 'bubble', 'number' => '01', 'title' => 'Bubble Publik', 'text' => 'Bagikan cerita dan saling menguatkan dengan identitas yang tetap anonim.', 'tone' => 'bg-secondary text-secondary-foreground'],
                    ['icon' => 'chat', 'number' => '02', 'title' => 'Konseling Privat', 'text' => 'Bicara empat mata dengan konselor dalam ruang yang hanya untuk kalian.', 'tone' => 'bg-primary text-primary-foreground'],
                    ['icon' => 'shield', 'number' => '03', 'title' => 'Ruang yang Terjaga', 'text' => 'Moderasi dan verifikasi membantu komunitas tetap aman untuk semua.', 'tone' => 'bg-accent text-accent-foreground'],
                ] as $feature)
                    <article class="group rounded-[1.5rem] bg-card p-5 transition hover:-translate-y-1 neu-sm sm:p-6">
                        <div class="flex items-center justify-between">
                            <span class="flex size-12 items-center justify-center rounded-2xl {{ $feature['tone'] }}"><x-icon :name="$feature['icon']" :size="21" /></span>
                            <span class="text-xs font-bold text-muted-foreground">{{ $feature['number'] }}</span>
                        </div>
                        <h3 class="mt-7 text-lg font-semibold">{{ $feature['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-muted-foreground">{{ $feature['text'] }}</p>
                        <span class="mt-7 flex items-center gap-2 text-xs font-bold text-foreground">Pelajari lebih lanjut <x-icon name="arrow-right" :size="14" class="transition group-hover:translate-x-1" /></span>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section id="cara-kerja" class="mx-auto max-w-6xl scroll-mt-28 px-5 py-16 sm:px-8 sm:py-20 lg:px-10">
        <div class="grid gap-10 lg:grid-cols-[.8fr_1.2fr] lg:items-start">
            <div>
                <span class="label-cap">Mulai dengan tenang</span>
                <h2 class="mt-3 text-3xl leading-tight font-semibold tracking-tight sm:text-4xl">Langkah kecil tetap berarti.</h2>
                <p class="mt-4 text-sm leading-relaxed text-muted-foreground sm:text-base">Kamu tidak perlu menyiapkan cerita yang sempurna. Cukup hadir sebagai dirimu sendiri.</p>
                <a href="{{ route('daftar') }}" class="btn-ink mt-6 px-5 py-3 text-sm">Buat akun gratis <x-icon name="arrow-right" :size="15" /></a>
            </div>

            <div class="space-y-3">
                @foreach ([
                    ['number' => '1', 'title' => 'Pilih ruang yang terasa aman', 'text' => 'Bercerita di Bubble atau mulai percakapan privat dengan konselor.'],
                    ['number' => '2', 'title' => 'Ceritakan secukupnya', 'text' => 'Kamu selalu boleh berhenti, kembali, dan mengatur batasanmu sendiri.'],
                    ['number' => '3', 'title' => 'Temukan dukungan', 'text' => 'Dapatkan respons dari komunitas atau pendamping yang siap mendengarkan.'],
                ] as $step)
                    <div class="flex items-start gap-4 rounded-[1.35rem] bg-card p-4 neu-sm sm:p-5">
                        <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-secondary text-sm font-bold text-secondary-foreground">{{ $step['number'] }}</span>
                        <div>
                            <h3 class="text-sm font-semibold sm:text-base">{{ $step['title'] }}</h3>
                            <p class="mt-1 text-xs leading-relaxed text-muted-foreground sm:text-sm">{{ $step['text'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="privasi" class="mx-auto max-w-6xl scroll-mt-28 px-5 pb-16 sm:px-8 sm:pb-20 lg:px-10">
        <div class="card-ink relative overflow-hidden rounded-[1.8rem] p-6 neu sm:p-9">
            <div class="pointer-events-none absolute -right-20 -bottom-28 size-80 rounded-full bg-secondary/15 blur-3xl" aria-hidden="true"></div>
            <div class="relative grid gap-8 lg:grid-cols-[.8fr_1.2fr] lg:items-center">
                <div>
                    <span class="inline-flex items-center gap-2 rounded-full border border-primary-foreground/20 px-3 py-1.5 text-xs font-semibold">
                        <x-icon name="lock" :size="13" />
                        Privasi sebagai dasar
                    </span>
                    <h2 class="mt-5 text-2xl leading-tight font-semibold tracking-tight sm:text-3xl">Kamu berhak merasa aman saat mencari bantuan.</h2>
                </div>
                <div class="grid gap-3 sm:grid-cols-3">
                    @foreach ([['icon' => 'user', 'title' => 'Anonim', 'text' => 'Nama dan identitasmu tidak tampil di ruang publik.'], ['icon' => 'lock', 'title' => 'Privat', 'text' => 'Percakapan konseling hanya untuk pihak yang terlibat.'], ['icon' => 'shield', 'title' => 'Terkendali', 'text' => 'Kamu bisa mengelola data dan persetujuanmu.']] as $item)
                        <div class="rounded-2xl border border-primary-foreground/15 bg-primary-foreground/5 p-4">
                            <x-icon :name="$item['icon']" :size="18" class="text-secondary" />
                            <h3 class="mt-4 text-sm font-semibold">{{ $item['title'] }}</h3>
                            <p class="mt-1.5 text-xs leading-relaxed text-primary-foreground/60">{{ $item['text'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-5 pb-4 sm:px-8 lg:px-10">
        <div class="flex flex-col items-start gap-4 rounded-[1.4rem] border-2 border-[var(--card-border)] bg-secondary/25 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <p class="flex items-start gap-2 text-xs leading-relaxed sm:items-center"><x-icon name="life-buoy" :size="17" class="mt-0.5 shrink-0 sm:mt-0" /><span><strong>Butuh bantuan segera?</strong> Kawan Tenang bukan layanan darurat. Hubungi <a href="tel:119" class="font-semibold underline underline-offset-2">119 ext. 8</a> atau <a href="tel:112" class="font-semibold underline underline-offset-2">112</a>.</span></p>
        </div>
    </section>
@endsection
