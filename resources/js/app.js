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
    MessagesSquare,
    Phone,
    Plus,
    ScanFace,
    Search,
    Send,
    Settings,
    ShieldCheck,
    TriangleAlert,
    User,
    UserCheck,
    Users,
    X,
    createIcons,
} from 'lucide';
import { initCharts } from './charts';

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
    MessagesSquare,
    Phone,
    Plus,
    ScanFace,
    Search,
    Send,
    Settings,
    ShieldCheck,
    TriangleAlert,
    User,
    UserCheck,
    Users,
    X,
};

window.Alpine = Alpine;
Alpine.start();

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
