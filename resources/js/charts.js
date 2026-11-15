import {
    ArcElement,
    BarController,
    BarElement,
    CategoryScale,
    Chart,
    DoughnutController,
    Filler,
    LineController,
    LineElement,
    LinearScale,
    PointElement,
    Tooltip,
} from 'chart.js';

const palette = {
    primary: '#789B8A',
    secondary: '#A9C7D5',
    accent: '#E8B89D',
    success: '#668C72',
    muted: '#78858A',
    surface: '#F8F7F2',
    text: '#34434A',
    grid: 'rgba(52, 67, 74, 0.08)',
};

Chart.register(CategoryScale, LinearScale, PointElement, LineElement, LineController, BarController, BarElement, Filler, Tooltip, DoughnutController, ArcElement);

Chart.defaults.font.family = "'Montserrat', ui-sans-serif, system-ui, sans-serif";
Chart.defaults.font.size = 10;
Chart.defaults.color = palette.muted;

const tooltip = {
    backgroundColor: palette.surface,
    titleColor: palette.text,
    bodyColor: palette.muted,
    borderColor: 'rgba(52, 67, 74, 0.12)',
    borderWidth: 1,
    cornerRadius: 10,
    padding: 10,
    displayColors: true,
    boxWidth: 8,
    boxHeight: 8,
    usePointStyle: true,
};

/** Draws whichever charts are on the current page. Safe to call after every navigation. */
export function initCharts() {
    const activityCanvas = document.getElementById('chartAktivitas');

    if (activityCanvas) {
        Chart.getChart(activityCanvas)?.destroy();

        const context = activityCanvas.getContext('2d');
        const gradient = context.createLinearGradient(0, 0, 0, activityCanvas.parentElement?.clientHeight || 240);
        gradient.addColorStop(0, 'rgba(120, 155, 138, 0.35)');
        gradient.addColorStop(1, 'rgba(120, 155, 138, 0)');

        new Chart(activityCanvas, {
            type: 'line',
            data: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                datasets: [
                    {
                        label: 'Cerita',
                        data: [12, 18, 9, 22, 17, 26, 21],
                        borderColor: palette.primary,
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 0,
                        pointHoverRadius: 4,
                        pointHoverBackgroundColor: palette.primary,
                    },
                    {
                        label: 'Pesan',
                        data: [30, 34, 28, 41, 38, 46, 40],
                        borderColor: palette.secondary,
                        borderWidth: 2,
                        borderDash: [5, 5],
                        tension: 0.4,
                        pointRadius: 0,
                        pointHoverRadius: 4,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: false,
                interaction: { mode: 'index', intersect: false },
                plugins: { legend: { display: false }, tooltip },
                scales: {
                    x: {
                        grid: { display: false },
                        border: { display: false },
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: palette.grid },
                        border: { display: false },
                        ticks: { maxTicksLimit: 4, padding: 8 },
                    },
                },
            },
        });
    }

    const sessionCanvas = document.getElementById('chartSesi');

    if (sessionCanvas) {
        Chart.getChart(sessionCanvas)?.destroy();

        new Chart(sessionCanvas, {
            type: 'doughnut',
            data: {
                labels: ['Aktif', 'Selesai', 'Menunggu', 'Ditutup'],
                datasets: [
                    {
                        data: [32, 41, 9, 4],
                        backgroundColor: [palette.primary, palette.success, palette.accent, 'rgba(120, 133, 138, 0.35)'],
                        borderWidth: 0,
                        borderRadius: 6,
                        spacing: 2,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: false,
                cutout: '74%',
                plugins: { legend: { display: false }, tooltip },
            },
        });
    }

    const counselorCanvas = document.getElementById('chartSesiKonselor');

    if (counselorCanvas) {
        Chart.getChart(counselorCanvas)?.destroy();

        new Chart(counselorCanvas, {
            type: 'bar',
            data: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                datasets: [
                    {
                        label: 'Selesai',
                        data: [4, 6, 3, 7, 5, 8, 6],
                        backgroundColor: palette.primary,
                        borderRadius: 6,
                        maxBarThickness: 18,
                    },
                    {
                        label: 'Baru',
                        data: [2, 3, 1, 4, 2, 3, 2],
                        backgroundColor: palette.secondary,
                        borderRadius: 6,
                        maxBarThickness: 18,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: false,
                plugins: { legend: { display: false }, tooltip },
                scales: {
                    x: {
                        grid: { display: false },
                        border: { display: false },
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: palette.grid },
                        border: { display: false },
                        ticks: { maxTicksLimit: 4, padding: 8 },
                    },
                },
            },
        });
    }
}
