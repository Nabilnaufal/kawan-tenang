@extends('layouts.landing')
@section('title', 'Ruang aman untuk ceritamu')
@section('content')
    {{-- Hero --}}
    <section class="mx-auto max-w-[86rem] px-5 pb-16 pt-32 sm:px-8 sm:pt-40 lg:px-10 lg:pt-44">
        <div class="grid items-center gap-14 lg:grid-cols-[1.04fr_.96fr] lg:gap-16">
            <div data-reveal>
                <span class="inline-flex items-center gap-2 rounded-full border border-border bg-card/75 px-3.5 py-2 text-xs font-semibold text-muted-foreground neu-inset">
                    <span class="size-2 rounded-full bg-success"></span>
                    Ruang aman &amp; anonim
                </span>

                <h1 class="mt-6 max-w-3xl text-4xl leading-[1.06] font-semibold tracking-tight sm:text-5xl lg:text-[4rem]">
                    Kamu tidak harus terlihat kuat <span class="text-success whitespace-nowrap">setiap hari.</span>
                </h1>
                <p class="mt-5 max-w-xl text-sm leading-relaxed text-muted-foreground sm:text-base">
                    Kawan Tenang adalah tempat untuk bercerita tanpa takut dikenali, menemukan dukungan, dan bicara privat dengan konselor yang siap mendengarkan.
                </p>

                <div class="mt-7 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('daftar') }}" class="btn-lime w-full px-6 py-3 text-sm whitespace-nowrap sm:w-auto">
                        Mulai bercerita
                        <x-icon name="arrow-right" :size="16" />
                    </a>
                    <a href="{{ route('masuk') }}" class="btn-soft w-full px-6 py-3 text-sm whitespace-nowrap sm:w-auto">
                        Sudah punya akun? Masuk
                    </a>
                </div>

                <div class="mt-8 flex flex-wrap gap-x-5 gap-y-2 text-[0.7rem] font-medium text-muted-foreground sm:text-xs">
                    <span class="flex items-center gap-1.5"><x-icon name="check-circle" :size="15" class="text-success" /> Identitas publik anonim</span>
                    <span class="flex items-center gap-1.5"><x-icon name="check-circle" :size="15" class="text-success" /> Percakapan privat</span>
                    <span class="flex items-center gap-1.5"><x-icon name="check-circle" :size="15" class="text-success" /> Kamu tetap memegang kendali</span>
                </div>
            </div>

            {{-- Interactive preview --}}
            <div
                class="relative mx-auto w-full max-w-[34rem]"
                data-reveal
                style="--reveal-delay: 140ms"
                x-data="{
                    pesan: [{ dari: 'konselor', teks: 'Halo, terima kasih sudah mampir. Apa yang paling terasa berat hari ini?' }],
                    teks: '',
                    mengetik: false,
                    balasan: [
                        'Itu terdengar melelahkan. Kamu tidak harus menyelesaikannya sendirian.',
                        'Tidak apa-apa memulai dari hal kecil. Mau ceritakan sedikit lagi?',
                        'Perasaanmu valid. Terima kasih sudah berani menuliskannya di sini.',
                    ],
                    indeks: 0,
                    kirim() {
                        const isi = this.teks.trim();
                        if (! isi || this.mengetik) return;
                        this.pesan.push({ dari: 'kamu', teks: isi });
                        this.teks = '';
                        this.$nextTick(() => { this.$refs.ruang.scrollTop = this.$refs.ruang.scrollHeight; });
                        this.mengetik = true;
                        const jawab = this.balasan[this.indeks++ % this.balasan.length];
                        setTimeout(() => {
                            this.pesan.push({ dari: 'konselor', teks: jawab });
                            this.mengetik = false;
                            this.$nextTick(() => { this.$refs.ruang.scrollTop = this.$refs.ruang.scrollHeight; });
                        }, 900);
                    },
                    contoh(teks) {
                        this.teks = teks;
                        this.kirim();
                    }
                }"
            >
                <span class="animate-bob absolute -top-3 right-4 z-10 inline-flex items-center gap-1.5 rounded-full bg-secondary px-3.5 py-1.5 text-[0.68rem] font-bold text-secondary-foreground neu-sm">
                    Kamu aman di sini
                    <span aria-hidden="true">✦</span>
                </span>

                <div class="card-ink relative overflow-hidden rounded-[2rem] p-4 neu sm:p-6">
                    <div class="pointer-events-none absolute -top-20 -right-16 size-64 rounded-full bg-secondary/20 blur-3xl" aria-hidden="true"></div>
                    <div class="pointer-events-none absolute -bottom-24 -left-16 size-64 rounded-full bg-white/10 blur-3xl" aria-hidden="true"></div>

                    <div class="relative flex items-center justify-between gap-3">
                        <div>
                            <p class="text-[0.65rem] font-semibold tracking-[0.15em] text-primary-foreground/50 uppercase">Ruang privat · contoh</p>
                            <p class="mt-1 text-base font-semibold sm:text-lg">Hari ini, pelan-pelan saja.</p>
                        </div>
                        <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-secondary text-secondary-foreground">
                            <x-icon name="heart" :size="18" />
                        </span>
                    </div>

                    <div class="relative mt-4 flex h-44 flex-col justify-end gap-2.5 overflow-y-auto pr-1 no-scrollbar" aria-live="polite" x-ref="ruang">
                        <template x-for="(item, i) in pesan" :key="i">
                            <div
                                :class="item.dari === 'kamu' ? 'ml-auto max-w-[85%] text-right' : 'max-w-[90%]'"
                                x-transition:enter="transition duration-300 ease-out"
                                x-transition:enter-start="opacity-0 translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                            >
                                <div
                                    :class="item.dari === 'kamu'
                                        ? 'rounded-[1.2rem] rounded-br-md bg-secondary px-3.5 py-2.5 text-xs font-medium text-secondary-foreground'
                                        : 'rounded-[1.2rem] rounded-bl-md bg-primary-foreground/10 px-3.5 py-2.5 text-xs leading-relaxed text-primary-foreground/85'"
                                    x-text="item.teks"
                                ></div>
                            </div>
                        </template>
                        <div x-show="mengetik" x-cloak class="flex items-center gap-1.5 rounded-full bg-primary-foreground/10 px-3 py-2 text-[0.65rem] text-primary-foreground/60">
                            <span class="size-1.5 animate-pulse rounded-full bg-secondary"></span>
                            <span class="size-1.5 animate-pulse rounded-full bg-secondary [animation-delay:150ms]"></span>
                            <span class="size-1.5 animate-pulse rounded-full bg-secondary [animation-delay:300ms]"></span>
                            Sedang menulis…
                        </div>
                    </div>

                    <div class="relative mt-3 flex flex-wrap gap-1.5">
                        @foreach (['Aku capek hari ini', 'Besok aku cemas', 'Aku cuma butuh didengar'] as $contoh)
                            <button type="button" @click="contoh('{{ $contoh }}')" class="rounded-full border border-primary-foreground/20 px-2.5 py-1 text-[0.62rem] font-medium text-primary-foreground/70 transition hover:border-secondary hover:text-secondary">
                                {{ $contoh }}
                            </button>
                        @endforeach
                    </div>

                    <form class="relative mt-3 flex items-center gap-2" @submit.prevent="kirim()">
                        <label for="contoh-pesan" class="sr-only">Tulis contoh pesan</label>
                        <input
                            id="contoh-pesan"
                            x-model="teks"
                            type="text"
                            maxlength="120"
                            placeholder="Tulis apa yang kamu rasakan…"
                            class="min-w-0 flex-1 rounded-xl bg-primary-foreground/10 px-3.5 py-2.5 text-xs text-primary-foreground placeholder:text-primary-foreground/40 focus:outline-none focus:ring-1 focus:ring-secondary"
                        >
                        <button type="submit" class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-secondary text-secondary-foreground transition hover:opacity-90" aria-label="Kirim contoh pesan" :disabled="mengetik">
                            <x-icon name="send" :size="16" />
                        </button>
                    </form>

                    <p class="relative mt-2.5 flex items-center gap-1.5 text-[0.62rem] text-primary-foreground/40">
                        <x-icon name="lock" :size="11" />
                        Ini contoh interaktif. Pesan tidak dikirim atau disimpan.
                    </p>
                </div>

                <div class="mt-3 flex flex-wrap items-center justify-between gap-2 rounded-2xl bg-card px-4 py-2.5 text-[0.68rem] font-semibold neu-sm">
                    <span class="flex items-center gap-1.5"><x-icon name="shield" :size="14" class="text-success" /> Privasi adalah default</span>
                    <span class="flex items-center gap-1.5 text-muted-foreground"><x-icon name="lock" :size="13" /> Enkripsi pesan</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Trust strip --}}
    <section class="mx-auto max-w-[86rem] px-5 pb-16 sm:px-8 lg:px-10">
        <div class="grid overflow-hidden rounded-[1.7rem] bg-card neu-sm sm:grid-cols-3">
            @foreach ([
                ['icon' => 'bubble', 'value' => '3', 'label' => 'ruang pendampingan'],
                ['icon' => 'user', 'value' => '0', 'label' => 'identitas publik'],
                ['icon' => 'chat', 'value' => '1×1', 'label' => 'dukungan yang lebih personal'],
            ] as $item)
                <div class="group flex items-center gap-3 border-b border-border/70 px-5 py-5 transition last:border-0 hover:bg-muted/40 sm:border-r sm:border-b-0 sm:px-7" data-reveal style="--reveal-delay: {{ $loop->index * 90 }}ms">
                    <span class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-secondary/40 text-foreground transition group-hover:bg-secondary">
                        <x-icon :name="$item['icon']" :size="16" />
                    </span>
                    <div>
                        <p class="text-2xl font-semibold tracking-tight">{{ $item['value'] }}</p>
                        <p class="mt-0.5 text-[0.68rem] text-muted-foreground">{{ $item['label'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Features --}}
    <section id="fitur" class="scroll-mt-28 bg-card/55 py-16 sm:py-20">
        <div class="mx-auto max-w-[86rem] px-5 sm:px-8 lg:px-10">
            <div class="max-w-2xl">
                <span class="label-cap">Ruang untukmu</span>
                <h2 class="mt-3 text-3xl leading-tight font-semibold tracking-tight sm:text-4xl">Pilih cara yang paling nyaman untuk mulai.</h2>
                <p class="mt-3 text-sm leading-relaxed text-muted-foreground sm:text-base">Tidak ada satu cara yang paling benar untuk mencari dukungan. Mulai dari yang terasa paling ringan.</p>
            </div>

            <div class="mt-8 grid gap-4 md:grid-cols-3">
                @foreach ([
                    ['icon' => 'bubble', 'number' => '01', 'title' => 'Bubble Publik', 'text' => 'Bagikan cerita dan saling menguatkan dengan identitas yang tetap anonim.', 'href' => route('daftar'), 'tone' => 'bg-secondary text-secondary-foreground'],
                    ['icon' => 'chat', 'number' => '02', 'title' => 'Konseling Privat', 'text' => 'Bicara empat mata dengan konselor dalam ruang yang hanya untuk kalian.', 'href' => route('daftar'), 'tone' => 'bg-primary text-primary-foreground'],
                    ['icon' => 'shield', 'number' => '03', 'title' => 'Ruang yang Terjaga', 'text' => 'Moderasi dan verifikasi membantu komunitas tetap aman untuk semua.', 'href' => '#privasi', 'tone' => 'bg-accent text-accent-foreground'],
                ] as $feature)
                    <a href="{{ $feature['href'] }}" class="group flex flex-col rounded-[1.5rem] bg-card p-5 transition duration-300 hover:-translate-y-1 hover:shadow-[6px_6px_0_var(--hard-shadow)] neu-sm sm:p-6" data-reveal style="--reveal-delay: {{ $loop->index * 90 }}ms">
                        <div class="flex items-center justify-between">
                            <span class="flex size-12 items-center justify-center rounded-2xl {{ $feature['tone'] }} transition group-hover:rotate-[-6deg]"><x-icon :name="$feature['icon']" :size="21" /></span>
                            <span class="text-xs font-bold text-muted-foreground">{{ $feature['number'] }}</span>
                        </div>
                        <h3 class="mt-7 text-lg font-semibold">{{ $feature['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-muted-foreground">{{ $feature['text'] }}</p>
                        <span class="mt-7 inline-flex items-center gap-2 text-xs font-bold text-foreground">
                            Pelajari lebih lanjut
                            <x-icon name="arrow-right" :size="14" class="transition group-hover:translate-x-1" />
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- How it works, interactive steps --}}
    <section id="cara-kerja" class="mx-auto max-w-[86rem] scroll-mt-28 px-5 py-16 sm:px-8 sm:py-20 lg:px-10" x-data="{ aktif: 1 }">
        <div class="grid gap-10 lg:grid-cols-[.8fr_1.2fr] lg:items-start">
            <div class="lg:sticky lg:top-28">
                <span class="label-cap">Mulai dengan tenang</span>
                <h2 class="mt-3 text-3xl leading-tight font-semibold tracking-tight sm:text-4xl">Langkah kecil tetap berarti.</h2>
                <p class="mt-4 text-sm leading-relaxed text-muted-foreground sm:text-base">Kamu tidak perlu menyiapkan cerita yang sempurna. Cukup hadir sebagai dirimu sendiri.</p>
                <a href="{{ route('daftar') }}" class="btn-ink mt-6 px-5 py-3 text-sm">Buat akun gratis <x-icon name="arrow-right" :size="15" /></a>
            </div>

            <div class="space-y-3">
                @foreach ([
                    ['number' => 1, 'title' => 'Pilih ruang yang terasa aman', 'text' => 'Bercerita di Bubble atau mulai percakapan privat dengan konselor.', 'detail' => 'Bubble cocok saat kamu ingin didengar tanpa nama. Konseling privat cocok saat kamu butuh teman bicara yang lebih fokus.'],
                    ['number' => 2, 'title' => 'Ceritakan secukupnya', 'text' => 'Kamu selalu boleh berhenti, kembali, dan mengatur batasanmu sendiri.', 'detail' => 'Tidak ada tekanan untuk menceritakan semuanya sekaligus. Kamu bisa berhenti kapan pun dan melanjutkan saat sudah siap.'],
                    ['number' => 3, 'title' => 'Temukan dukungan', 'text' => 'Dapatkan respons dari komunitas atau pendamping yang siap mendengarkan.', 'detail' => 'Balasan bisa datang dari kawan di Bubble atau konselor yang ditugaskan. Kamu tetap memegang kendali atas percakapanmu.'],
                ] as $step)
                    <div class="overflow-hidden rounded-[1.35rem] bg-card transition neu-sm" data-reveal style="--reveal-delay: {{ $loop->index * 90 }}ms">
                        <button
                            type="button"
                            @click="aktif = aktif === {{ $step['number'] }} ? 0 : {{ $step['number'] }}"
                            class="flex w-full items-center gap-4 p-4 text-left sm:p-5"
                            :aria-expanded="aktif === {{ $step['number'] }}"
                        >
                            <span class="flex size-10 shrink-0 items-center justify-center rounded-full text-sm font-bold transition" :class="aktif === {{ $step['number'] }} ? 'bg-primary text-primary-foreground' : 'bg-secondary text-secondary-foreground'">
                                {{ $step['number'] }}
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="block text-sm font-semibold sm:text-base">{{ $step['title'] }}</span>
                                <span class="mt-0.5 block text-xs leading-relaxed text-muted-foreground sm:text-sm">{{ $step['text'] }}</span>
                            </span>
                            <x-icon name="chevron-down" :size="17" class="shrink-0 text-muted-foreground transition" x-bind:class="aktif === {{ $step['number'] }} ? 'rotate-180' : ''" />
                        </button>
                        <div class="grid transition-[grid-template-rows] duration-300 ease-out" :class="aktif === {{ $step['number'] }} ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
                            <div class="overflow-hidden">
                                <p class="border-t border-border/60 px-4 py-3.5 pl-[4.5rem] text-xs leading-relaxed text-muted-foreground sm:px-5 sm:pl-[4.75rem] sm:text-sm">{{ $step['detail'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Privacy --}}
    <section id="privasi" class="mx-auto max-w-[86rem] scroll-mt-28 px-5 pb-16 sm:px-8 sm:pb-20 lg:px-10">
        <div class="card-ink relative overflow-hidden rounded-[1.8rem] p-6 neu sm:p-9">
            <div class="pointer-events-none absolute -right-20 -bottom-28 size-80 rounded-full bg-secondary/15 blur-3xl" aria-hidden="true"></div>
            <div class="relative grid gap-8 lg:grid-cols-[.8fr_1.2fr] lg:items-center">
                <div>
                    <span class="inline-flex items-center gap-2 rounded-full border border-primary-foreground/20 px-3 py-1.5 text-xs font-semibold">
                        <x-icon name="lock" :size="13" />
                        Privasi sebagai dasar
                    </span>
                    <h2 class="mt-5 text-2xl leading-tight font-semibold tracking-tight sm:text-3xl">Kamu berhak merasa aman saat mencari bantuan.</h2>
                    <p class="mt-3 text-xs leading-relaxed text-primary-foreground/60 sm:text-sm">Dirancang agar identitasmu tidak menjadi beban saat ingin bercerita.</p>
                </div>
                <div class="grid gap-3 sm:grid-cols-3">
                    @foreach ([
                        ['icon' => 'user', 'title' => 'Anonim', 'text' => 'Nama dan identitasmu tidak tampil di ruang publik.'],
                        ['icon' => 'lock', 'title' => 'Privat', 'text' => 'Percakapan konseling hanya untuk pihak yang terlibat.'],
                        ['icon' => 'shield', 'title' => 'Terkendali', 'text' => 'Kamu bisa mengelola data dan persetujuanmu.'],
                    ] as $item)
                        <div class="group rounded-2xl border border-primary-foreground/15 bg-primary-foreground/5 p-4 transition hover:border-secondary/50 hover:bg-primary-foreground/10" data-reveal style="--reveal-delay: {{ $loop->index * 90 }}ms">
                            <x-icon :name="$item['icon']" :size="18" class="text-secondary transition group-hover:scale-110" />
                            <h3 class="mt-4 text-sm font-semibold">{{ $item['title'] }}</h3>
                            <p class="mt-1.5 text-xs leading-relaxed text-primary-foreground/60">{{ $item['text'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Final CTA --}}
    <section class="mx-auto max-w-[86rem] px-5 pb-6 sm:px-8 lg:px-10">
        <div class="relative overflow-hidden rounded-[1.8rem] bg-secondary px-6 py-9 text-secondary-foreground neu sm:px-9 sm:py-10">
            <div class="pointer-events-none absolute -top-16 -right-10 size-52 rounded-full bg-white/25 blur-2xl" aria-hidden="true"></div>
            <div class="relative flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                <div class="max-w-xl">
                    <p class="text-[0.65rem] font-bold tracking-[0.16em] uppercase opacity-60">Mulai dari satu kalimat</p>
                    <h2 class="mt-2 text-2xl leading-tight font-semibold tracking-tight sm:text-3xl">Tidak apa-apa datang dengan cerita yang belum rapi.</h2>
                    <p class="mt-2 text-xs leading-relaxed opacity-70 sm:text-sm">Buat akun anonim, pilih ruang yang paling nyaman, dan mulai pelan-pelan.</p>
                </div>
                <div class="flex shrink-0 flex-col gap-2 sm:flex-row">
                    <a href="{{ route('daftar') }}" class="btn-ink px-5 py-3 text-sm">Buat akun <x-icon name="arrow-right" :size="15" /></a>
                    <a href="{{ route('masuk') }}" class="inline-flex items-center justify-center rounded-full border-2 border-secondary-foreground/30 px-5 py-3 text-sm font-semibold transition hover:bg-secondary-foreground/10">Masuk</a>
                </div>
            </div>
        </div>
    </section>

    {{-- Emergency notice --}}
    <section class="mx-auto max-w-[86rem] px-5 pb-4 pt-4 sm:px-8 lg:px-10">
        <div class="flex flex-col items-start gap-4 rounded-[1.4rem] border-2 border-[var(--card-border)] bg-card/60 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <p class="flex items-start gap-2 text-xs leading-relaxed sm:items-center"><x-icon name="life-buoy" :size="17" class="mt-0.5 shrink-0 sm:mt-0" /><span><strong>Butuh bantuan segera?</strong> Kawan Tenang bukan layanan darurat. Hubungi <a href="tel:119" class="font-semibold underline underline-offset-2">119 ext. 8</a> atau <a href="tel:112" class="font-semibold underline underline-offset-2">112</a>.</span></p>
            <a href="#privasi" class="shrink-0 text-xs font-semibold hover:underline">Baca soal privasi</a>
        </div>
    </section>
@endsection