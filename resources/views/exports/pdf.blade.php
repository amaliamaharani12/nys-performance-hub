@php
    // SVG icon definitions
    $chartIconSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M18.5 8l-5 5-3-3-4 4"/></svg>';
    $checkmarkSvg = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#16a34a" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>';
    $crossSvg = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#dc2626" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>';
    $infoSvg = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#9ca3af" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>';
    
    // Emojis
    $neutralFaceSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" fill="#9ca3af"/><circle cx="9" cy="10" r="1.2" fill="#fff"/><circle cx="15" cy="10" r="1.2" fill="#fff"/><line x1="8" y1="15" x2="16" y2="15" stroke="#fff" stroke-width="1.5" stroke-linecap="round"/></svg>';
    $sadFaceSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" fill="#dc2626"/><circle cx="9" cy="10" r="1.2" fill="#fff"/><circle cx="15" cy="10" r="1.2" fill="#fff"/><path d="M8 16c1-1.5 2.5-2 4-2s3 .5 4 2" stroke="#fff" stroke-width="1.5" fill="none" stroke-linecap="round"/></svg>';
    $happyFaceSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" fill="#16a34a"/><circle cx="9" cy="10" r="1.2" fill="#fff"/><circle cx="15" cy="10" r="1.2" fill="#fff"/><path d="M8 14c1 1.5 2.5 2 4 2s3-.5 4-2" stroke="#fff" stroke-width="1.5" fill="none" stroke-linecap="round"/></svg>';
    $overAchieveFaceSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" fill="#16a34a"/><circle cx="9" cy="10" r="1.2" fill="#fff"/><circle cx="15" cy="10" r="1.2" fill="#fff"/><path d="M7.5 13.5c1.2 2 2.8 2.8 4.5 2.8s3.3-.8 4.5-2.8" stroke="#fff" stroke-width="1.5" fill="none" stroke-linecap="round"/></svg>';

    // Trend arrows
    $trendUpSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>';
    $trendDownSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/><polyline points="17 18 23 18 23 12"/></svg>';

    $getSrc = function($svg) {
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    };
@endphp
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: "Helvetica", "Arial", sans-serif;
        background: #f4f6f9;
        color: #1f2937;
        font-size: 7.5px;
        padding: 0;
        margin: 0;
    }

    /* ── NAVBAR ─────────────────────────────────────────────────────── */
    .navbar {
        background: #ffffff;
        border-bottom: 1.5px solid #e5e7eb;
        padding: 8px 16px;
        margin-bottom: 8px;
        width: 100%;
    }
    .navbar table { width: 100%; border-collapse: collapse; }
    .nb-logo { width: 100px; vertical-align: middle; }
    .nb-logo img { height: 32px; display: block; }
    .nb-title { text-align: center; vertical-align: middle; }
    .nb-title-row { margin-bottom: 2px; text-align: center; }
    .nb-icon-box {
        display: inline-block;
        width: 20px; height: 20px;
        background: #1d4ed8;
        border-radius: 5px;
        vertical-align: middle;
        margin-right: 5px;
        text-align: center;
    }
    .nb-icon-box img {
        width: 11px; height: 11px;
        margin-top: 4px;
    }
    .nb-h1 {
        display: inline-block;
        font-size: 16px;
        font-weight: 800;
        color: #1d4ed8;
        vertical-align: middle;
        letter-spacing: 0.2px;
    }
    .nb-underline {
        width: 40px; height: 2.5px;
        background: #1d4ed8;
        border-radius: 999px;
        margin: 2px auto 0;
    }
    .nb-right { width: 100px; text-align: right; vertical-align: middle; }

    /* ── CONTAINER ───────────────────────────────────────────────────── */
    .wrap { padding: 0 12px 12px; }

    /* ── FILTER BAR ──────────────────────────────────────────────────── */
    .filter-bar {
        background: #ffffff;
        border-radius: 8px;
        padding: 8px 12px;
        margin-bottom: 8px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .filter-bar table { width: 100%; border-collapse: collapse; }
    .filter-bar td { padding: 0 12px 0 0; vertical-align: middle; }
    .filter-bar td:last-child { width: auto; text-align: right; vertical-align: middle; padding-right: 0; }
    
    .fg-label {
        font-size: 9px;
        color: #6b7280;
        font-weight: 600;
        display: block;
        margin-bottom: 3px;
    }
    
    .select-box {
        background-color: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        display: block;
        height: 28px;
        width: 155px; /* Compact width matching Gambar 2 */
        overflow: hidden;
    }

    .select-arrow {
        display: inline-block;
        width: 0;
        height: 0;
        border-left: 3.5px solid transparent;
        border-right: 3.5px solid transparent;
        border-top: 4.5px solid #4b5563; /* Choices.js style triangle */
    }

    /* ── TOP SECTION: summary + chart ───────────────────────────────── */
    .top-section { margin-bottom: 8px; page-break-inside: avoid; }
    .top-section > table { width: 100%; border-collapse: collapse; }
    
    /* Left side summary cards */
    .summary-container-td {
        width: 72%;
        vertical-align: top;
        padding-right: 10px;
    }
    
    /* Right side chart panel */
    .chart-container-td {
        width: 28%;
        vertical-align: top;
    }

    .summary-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 6px 0;
        margin-left: -6px;
        margin-right: -6px;
    }

    .sc-card {
        background: #ffffff;
        border-radius: 8px;
        padding: 10px 12px;
        border: 1px solid #e5e7eb;
        border-left: 3.5px solid #d1d5db;
        vertical-align: middle;
        width: 33.33%;
    }
    .sc-card.achieve     { border-left-color: #16a34a; }
    .sc-card.non-achieve { border-left-color: #dc2626; }
    .sc-card.no-data     { border-left-color: #9ca3af; }
    
    .sc-num { font-size: 20px; font-weight: 700; line-height: 1.1; color: #1f2937; }
    .sc-label { font-size: 8px; color: #6b7280; line-height: 1.2; margin-top: 2px; }

    /* Pie chart panel */
    .pie-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 8px 10px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .chart-legend {
        vertical-align: middle;
        padding-left: 8px;
    }
    
    .chart-legend-item {
        font-size: 7.5px;
        color: #374151;
        font-weight: 600;
        padding: 2px 0;
        white-space: nowrap;
    }
    
    .dot {
        display: inline-block;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        margin-right: 3px;
        vertical-align: middle;
    }
    .dot.achieve     { background: #16a34a; }
    .dot.non-achieve { background: #dc2626; }
    .dot.no-data     { background: #9ca3af; }

    /* ── ITEM CARDS GRID ─────────────────────────────────────────────── */
    table.cards {
        width: 100%;
        border-collapse: separate;
        border-spacing: 6px 6px;
        page-break-inside: auto;
    }
    tr {
        page-break-inside: avoid;
    }
    td.card {
        width: 20%;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-top: 3px solid #d1d5db;
        border-radius: 8px;
        padding: 9px 10px 8px;
        vertical-align: top;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    td.card.achieve     { border-top-color: #16a34a; }
    td.card.non-achieve { border-top-color: #dc2626; }
    td.card.no-data     { border-top-color: #9ca3af; }
    td.card.empty       { border: none; background: transparent; box-shadow: none; }

    /* Card Details */
    .group-tag { font-size: 8px; color: #9ca3af; margin-bottom: 2px; }
    .item-name {
        text-align: center;
        font-weight: 600;
        font-size: 9px;
        line-height: 1.3;
        margin-bottom: 6px;
        height: 22px;
        overflow: hidden;
    }

    .badge-box {
        border: 1.2px solid #e5e7eb;
        border-radius: 6px;
        padding: 4px 6px 3px;
        text-align: center;
        background: transparent;
    }
    .badge-label {
        display: block;
        font-size: 6.5px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: #6b7280;
        margin-bottom: 1px;
    }
    .badge-pct {
        display: block;
        font-size: 15px;
        font-weight: 800;
        line-height: 1.1;
    }
    .badge-pct.achieve     { color: #16a34a; }
    .badge-pct.non-achieve { color: #dc2626; }
    .badge-pct.no-data     { color: #9ca3af; }

    .meta {
        font-size: 7.5px;
        color: #6b7280;
        line-height: 1.6;
    }
    .t-label { font-size: 7px; color: #9ca3af; font-weight: 400; display: block; margin-bottom: 2px; }
    
    .trend-icon {
        width: 11px; height: 11px;
        vertical-align: middle;
        margin-right: 2px;
    }
    .trend-text {
        vertical-align: middle;
        font-size: 8.5px;
        font-weight: 800;
    }
    .trend-text.up { color: #16a34a; }
    .trend-text.down { color: #dc2626; }
    .trend-muted { color: #9ca3af; font-weight: 500; font-size: 7.5px; }

    @page { margin: 6mm 6mm 6mm 6mm; }
</style>
</head>
<body>

{{-- ─── NAVBAR ──────────────────────────────────────────────────────────── --}}
<div class="navbar">
    <table>
        <tr>
            <td class="nb-logo">
                @if(file_exists(public_path('img/logo-pemi.png')))
                    <img src="{{ public_path('img/logo-pemi.png') }}" alt="Logo">
                @endif
            </td>
            <td class="nb-title">
                <div class="nb-title-row">
                    <span class="nb-icon-box">
                        <img src="{{ $getSrc($chartIconSvg) }}" alt="chart icon">
                    </span>
                    <span class="nb-h1">Progress Achievement</span>
                </div>
                <div class="nb-underline"></div>
            </td>
            <td class="nb-right">
                <span style="background-color: #69f0ae; border-radius: 9999px; padding: 4px 10px; display: inline-block; font-size:8px; font-weight:600; color:#064e3b; text-align: center;">
                    Public View
                </span>
            </td>
        </tr>
    </table>
</div>

<div class="wrap">

{{-- ─── FILTER BAR ──────────────────────────────────────────────────────── --}}
<div class="filter-bar">
    <table>
        <tr>
            <td style="width: 165px;">
                <span class="fg-label">Period</span>
                <div class="select-box">
                    <div style="float: left; width: 125px; padding-left: 10px; padding-top: 7.5px;">
                        <span style="font-size: 9px; font-weight: 500; color: #374151;">
                            {{ $judulPeriode }}
                        </span>
                    </div>
                    <div style="float: right; width: 20px; padding-top: 11.5px;">
                        <div class="select-arrow"></div>
                    </div>
                    <div style="clear: both;"></div>
                </div>
            </td>
            <td style="width: 165px;">
                <span class="fg-label">Group</span>
                <div class="select-box">
                    <div style="float: left; width: 125px; padding-left: 10px; padding-top: 7.5px;">
                        <span style="font-size: 9px; font-weight: 500; color: #374151;">
                            {{ $groupLabel }}
                        </span>
                    </div>
                    <div style="float: right; width: 20px; padding-top: 11.5px;">
                        <div class="select-arrow"></div>
                    </div>
                    <div style="clear: both;"></div>
                </div>
            </td>
            <td style="width: 165px;">
                <span class="fg-label">Status</span>
                <div class="select-box">
                    <div style="float: left; width: 125px; padding-left: 10px; padding-top: 7.5px;">
                        <span style="font-size: 9px; font-weight: 500; color: #374151;">
                            {{ $statusLabel }}
                        </span>
                    </div>
                    <div style="float: right; width: 20px; padding-top: 11.5px;">
                        <div class="select-arrow"></div>
                    </div>
                    <div style="clear: both;"></div>
                </div>
            </td>
            <td style="text-align:right; vertical-align:middle;">
                <span style="font-size:7.5px; color:#9ca3af; font-weight:600;">Generated: {{ now()->format('d M Y H:i') }}</span>
            </td>
        </tr>
    </table>
</div>

{{-- ─── SUMMARY + PIE CHART ─────────────────────────────────────────────── --}}
@php
    $chartTotal = $totalAchieve + $totalNonAchieve + $totalNoData;
@endphp
<div class="top-section">
    <table>
        <tr>
            {{-- Summary cards on the left --}}
            <td class="summary-container-td">
                <table class="summary-table">
                    <tr>
                        {{-- Achieve card --}}
                        <td class="sc-card achieve">
                            <img src="{{ $getSrc($checkmarkSvg) }}" width="18" height="18" style="float: right; opacity: 0.85;" alt="check">
                            <div class="sc-num">{{ $totalAchieve }}</div>
                            <div class="sc-label">Achieve Items<br>(including over-achieve)</div>
                        </td>

                        {{-- Non-Achieve card --}}
                        <td class="sc-card non-achieve">
                            <img src="{{ $getSrc($crossSvg) }}" width="18" height="18" style="float: right; opacity: 0.85;" alt="cross">
                            <div class="sc-num">{{ $totalNonAchieve }}</div>
                            <div class="sc-label">Non-Achieve Items</div>
                        </td>

                        {{-- No Data card --}}
                        <td class="sc-card no-data">
                            <img src="{{ $getSrc($infoSvg) }}" width="18" height="18" style="float: right; opacity: 0.85;" alt="info">
                            <div class="sc-num">{{ $totalNoData }}</div>
                            <div class="sc-label">No Data Yet</div>
                        </td>
                    </tr>
                </table>
            </td>

            {{-- Pie chart on the right --}}
            <td class="chart-container-td">
                <div class="pie-card">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 50%; text-align: center; vertical-align: middle; padding: 0;">
                                @if($chartTotal > 0)
                                    @php
                                        $cx = 36;
                                        $cy = 36;
                                        $r = 18;
                                        $circumference = 2 * M_PI * $r;
                                        $offset = 0;
                                        $segments = [
                                            ['value' => $totalAchieve,    'color' => '#16a34a'],
                                            ['value' => $totalNonAchieve, 'color' => '#dc2626'],
                                            ['value' => $totalNoData,     'color' => '#9ca3af'],
                                        ];
                                    @endphp
                                    <svg width="70" height="70" viewBox="0 0 72 72">
                                        <!-- background circle -->
                                        <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}" fill="#eef0f3" />
                                        @foreach($segments as $seg)
                                            @if($seg['value'] > 0)
                                                @php
                                                    $dash = ($seg['value'] / $chartTotal) * $circumference;
                                                    $gap = $circumference - $dash;
                                                @endphp
                                                <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}" 
                                                        fill="none" 
                                                        stroke="{{ $seg['color'] }}" 
                                                        stroke-width="36" 
                                                        stroke-dasharray="{{ $dash }} {{ $gap }}" 
                                                        stroke-dashoffset="{{ -$offset }}" 
                                                        transform="rotate(-90 {{ $cx }} {{ $cy }})" />
                                                @php $offset += $dash; @endphp
                                            @endif
                                        @endforeach
                                        
                                        {{-- render inner labels inside slices --}}
                                        @php
                                            $off2 = 0;
                                            $textR = 10;
                                        @endphp
                                        @foreach($segments as $seg)
                                            @if($seg['value'] > 0)
                                                @php
                                                    $slicePct = $seg['value'] / $chartTotal;
                                                    $midAngle = -90 + ($off2 / $circumference) * 360 + ($slicePct * 360 / 2);
                                                    $lx = $cx + $textR * cos(deg2rad($midAngle));
                                                    $ly = $cy + $textR * sin(deg2rad($midAngle));
                                                    $pctText = round($slicePct * 100) . '%';
                                                    $off2 += $slicePct * $circumference;
                                                @endphp
                                                <text x="{{ $lx }}" y="{{ $ly }}" text-anchor="middle" dominant-baseline="central" fill="#ffffff" font-size="5.5" font-family="Helvetica" font-weight="bold">{{ $pctText }}</text>
                                            @endif
                                        @endforeach
                                    </svg>
                                @endif
                            </td>
                            <td class="chart-legend" style="vertical-align: middle; padding-left: 6px;">
                                <div class="chart-legend-item">
                                    <span class="dot achieve"></span>Ach ({{ $chartTotal ? round($totalAchieve/$chartTotal*100) : 0 }}%)
                                </div>
                                <div class="chart-legend-item">
                                    <span class="dot non-achieve"></span>Non-Ach ({{ $chartTotal ? round($totalNonAchieve/$chartTotal*100) : 0 }}%)
                                </div>
                                <div class="chart-legend-item">
                                    <span class="dot no-data"></span>No Data ({{ $chartTotal ? round($totalNoData/$chartTotal*100) : 0 }}%)
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
</div>

{{-- ─── ITEM CARDS ──────────────────────────────────────────────────────── --}}
@if(count($items) === 0)
    <p style="text-align:center;color:#9ca3af;padding:20px;">No data available.</p>
@else
    @php $rows = array_chunk($items, 5); @endphp
    @foreach($rows as $row)
        <table class="cards">
            <tr>
                @foreach($row as $item)
                    @php
                        $isNoData    = $item['kategori'] === 'tidak_ada_data';
                        $sc = $isNoData ? 'no-data' : ($item['kategori']==='kurang' ? 'non-achieve' : 'achieve');
                        $trendPct  = $item['trend_pct'] ?? null;
                        $trendNaik = $item['trend_naik'] ?? null;
                        $hasTrend  = !$isNoData && $trendPct !== null;
                        $badgeLabel = match($item['kategori']) {
                            'kurang'    => 'NON-ACHIEVE',
                            'tercapai'  => 'ACHIEVE',
                            'melampaui' => 'OVER-ACHIEVE',
                            default     => 'NO DATA',
                        };
                        $badgePct = $isNoData ? '&#x2014;' : ($item['persen_achievement'] . '%');

                        // Pick SVG emoji source
                        $faceSvg = match(true) {
                            $isNoData => $neutralFaceSvg,
                            $item['kategori'] === 'kurang' => $sadFaceSvg,
                            $item['kategori'] === 'tercapai' => $happyFaceSvg,
                            default => $overAchieveFaceSvg
                        };
                    @endphp
                    <td class="card {{ $sc }}">

                        {{-- Group tag --}}
                        <div class="group-tag">Group {{ $item['kode_group'] }}</div>

                        {{-- Item name --}}
                        <div class="item-name">{{ $item['nama_item'] }}</div>

                        {{-- Badge + face --}}
                        <div style="margin-bottom: 8px;">
                            <div class="badge-box" style="float: left; width: 68%;">
                                <span class="badge-label">{{ $badgeLabel }}</span>
                                <span class="badge-pct {{ $sc }}">{!! $badgePct !!}</span>
                            </div>
                            <div style="float: right; width: 28%; text-align: right; padding-top: 3px;">
                                <img src="{{ $getSrc($faceSvg) }}" width="20" height="20" alt="face">
                            </div>
                            <div style="clear: both;"></div>
                        </div>

                        {{-- Target / Actual + Trend --}}
                        <div>
                            <div class="meta" style="float: left; width: 56%;">
                                Target: {{ $item['nilai_target'] !== null ? number_format($item['nilai_target'], 2) : '-' }} {{ $item['satuan'] }}<br>
                                Actual: {{ $item['nilai_actual'] !== null ? number_format($item['nilai_actual'], 2) : '-' }} {{ $item['satuan'] }}
                            </div>
                            <div style="float: right; width: 42%; text-align: right;">
                                @if($isNoData)
                                    <span class="trend-muted">No report yet</span>
                                @elseif(!$hasTrend)
                                    <span class="trend-muted">vs last month: -</span>
                                @else
                                    <span class="t-label">vs last month</span>
                                    <div style="display: inline-block;">
                                        @if($trendNaik)
                                            <img src="{{ $getSrc($trendUpSvg) }}" class="trend-icon" alt="up">
                                            <span class="trend-text up">+{{ abs($trendPct) }}%</span>
                                        @else
                                            <img src="{{ $getSrc($trendDownSvg) }}" class="trend-icon" alt="down">
                                            <span class="trend-text down">-{{ abs($trendPct) }}%</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div style="clear: both;"></div>
                        </div>

                    </td>
                @endforeach
                @for($i = count($row); $i < 5; $i++)
                    <td class="card empty"></td>
                @endfor
            </tr>
        </table>
    @endforeach
@endif

</div>{{-- /wrap --}}
</body>
</html>
