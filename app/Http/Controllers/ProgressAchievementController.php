<?php

namespace App\Http\Controllers;

use App\Models\Actual;
use App\Models\Group;
use App\Models\Metric;
use App\Models\Target;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProgressAchievementController extends Controller
{
    public function index(Request $request)
    {
        $groups = Group::orderBy('kode_group')->get();

        $availablePeriods = Target::selectRaw('DISTINCT periode_mulai')
            ->orderBy('periode_mulai', 'desc')
            ->pluck('periode_mulai');

        $periode = $request->input('periode', optional($availablePeriods->first())->format('Y-m-d'));
        $groupId = $request->input('group_id', 'all');
        $status = $request->input('status', 'all');

        $metricsQuery = Metric::with('group')->where('is_aktif', true);

        if ($groupId !== 'all') {
            $metricsQuery->where('group_id', $groupId);
        }

        $metrics = $metricsQuery->orderBy('group_id')->orderBy('id')->get();

        // Periode bulan sebelumnya, buat bandingin trend naik/turun actual vs bulan lalu
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

            // Belum ada Target dan/atau Actual yang diinput untuk periode ini
            // (di Excel: sel kosong) -> tampilkan sebagai "No Data", bukan di-skip
            if (!$target || !$actual) {
                $items[] = [
                    'metric_id' => $metric->id,
                    'nama_item' => $metric->nama_item,
                    'kode_group' => $metric->group->kode_group,
                    'satuan' => $metric->satuan,
                    'arah_target' => $metric->arah_target,
                    'nilai_target' => $target ? (float) $target->nilai_target : null,
                    'nilai_actual' => $actual ? (float) $actual->nilai_actual : null,
                    'persen_achievement' => null,
                    'kategori' => 'tidak_ada_data',
                    'is_achieve' => null,
                    'trend_pct' => null,
                    'trend_naik' => null,
                ];
                continue;
            }

            $nilaiTarget = (float) $target->nilai_target;
            $nilaiActual = (float) $actual->nilai_actual;

            // Hitung persen achievement — handle semua edge case tanpa skip
            if ($nilaiTarget == 0 && $nilaiActual == 0) {
                // Zero-tolerance metric (mis. 0 kecelakaan) dan tercapai
                $persenAchievement = 100.0;
            } elseif ($nilaiTarget == 0) {
                // Target 0 tapi actual ada → untuk 'turun' berarti gagal, untuk 'naik' juga aneh
                $persenAchievement = $metric->arah_target === 'naik' ? 0.0 : 0.0;
            } elseif ($metric->arah_target !== 'naik' && $nilaiActual == 0) {
                // Arah turun, actual 0 → target/0 = infinity, treat as 0%
                $persenAchievement = 0.0;
            } elseif ($metric->arah_target === 'naik') {
                $persenAchievement = ($nilaiActual / $nilaiTarget) * 100;
            } else {
                $persenAchievement = ($nilaiTarget / $nilaiActual) * 100;
            }

            $persenAchievement = round($persenAchievement, 1);

            if ($persenAchievement > 100) {
                $kategori = 'melampaui';
            } elseif ($persenAchievement == 100) {
                $kategori = 'tercapai';
            } else {
                $kategori = 'kurang';
            }

            $isAchieve = $persenAchievement >= 100;

            // Trend: naik/turun nilai actual dibanding bulan sebelumnya (bukan dibanding target)
            $trendPct = null;
            $trendNaik = null;
            if ($actualSebelumnya) {
                $nilaiSebelumnya = (float) $actualSebelumnya->nilai_actual;
                if ($nilaiSebelumnya != 0) {
                    $trendPct = round((($nilaiActual - $nilaiSebelumnya) / abs($nilaiSebelumnya)) * 100, 1);
                    $trendNaik = $nilaiActual >= $nilaiSebelumnya;
                } elseif ($nilaiActual == 0) {
                    // Sama-sama 0, ga ada perubahan
                    $trendPct = 0.0;
                    $trendNaik = true;
                }
                // Kalau bulan lalu 0 dan sekarang > 0, persen kenaikannya ga terhingga,
                // jadi $trendPct dibiarkan null (ga bisa ditampilkan sebagai %)
            }

            $items[] = [
                'metric_id' => $metric->id,
                'nama_item' => $metric->nama_item,
                'kode_group' => $metric->group->kode_group,
                'satuan' => $metric->satuan,
                'arah_target' => $metric->arah_target,
                'nilai_target' => $nilaiTarget,
                'nilai_actual' => $nilaiActual,
                'persen_achievement' => $persenAchievement,
                'kategori' => $kategori,
                'is_achieve' => $isAchieve,
                'trend_pct' => $trendPct,
                'trend_naik' => $trendNaik,
            ];
        }

        $totalAchieve = collect($items)->filter(fn($i) => $i['is_achieve'] === true)->count();
        $totalNonAchieve = collect($items)->filter(fn($i) => $i['is_achieve'] === false)->count();
        $totalNoData = collect($items)->filter(fn($i) => $i['kategori'] === 'tidak_ada_data')->count();

        if ($status === 'achieve') {
            $items = array_values(array_filter($items, fn($i) => $i['is_achieve'] === true));
        } elseif ($status === 'non_achieve') {
            $items = array_values(array_filter($items, fn($i) => $i['is_achieve'] === false));
        } elseif ($status === 'tidak_ada_data') {
            $items = array_values(array_filter($items, fn($i) => $i['kategori'] === 'tidak_ada_data'));
        }

        return view('progress-achievement', compact(
            'groups',
            'availablePeriods',
            'periode',
            'groupId',
            'status',
            'items',
            'totalAchieve',
            'totalNonAchieve',
            'totalNoData'
        ));
    }

    public function detail(Request $request, Metric $metric)
    {
        $targets = Target::where('metric_id', $metric->id)
            ->orderBy('periode_mulai')
            ->get();

        $actuals = Actual::where('metric_id', $metric->id)
            ->where('status', 'approved')
            ->orderBy('periode')
            ->get()
            ->keyBy(fn($a) => $a->periode->format('Y-m'));

        $history = [];
        foreach ($targets as $target) {
            $key = $target->periode_mulai->format('Y-m');
            $actual = $actuals->get($key);

            $history[] = [
                'periode' => $target->periode_mulai->format('M Y'),
                'target' => (float) $target->nilai_target,
                'actual' => $actual ? (float) $actual->nilai_actual : null,
            ];
        }

        return response()->json([
            'nama_item' => $metric->nama_item,
            'satuan' => $metric->satuan,
            'arah_target' => $metric->arah_target,
            'history' => $history,
        ]);
    }
}