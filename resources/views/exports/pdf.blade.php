<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
            color: #111827;
        }

        h1 {
            font-size: 16px;
            margin: 0 0 2px;
        }

        .subtitle {
            font-size: 11px;
            color: #555;
            margin-bottom: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 5px 6px;
            text-align: left;
        }

        th {
            background: #e5e7eb;
        }

        .text-right {
            text-align: right;
        }

        .status-achieve {
            color: #16a34a;
            font-weight: bold;
        }

        .status-nonachieve {
            color: #dc2626;
            font-weight: bold;
        }

        .status-nodata {
            color: #9ca3af;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h1>Progress Achievement</h1>
    <div class="subtitle">
        Periode: {{ $judulPeriode }} &nbsp;|&nbsp; Dicetak: {{ now()->format('d M Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Group</th>
                <th>Item</th>
                <th>Satuan</th>
                <th class="text-right">Target</th>
                <th class="text-right">Actual</th>
                <th class="text-right">Achievement</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($items as $item)
                <tr>
                    <td>{{ $item['kode_group'] }}</td>
                    <td>{{ $item['nama_item'] }}</td>
                    <td>{{ $item['satuan'] }}</td>
                    <td class="text-right">
                        {{ $item['nilai_target'] !== null ? number_format($item['nilai_target'], 2) : '-' }}
                    </td>
                    <td class="text-right">
                        {{ $item['nilai_actual'] !== null ? number_format($item['nilai_actual'], 2) : '-' }}
                    </td>
                    <td class="text-right">
                        {{ $item['persen_achievement'] !== null ? $item['persen_achievement'] . '%' : '-' }}
                    </td>
                    <td
                        class="{{ $item['kategori'] === 'tidak_ada_data' ? 'status-nodata' : ($item['kategori'] === 'kurang' ? 'status-nonachieve' : 'status-achieve') }}">
                        {{ $item['label'] }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Tidak ada data untuk filter ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>

```