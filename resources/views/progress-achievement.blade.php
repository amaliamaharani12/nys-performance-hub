<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }} - Progress Achievement</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, "Segoe UI", Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
            color: #1f2937;
        }

        .navbar {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            padding: 16px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h1 {
            font-size: 20px;
            margin: 0;
            color: #1d4ed8;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px 20px 60px;
        }

        .filter-bar {
            background: #ffffff;
            border-radius: 10px;
            padding: 16px 20px;
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            align-items: flex-end;
            margin-bottom: 0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
            flex: 1;
            min-width: 150px;
        }

        .filter-group label {
            font-size: 12px;
            color: #6b7280;
            font-weight: 600;
        }

        .filter-group select {
            appearance: none;
            -webkit-appearance: none;
            width: 100%;
            padding: 10px 36px 10px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            min-width: 180px;
            background-color: #f9fafb;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            cursor: pointer;
        }

        .filter-group select:hover {
            border-color: #d1d5db;
            background-color: #ffffff;
        }

        .filter-group select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
            background-color: #ffffff;
        }

        .summary-bar {
            display: flex;
            gap: 16px;
            margin-bottom: 0;
        }

        .summary-card {
            flex: 1;
            min-width: 240px;
            background: #ffffff;
            border-radius: 10px;
            padding: 16px 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            border-left: 4px solid #d1d5db;
        }

        .summary-card.achieve {
            border-left-color: #16a34a;
        }

        .summary-card.non-achieve {
            border-left-color: #dc2626;
        }

        .summary-card .num {
            font-size: 26px;
            font-weight: 700;
        }

        .summary-card .label {
            font-size: 13px;
            color: #6b7280;
        }

        .top-section {
            display: flex;
            gap: 20px;
            margin-bottom: 24px;
            align-items: stretch;
            justify-content: flex-start;
        }

        .left-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .right-panel {
            flex: 0 0 350px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            position: relative;
        }
        
        .chart-container {
            position: absolute;
            top: 8px;
            bottom: 8px;
            left: 12px;
            right: 12px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 14px;
        }

        .item-card {
            background: #ffffff;
            border-radius: 10px;
            padding: 14px 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            cursor: pointer;
            border-top: 4px solid #d1d5db;
            transition: transform 0.12s ease;
        }

        .item-card:hover {
            transform: translateY(-2px);
        }

        .item-card.kurang {
            border-top-color: #dc2626;
        }

        .item-card.tercapai {
            border-top-color: #16a34a;
        }

        .item-card.melampaui {
            border-top-color: #16a34a;
        }

        .item-card .group-tag {
            font-size: 11px;
            color: #9ca3af;
            margin-bottom: 2px;
        }

        /* item name — no min-height so badge sits right below */
        .item-name-center {
            text-align: center;
            font-weight: 600;
            font-size: 13px;
            margin-bottom: 6px;
            line-height: 1.35;
        }

        /* Badge row: badge on the left, emoji on the right */
        .badge-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            gap: 8px;
        }

        /* Badge box: transparent bg, only label text neutral gray */
        .badge-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1px;
            padding: 6px 16px;
            border-radius: 8px;
            border: 1.5px solid #e5e7eb;
            background: transparent;
            flex: 1;
        }

        .badge-box .badge-label {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #6b7280;   /* always gray — no color tinting */
        }

        /* Only the number gets a color */
        .badge-box .badge-pct {
            font-size: 22px;
            font-weight: 800;
            line-height: 1.1;
        }
        .kurang   .badge-box .badge-pct { color: #dc2626; }
        .tercapai .badge-box .badge-pct { color: #16a34a; }
        .melampaui .badge-box .badge-pct { color: #16a34a; }

        /* Emoji at top-right, bigger */
        .face-top {
            width: 36px;
            height: 36px;
            flex-shrink: 0;
        }

        .bottom-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .values-left {
            font-size: 11px;
            color: #6b7280;
            line-height: 1.7;
        }

        /* Right side: trend arrow + delta pct (bigger, right-aligned) */
        .indicator-right {
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .trend-arrow {
            display: flex;
            align-items: center;
            gap: 3px;
            font-size: 13px;
            font-weight: 800;
        }
        .trend-arrow svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }
        .trend-arrow.up   { color: #16a34a; }
        .trend-arrow.down { color: #dc2626; }
        .trend-arrow.gold { color: #16a34a; }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #9ca3af;
        }

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.45);
            align-items: center;
            justify-content: center;
            z-index: 50;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-box {
            background: #ffffff;
            border-radius: 12px;
            padding: 24px;
            width: 90%;
            max-width: 640px;
            max-height: 85vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .modal-header h2 {
            font-size: 18px;
            margin: 0;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 22px;
            cursor: pointer;
            color: #6b7280;
            line-height: 1;
        }

        table.detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
            font-size: 13px;
        }

        table.detail-table th,
        table.detail-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #eee;
            text-align: right;
        }

        table.detail-table th:first-child,
        table.detail-table td:first-child {
            text-align: left;
        }
    </style>
</head>

<body>

    <div class="navbar">
        <h1>Progress Achievement</h1>
        <span
            style="font-size: 13px; color: #6b7280;">{{ auth()->check() ? auth()->user()->name : 'Public View' }}</span>
    </div>

    <div class="container">

        <div class="top-section">
            <div class="left-panel">
                <form class="filter-bar" method="GET" action="{{ route('progress-achievement') }}" id="filterForm">
                    <div class="filter-group">
                        <label>Period</label>
                        <select name="periode" onchange="document.getElementById('filterForm').submit()">
                            @foreach ($availablePeriods as $p)
                                <option value="{{ $p->format('Y-m-d') }}" {{ $periode === $p->format('Y-m-d') ? 'selected' : '' }}>
                                    {{ $p->format('F Y') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>Group</label>
                        <select name="group_id" onchange="document.getElementById('filterForm').submit()">
                            <option value="all" {{ $groupId === 'all' ? 'selected' : '' }}>All Groups</option>
                            @foreach ($groups as $g)
                                <option value="{{ $g->id }}" {{ (string) $groupId === (string) $g->id ? 'selected' : '' }}>
                                    Group {{ $g->kode_group }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>Status</label>
                        <select name="status" onchange="document.getElementById('filterForm').submit()">
                            <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All</option>
                            <option value="achieve" {{ $status === 'achieve' ? 'selected' : '' }}>Achieve</option>
                            <option value="non_achieve" {{ $status === 'non_achieve' ? 'selected' : '' }}>Non-Achieve</option>
                        </select>
                    </div>
                </form>

                <div class="summary-bar">
                    <div class="summary-card achieve">
                        <div class="num">{{ $totalAchieve }}</div>
                        <div class="label">Achieve Items (including over-achieve)</div>
                    </div>
                    <div class="summary-card non-achieve">
                        <div class="num">{{ $totalNonAchieve }}</div>
                        <div class="label">Non-Achieve Items</div>
                    </div>
                </div>
            </div>

            <div class="right-panel">
                <div class="chart-container">
                    <canvas id="summaryPieChart"></canvas>
                </div>
            </div>
        </div>

        @if (count($items) === 0)
            <div class="empty-state">No data available for the selected filters.</div>
        @else
            <div class="grid">
                @foreach ($items as $item)
                    @php
                        $delta = round(abs($item['persen_achievement'] - 100), 1);
                        $isUp  = $item['persen_achievement'] >= 100;
                        $trendClass = $item['kategori'] === 'melampaui' ? 'gold' : ($isUp ? 'up' : 'down');
                    @endphp
                    <div class="item-card {{ $item['kategori'] }}"
                        onclick="openDetail({{ $item['metric_id'] }}, '{{ addslashes($item['nama_item']) }}')">

                        {{-- Row 1: Group tag --}}
                        <div class="group-tag">Group {{ $item['kode_group'] }}</div>

                        {{-- Row 2: Item name (no min-height) --}}
                        <div class="item-name-center">{{ $item['nama_item'] }}</div>

                        {{-- Row 3: Badge (left) + Emoji (right, bigger) --}}
                        <div class="badge-row">
                            <div class="badge-box">
                                <span class="badge-label">
                                    @if ($item['kategori'] === 'kurang') Non-Achieve
                                    @elseif ($item['kategori'] === 'tercapai') Achieve
                                    @else Over-Achieve
                                    @endif
                                </span>
                                <span class="badge-pct">{{ $item['persen_achievement'] }}%</span>
                            </div>

                            {{-- Emoji at top-right, enlarged --}}
                            @if ($item['kategori'] === 'kurang')
                                <svg class="face-top" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" fill="#dc2626" />
                                    <circle cx="9" cy="10" r="1.2" fill="#fff" />
                                    <circle cx="15" cy="10" r="1.2" fill="#fff" />
                                    <path d="M8 16c1-1.5 2.5-2 4-2s3 0.5 4 2" stroke="#fff" stroke-width="1.5" fill="none" stroke-linecap="round" />
                                </svg>
                            @elseif ($item['kategori'] === 'tercapai')
                                <svg class="face-top" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" fill="#16a34a" />
                                    <circle cx="9" cy="10" r="1.2" fill="#fff" />
                                    <circle cx="15" cy="10" r="1.2" fill="#fff" />
                                    <path d="M8 14c1 1.5 2.5 2 4 2s3-0.5 4-2" stroke="#fff" stroke-width="1.5" fill="none" stroke-linecap="round" />
                                </svg>
                            @else
                                <svg class="face-top" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" fill="#16a34a" />
                                    <path d="M8 9.5c0-0.5 0.4-1 1-1s1 0.5 1 1M14 9.5c0-0.5 0.4-1 1-1s1 0.5 1 1" stroke="#fff" stroke-width="1.4" stroke-linecap="round" />
                                    <path d="M7.5 13.5c1.2 2 2.8 2.8 4.5 2.8s3.3-0.8 4.5-2.8" stroke="#fff" stroke-width="1.6" fill="none" stroke-linecap="round" />
                                </svg>
                            @endif
                        </div>

                        {{-- Row 4: Target/Actual (left) + trend arrow with delta % (right, bigger) --}}
                        <div class="bottom-row">
                            <div class="values-left">
                                <div>Target: {{ number_format($item['nilai_target'], 2) }} {{ $item['satuan'] }}</div>
                                <div>Actual: {{ number_format($item['nilai_actual'], 2) }} {{ $item['satuan'] }}</div>
                            </div>
                            <div class="indicator-right">
                                <div class="trend-arrow {{ $trendClass }}">
                                    @if ($isUp)
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                            <polyline points="17 6 23 6 23 12"></polyline>
                                        </svg>
                                    @else
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                                            <polyline points="17 18 23 18 23 12"></polyline>
                                        </svg>
                                    @endif
                                    <span>{{ $isUp ? '+' : '-' }}{{ $delta }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

    <div class="modal-overlay" id="modalOverlay">
        <div class="modal-box">
            <div class="modal-header">
                <h2 id="modalTitle">Item Details</h2>
                <button class="modal-close" onclick="closeDetail()">&times;</button>
            </div>
            <canvas id="detailChart" height="140"></canvas>
            <table class="detail-table" id="detailTable">
                <thead>
                    <tr>
                        <th>Period</th>
                        <th>Target</th>
                        <th>Actual</th>
                    </tr>
                </thead>
                <tbody id="detailTableBody"></tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const achieveCount = {{ $totalAchieve }};
            const nonAchieveCount = {{ $totalNonAchieve }};
            
            if (achieveCount > 0 || nonAchieveCount > 0) {
                new Chart(document.getElementById('summaryPieChart'), {
                    type: 'pie',
                    plugins: [ChartDataLabels],
                    data: {
                        labels: ['Achieve', 'Non-Achieve'],
                        datasets: [{
                            data: [achieveCount, nonAchieveCount],
                            backgroundColor: ['#16a34a', '#dc2626'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: {
                            padding: 4
                        },
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    boxWidth: 12,
                                    font: { size: 12 }
                                }
                            },
                            datalabels: {
                                color: '#ffffff',
                                font: {
                                    weight: 'bold',
                                    size: 16
                                },
                                formatter: (value, context) => {
                                    if (value === 0) return '';
                                    let total = context.dataset.data.reduce((acc, curr) => acc + curr, 0);
                                    return Math.round((value / total) * 100) + "%";
                                }
                            }
                        }
                    }
                });
            }
        });

        let detailChartInstance = null;

        function openDetail(metricId, namaItem) {
            document.getElementById('modalTitle').textContent = namaItem;
            document.getElementById('modalOverlay').classList.add('active');

            fetch(`/progress-achievement/${metricId}/detail`)
                .then(res => res.json())
                .then(data => {
                    const labels = data.history.map(h => h.periode);
                    const targets = data.history.map(h => h.target);
                    const actuals = data.history.map(h => h.actual);

                    if (detailChartInstance) {
                        detailChartInstance.destroy();
                    }

                    detailChartInstance = new Chart(document.getElementById('detailChart'), {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [
                                { label: 'Target', data: targets, backgroundColor: '#93c5fd' },
                                { label: 'Actual', data: actuals, backgroundColor: '#3b82f6' }
                            ]
                        },
                        options: { responsive: true, scales: { y: { beginAtZero: false } } }
                    });

                    const tbody = document.getElementById('detailTableBody');
                    tbody.innerHTML = '';
                    data.history.forEach(h => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `<td>${h.periode}</td><td>${h.target ?? '-'}</td><td>${h.actual ?? '-'}</td>`;
                        tbody.appendChild(tr);
                    });
                });
        }

        function closeDetail() {
            document.getElementById('modalOverlay').classList.remove('active');
        }
    </script>

</body>

</html>