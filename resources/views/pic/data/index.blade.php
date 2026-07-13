@extends('pic.layout')

@section('title', 'My Submissions')
@section('page-title', 'My Submissions')
@section('page-subtitle', 'All data entries you have submitted')

@section('topbar-actions')
    <a href="{{ route('pic.data.create') }}" class="btn btn-primary">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Submit New Data
    </a>
@endsection

@section('content')

{{-- Filters --}}
<form method="GET" action="{{ route('pic.data.index') }}" style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:20px;align-items:flex-end;">
    <div style="display:flex;flex-direction:column;gap:4px;">
        <label style="font-size:12px;font-weight:600;color:#64748b;">Period</label>
        <input type="month" name="periode" value="{{ request('periode') }}" class="form-input" style="width:160px;">
    </div>
    <div style="display:flex;flex-direction:column;gap:4px;">
        <label style="font-size:12px;font-weight:600;color:#64748b;">Status</label>
        <select name="status" class="form-select" style="width:140px;">
            <option value="">All</option>
            <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="draft"    {{ request('status') === 'draft'    ? 'selected' : '' }}>Draft</option>
        </select>
    </div>
    <div style="display:flex;gap:8px;align-items:flex-end;">
        <button type="submit" class="btn btn-primary" style="height:38px;">Filter</button>
        @if(request()->hasAny(['status','periode']))
            <a href="{{ route('pic.data.index') }}" class="btn btn-outline" style="height:38px;">Clear</a>
        @endif
    </div>
</form>

<div class="card">
    <div class="card-body" style="padding:0;">
        @if($actuals->isEmpty())
            <div style="text-align:center;padding:64px 24px;color:#94a3b8;">
                <svg width="44" height="44" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin:0 auto 12px;display:block;opacity:0.35;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <div style="font-size:14px;font-weight:600;color:#64748b;">No entries found</div>
                <div style="font-size:13px;margin-top:4px;">
                    <a href="{{ route('pic.data.create') }}" style="color:#3b82f6;text-decoration:none;">Submit your first data entry →</a>
                </div>
            </div>
        @else
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                        <th style="padding:11px 20px;text-align:left;font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.04em;">Metric</th>
                        <th style="padding:11px 20px;text-align:left;font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.04em;">Period</th>
                        <th style="padding:11px 20px;text-align:right;font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.04em;">Value</th>
                        <th style="padding:11px 20px;text-align:left;font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.04em;">Source</th>
                        <th style="padding:11px 20px;text-align:center;font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.04em;">Status</th>
                        <th style="padding:11px 20px;text-align:left;font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.04em;">Note</th>
                        <th style="padding:11px 20px;text-align:center;font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.04em;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($actuals as $actual)
                    <tr style="border-bottom:1px solid #f1f5f9;transition:background .1s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
                        <td style="padding:13px 20px;">
                            <div style="font-size:13.5px;font-weight:600;color:#1e293b;">{{ $actual->metric->nama_item ?? '—' }}</div>
                            <div style="font-size:12px;color:#94a3b8;">{{ $actual->metric->satuan ?? '' }}</div>
                        </td>
                        <td style="padding:13px 20px;font-size:13.5px;color:#475569;white-space:nowrap;">
                            {{ \Carbon\Carbon::parse($actual->periode)->format('M Y') }}
                        </td>
                        <td style="padding:13px 20px;text-align:right;font-size:14px;font-weight:700;color:#1e293b;">
                            {{ number_format($actual->nilai_actual, 2) }}
                        </td>
                        <td style="padding:13px 20px;">
                            <span style="font-size:12px;padding:3px 8px;border-radius:4px;background:#f1f5f9;color:#475569;font-weight:500;text-transform:capitalize;">
                                {{ $actual->sumber }}
                            </span>
                        </td>
                        <td style="padding:13px 20px;text-align:center;">
                            @if($actual->status === 'approved')
                                <span style="background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">Approved</span>
                            @elseif($actual->status === 'pending')
                                <span style="background:#fffbeb;color:#d97706;border:1px solid #fde68a;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">Pending</span>
                            @else
                                <span style="background:#f1f5f9;color:#64748b;border:1px solid #e2e8f0;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">Draft</span>
                            @endif
                        </td>
                        <td style="padding:13px 20px;max-width:200px;">
                            @if($actual->catatan)
                                <span style="font-size:12.5px;color:#475569;" title="{{ $actual->catatan }}">
                                    {{ \Illuminate\Support\Str::limit($actual->catatan, 60) }}
                                </span>
                            @else
                                <span style="color:#cbd5e1;font-size:12.5px;">—</span>
                            @endif
                        </td>
                        <td style="padding:13px 20px;text-align:center;">
                            <div style="display:flex;gap:8px;justify-content:center;align-items:center;">
                                @if($actual->status !== 'approved')
                                    <a href="{{ route('pic.data.edit', $actual) }}"
                                       style="font-size:12.5px;color:#3b82f6;text-decoration:none;font-weight:500;display:inline-flex;align-items:center;gap:4px;">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </a>
                                    @if($actual->status === 'draft')
                                        <span style="color:#e2e8f0;">|</span>
                                        <form method="POST" action="{{ route('pic.data.destroy', $actual) }}"
                                              style="display:inline;"
                                              data-confirm="Delete this draft entry? This action cannot be undone."
                                              data-confirm-title="Delete Entry"
                                              data-confirm-btn="Yes, Delete"
                                              data-confirm-type="danger">
                                            @csrf @method('DELETE')
                                            <button type="submit" style="background:none;border:none;cursor:pointer;font-size:12.5px;color:#ef4444;font-weight:500;padding:0;font-family:inherit;">Delete</button>
                                        </form>
                                    @endif
                                @else
                                    <span style="color:#cbd5e1;font-size:12.5px;">—</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination --}}
            @if($actuals->hasPages())
                <div style="padding:16px 20px;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
                    <div style="font-size:13px;color:#64748b;">
                        Showing {{ $actuals->firstItem() }}–{{ $actuals->lastItem() }} of {{ $actuals->total() }} entries
                    </div>
                    <div style="display:flex;gap:6px;">
                        @if($actuals->onFirstPage())
                            <span style="padding:6px 12px;border-radius:6px;border:1px solid #e2e8f0;font-size:13px;color:#cbd5e1;cursor:default;">← Prev</span>
                        @else
                            <a href="{{ $actuals->previousPageUrl() }}" style="padding:6px 12px;border-radius:6px;border:1px solid #e2e8f0;font-size:13px;color:#3b82f6;text-decoration:none;">← Prev</a>
                        @endif
                        @if($actuals->hasMorePages())
                            <a href="{{ $actuals->nextPageUrl() }}" style="padding:6px 12px;border-radius:6px;border:1px solid #e2e8f0;font-size:13px;color:#3b82f6;text-decoration:none;">Next →</a>
                        @else
                            <span style="padding:6px 12px;border-radius:6px;border:1px solid #e2e8f0;font-size:13px;color:#cbd5e1;cursor:default;">Next →</span>
                        @endif
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>

@endsection
