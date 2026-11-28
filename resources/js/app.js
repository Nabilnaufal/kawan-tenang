import './bootstrap';
import '@hotwired/turbo';
import Alpine from 'alpinejs';
import { animate as tween } from 'animejs';
import {
    ArrowRight,
    Bell,
    Calendar,
    Check,
    ChevronDown,
    ChevronRight,
    CircleCheck,
    ClipboardList,
    Clock,
    Download,
    Ellipsis,
    Eye,
    FileText,
    Flag,
    Heart,
    History,
    House,
    Inbox,
    LayoutDashboard,
    LifeBuoy,
    Lock,
    LockOpen,
    LogOut,
    Mail,
    MessageCircle,
    Moon,
    MessagesSquare,
    Phone,
    Plus,
    ScanFace,
    Search,
    Send,
    Settings,
    ShieldCheck,
    Sun,
    TriangleAlert,
    User,
    UserCheck,
    Users,
    X,
    createIcons,
} from 'lucide';
import { initCharts } from './charts';
import { hentikanKamera, mulaiKamera, pesanKamera, pindaiWajah } from './wajah';

const icons = {
    ArrowRight,
    Bell,
    Calendar,
    Check,
    ChevronDown,
    ChevronRight,
    CircleCheck,
    ClipboardList,
    Clock,
    Download,
    Ellipsis,
    Eye,
    FileText,
    Flag,
    Heart,
    History,
    House,
    Inbox,
    LayoutDashboard,
    LifeBuoy,
    Lock,
    LockOpen,
    LogOut,
    Mail,
    MessageCircle,
    Moon,
    MessagesSquare,
    Phone,
    Plus,
    ScanFace,
    Search,
    Send,
    Settings,
    ShieldCheck,
    Sun,
    TriangleAlert,
    User,
    UserCheck,
    Users,
    X,
};

window.Alpine = Alpine;

Alpine.data('daftarWizard', (awal = 1) => ({
    step: awal,
    wajahSiap: false,
    pesan: '',
    password: '',
    konfirmasi: '',
    lanjut() {
        const wajib = [...this.$el.querySelectorAll(`[data-langkah="${this.step}"] [required]`)];

        if (! wajib.every((input) => input.reportValidity())) {
            return;
        }

        if (this.step === 2 && this.password !== this.konfirmasi) {
            this.pesan = 'Ulangi kata sandi tidak sama.';
            return;
        }

        this.pesan = '';
        this.$dispatch('tutup-kamera');
        this.step++;
    },
    kembali() {
        this.pesan = '';
        this.$dispatch('tutup-kamera');
        this.step--;
    },
    kirim(event) {
        if (this.step < 3) {
            event.preventDefault();
            this.lanjut();
            return;
        }

        if (! this.wajahSiap) {
            event.preventDefault();
            this.pesan = 'Pindai wajah dulu di langkah 3.';
        }
    },
}));

Alpine.data('pemindaiWajah', () => ({
    aktif: false,
    memuat: false,
    pesan: '',
    deskriptor: [],
    video() {
        return this.$root.querySelector('video');
    },
    input() {
        return this.$root.querySelector('input[type="hidden"]');
    },
    livenessInput() {
        return this.$root.querySelector('[data-liveness]');
    },
    resetHasil() {
        this.deskriptor = [];
        this.input().value = '';
        this.livenessInput().value = '';
        this.$dispatch('wajah-reset');
    },
    async buka() {
        this.aktif = true;
        this.pesan = '';
        this.resetHasil();

        await this.$nextTick();

        try {
            await mulaiKamera(this.video());
        } catch (error) {
            this.tutup();
            this.pesan = pesanKamera(error);
        }
    },
    tutup() {
        hentikanKamera(this.video());
        this.aktif = false;
    },
    async pindai() {
        this.memuat = true;
        this.pesan = '';

        try {
            this.deskriptor = await pindaiWajah(this.video(), {
                onStatus: (status) => {
                    this.pesan = status;
                },
            });
            this.input().value = JSON.stringify(this.deskriptor);
            this.input().dispatchEvent(new Event('input', { bubbles: true }));
            this.livenessInput().value = '1';
            this.livenessInput().dispatchEvent(new Event('input', { bubbles: true }));
            this.tutup();
            this.pesan = 'Liveness berhasil. Wajah siap dipakai.';
            this.$dispatch('wajah-siap', { deskriptor: this.deskriptor });
        } catch (error) {
            this.livenessInput().value = '';
            this.pesan = pesanKamera(error);
        } finally {
            this.memuat = false;
        }
    },
}));

Alpine.data('chatRealtime', ({ poll, send, count }) => ({
    jumlah: count,
    mengirim: false,
    timer: null,
    init() {
        this.timer = setInterval(() => this.cek(), 3000);
        this.keBawah();
    },
    destroy() {
        clearInterval(this.timer);
    },
    async cek() {
        if (document.hidden) {
            return;
        }

        try {
            const response = await fetch(poll, { headers: { Accept: 'application/json' } });

            if (!response.ok) {
                return;
            }

            const data = await response.json();

            if (data.count !== this.jumlah) {
                this.$refs.list.innerHTML = data.html;
                this.jumlah = data.count;
                this.keBawah();
            }
        } catch {
            // Jaringan putus sementara; coba lagi pada interval berikutnya.
        }
    },
    async kirim(event) {
        const input = event.target.querySelector('[name="body"]');
        const body = input.value.trim();

        if (!body || this.mengirim) {
            return;
        }

        this.mengirim = true;

        try {
            const response = await fetch(send, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                },
                body: JSON.stringify({ body }),
            });

            if (!response.ok) {
                return;
            }

            const data = await response.json();
            this.$refs.list.innerHTML = data.html;
            this.jumlah = data.count;
            input.value = '';
            this.keBawah();
        } catch {
            // Biarkan teks tetap di input agar bisa dikirim ulang.
        } finally {
            this.mengirim = false;
        }
    },
    keBawah() {
        this.$nextTick(() => {
            this.$refs.list.scrollTop = this.$refs.list.scrollHeight;
        });
    },
}));

Alpine.start();

window.toggleTheme = () => {
    const dark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('kt-theme', dark ? 'dark' : 'light');
    initCharts();
};

const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const numberFormat = new Intl.NumberFormat('id-ID');

/** Count-up stats: "1.284", "98%", "7". */
function countUp(element) {
    const raw = element.dataset.count ?? '';
    const number = raw.match(/[\d.,]+/);

    if (!number) {
        return;
    }

    const target = Number(number[0].replace(/\./g, '').replace(',', '.'));
    const suffix = raw.replace(number[0], '');

    if (!Number.isFinite(target)) {
        return;
    }

    if (prefersReduced) {
        element.textContent = numberFormat.format(target) + suffix;
        return;
    }

    const state = { value: 0 };

    tween(state, {
        value: target,
        duration: 1100,
        ease: 'outQuart',
        onUpdate: () => {
            element.textContent = numberFormat.format(Math.round(state.value)) + suffix;
        },
        onComplete: () => {
            element.textContent = numberFormat.format(target) + suffix;
        },
    });
}

function initCounters() {
    const counters = document.querySelectorAll('[data-count]');

    if (!('IntersectionObserver' in window)) {
        counters.forEach(countUp);
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                countUp(entry.target);
                observer.unobserve(entry.target);
            });
        },
        { threshold: 0.4 },
    );

    counters.forEach((counter) => observer.observe(counter));
}

function initReveal() {
    const items = document.querySelectorAll('[data-reveal]');

    if (!items.length) {
        return;
    }

    if (!('IntersectionObserver' in window)) {
        items.forEach((item) => item.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            });
        },
        { threshold: 0, rootMargin: '0px 0px -24px' },
    );

    items.forEach((item) => observer.observe(item));
}

/** Turbo swaps the body without a reload, so page-level widgets re-init here. */
document.addEventListener('turbo:load', () => {
    createIcons({ icons, attrs: { 'stroke-width': 1.8 } });
    initCharts();
    initCounters();
    initReveal();
});
