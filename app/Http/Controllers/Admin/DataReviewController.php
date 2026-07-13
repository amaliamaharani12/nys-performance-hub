<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Actual;
use App\Models\Group;
use App\Models\RevisionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataReviewController extends Controller
{
    public function index(Request $request)
    {
        $groups = Group::orderBy('kode_group')->get();

        $query = Actual::with(['metric.group', 'inputBy'])
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 WHEN status = 'draft' THEN 1 ELSE 2 END")
            ->orderBy('created_at', 'desc');

        if ($request->filled('group_id')) {
            $query->whereHas('metric', fn($q) => $q->where('group_id', $request->group_id));
        }

        if ($request->filled('status') && in_array($request->status, ['pending', 'draft', 'approved'])) {
            $query->where('status', $request->status);
        }

        if ($request->filled('periode')) {
            $parts = explode('-', $request->periode); // [YYYY, MM]
            if (count($parts) === 2) {
                $query->whereYear('periode', $parts[0])
                    ->whereMonth('periode', $parts[1]);
            }
        }

        $actuals = $query->paginate(20)->withQueryString();

        return view('admin.data.index', compact('actuals', 'groups'));
    }

    public function approve(Actual $actual)
    {
        abort_if($actual->status !== 'pending', 422, 'Data is not in pending status.');

        $actual->update(['status' => 'approved', 'catatan' => null]);

        return redirect()->route('admin.data.index')
            ->with('success', "Data \"{$actual->metric->nama_item}\" for period {$actual->periode->format('M Y')} has been successfully approved.");
    }

    public function revise(Actual $actual)
    {
        abort_if(!in_array($actual->status, ['pending', 'approved']), 422, 'Data cannot be revised.');
        $actual->load('metric.group', 'inputBy', 'revisionLogs.revisedBy');
        return view('admin.data.revise', compact('actual'));
    }

    public function storeRevision(Request $request, Actual $actual)
    {
        abort_if(!in_array($actual->status, ['pending', 'approved']), 422, 'Data cannot be revised.');

        $request->validate([
            'nilai_baru' => ['required', 'numeric', 'min:0'],
            'alasan' => ['required', 'string', 'max:500'],
        ], [
            'nilai_baru.required' => 'New value is required.',
            'nilai_baru.numeric' => 'Value must be a number.',
            'alasan.required' => 'Revision reason is required.',
        ]);

        $nilaiLama = $actual->nilai_actual;

        RevisionLog::create([
            'actual_id' => $actual->id,
            'revised_by' => Auth::id(),
            'nilai_lama' => $nilaiLama,
            'nilai_baru' => $request->nilai_baru,
            'alasan' => $request->alasan,
        ]);

        $actual->update([
            'nilai_actual' => $request->nilai_baru,
            'status' => 'approved',
            'catatan' => null,
        ]);

        return redirect()->route('admin.data.index')
            ->with('success', "Data \"{$actual->metric->nama_item}\" has been successfully revised and approved.");
    }

    public function reject(Request $request, Actual $actual)
    {
        abort_if($actual->status !== 'pending', 422, 'Data is not in pending status.');

        $request->validate([
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $actual->update([
            'status' => 'draft',
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('admin.data.index')
            ->with('success', "Data \"{$actual->metric->nama_item}\" has been returned to the PIC.");
    }
}
