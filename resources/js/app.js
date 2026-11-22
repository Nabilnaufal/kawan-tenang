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
    async buka() {
        this.aktif = true;
        this.pesan = '';

        await this.$nextTick();

        try {
            await mulaiKamera(this.$refs.video);
        } catch (error) {
            this.tutup();
            this.pesan = pesanKamera(error);
        }
    },
    tutup() {
        hentikanKamera(this.$refs.video);
        this.aktif = false;
    },
    async pindai() {
        this.memuat = true;
        this.pesan = '';

        try {
            this.deskriptor = await pindaiWajah(this.$refs.video);
            this.$refs.input.value = JSON.stringify(this.deskriptor);
            this.$refs.input.dispatchEvent(new Event('input', { bubbles: true }));
            this.tutup();
            this.pesan = 'Wajah berhasil dipindai.';
            this.$dispatch('wajah-siap', { deskriptor: this.deskriptor });
        } catch (error) {
            this.pesan = pesanKamera(error);
        } finally {
            this.memuat = false;
        }
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

/** Turbo swaps the body without a reload, so page-level widgets re-init here. */
document.addEventListener('turbo:load', () => {
    createIcons({ icons, attrs: { 'stroke-width': 1.8 } });
    initCharts();
    initCounters();
});
