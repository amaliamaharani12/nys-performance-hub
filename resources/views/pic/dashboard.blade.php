@extends('pic.layout')

@section('title', 'PIC Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle')
    Welcome back, <strong>{{ auth()->user()->name }}</strong>
    @if(auth()->user()->group)
        &mdash; Group {{ auth()->user()->group->kode_group }}
        @if(auth()->user()->group->nama_group)
            ({{ auth()->user()->group->nama_group }})
        @endif
    @endif
@endsection

@section('content')

{{-- Stats Cards --}}
<div class="stats-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;margin-bottom:24px;">
    <div class="stat-card" style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:20px 22px;display:flex;align-items:flex-start;gap:14px;">
        <div style="width:44px;height:44px;border-radius:10px;background:linear-gradient(135deg,#3b82f6,#6366f1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="20" height="20" fill="none" stroke="white" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <div>
            <div style="font-size:26px;font-weight:800;color:#1e293b;line-height:1.1;">{{ $total }}</div>
            <div style="font-size:12.5px;color:#64748b;margin-top:3px;font-weight:500;">Total Submissions</div>
        </div>
    </div>

    <div class="stat-card" style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:20px 22px;display:flex;align-items:flex-start;gap:14px;">
        <div style="width:44px;height:44px;border-radius:10px;background:linear-gradient(135deg,#f59e0b,#f97316);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="20" height="20" fill="none" stroke="white" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <div style="font-size:26px;font-weight:800;color:#1e293b;line-height:1.1;">{{ $pending }}</div>
            <div style="font-size:12.5px;color:#64748b;margin-top:3px;font-weight:500;">Pending Review</div>
        </div>
    </div>

    <div class="stat-card" style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:20px 22px;display:flex;align-items:flex-start;gap:14px;">
        <div style="width:44px;height:44px;border-radius:10px;background:linear-gradient(135deg,#22c55e,#16a34a);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="20" height="20" fill="none" stroke="white" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <div style="font-size:26px;font-weight:800;color:#1e293b;line-height:1.1;">{{ $approved }}</div>
            <div style="font-size:12.5px;color:#64748b;margin-top:3px;font-weight:500;">Approved</div>
        </div>
    </div>

    <div class="stat-card" style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:20px 22px;display:flex;align-items:flex-start;gap:14px;">
        <div style="width:44px;height:44px;border-radius:10px;background:linear-gradient(135deg,#94a3b8,#64748b);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="20" height="20" fill="none" stroke="white" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
        </div>
        <div>
            <div style="font-size:26px;font-weight:800;color:#1e293b;line-height:1.1;">{{ $draft }}</div>
            <div style="font-size:12.5px;color:#64748b;margin-top:3px;font-weight:500;">Draft / Returned</div>
        </div>
    </div>
</div>

{{-- Recent Submissions --}}
<div class="card">
    <div class="card-header">
        <span class="card-title">Recent Submissions</span>
        <a href="{{ route('pic.data.index') }}" style="font-size:13px;color:#3b82f6;text-decoration:none;font-weight:500;">View All →</a>
    </div>
    <div class="card-body" style="padding:0;">
        @if($recent->isEmpty())
            <div style="text-align:center;padding:48px 24px;color:#94a3b8;">
                <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin:0 auto 12px;display:block;opacity:0.4;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <div style="font-size:14px;font-weight:600;">No submissions yet</div>
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
                        <th style="padding:11px 20px;text-align:center;font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.04em;">Status</th>
                        <th style="padding:11px 20px;text-align:center;font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.04em;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recent as $actual)
                    <tr style="border-bottom:1px solid #f1f5f9;transition:background .1s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
                        <td style="padding:12px 20px;">
                            <div style="font-size:13.5px;font-weight:600;color:#1e293b;">{{ $actual->metric->nama_item ?? '—' }}</div>
                            <div style="font-size:12px;color:#94a3b8;">{{ $actual->metric->satuan ?? '' }}</div>
                        </td>
                        <td style="padding:12px 20px;font-size:13.5px;color:#475569;">
                            {{ \Carbon\Carbon::parse($actual->periode)->format('M Y') }}
                        </td>
                        <td style="padding:12px 20px;text-align:right;font-size:14px;font-weight:700;color:#1e293b;">
                            {{ number_format($actual->nilai_actual, 2) }}
                        </td>
                        <td style="padding:12px 20px;text-align:center;">
                            @if($actual->status === 'approved')
                                <span style="background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">Approved</span>
                            @elseif($actual->status === 'pending')
                                <span style="background:#fffbeb;color:#d97706;border:1px solid #fde68a;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">Pending</span>
                            @else
                                <span style="background:#f1f5f9;color:#64748b;border:1px solid #e2e8f0;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">Draft</span>
                            @endif
                        </td>
                        <td style="padding:12px 20px;text-align:center;">
                            @if($actual->status !== 'approved')
                                <a href="{{ route('pic.data.edit', $actual) }}" style="font-size:12.5px;color:#3b82f6;text-decoration:none;font-weight:500;">Edit</a>
                            @else
                                <span style="font-size:12.5px;color:#cbd5e1;">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

@endsection