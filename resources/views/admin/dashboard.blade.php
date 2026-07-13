@extends('admin.layout')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome, ' . auth()->user()->name)

@section('content')

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Active PICs</div>
            <div class="stat-value blue">{{ $totalPicAktif }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Inactive PICs</div>
            <div class="stat-value slate">{{ $totalPicNonaktif }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Pending Data</div>
            <div class="stat-value orange">{{ $totalPending }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Approved This Month</div>
            <div class="stat-value green">{{ $totalApproved }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Draft (Returned)</div>
            <div class="stat-value red">{{ $totalDraft }}</div>
        </div>
    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Quick Actions</div>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:10px;">
                <a href="{{ route('admin.data.index') }}" class="btn btn-primary"
                    style="width:100%;justify-content:center;">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2" />
                    </svg>
                    Review Pending Data
                    @if($totalPending > 0)
                        <span
                            style="background:rgba(255,255,255,0.25);border-radius:20px;padding:1px 8px;font-size:11px;font-weight:700;">{{ $totalPending }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.pic.create') }}" class="btn btn-outline"
                    style="width:100%;justify-content:center;">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Create New PIC Account
                </a>
                <a href="{{ route('admin.pic.index') }}" class="btn btn-outline" style="width:100%;justify-content:center;">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0" />
                    </svg>
                    Manage PIC Accounts
                </a>
            </div>
        </div>

        <!-- Summary Status -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Actual Data Status</div>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <div style="width:10px;height:10px;border-radius:50%;background:#f59e0b;"></div>
                        <span style="font-size:13.5px;color:#475569;">Pending (awaiting review)</span>
                    </div>
                    <span style="font-weight:700;font-size:15px;color:#d97706;">{{ $totalPending }}</span>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <div style="width:10px;height:10px;border-radius:50%;background:#22c55e;"></div>
                        <span style="font-size:13.5px;color:#475569;">Approved (this month)</span>
                    </div>
                    <span style="font-weight:700;font-size:15px;color:#16a34a;">{{ $totalApproved }}</span>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <div style="width:10px;height:10px;border-radius:50%;background:#ef4444;"></div>
                        <span style="font-size:13.5px;color:#475569;">Draft (returned to PIC)</span>
                    </div>
                    <span style="font-weight:700;font-size:15px;color:#ef4444;">{{ $totalDraft }}</span>
                </div>

                @if($totalPending > 0)
                    <div
                        style="background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:10px 14px;font-size:12.5px;color:#92400e;margin-top:4px;">
                        ⚠️ There are {{ $totalPending }} data awaiting review.
                        <a href="{{ route('admin.data.index') }}"
                            style="color:#d97706;font-weight:600;text-decoration:none;">Review now →</a>
                    </div>
                @endif
            </div>
        </div>

    </div>

@endsection