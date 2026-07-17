<?php

namespace App\Http\Controllers;

use App\Models\Actual;
use App\Models\Group;
use App\Models\Metric;
use App\Models\Target;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportController extends Controller
{
    private function buildItems(Request $request): array
    {
        $periode = $request->input('periode');
        $groupId = $request->input('group_id', 'all');
        $status = $request->input('status', 'all');

        $metricsQuery = Metric::with('group')->where('is_aktif', true);

        if ($groupId !== 'all') {
            $metricsQuery->where('group_id', $groupId);
        }

        $metrics = $metricsQuery->orderBy('group_id')->orderBy('id')->get();

        $periodeSebelumnya = $periode ? Carbon::parse($periode)->subMonthNoOverflow()->format('Y-m-d') : null;

        $items = [];

        foreach ($metrics as $metric) {
            $target = Target::where('metric_id', $metric->id)
                ->where('periode_mulai', $periode)
                ->first();

            $actual = Actual::where('metric_id', $metric->id)
                ->where('periode', $periode)
                ->where('status', 'approved')
                ->first();

            $actualSebelumnya = $periodeSebelumnya
                ? Actual::where('metric_id', $metric->id)
                    ->where('periode', $periodeSebelumnya)
                    ->where('status', 'approved')
                    ->first()
                : null;

            if (!$target || !$actual) {
                $items[] = [
                    'kode_group' => $metric->group->kode_group,
                    'nama_item' => $metric->nama_item,
                    'satuan' => $metric->satuan,
                    'arah_target' => $metric->arah_target,
                    'nilai_target' => $target ? (float) $target->nilai_target : null,
                    'nilai_actual' => $actual ? (float) $actual->nilai_actual : null,
                    'persen_achievement' => null,
                    'kategori' => 'tidak_ada_data',
                    'is_achieve' => null,
                    'label' => 'No Data',
                    'trend_pct' => null,
                    'trend_naik' => null,
                ];
                continue;
            }

            $nilaiTarget = (float) $target->nilai_target;
            $nilaiActual = (float) $actual->nilai_actual;

            if ($nilaiTarget == 0 && $nilaiActual == 0) {
                $persenAchievement = 100.0;
            } elseif ($nilaiTarget == 0) {
                $persenAchievement = 0.0;
            } elseif ($metric->arah_target !== 'naik' && $nilaiActual == 0) {
                $persenAchievement = 0.0;
            } elseif ($metric->arah_target === 'naik') {
                $persenAchievement = ($nilaiActual / $nilaiTarget) * 100;
            } else {
                $persenAchievement = ($nilaiTarget / $nilaiActual) * 100;
            }

            $persenAchievement = round($persenAchievement, 1);

            if ($persenAchievement > 100) {
                $kategori = 'melampaui';
                $label = 'Over-Achieve';
            } elseif ($persenAchievement == 100) {
                $kategori = 'tercapai';
                $label = 'Achieve';
            } else {
                $kategori = 'kurang';
                $label = 'Non-Achieve';
            }

            $trendPct = null;
            $trendNaik = null;
            if ($actualSebelumnya) {
                $nilaiSebelumnya = (float) $actualSebelumnya->nilai_actual;
                if ($nilaiSebelumnya != 0) {
                    $trendPct = round((($nilaiActual - $nilaiSebelumnya) / abs($nilaiSebelumnya)) * 100, 1);
                    $trendNaik = $nilaiActual >= $nilaiSebelumnya;
                } elseif ($nilaiActual == 0) {
                    $trendPct = 0.0;
                    $trendNaik = true;
                }
            }

            $items[] = [
                'kode_group' => $metric->group->kode_group,
                'nama_item' => $metric->nama_item,
                'satuan' => $metric->satuan,
                'arah_target' => $metric->arah_target,
                'nilai_target' => $nilaiTarget,
                'nilai_actual' => $nilaiActual,
                'persen_achievement' => $persenAchievement,
                'kategori' => $kategori,
                'is_achieve' => $persenAchievement >= 100,
                'label' => $label,
                'trend_pct' => $trendPct,
                'trend_naik' => $trendNaik,
            ];
        }

        if ($status === 'achieve') {
            $items = array_values(array_filter($items, fn($i) => $i['is_achieve'] === true));
        } elseif ($status === 'non_achieve') {
            $items = array_values(array_filter($items, fn($i) => $i['is_achieve'] === false));
        } elseif ($status === 'tidak_ada_data') {
            $items = array_values(array_filter($items, fn($i) => $i['kategori'] === 'tidak_ada_data'));
        }

        return $items;
    }

    private function judulPeriode(?string $periode): string
    {
        return $periode ? Carbon::parse($periode)->format('F Y') : '-';
    }

    /**
     * Human-readable label for the Group filter, used in the PDF/Excel header.
     */
    private function groupLabel(Request $request): string
    {
        $groupId = $request->input('group_id', 'all');

        if ($groupId === 'all') {
            return 'All Groups';
        }

        $group = Group::find($groupId);

        return $group ? 'Group ' . $group->kode_group : 'All Groups';
    }

    /**
     * Human-readable label for the Status filter, used in the PDF/Excel header.
     */
    private function statusLabel(Request $request): string
    {
        return match ($request->input('status', 'all')) {
            'achieve' => 'Achieve',
            'non_achieve' => 'Non-Achieve',
            'tidak_ada_data' => 'No Data',
            default => 'All',
        };
    }

    /**
     * Counts used for the summary boxes at the top of the PDF/image export.
     */
    private function summaryCounts(array $items): array
    {
        $achieve = 0;
        $nonAchieve = 0;
        $noData = 0;

        foreach ($items as $item) {
            if ($item['kategori'] === 'tidak_ada_data') {
                $noData++;
            } elseif ($item['is_achieve']) {
                $achieve++;
            } else {
                $nonAchieve++;
            }
        }

        return compact('achieve', 'nonAchieve', 'noData');
    }

    public function excel(Request $request)
    {
        $items = $this->buildItems($request);
        $judulPeriode = $this->judulPeriode($request->input('periode'));

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Progress Achievement');

        $sheet->setCellValue('A1', 'Progress Achievement - ' . $judulPeriode);
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $sheet->setCellValue('A2', $this->groupLabel($request) . ' • ' . $this->statusLabel($request) . ' • Generated ' . now()->format('d M Y H:i'));
        $sheet->mergeCells('A2:G2');
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(9)->getColor()->setRGB('6B7280');

        $headers = ['Group', 'Item', 'Unit', 'Target', 'Actual', 'Achievement (%)', 'Status'];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '3', $h);
            $col++;
        }
        $sheet->getStyle('A3:G3')->getFont()->setBold(true);
        $sheet->getStyle('A3:G3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E5E7EB');

        $row = 4;
        foreach ($items as $item) {
            $sheet->setCellValue('A' . $row, $item['kode_group']);
            $sheet->setCellValue('B' . $row, $item['nama_item']);
            $sheet->setCellValue('C' . $row, $item['satuan']);
            $sheet->setCellValue('D' . $row, $item['nilai_target'] ?? '-');
            $sheet->setCellValue('E' . $row, $item['nilai_actual'] ?? '-');
            $sheet->setCellValue('F' . $row, $item['persen_achievement'] !== null ? $item['persen_achievement'] . '%' : '-');
            $sheet->setCellValue('G' . $row, $item['label']);
            $row++;
        }

        foreach (range('A', 'G') as $c) {
            $sheet->getColumnDimension($c)->setAutoSize(true);
        }

        $filename = 'progress-achievement-' . str_replace(' ', '-', $judulPeriode) . '.xlsx';

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function pdf(Request $request)
    {
        $items = $this->buildItems($request);
        $judulPeriode = $this->judulPeriode($request->input('periode'));
        $summary = $this->summaryCounts($items);

        $pdf = Pdf::loadView('exports.pdf', [
            'items' => $items,
            'judulPeriode' => $judulPeriode,
            'groupLabel' => $this->groupLabel($request),
            'statusLabel' => $this->statusLabel($request),
            'totalAchieve' => $summary['achieve'],
            'totalNonAchieve' => $summary['nonAchieve'],
            'totalNoData' => $summary['noData'],
        ])->setPaper('a4', 'landscape');

        $filename = 'progress-achievement-' . str_replace(' ', '-', $judulPeriode) . '.pdf';

        return $pdf->stream($filename);
    }
}