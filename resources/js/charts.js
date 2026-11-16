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

Chart.register(CategoryScale, LinearScale, PointElement, LineElement, LineController, BarController, BarElement, Filler, Tooltip, DoughnutController, ArcElement);

Chart.defaults.font.family = "'Montserrat', ui-sans-serif, system-ui, sans-serif";
Chart.defaults.font.size = 14;

const css = (name, fallback) => getComputedStyle(document.documentElement).getPropertyValue(name).trim() || fallback;

/** Draws whichever charts are on the current page. Safe to call after navigation or a theme change. */
export function initCharts() {
    const ink = css('--foreground', '#171b1d');
    const card = css('--card', '#ffffff');
    const lime = css('--secondary', '#c9f24d');
    const muted = css('--muted-foreground', '#6f7678');
    const line = css('--border', 'rgba(23,27,29,0.08)');

    Chart.defaults.color = muted;

    const tooltip = {
        backgroundColor: card,
        titleColor: ink,
        bodyColor: muted,
        borderColor: line,
        borderWidth: 1,
        cornerRadius: 12,
        padding: 10,
        displayColors: true,
        boxWidth: 8,
        boxHeight: 8,
        usePointStyle: true,
    };

    const activityCanvas = document.getElementById('chartAktivitas');

    if (activityCanvas) {
        Chart.getChart(activityCanvas)?.destroy();

        const context = activityCanvas.getContext('2d');
        const gradient = context.createLinearGradient(0, 0, 0, activityCanvas.parentElement?.clientHeight || 240);
        gradient.addColorStop(0, `${ink}2e`);
        gradient.addColorStop(1, `${ink}00`);

        new Chart(activityCanvas, {
            type: 'line',
            data: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                datasets: [
                    {
                        label: 'Cerita',
                        data: [12, 18, 9, 22, 17, 26, 21],
                        borderColor: ink,
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 0,
                        pointHoverRadius: 4,
                        pointHoverBackgroundColor: ink,
                    },
                    {
                        label: 'Pesan',
                        data: [30, 34, 28, 41, 38, 46, 40],
                        borderColor: lime,
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
                        grid: { color: line },
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
                        backgroundColor: [ink, lime, '#a8b09c', `${muted}55`],
                        borderWidth: 0,
                        borderRadius: 8,
                        spacing: 3,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: false,
                cutout: '76%',
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
                        backgroundColor: ink,
                        borderRadius: 6,
                        maxBarThickness: 18,
                    },
                    {
                        label: 'Baru',
                        data: [2, 3, 1, 4, 2, 3, 2],
                        backgroundColor: lime,
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
                        ticks: { maxRotation: 0, minRotation: 0, autoSkip: false },
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: line },
                        border: { display: false },
                        ticks: { maxTicksLimit: 4, padding: 8 },
                    },
                },
            },
        });
    }
}
