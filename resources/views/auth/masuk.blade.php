<x-layouts.guest title="Masuk">
    <div>
        <h2 class="text-2xl font-semibold tracking-tight">Masuk ke akunmu</h2>
        <p class="mt-1.5 text-sm text-muted-foreground">Ruang amanmu tetap anonim dan privat.</p>

        <form class="mt-7 space-y-4" action="{{ route('masuk.store') }}" method="POST">
            @csrf

            <div>
                <label for="email" class="mb-1.5 block text-xs font-medium text-muted-foreground">Email</label>
                <div class="flex items-center gap-2 rounded-xl bg-input px-3.5 py-3 neu-inset">
                    <x-icon name="mail" :size="16" class="text-muted-foreground" />
                    <input
                        id="email"
                        name="email"
                        type="email"
                        required
                        autocomplete="email"
                        placeholder="nama@email.com"
                        class="min-w-0 flex-1 bg-transparent text-sm text-foreground placeholder:text-muted-foreground focus:outline-none"
                    >
                </div>
            </div>

            <div>
                <div class="mb-1.5 flex items-center justify-between">
                    <label for="password" class="block text-xs font-medium text-muted-foreground">Kata sandi</label>
                    <a href="{{ route('lupa-sandi') }}" class="text-[11px] font-semibold text-brand-deep hover:underline">Lupa sandi?</a>
                </div>
                <div x-data="{ show: false }" class="flex items-center gap-2 rounded-xl bg-input px-3.5 py-3 neu-inset">
                    <x-icon name="lock" :size="16" class="text-muted-foreground" />
                    <input
                        id="password"
                        name="password"
                        :type="show ? 'text' : 'password'"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="min-w-0 flex-1 bg-transparent text-sm text-foreground placeholder:text-muted-foreground focus:outline-none"
                    >
                    <button type="button" class="text-muted-foreground hover:text-foreground" @click="show = !show" :aria-label="show ? 'Sembunyikan sandi' : 'Tampilkan sandi'">
                        <x-icon name="eye" :size="16" />
                    </button>
                </div>
            </div>

            <label class="flex items-center gap-2 text-xs text-muted-foreground">
                <input type="checkbox" name="remember" class="size-4 rounded border-border text-primary focus:ring-ring">
                Ingat saya di perangkat ini
            </label>

            <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-4 py-3 text-sm font-semibold text-primary-foreground neu-primary transition hover:bg-brand-deep">
                Masuk
                <x-icon name="arrow-right" :size="15" />
            </button>
        </form>

        <p class="mt-5 text-center text-xs text-muted-foreground">
            Belum punya akun?
            <a href="{{ route('daftar') }}" class="font-semibold text-brand-deep hover:underline">Daftar sekarang</a>
        </p>

        <p class="mt-6 flex items-start gap-2 rounded-xl bg-muted/60 p-3 text-[11px] leading-relaxed text-muted-foreground">
            <x-icon name="lock" :size="13" class="mt-0.5" />
            Nama dan email tidak pernah ditampilkan di Bubble Publik. Identitas publik selalu "Anonim".
        </p>

        <p class="mt-6 border-t border-border/60 pt-4 text-center text-[11px] text-muted-foreground">
            Tampilan peran:
            <a href="{{ route('konselor.dasbor') }}" class="font-semibold text-brand-deep hover:underline">Konselor</a>
            <span class="mx-1">·</span>
            <a href="{{ route('admin.dasbor') }}" class="font-semibold text-brand-deep hover:underline">Administrator</a>
        </p>
    </div>
</x-layouts.guest>
