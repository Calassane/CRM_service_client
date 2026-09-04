import Chart from 'chart.js/auto';

const dataElement = document.getElementById('dashboard-chart-data');

if (dataElement) {
    const charts = JSON.parse(dataElement.textContent);
    const gridColor = 'rgba(148, 163, 184, 0.18)';
    const textColor = '#475569';

    Chart.defaults.color = textColor;
    Chart.defaults.font.family = 'Figtree, ui-sans-serif, system-ui, sans-serif';

    const cartesianOptions = {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { intersect: false, mode: 'index' },
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false } },
            y: {
                beginAtZero: true,
                ticks: { precision: 0 },
                grid: { color: gridColor },
            },
        },
    };

    const dailyCanvas = document.getElementById('daily-calls-chart');

    if (dailyCanvas) {
        new Chart(dailyCanvas, {
            type: 'line',
            data: {
                labels: charts.daily.labels,
                datasets: [{
                    label: 'Appels',
                    data: charts.daily.values,
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79, 70, 229, 0.12)',
                    borderWidth: 2,
                    fill: true,
                    pointBackgroundColor: '#4f46e5',
                    pointRadius: charts.daily.values.length > 31 ? 0 : 3,
                    tension: 0.3,
                }],
            },
            options: cartesianOptions,
        });
    }

    const weeklyCanvas = document.getElementById('weekly-calls-chart');

    if (weeklyCanvas) {
        new Chart(weeklyCanvas, {
            type: 'bar',
            data: {
                labels: charts.weekly.labels,
                datasets: [{
                    label: 'Appels',
                    data: charts.weekly.values,
                    backgroundColor: '#0f766e',
                    borderRadius: 6,
                }],
            },
            options: cartesianOptions,
        });
    }

    const distributionOptions = {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '64%',
        plugins: {
            legend: {
                position: 'bottom',
                labels: { boxWidth: 12, padding: 16, usePointStyle: true },
            },
        },
    };

    const createDoughnut = (elementId, distribution, colors) => {
        const canvas = document.getElementById(elementId);

        if (!canvas) {
            return;
        }

        new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: distribution.labels,
                datasets: [{
                    data: distribution.values,
                    backgroundColor: colors,
                    borderColor: '#ffffff',
                    borderWidth: 3,
                }],
            },
            options: distributionOptions,
        });
    };

    createDoughnut('call-reasons-chart', charts.reasons, [
        '#4f46e5', '#e11d48', '#0891b2', '#d97706', '#64748b',
    ]);
    createDoughnut('call-statuses-chart', charts.statuses, [
        '#16a34a', '#d97706', '#dc2626',
    ]);
}
