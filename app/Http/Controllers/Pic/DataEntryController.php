<?php

namespace App\Http\Controllers\Pic;

use App\Http\Controllers\Controller;
use App\Models\Actual;
use App\Models\Metric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataEntryController extends Controller
{
    /**
     * List all actuals submitted by the logged-in PIC.
     */
    public function index(Request $request)
    {
        $user   = Auth::user();
        $query  = Actual::with('metric')
                    ->where('input_by', $user->id)
                    ->orderByDesc('periode')
                    ->orderByDesc('created_at');

        // Filter by status
        if ($request->filled('status') && in_array($request->status, ['pending', 'approved', 'draft'])) {
            $query->where('status', $request->status);
        }

        // Filter by period (YYYY-MM)
        if ($request->filled('periode')) {
            $query->where('periode', 'like', $request->periode . '%');
        }

        $actuals = $query->paginate(15)->withQueryString();

        return view('pic.data.index', compact('actuals'));
    }

    /**
     * Show form to create a new actual entry.
     */
    public function create()
    {
        $user    = Auth::user();
        $metrics = Metric::where('group_id', $user->group_id)
                         ->where('is_aktif', true)
                         ->orderBy('nama_item')
                         ->get();

        return view('pic.data.create', compact('metrics'));
    }

    /**
     * Store a newly submitted actual entry.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'metric_id'    => ['required', 'exists:metrics,id'],
            'periode'      => ['required', 'date_format:Y-m'],
            'nilai_actual' => ['required', 'numeric', 'min:0'],
            'catatan'      => ['nullable', 'string', 'max:500'],
        ]);

        // Ensure the metric belongs to this PIC's group
        $metric = Metric::where('id', $data['metric_id'])
                        ->where('group_id', $user->group_id)
                        ->where('is_aktif', true)
                        ->firstOrFail();

        // Check for duplicate: same metric + same period by this user
        $existing = Actual::where('metric_id', $metric->id)
                          ->where('input_by', $user->id)
                          ->where('periode', 'like', $data['periode'] . '%')
                          ->first();

        if ($existing) {
            return back()
                ->withInput()
                ->withErrors(['periode' => 'You already have an entry for this metric and period. Please edit the existing one.']);
        }

        Actual::create([
            'metric_id'    => $metric->id,
            'periode'      => $data['periode'] . '-01',   // store as full date
            'nilai_actual' => $data['nilai_actual'],
            'input_by'     => $user->id,
            'sumber'       => 'manual',
            'status'       => 'pending',
            'catatan'      => $data['catatan'] ?? null,
        ]);

        return redirect()->route('pic.data.index')
                         ->with('success', 'Data submitted successfully and is pending admin review.');
    }

    /**
     * Show the edit form for an existing actual.
     * Only draft or pending entries can be edited (not yet approved).
     */
    public function edit(Actual $actual)
    {
        $user = Auth::user();

        // Ownership & editability check
        if ($actual->input_by !== $user->id) {
            abort(403, 'You do not have permission to edit this record.');
        }

        if ($actual->status === 'approved') {
            return redirect()->route('pic.data.index')
                             ->with('error', 'Approved entries cannot be edited.');
        }

        $metrics = Metric::where('group_id', $user->group_id)
                         ->where('is_aktif', true)
                         ->orderBy('nama_item')
                         ->get();

        return view('pic.data.edit', compact('actual', 'metrics'));
    }

    /**
     * Update an existing actual entry.
     */
    public function update(Request $request, Actual $actual)
    {
        $user = Auth::user();

        if ($actual->input_by !== $user->id) {
            abort(403, 'You do not have permission to update this record.');
        }

        if ($actual->status === 'approved') {
            return redirect()->route('pic.data.index')
                             ->with('error', 'Approved entries cannot be edited.');
        }

        $data = $request->validate([
            'metric_id'    => ['required', 'exists:metrics,id'],
            'periode'      => ['required', 'date_format:Y-m'],
            'nilai_actual' => ['required', 'numeric', 'min:0'],
            'catatan'      => ['nullable', 'string', 'max:500'],
        ]);

        $metric = Metric::where('id', $data['metric_id'])
                        ->where('group_id', $user->group_id)
                        ->where('is_aktif', true)
                        ->firstOrFail();

        // Check for duplicate (exclude self)
        $existing = Actual::where('metric_id', $metric->id)
                          ->where('input_by', $user->id)
                          ->where('periode', 'like', $data['periode'] . '%')
                          ->where('id', '!=', $actual->id)
                          ->first();

        if ($existing) {
            return back()
                ->withInput()
                ->withErrors(['periode' => 'Another entry for this metric and period already exists.']);
        }

        $actual->update([
            'metric_id'    => $metric->id,
            'periode'      => $data['periode'] . '-01',
            'nilai_actual' => $data['nilai_actual'],
            'status'       => 'pending',   // re-submit resets to pending
            'catatan'      => $data['catatan'] ?? null,
        ]);

        return redirect()->route('pic.data.index')
                         ->with('success', 'Entry updated and re-submitted for admin review.');
    }

    /**
     * Delete a draft entry (not pending or approved).
     */
    public function destroy(Actual $actual)
    {
        $user = Auth::user();

        if ($actual->input_by !== $user->id) {
            abort(403, 'You do not have permission to delete this record.');
        }

        if ($actual->status !== 'draft') {
            return redirect()->route('pic.data.index')
                             ->with('error', 'Only draft entries can be deleted.');
        }

        $actual->delete();

        return redirect()->route('pic.data.index')
                         ->with('success', 'Draft entry deleted.');
    }
}
