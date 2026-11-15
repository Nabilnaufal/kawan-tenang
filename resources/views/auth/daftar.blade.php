<x-layouts.guest title="Daftar">
    <div>
        <h2 class="text-2xl font-semibold tracking-tight">Buat akun Kawan Tenang</h2>
        <p class="mt-1.5 text-sm text-muted-foreground">Butuh kurang dari satu menit.</p>

        <form class="mt-6 space-y-4" action="{{ route('daftar.store') }}" method="POST">
            @csrf

            <fieldset>
                <legend class="mb-2 text-xs font-medium text-muted-foreground">Daftar sebagai</legend>
                <div class="grid grid-cols-2 gap-2" x-data="{ role: 'pengguna' }">
                    @foreach ([['pengguna', 'Pengguna', 'Mencari dukungan'], ['konselor', 'Konselor', 'Memberi dukungan']] as [$value, $label, $hint])
                        <label class="flex cursor-pointer items-start gap-2.5 rounded-xl bg-input p-3 text-xs neu-inset" :class="role === '{{ $value }}' ? 'ring-2 ring-primary' : ''">
                            <input type="radio" name="role" value="{{ $value }}" x-model="role" class="mt-0.5 size-4 border-border text-primary">
                            <span>
                                <span class="block font-semibold">{{ $label }}</span>
                                <span class="block text-[11px] text-muted-foreground">{{ $hint }}</span>
                            </span>
                        </label>
                    @endforeach
                </div>
            </fieldset>

            <div>
                <label for="nama" class="mb-1.5 block text-xs font-medium text-muted-foreground">Nama</label>
                <div class="flex items-center gap-2 rounded-xl bg-input px-3.5 py-3 neu-inset">
                    <x-icon name="user" :size="16" class="text-muted-foreground" />
                    <input id="nama" name="nama" type="text" required autocomplete="name" placeholder="Nama panggilanmu" class="min-w-0 flex-1 bg-transparent text-sm placeholder:text-muted-foreground focus:outline-none">
                </div>
            </div>

            <div>
                <label for="email" class="mb-1.5 block text-xs font-medium text-muted-foreground">Email</label>
                <div class="flex items-center gap-2 rounded-xl bg-input px-3.5 py-3 neu-inset">
                    <x-icon name="mail" :size="16" class="text-muted-foreground" />
                    <input id="email" name="email" type="email" required autocomplete="email" placeholder="nama@email.com" class="min-w-0 flex-1 bg-transparent text-sm placeholder:text-muted-foreground focus:outline-none">
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="password" class="mb-1.5 block text-xs font-medium text-muted-foreground">Kata sandi</label>
                    <div class="flex items-center gap-2 rounded-xl bg-input px-3.5 py-3 neu-inset">
                        <x-icon name="lock" :size="16" class="text-muted-foreground" />
                        <input id="password" name="password" type="password" required autocomplete="new-password" placeholder="Min. 8 karakter" class="min-w-0 flex-1 bg-transparent text-sm placeholder:text-muted-foreground focus:outline-none">
                    </div>
                </div>
                <div>
                    <label for="password_confirmation" class="mb-1.5 block text-xs font-medium text-muted-foreground">Ulangi sandi</label>
                    <div class="flex items-center gap-2 rounded-xl bg-input px-3.5 py-3 neu-inset">
                        <x-icon name="lock" :size="16" class="text-muted-foreground" />
                        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" placeholder="••••••••" class="min-w-0 flex-1 bg-transparent text-sm placeholder:text-muted-foreground focus:outline-none">
                    </div>
                </div>
            </div>

            <label class="flex items-start gap-2.5 text-[11px] leading-relaxed text-muted-foreground">
                <input type="checkbox" required class="mt-0.5 size-4 rounded border-border text-primary">
                <span>
                    Saya menyetujui <a href="#" class="font-semibold text-brand-deep hover:underline">Kebijakan Privasi</a> dan
                    <a href="#" class="font-semibold text-brand-deep hover:underline">Aturan Komunitas</a>.
                </span>
            </label>

            <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-4 py-3 text-sm font-semibold text-primary-foreground neu-primary transition hover:bg-brand-deep">
                Buat akun
                <x-icon name="arrow-right" :size="15" />
            </button>
        </form>

        <p class="mt-5 text-center text-xs text-muted-foreground">
            Sudah punya akun?
            <a href="{{ route('masuk') }}" class="font-semibold text-brand-deep hover:underline">Masuk</a>
        </p>
    </div>
</x-layouts.guest>
