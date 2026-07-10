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

        $items = [];

        foreach ($metrics as $metric) {
            $target = Target::where('metric_id', $metric->id)
                ->where('periode_mulai', $periode)
                ->first();

            $actual = Actual::where('metric_id', $metric->id)
                ->where('periode', $periode)
                ->where('status', 'approved')
                ->first();

            if (!$target || !$actual) {
                continue;
            }

            $nilaiTarget = (float) $target->nilai_target;
            $nilaiActual = (float) $actual->nilai_actual;

            if ($nilaiTarget == 0) {
                continue;
            }

            // Skip 'turun' metrics where actual is 0 (division by zero would give INF)
            if ($metric->arah_target !== 'naik' && $nilaiActual == 0) {
                continue;
            }

            if ($metric->arah_target === 'naik') {
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
            ];
        }

        $totalAchieve = collect($items)->where('is_achieve', true)->count();
        $totalNonAchieve = collect($items)->where('is_achieve', false)->count();

        if ($status === 'achieve') {
            $items = array_values(array_filter($items, fn($i) => $i['is_achieve'] === true));
        } elseif ($status === 'non_achieve') {
            $items = array_values(array_filter($items, fn($i) => $i['is_achieve'] === false));
        }

        return view('progress-achievement', compact(
            'groups',
            'availablePeriods',
            'periode',
            'groupId',
            'status',
            'items',
            'totalAchieve',
            'totalNonAchieve'
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