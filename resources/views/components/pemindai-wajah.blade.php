@props(['name' => 'wajah'])

<div x-data="pemindaiWajah" @tutup-kamera.window="tutup()" {{ $attributes->class('rounded-2xl bg-input p-3 neu-inset') }}>
    <input type="hidden" name="{{ $name }}" x-ref="input">

    <template x-if="! aktif">
        <div class="flex flex-col items-center gap-3 py-4 text-center">
            <span class="flex size-14 items-center justify-center rounded-full bg-accent/40 text-accent-foreground">
                <x-icon name="face" :size="26" />
            </span>
            <p class="max-w-xs text-xs leading-relaxed text-muted-foreground">
                Pindai wajahmu sekali untuk masuk tanpa mengetik kata sandi. Foto tidak disimpan, hanya pola wajah.
            </p>
            <button type="button" class="btn-lime text-xs" @click="buka()">
                <x-icon name="face" :size="15" />
                Buka kamera
            </button>
        </div>
    </template>

    <template x-if="aktif">
        <div class="space-y-3">
            <div class="relative mx-auto aspect-[4/3] w-full max-w-sm overflow-hidden rounded-2xl bg-foreground">
                <video x-ref="video" autoplay playsinline muted class="size-full scale-x-[-1] object-cover"></video>
                <span class="pointer-events-none absolute inset-0 m-auto h-[72%] w-[52%] rounded-[50%] border-2 border-dashed border-secondary/80" aria-hidden="true"></span>
            </div>

            <div class="flex flex-wrap justify-center gap-2">
                <button type="button" class="btn-lime text-xs" :disabled="memuat" @click="pindai()">
                    <x-icon name="face" :size="15" />
                    <span x-text="memuat ? 'Memindai…' : 'Pindai wajah'"></span>
                </button>
                <button type="button" class="rounded-full bg-background px-4 py-2.5 text-xs font-semibold neu-sm" @click="tutup()">
                    Tutup kamera
                </button>
            </div>
        </div>
    </template>

    <p x-show="pesan" x-cloak class="mt-2 text-center text-xs font-medium" :class="deskriptor.length ? 'text-success' : 'text-destructive'" x-text="pesan"></p>

    <p x-show="deskriptor.length && ! aktif" x-cloak class="mt-2 flex items-center justify-center gap-1.5 text-center text-xs font-semibold text-success">
        <x-icon name="check-circle" :size="14" />
        Wajah siap dipakai
    </p>
</div>
