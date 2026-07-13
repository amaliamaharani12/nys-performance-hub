@extends('admin.layout')

@section('title', 'Review & Revise Data')
@section('page-title', 'Review & Revise Data')
@section('page-subtitle', 'Approve, revise, or return actual data submitted by PICs')

@section('content')

    <div class="card">
        <!-- Filter -->
        <form method="GET" action="{{ route('admin.data.index') }}">
            <div
                style="display:flex;justify-content:space-between;align-items:flex-end;padding:14px 18px;background:#f8fafc;border-bottom:1px solid #e2e8f0;flex-wrap:wrap;gap:16px;">
                <div style="display:flex;gap:12px;flex-wrap:wrap;flex:1;">
                    <div class="form-group" style="width:260px;">
                        <label class="form-label">Group</label>
                        <select name="group_id" class="form-select">
                            <option value="">All Groups</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>
                                    Group {{ $group->kode_group }}{{ $group->nama_group ? ' (' . $group->nama_group . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="width:160px;">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved
                            </option>
                        </select>
                    </div>
                    <div class="form-group" style="width:180px;">
                        <label class="form-label">Period</label>
                        <input type="month" name="periode" class="form-input"
                            value="{{ request('periode') ? \Carbon\Carbon::parse(request('periode'))->format('Y-m') : '' }}">
                    </div>
                </div>
                <div style="display:flex;gap:8px;padding-bottom:1px;">
                    <button type="submit" class="btn btn-primary" style="height:38px;padding:0 20px;">Filter</button>
                    <a href="{{ route('admin.data.index') }}" class="btn btn-outline"
                        style="height:38px;padding:0 18px;display:inline-flex;align-items:center;justify-content:center;">Reset</a>
                </div>
            </div>
        </form>



        @if($actuals->isEmpty())
            <div class="empty-state">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                <h3>No data found</h3>
                <p>There is no actual data that needs review for the selected filters.</p>
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Metric</th>
                            <th>Group</th>
                            <th>Period</th>
                            <th>Actual Value</th>
                            <th>Submitted By</th>
                            <th>Status</th>
                            <th>Notes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($actuals as $actual)
                            <tr>
                                <td>
                                    <div style="font-weight:600;font-size:13px;">{{ $actual->metric->nama_item }}</div>
                                    <div style="font-size:11.5px;color:#94a3b8;">{{ $actual->metric->satuan }}</div>
                                </td>
                                <td>
                                    <span class="badge badge-blue">Group {{ $actual->metric->group->kode_group ?? '—' }}</span>
                                </td>
                                <td style="font-size:13px;white-space:nowrap;">
                                    {{ $actual->periode->format('M Y') }}
                                </td>
                                <td>
                                    <span style="font-size:15px;font-weight:700;color:#1e293b;">
                                        {{ number_format($actual->nilai_actual, 2, ',', '.') }}
                                    </span>
                                    <span style="font-size:11px;color:#94a3b8;margin-left:2px;">{{ $actual->metric->satuan }}</span>
                                </td>
                                <td style="font-size:13px;color:#475569;">
                                    {{ $actual->inputBy->name ?? '—' }}
                                </td>
                                <td>
                                    @if($actual->status === 'pending')
                                        <span class="badge badge-yellow">● Pending</span>
                                    @elseif($actual->status === 'approved')
                                        <span class="badge badge-green">● Approved</span>
                                    @else
                                        <span class="badge badge-red">● Draft</span>
                                    @endif
                                </td>
                                <td style="max-width:160px;">
                                    @if($actual->catatan)
                                        <div style="font-size:12px;color:#64748b;white-space:pre-wrap;">
                                            {{ Str::limit($actual->catatan, 60) }}</div>
                                    @else
                                        <span style="color:#cbd5e1;font-size:12px;">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-actions">
                                        @if($actual->status === 'pending')
                                            <!-- Approve -->
                                            <form method="POST" action="{{ route('admin.data.approve', $actual) }}"
                                                style="display:inline;"
                                                data-confirm="Are you sure you want to approve this data?"
                                                data-confirm-title="Approve Data"
                                                data-confirm-btn="Yes, Approve"
                                                data-confirm-type="info">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:2px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                    Approve
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Revise -->
                                        <a href="{{ route('admin.data.revise', $actual) }}" class="btn btn-warning btn-sm">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Revise
                                        </a>

                                        @if($actual->status === 'pending')
                                            <!-- Reject / Return -->
                                            <button type="button" class="btn btn-outline-danger btn-sm"
                                                onclick="openRejectModal({{ $actual->id }}, '{{ addslashes($actual->metric->nama_item) }}')">
                                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:2px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                                Return
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($actuals->hasPages())
                <div
                    style="padding:16px 20px;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
                    <div style="font-size:13px;color:#64748b;">
                        Showing {{ $actuals->firstItem() }}–{{ $actuals->lastItem() }} of {{ $actuals->total() }} entries
                    </div>
                    <div style="display:flex;gap:6px;">
                        @if($actuals->onFirstPage())
                            <span
                                style="padding:6px 12px;border-radius:6px;border:1px solid #e2e8f0;font-size:13px;color:#cbd5e1;cursor:default;">←
                                Prev</span>
                        @else
                            <a href="{{ $actuals->previousPageUrl() }}"
                                style="padding:6px 12px;border-radius:6px;border:1px solid #e2e8f0;font-size:13px;color:#3b82f6;text-decoration:none;">←
                                Prev</a>
                        @endif
                        @if($actuals->hasMorePages())
                            <a href="{{ $actuals->nextPageUrl() }}"
                                style="padding:6px 12px;border-radius:6px;border:1px solid #e2e8f0;font-size:13px;color:#3b82f6;text-decoration:none;">Next
                                →</a>
                        @else
                            <span
                                style="padding:6px 12px;border-radius:6px;border:1px solid #e2e8f0;font-size:13px;color:#cbd5e1;cursor:default;">Next
                                →</span>
                        @endif
                    </div>
                </div>
            @endif
        @endif
    </div>

    <!-- Reject Modal -->
    <div class="modal-backdrop" id="rejectModal">
        <div class="modal">
            <h3>Return Data to PIC</h3>
            <p id="rejectModalText">Data status will be changed back to Draft and the PIC will need to resubmit.</p>
            <form id="rejectForm" method="POST">
                @csrf @method('PATCH')
                <div class="form-group" style="margin-bottom:16px;">
                    <label class="form-label" for="rejectCatatan">Notes for PIC <span
                            class="optional">(optional)</span></label>
                    <textarea id="rejectCatatan" name="catatan" class="form-textarea" rows="3"
                        placeholder="Explain the reason for returning the data..."></textarea>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-outline" onclick="closeRejectModal()">Cancel</button>
                    <button type="submit" class="btn btn-danger">Return to Draft</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function openRejectModal(id, name) {
            document.getElementById('rejectModalText').textContent =
                'Data "' + name + '" will be returned to Draft status, and the PIC will need to resubmit.';
            document.getElementById('rejectForm').action = '/admin/data/' + id + '/reject';
            document.getElementById('rejectModal').classList.add('open');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.remove('open');
            document.getElementById('rejectCatatan').value = '';
        }

        document.getElementById('rejectModal').addEventListener('click', function (e) {
            if (e.target === this) closeRejectModal();
        });
    </script>
@endpush