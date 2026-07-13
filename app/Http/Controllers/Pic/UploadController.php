<?php

namespace App\Http\Controllers\Pic;

use App\Http\Controllers\Controller;
use App\Models\Actual;
use App\Models\Metric;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class UploadController extends Controller
{
    /**
     * Show upload page and history.
     */
    public function index()
    {
        $user = Auth::user();
        $uploads = Upload::where('user_id', $user->id)
                         ->orderByDesc('created_at')
                         ->paginate(10);

        return view('pic.upload.index', compact('uploads'));
    }

    /**
     * Download CSV template for the PIC's active metrics.
     */
    public function downloadTemplate()
    {
        $user = Auth::user();
        $metrics = Metric::where('group_id', $user->group_id)
                         ->where('is_aktif', true)
                         ->orderBy('nama_item')
                         ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_group_' . strtolower($user->group->kode_group) . '.csv"',
        ];

        $callback = function () use ($metrics) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, [
                'Metric ID',
                'Metric Name',
                'Unit',
                'Period (YYYY-MM)',
                'Actual Value',
                'Notes'
            ]);

            // Pre-fill metrics
            foreach ($metrics as $metric) {
                fputcsv($file, [
                    $metric->id,
                    $metric->nama_item,
                    $metric->satuan ?? '',
                    Carbon::now()->format('Y-m'),
                    '',
                    ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Process the uploaded CSV.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'file_csv' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ]);

        $file = $request->file('file_csv');
        $originalName = $file->getClientOriginalName();
        
        // Save the file to local storage
        $path = $file->storeAs(
            'uploads/' . $user->id,
            time() . '_' . $originalName
        );

        // Create upload entry in DB
        $uploadRecord = Upload::create([
            'user_id' => $user->id,
            'group_id' => $user->group_id,
            'nama_file' => $originalName,
            'path_file' => $path,
            'status' => 'diproses',
        ]);

        $filePath = Storage::path($path);
        
        if (!file_exists($filePath) || !is_readable($filePath)) {
            $uploadRecord->update([
                'status' => 'gagal',
                'catatan_error' => 'Unable to read the uploaded file.',
            ]);
            return back()->with('error', 'Upload failed: File is unreadable.');
        }

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            $uploadRecord->update([
                'status' => 'gagal',
                'catatan_error' => 'Unable to open file stream.',
            ]);
            return back()->with('error', 'Upload failed: Could not open file.');
        }

        // Parse header
        $header = fgetcsv($handle);
        if (!$header || count($header) < 5) {
            fclose($handle);
            $uploadRecord->update([
                'status' => 'gagal',
                'catatan_error' => 'Invalid CSV format. Missing required columns.',
            ]);
            return back()->with('error', 'Upload failed: Invalid CSV template headers.');
        }

        // Collect all metrics for this group for verification
        $validMetricIds = Metric::where('group_id', $user->group_id)
                                ->where('is_aktif', true)
                                ->pluck('id')
                                ->toArray();

        $errors = [];
        $rowsToInsert = [];
        $lineNumber = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $lineNumber++;
            
            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            if (count($row) < 5) {
                $errors[] = "Row {$lineNumber}: Missing data columns.";
                continue;
            }

            $metricId    = trim($row[0]);
            $metricName  = trim($row[1]);
            $period      = trim($row[3]);
            $actualVal   = trim($row[4]);
            $catatan     = isset($row[5]) ? trim($row[5]) : null;

            // 1. Metric ID verification
            if (!in_array($metricId, $validMetricIds)) {
                $errors[] = "Row {$lineNumber}: Metric ID '{$metricId}' is invalid or does not belong to your group.";
                continue;
            }

            // 2. Period format validation
            if (!preg_match('/^\d{4}-\d{2}$/', $period)) {
                $errors[] = "Row {$lineNumber}: Period '{$period}' must be in YYYY-MM format (e.g. 2025-07).";
                continue;
            }

            // 3. Numeric actual value validation
            if ($actualVal === '') {
                // If it is blank, skip it
                continue;
            }

            if (!is_numeric($actualVal) || floatval($actualVal) < 0) {
                $errors[] = "Row {$lineNumber}: Actual Value '{$actualVal}' must be a positive number.";
                continue;
            }

            $rowsToInsert[] = [
                'metric_id'    => intval($metricId),
                'periode'      => $period . '-01',
                'nilai_actual' => floatval($actualVal),
                'catatan'      => $catatan,
            ];
        }

        fclose($handle);

        if (!empty($errors)) {
            $uploadRecord->update([
                'status' => 'gagal',
                'catatan_error' => implode("\n", array_slice($errors, 0, 10)) . (count($errors) > 10 ? "\n...and " . (count($errors) - 10) . " more errors." : ''),
            ]);
            return back()->with('error', 'Upload failed: Validation errors found in CSV. See details in Upload History.');
        }

        if (empty($rowsToInsert)) {
            $uploadRecord->update([
                'status' => 'gagal',
                'catatan_error' => 'No valid data rows found in the CSV.',
            ]);
            return back()->with('error', 'Upload failed: No data rows found to import.');
        }

        // Perform transactional insertion/update
        try {
            DB::beginTransaction();

            foreach ($rowsToInsert as $data) {
                // Upsert logic: if same metric + user + month already exists, update it. Otherwise insert.
                $existing = Actual::where('metric_id', $data['metric_id'])
                                  ->where('input_by', $user->id)
                                  ->where('periode', $data['periode'])
                                  ->first();

                if ($existing) {
                    if ($existing->status === 'approved') {
                        // Skip or throw error? Better to skip or throw error. Let's throw error to prevent overwriting approved data.
                        throw new \Exception("Cannot overwrite approved data for Metric ID {$data['metric_id']} in period " . Carbon::parse($data['periode'])->format('Y-m'));
                    }
                    $existing->update([
                        'nilai_actual' => $data['nilai_actual'],
                        'status' => 'pending',
                        'catatan' => $data['catatan'] ?: $existing->catatan,
                    ]);
                } else {
                    Actual::create([
                        'metric_id' => $data['metric_id'],
                        'periode' => $data['periode'],
                        'nilai_actual' => $data['nilai_actual'],
                        'input_by' => $user->id,
                        'sumber' => 'upload',
                        'status' => 'pending',
                        'catatan' => $data['catatan'],
                    ]);
                }
            }

            DB::commit();

            $uploadRecord->update([
                'status' => 'berhasil',
            ]);

            return redirect()->route('pic.upload.index')
                             ->with('success', 'CSV data uploaded successfully. All entries submitted for review.');

        } catch (\Exception $e) {
            DB::rollBack();
            $uploadRecord->update([
                'status' => 'gagal',
                'catatan_error' => 'Database error: ' . $e->getMessage(),
            ]);
            return back()->with('error', 'Upload failed: ' . $e->getMessage());
        }
    }
}
