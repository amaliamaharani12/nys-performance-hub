<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        * { box-sizing: border-box; }

        body { font-family: sans-serif; font-size: 10px; color: #1f2937; }

        .header { margin-bottom: 12px; }
        .header h1 { font-size: 18px; margin: 0 0 3px; color: #1d4ed8; }
        .header .subtitle { font-size: 10px; color: #6b7280; }

        /* Summary boxes */
        table.summary { width: 100%; border-collapse: collapse; margin: 12px 0; }
        table.summary td {
            width: 33.33%; border: 1px solid #e5e7eb; border-radius: 6px;
            padding: 8px 10px; vertical-align: top;
        }
        table.summary .num { font-size: 20px; font-weight: bold; }
        table.summary .label { font-size: 9px; color: #6b7280; }
        .num.achieve { color: #16a34a; }
        .num.non-achieve { color: #dc2626; }
        .num.no-data { color: #9ca3af; }

        /* Item cards */
        table.cards { width: 100%; border-collapse: separate; border-spacing: 4px; margin-top: 6px; }
        table.cards td.card {
            width: 33.33%; border: 1px solid #e5e7eb; border-top: 3px solid #d1d5db; border-radius: 6px;
            padding: 8px 9px; vertical-align: top;
        }
        table.cards td.card.achieve { border-top-color: #16a34a; }
        table.cards td.card.non-achieve { border-top-color: #dc2626; }
        table.cards td.card.no-data { border-top-color: #9ca3af; }
        .card .group { font-size: 8.5px; color: #6b7280; }
        .card .item-name { font-size: 10.5px; font-weight: bold; margin: 1px 0 6px; }
        .card .status-row { margin-bottom: 4px; }
        .card .status-badge {
            display: inline-block; font-size: 8px; font-weight: bold;
            padding: 2px 7px; border-radius: 999px;
        }
        .status-badge.achieve { background: #dcfce7; color: #16a34a; }
        .status-badge.non-achieve { background: #fee2e2; color: #dc2626; }
        .status-badge.no-data { background: #f3f4f6; color: #9ca3af; }
        .card .pct { font-size: 15px; font-weight: bold; margin-bottom: 4px; }
        .pct.achieve { color: #16a34a; }
        .pct.non-achieve { color: #dc2626; }
        .pct.no-data { color: #9ca3af; }
        .card .meta { font-size: 8.5px; color: #4b5563; }
    </style>
</head>

<body>
    <div class="header">
        <h1>Progress Achievement</h1>
        <div class="subtitle">
            Period: {{ $judulPeriode }} &nbsp;|&nbsp; {{ $groupLabel }} &nbsp;|&nbsp; Status: {{ $statusLabel }}
            &nbsp;|&nbsp; Generated: {{ now()->format('d M Y H:i') }}
        </div>
    </div>

    <table class="summary">
        <tr>
            <td>
                <div class="num achieve">{{ $totalAchieve }}</div>
                <div class="label">Achieve Items (including over-achieve)</div>
            </td>
            <td>
                <div class="num non-achieve">{{ $totalNonAchieve }}</div>
                <div class="label">Non-Achieve Items</div>
            </td>
            <td>
                <div class="num no-data">{{ $totalNoData }}</div>
                <div class="label">No Data Yet</div>
            </td>
        </tr>
    </table>

    @if (count($items) === 0)
        <p>No data available for this filter.</p>
    @else
        <table class="cards">
            @php
                $rows = array_chunk($items, 3);
            @endphp
            @foreach ($rows as $row)
                <tr>
                    @foreach ($row as $item)
                        @php
                            $statusClass = $item['kategori'] === 'tidak_ada_data'
                                ? 'no-data'
                                : ($item['kategori'] === 'kurang' ? 'non-achieve' : 'achieve');
                        @endphp
                        <td class="card {{ $statusClass }}">
                            <div class="group">{{ $item['kode_group'] }}</div>
                            <div class="item-name">{{ $item['nama_item'] }}</div>
                            <div class="status-row">
                                <span class="status-badge {{ $statusClass }}">{{ $item['label'] }}</span>
                            </div>
                            <div class="pct {{ $statusClass }}">
                                {{ $item['persen_achievement'] !== null ? $item['persen_achievement'] . '%' : '—' }}
                            </div>
                            <div class="meta">
                                Target:
                                {{ $item['nilai_target'] !== null ? number_format($item['nilai_target'], 2) : '-' }}
                                {{ $item['satuan'] }}
                                <br>
                                Actual:
                                {{ $item['nilai_actual'] !== null ? number_format($item['nilai_actual'], 2) : '-' }}
                                {{ $item['satuan'] }}
                            </div>
                        </td>
                    @endforeach
                    @for ($i = count($row); $i < 3; $i++)
                        <td class="card" style="border: none;"></td>
                    @endfor
                </tr>
            @endforeach
        </table>
    @endif
</body>

</html>