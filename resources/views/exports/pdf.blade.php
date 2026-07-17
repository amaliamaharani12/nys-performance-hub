<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 9px;
            color: #1f2937;
        }

        /* ─── Header (mirrors the navbar title on the web page) ─────────────── */
        .header {
            margin-bottom: 10px;
            width: 100%;
        }

        .header table {
            width: 100%;
            border-collapse: collapse;
        }

        .header .logo-cell {
            width: 90px;
            vertical-align: middle;
        }

        .header .logo-cell img {
            height: 34px;
        }

        .header .title-cell {
            vertical-align: middle;
        }

        .header h1 {
            font-size: 20px;
            font-weight: 800;
            margin: 0 0 3px;
            color: #1d4ed8;
        }

        .header .subtitle {
            font-size: 9px;
            color: #6b7280;
        }

        /* ─── Summary cards (mirrors .summary-bar / .summary-card) ──────────── */
        table.summary {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px 0;
            margin: 10px 0 14px;
        }

        table.summary td {
            width: 33.33%;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-left: 4px solid #d1d5db;
            border-radius: 6px;
            padding: 8px 12px;
            vertical-align: middle;
        }

        table.summary td.achieve {
            border-left-color: #16a34a;
        }

        table.summary td.non-achieve {
            border-left-color: #dc2626;
        }

        table.summary td.no-data {
            border-left-color: #9ca3af;
        }

        table.summary .num {
            font-size: 22px;
            font-weight: 800;
            line-height: 1.1;
        }

        table.summary .num.achieve {
            color: #16a34a;
        }

        table.summary .num.non-achieve {
            color: #dc2626;
        }

        table.summary .num.no-data {
            color: #9ca3af;
        }

        table.summary .label {
            font-size: 8.5px;
            color: #6b7280;
        }

        table.summary .icon {
            width: 30px;
            font-size: 22px;
            font-weight: 800;
            text-align: right;
            opacity: 0.22;
        }

        table.summary .icon.achieve {
            color: #16a34a;
        }

        table.summary .icon.non-achieve {
            color: #dc2626;
        }

        table.summary .icon.no-data {
            color: #9ca3af;
        }

        /* ─── Donut chart (mirrors the pie chart in the right panel) ────────── */
        .chart-wrap {
            text-align: center;
            margin-bottom: 4px;
        }

        .chart-legend {
            font-size: 7.5px;
            color: #4b5563;
            margin-top: 6px;
            text-align: left;
        }

        .chart-legend div {
            margin-bottom: 2px;
            white-space: nowrap;
        }

        .dot {
            display: inline-block;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            margin-right: 3px;
        }

        .dot.achieve {
            background: #16a34a;
        }

        .dot.non-achieve {
            background: #dc2626;
        }

        .dot.no-data {
            background: #9ca3af;
        }

        /* ─── Item cards (mirrors .item-card in the grid) ────────────────────── */
        table.cards {
            width: 100%;
            border-collapse: separate;
            border-spacing: 6px 6px;
        }

        table.cards td.card {
            width: 20%;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-top: 3px solid #d1d5db;
            border-radius: 8px;
            padding: 8px 9px;
            vertical-align: top;
        }

        table.cards td.card.achieve {
            border-top-color: #16a34a;
        }

        table.cards td.card.non-achieve {
            border-top-color: #dc2626;
        }

        table.cards td.card.no-data {
            border-top-color: #9ca3af;
            opacity: 0.9;
        }

        .card .group-tag {
            font-size: 7.5px;
            color: #9ca3af;
            margin-bottom: 2px;
        }

        .card .item-name {
            text-align: center;
            font-weight: 700;
            font-size: 9px;
            line-height: 1.25;
            margin-bottom: 6px;
            min-height: 22px;
        }

        /* Badge row: badge box (left) + face icon (right) */
        table.badge-row {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        table.badge-row td {
            vertical-align: middle;
        }

        .badge-box {
            border: 1.3px solid #e5e7eb;
            border-radius: 6px;
            padding: 4px 6px;
            text-align: center;
        }

        .badge-box .badge-label {
            display: block;
            font-size: 6.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: #6b7280;
        }

        .badge-box .badge-pct {
            display: block;
            font-size: 15px;
            font-weight: 800;
            line-height: 1.15;
        }

        .badge-pct.achieve {
            color: #16a34a;
        }

        .badge-pct.non-achieve {
            color: #dc2626;
        }

        .badge-pct.no-data {
            color: #9ca3af;
        }

        .face {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            color: #ffffff;
            font-size: 11px;
            font-weight: 800;
            text-align: center;
            line-height: 22px;
        }

        .face.achieve {
            background: #16a34a;
        }

        .face.non-achieve {
            background: #dc2626;
        }

        .face.no-data {
            background: #9ca3af;
        }

        /* Bottom row: target/actual (left) + trend (right) */
        table.bottom-row {
            width: 100%;
            border-collapse: collapse;
        }

        .bottom-row .meta {
            font-size: 7.5px;
            color: #6b7280;
            line-height: 1.6;
            text-align: left;
        }

        .bottom-row .trend {
            font-size: 8.5px;
            font-weight: 800;
            text-align: right;
        }

        .trend.up {
            color: #16a34a;
        }

        .trend.down {
            color: #dc2626;
        }

        .trend.muted {
            color: #9ca3af;
            font-weight: 500;
            font-size: 7.5px;
        }
    </style>
</head>

<body>
    <div class="header">
        <table>
            <tr>
                <td class="logo-cell">
                    @if (file_exists(public_path('img/logo-pemi.png')))
                        <img src="{{ public_path('img/logo-pemi.png') }}" alt="Logo">
                    @endif
                </td>
                <td class="title-cell">
                    <h1>Progress Achievement</h1>
                    <div class="subtitle">
                        Period: {{ $judulPeriode }} &nbsp;|&nbsp; {{ $groupLabel }} &nbsp;|&nbsp; Status:
                        {{ $statusLabel }}
                        &nbsp;|&nbsp; Generated: {{ now()->format('d M Y H:i') }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    @php
        $chartTotal = $totalAchieve + $totalNonAchieve + $totalNoData;
    @endphp
    <table style="width: 100%; border-collapse: collapse; margin: 10px 0 14px;">
        <tr>
            <td style="width: 76%; vertical-align: top;">
                <table class="summary">
                    <tr>
                        <td class="achieve">
                            <table style="width: 100%;">
                                <tr>
                                    <td>
                                        <span class="num achieve">{{ $totalAchieve }}</span><br>
                                        <span class="label">Achieve Items (including over-achieve)</span>
                                    </td>
                                    <td class="icon achieve">+</td>
                                </tr>
                            </table>
                        </td>
                        <td class="non-achieve">
                            <table style="width: 100%;">
                                <tr>
                                    <td>
                                        <span class="num non-achieve">{{ $totalNonAchieve }}</span><br>
                                        <span class="label">Non-Achieve Items</span>
                                    </td>
                                    <td class="icon non-achieve">X</td>
                                </tr>
                            </table>
                        </td>
                        <td class="no-data">
                            <table style="width: 100%;">
                                <tr>
                                    <td>
                                        <span class="num no-data">{{ $totalNoData }}</span><br>
                                        <span class="label">No Data Yet</span>
                                    </td>
                                    <td class="icon no-data">!</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width: 24%; vertical-align: middle; padding-left: 10px;">
                @if ($chartTotal > 0)
                    @php
                        $r = 34;
                        $circumference = 2 * M_PI * $r;
                        $segments = [
                            ['value' => $totalAchieve, 'color' => '#16a34a'],
                            ['value' => $totalNonAchieve, 'color' => '#dc2626'],
                            ['value' => $totalNoData, 'color' => '#9ca3af'],
                        ];
                        $offsetSoFar = 0;
                    @endphp
                    <div class="chart-wrap">
                        <svg width="80" height="80" viewBox="0 0 90 90">
                            <circle cx="45" cy="45" r="{{ $r }}" fill="none" stroke="#eef0f3" stroke-width="14" />
                            @foreach ($segments as $seg)
                                @if ($seg['value'] > 0)
                                    @php
                                        $dash = ($seg['value'] / $chartTotal) * $circumference;
                                        $gap = $circumference - $dash;
                                    @endphp
                                    <circle cx="45" cy="45" r="{{ $r }}" fill="none" stroke="{{ $seg['color'] }}"
                                        stroke-width="14" stroke-dasharray="{{ $dash }} {{ $gap }}"
                                        stroke-dashoffset="{{ -$offsetSoFar }}" transform="rotate(-90 45 45)" />
                                    @php $offsetSoFar += $dash; @endphp
                                @endif
                            @endforeach
                        </svg>
                        <div class="chart-legend">
                            <div><span class="dot achieve"></span>Achieve
                                ({{ $chartTotal ? round($totalAchieve / $chartTotal * 100) : 0 }}%)</div>
                            <div><span class="dot non-achieve"></span>Non-Achieve
                                ({{ $chartTotal ? round($totalNonAchieve / $chartTotal * 100) : 0 }}%)</div>
                            <div><span class="dot no-data"></span>No Data
                                ({{ $chartTotal ? round($totalNoData / $chartTotal * 100) : 0 }}%)</div>
                        </div>
                    </div>
                @endif
            </td>
        </tr>
    </table>

    @if (count($items) === 0)
        <p>No data available for this filter.</p>
    @else
        <table class="cards">
            @php
                $rows = array_chunk($items, 5);
            @endphp
            @foreach ($rows as $row)
                <tr>
                    @foreach ($row as $item)
                        @php
                            $statusClass =
                                $item['kategori'] === 'tidak_ada_data'
                                    ? 'no-data'
                                    : ($item['kategori'] === 'kurang'
                                        ? 'non-achieve'
                                        : 'achieve');
                            $face = match (true) {
                                $item['kategori'] === 'tidak_ada_data' => '-',
                                $item['kategori'] === 'kurang' => 'X',
                                default => '+',
                            };
                        @endphp
                        <td class="card {{ $statusClass }}">
                            <div class="group-tag">Group {{ $item['kode_group'] }}</div>
                            <div class="item-name">{{ $item['nama_item'] }}</div>

                            <table class="badge-row">
                                <tr>
                                    <td style="width: 76%;">
                                        <div class="badge-box">
                                            <span class="badge-label">{{ $item['label'] }}</span>
                                            <span class="badge-pct {{ $statusClass }}">
                                                {{ $item['persen_achievement'] !== null ? $item['persen_achievement'] . '%' : '-' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td style="width: 24%; text-align: right;">
                                        <div class="face {{ $statusClass }}">{{ $face }}</div>
                                    </td>
                                </tr>
                            </table>

                            <table class="bottom-row">
                                <tr>
                                    <td class="meta">
                                        Target:
                                        {{ $item['nilai_target'] !== null ? number_format($item['nilai_target'], 2) : '-' }}
                                        {{ $item['satuan'] }}<br>
                                        Actual:
                                        {{ $item['nilai_actual'] !== null ? number_format($item['nilai_actual'], 2) : '-' }}
                                        {{ $item['satuan'] }}
                                    </td>
                                    <td class="trend {{ $item['trend_naik'] ? 'up' : 'down' }}"
                                        style="width: 34%;">
                                        @if ($item['kategori'] === 'tidak_ada_data')
                                            <span class="trend muted">No report yet</span>
                                        @elseif ($item['trend_pct'] === null)
                                            <span class="trend muted">vs last month: -</span>
                                        @else
                                            {{ $item['trend_naik'] ? '+' : '-' }}{{ abs($item['trend_pct']) }}%
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    @endforeach
                    @for ($i = count($row); $i < 5; $i++)
                        <td class="card" style="border: none;"></td>
                    @endfor
                </tr>
            @endforeach
        </table>
    @endif
</body>

</html>