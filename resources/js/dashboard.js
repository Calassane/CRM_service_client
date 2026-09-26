import Chart from 'chart.js/auto';

const dataElement = document.getElementById('dashboard-chart-data');

if (dataElement) {
    const charts = JSON.parse(dataElement.textContent);
    const gridColor = 'rgba(148, 163, 184, 0.14)';
    const textColor = '#64748b';

    Chart.defaults.color = textColor;
    Chart.defaults.font.family = 'Manrope, ui-sans-serif, system-ui, sans-serif';

    const cartesianOptions = {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { intersect: false, mode: 'index' },
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#0f172a',
                padding: 12,
                cornerRadius: 10,
                titleFont: { weight: '700' },
            },
        },
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
                    borderColor: '#2178ef',
                    backgroundColor: 'rgba(33, 120, 239, 0.10)',
                    borderWidth: 2.5,
                    fill: true,
                    pointBackgroundColor: '#2178ef',
                    pointRadius: charts.daily.values.length > 31 ? 0 : 3,
                    tension: 0.35,
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
                    backgroundColor: '#22d3ee',
                    hoverBackgroundColor: '#0891b2',
                    borderRadius: 8,
                }],
            },
            options: cartesianOptions,
        });
    }

    const distributionOptions = {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '68%',
        plugins: {
            legend: {
                position: 'bottom',
                labels: { boxWidth: 9, padding: 18, usePointStyle: true, font: { weight: '600' } },
            },
            tooltip: {
                backgroundColor: '#0f172a',
                padding: 12,
                cornerRadius: 10,
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
                    borderWidth: 4,
                    hoverOffset: 5,
                }],
            },
            options: distributionOptions,
        });
    };

    createDoughnut('call-reasons-chart', charts.reasons, [
        '#2178ef', '#8b5cf6', '#06b6d4', '#f59e0b', '#94a3b8',
    ]);
    createDoughnut('call-statuses-chart', charts.statuses, [
        '#10b981', '#f59e0b', '#ef4444', '#2178ef',
    ]);
}
