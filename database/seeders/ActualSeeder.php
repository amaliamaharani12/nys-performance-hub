<?php

namespace Database\Seeders;

use App\Models\Actual;
use App\Models\Metric;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ActualSeeder extends Seeder
{
    public function run(): void
    {
        // Siapkan lookup user PIC per kode group, misal 'A' => id user pic.a@nys.test
        $picByGroup = [];
        foreach (range('A', 'K') as $kode) {
            $user = User::where('email', 'pic.' . strtolower($kode) . '@nys.test')->first();
            if ($user) {
                $picByGroup[$kode] = $user->id;
            }
        }

        $months = [];
        $start = Carbon::create(2025, 7, 1);
        for ($i = 0; $i < 12; $i++) {
            $months[] = $start->copy()->addMonths($i);
        }

        // Nilai actual per bulan, urutan sesuai urutan metric di MetricSeeder (52 baris)
        $actualPerMetric = [
            [165.779, 147.525, 159.048, 152.134, 142.114, null, null, null, null, null, null, null],
            [3, 1, 0, 1, 0, null, null, null, null, null, null, null],
            [1.848, 0.67, 0, 0.62, 0, null, null, null, null, null, null, null],
            [null, null, null, null, null, null, null, null, null, null, null, null],
            [null, null, null, null, null, null, null, null, null, null, null, null],
            [null, null, null, null, null, null, null, null, null, null, null, null],
            [null, null, null, null, null, null, null, null, null, null, null, null],
            [0.067, 0, 0.074, 0.071, 0.075, null, null, null, null, null, null, null],
            [6467.206, 3403.425, 4388.329, 6247.365, 6064.41, null, null, null, null, null, null, null],
            [2443, 2526, 2521, 2310, 2297, 0, 0, 0, 0, 0, 0, 0],
            [1177, 1189, 1269, 1135, 1127, null, null, null, null, null, null, null],
            [785, 851, 769, 705, 708, null, null, null, null, null, null, null],
            [421, 425, 423, 410, 404, null, null, null, null, null, null, null],
            [60, 61, 60, 60, 58, null, null, null, null, null, null, null],
            [67, 62, 62, 52, 52, null, null, null, null, null, null, null],
            [165.779, 147.525, 159.048, 152.134, 142.114, 0, 0, 0, 0, 0, 0, 0],
            [80.833, 82.031, 83.144, 83.219, 84.023, null, null, null, null, null, null, null],
            [73.573, 76.598, 75.013, 71.389, 74.135, null, null, null, null, null, null, null],
            [2.961, 2.224, 4.162, 5.013, 5.004, null, null, null, null, null, null, null],
            [6.02, 4.399, 5.616, 9.203, 6.765, null, null, null, null, null, null, null],
            [44.245, 44.852, 45.738, 42.088, 45.274, null, null, null, null, null, null, null],
            [35.776, 36.015, 37.024, 33.823, 36.585, null, null, null, null, null, null, null],
            [null, null, null, null, null, null, null, null, null, null, null, null],
            [67.859, 58.403, 63.089, 65.859, 61.869, null, null, null, null, null, null, null],
            [51.822, 52.93, 49.663, 50.866, 50.936, null, null, null, null, null, null, null],
            [96.952, 97.27, 97.097, 97.168, 97.67, null, null, null, null, null, null, null],
            [96.87, 96.233, 97.097, 93.539, 97.67, null, null, null, null, null, null, null],
            [0.279, 0.155, 0.194, 9.356, 0.553, null, null, null, null, null, null, null],
            [10.327, 9.407, 9.88, 9.45, 9.081, null, null, null, null, null, null, null],
            [0.233, 0.2, null, null, null, null, null, null, null, null, null, null],
            [162.312, 149.213, 152.325, 161.243, 139.671, null, null, null, null, null, null, null],
            [7706.761, 7189.243, 7274.87, 7801.5, 6818.198, null, null, null, null, null, null, null],
            [19.68, 20.991, 21.751, 19.614, 19.854, null, null, null, null, null, null, null],
            [188.242, 192.32, 156.392, 136.576, 196.852, null, null, null, null, null, null, null],
            [null, null, null, null, null, null, null, null, null, null, null, null],
            [1.186, 9.244, 11.661, 16.149, 9.219, null, null, null, null, null, null, null],
            [3.45, 0.155, 0, 0.238, 0, null, null, null, null, null, null, null],
            [6.796, 0.747, 0.71, 5.604, 0.158, null, null, null, null, null, null, null],
            [193.177, 86.691, 142.748, 131.502, 170.661, null, null, null, null, null, null, null],
            [25.312, 24.012, 22.312, 25.386, 20.944, null, null, null, null, null, null, null],
            [2.18, 2.236, 1.974, 1.851, 2.131, null, null, null, null, null, null, null],
            [5.865, 5.684, 5.867, 5.841, 5.355, null, null, null, null, null, null, null],
            [1128, 1156.591, 1171.205, 1176.32, 1181.098, null, null, null, null, null, null, null],
            [998.619, 979.667, 1015.466, 988.924, 1038.483, null, null, null, null, null, null, null],
            [0.552, 0.563, 0.565, 0.565, 0.554, null, null, null, null, null, null, null],
            [-2.87, -3.579, -8.085, -12.826, -7.509, null, null, null, null, null, null, null],
            [0.144, 0, 0.076, 0.065, 0.455, null, null, null, null, null, null, null],
            [100, 100, 100, 100, 100, null, null, null, null, null, null, null],
            [0, 0, 0, 0, 0, null, null, null, null, null, null, null],
            [0.352, 4.218, 0, 0, 0, null, null, null, null, null, null, null],
            [0.008, 0, 0, 16.873, 0, null, null, null, null, null, null, null],
            [39.519, 18.035, 29.948, 27.377, 36.925, null, null, null, null, null, null, null],
        ];

        $metrics = Metric::with('group')->orderBy('id')->get();

        foreach ($metrics as $index => $metric) {
            $actualValues = $actualPerMetric[$index] ?? array_fill(0, 12, null);
            $inputBy = $picByGroup[$metric->group->kode_group] ?? 1;

            foreach ($months as $i => $month) {
                $val = $actualValues[$i];
                
                if ($val === null) {
                    // Look up target to generate mock actual
                    $target = \App\Models\Target::where('metric_id', $metric->id)
                        ->where('periode_mulai', $month->format('Y-m-d'))
                        ->first();
                    
                    if ($target) {
                        $targetVal = (float) $target->nilai_target;
                        // Use a deterministic seed/hash for consistent mock values
                        $hash = crc32($metric->id . $month->format('Y-m-d'));
                        $percent = 90 + ($hash % 16); // generates 90% to 105%
                        $val = round($targetVal * ($percent / 100), 3);
                    } else {
                        continue;
                    }
                }

                Actual::create([
                    'metric_id' => $metric->id,
                    'periode' => $month->format('Y-m-d'),
                    'nilai_actual' => $val,
                    'input_by' => $inputBy,
                    'sumber' => 'upload',
                    'status' => 'approved',
                ]);
            }
        }
    }
}