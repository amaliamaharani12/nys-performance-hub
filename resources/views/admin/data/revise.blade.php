@extends('admin.layout')

@section('title', 'Revise Data')
@section('page-title', 'Revise Actual Data')
@section('page-subtitle', $actual->metric->nama_item . ' — ' . $actual->periode->format('F Y'))

@section('content')

    <div style="display:grid;grid-template-columns:1fr 380px;gap:20px;align-items:start;">

        <!-- Form Revisi -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Value Revision Form</div>
                <a href="{{ route('admin.data.index') }}" class="btn btn-outline btn-sm">← Back</a>
            </div>
            <div class="card-body">

                <!-- Info Data -->
                <div
                    style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:16px;margin-bottom:20px;display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div>
                        <div
                            style="font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:3px;">
                            Metric</div>
                        <div style="font-size:14px;font-weight:600;">{{ $actual->metric->nama_item }}</div>
                    </div>
                    <div>
                        <div
                            style="font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:3px;">
                            Group</div>
                        <div style="font-size:14px;font-weight:600;">
                            {{ $actual->metric->group ? 'Group ' . $actual->metric->group->kode_group : '—' }}</div>
                    </div>
                    <div>
                        <div
                            style="font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:3px;">
                            Period</div>
                        <div style="font-size:14px;font-weight:600;">{{ $actual->periode->format('d F Y') }}</div>
                    </div>
                    <div>
                        <div
                            style="font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:3px;">
                            Submitted By</div>
                        <div style="font-size:14px;font-weight:600;">{{ $actual->inputBy->name ?? '—' }}</div>
                    </div>
                </div>

                <!-- Nilai Sekarang -->
                <div
                    style="background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:14px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;">
                    <div>
                        <div style="font-size:11px;font-weight:600;color:#92400e;text-transform:uppercase;">Current Value
                        </div>
                        <div style="font-size:26px;font-weight:800;color:#d97706;margin-top:2px;">
                            {{ number_format($actual->nilai_actual, 2, ',', '.') }}
                            <span style="font-size:14px;font-weight:500;color:#92400e;">{{ $actual->metric->satuan }}</span>
                        </div>
                    </div>
                    @if($actual->status === 'pending')
                        <span class="badge badge-yellow">● Pending</span>
                    @else
                        <span class="badge badge-green">● Approved</span>
                    @endif
                </div>

                <form method="POST" action="{{ route('admin.data.revise.store', $actual) }}" data-confirm="Are you sure you want to save this revision and approve the data?" data-confirm-title="Save Revision" data-confirm-btn="Save & Approve" data-confirm-type="warning">
                    @csrf @method('PATCH')

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label" for="nilai_baru">
                                New Value <span class="required">*</span>
                                <span class="optional">{{ $actual->metric->satuan }}</span>
                            </label>
                            <input type="number" id="nilai_baru" name="nilai_baru" class="form-input"
                                value="{{ old('nilai_baru', $actual->nilai_actual) }}" step="0.01" min="0" required
                                style="font-size:18px;font-weight:700;">
                            @error('nilai_baru')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="alasan">Revision Reason <span class="required">*</span></label>
                            <textarea id="alasan" name="alasan" class="form-textarea" rows="4"
                                placeholder="Explain the reason for revising this value..."
                                required>{{ old('alasan') }}</textarea>
                            @error('alasan')<div class="form-error">{{ $message }}</div>@enderror
                            <div class="form-hint">This reason will be stored in the revision log.</div>
                        </div>
                    </div>

                    <div class="form-actions" style="margin-top:20px;">
                        <button type="submit" class="btn btn-warning">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Save Revision & Approve
                        </button>
                        <a href="{{ route('admin.data.index') }}" class="btn btn-outline">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Riwayat Revisi -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Revision History</div>
            </div>
            @if($actual->revisionLogs->isEmpty())
                <div class="card-body" style="text-align:center;color:#94a3b8;padding:32px;">
                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        style="margin:0 auto 8px;display:block;opacity:0.3;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2" />
                    </svg>
                    <div style="font-size:13px;">No revision history yet</div>
                </div>
            @else
                <div style="padding:16px;display:flex;flex-direction:column;gap:12px;">
                    @foreach($actual->revisionLogs->sortByDesc('created_at') as $log)
                        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:12px;">
                            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
                                <span
                                    style="font-size:12px;font-weight:600;color:#475569;">{{ $log->revisedBy->name ?? '—' }}</span>
                                <span style="font-size:11px;color:#94a3b8;">{{ $log->created_at->format('d M Y H:i') }}</span>
                            </div>
                            <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                                <span
                                    style="font-size:14px;font-weight:700;color:#ef4444;">{{ number_format($log->nilai_lama, 2, ',', '.') }}</span>
                                <svg width="14" height="14" fill="none" stroke="#94a3b8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                                <span
                                    style="font-size:14px;font-weight:700;color:#16a34a;">{{ number_format($log->nilai_baru, 2, ',', '.') }}</span>
                            </div>
                            @if($log->alasan)
                                <div
                                    style="font-size:12px;color:#64748b;background:#fff;border:1px solid #e2e8f0;border-radius:5px;padding:8px;line-height:1.5;">
                                    "{{ $log->alasan }}"
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

@endsection