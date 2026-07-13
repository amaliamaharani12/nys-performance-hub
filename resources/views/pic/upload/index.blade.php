@extends('pic.layout')

@section('title', 'Import CSV Data')
@section('page-title', 'Import CSV Data')
@section('page-subtitle', 'Batch upload actual performance data using a CSV file')

@section('content')

{{-- Two-column Layout for Form and Instructions --}}
<div style="display:grid;grid-template-columns: 1fr 1fr;gap:24px;margin-bottom:24px;align-items:start;">
    
    {{-- Left Column: Upload Form --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Upload CSV File</span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('pic.upload.store') }}" enctype="multipart/form-data" data-confirm="Are you sure you want to upload and import this CSV file?" data-confirm-title="Import CSV" data-confirm-btn="Upload" data-confirm-type="info">
                @csrf
                
                <div class="form-group" style="margin-bottom:18px;">
                    <label class="form-label" for="file_csv">Select CSV File <span class="required">*</span></label>
                    <input type="file" name="file_csv" id="file_csv" class="form-input" accept=".csv,.txt" required style="padding: 8px;">
                    <span class="form-hint">Maximum file size: 2MB. Only .csv or plain text files are supported.</span>
                    @error('file_csv')
                        <span class="form-error" style="display:block;margin-top:4px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="display:flex;gap:10px;margin-top:20px;padding-top:16px;border-top:1px solid #f1f5f9;">
                    <button type="submit" class="btn btn-primary">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:2px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Upload & Import
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Right Column: Download Template & Instructions --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Instructions & Template</span>
        </div>
        <div class="card-body" style="font-size:13.5px;line-height:1.6;color:#475569;">
            <p style="margin-bottom:12px;">
                To upload data, download the template pre-populated with your group's metrics. Add dates and values in Excel or Google Sheets, then save as CSV.
            </p>
            <div style="margin-bottom:18px;">
                <a href="{{ route('pic.upload.template') }}" class="btn btn-outline" style="display:inline-flex;align-items:center;gap:6px;font-weight:600;color:#1d4ed8;border-color:#bfdbfe;background:#eff6ff;">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download CSV Template
                </a>
            </div>
            
            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:12px 14px;font-size:12.5px;">
                <strong style="color:#1e293b;display:block;margin-bottom:6px;">Required CSV columns:</strong>
                <ol style="margin-left:20px;display:flex;flex-direction:column;gap:3px;">
                    <li><strong>Metric ID</strong>: Do not change the pre-filled IDs.</li>
                    <li><strong>Metric Name</strong>: For reference; ignored during import.</li>
                    <li><strong>Unit</strong>: For reference; ignored during import.</li>
                    <li><strong>Period (YYYY-MM)</strong>: Format e.g. <code style="background:#e2e8f0;padding:1px 4px;border-radius:3px;">2025-07</code>.</li>
                    <li><strong>Actual Value</strong>: Positive numeric value. Leave blank to skip.</li>
                    <li><strong>Notes</strong>: Optional notes for this entry.</li>
                </ol>
            </div>
        </div>
    </div>

</div>

{{-- Upload History --}}
<div class="card">
    <div class="card-header">
        <span class="card-title">Upload History</span>
    </div>
    <div class="card-body" style="padding:0;">
        @if($uploads->isEmpty())
            <div style="text-align:center;padding:48px 24px;color:#94a3b8;">
                <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin:0 auto 12px;display:block;opacity:0.4;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <div style="font-size:14px;font-weight:600;">No uploads found</div>
                <p style="font-size:13px;margin-top:2px;">Your uploaded file logs will appear here.</p>
            </div>
        @else
            <table style="width:100%;border-collapse:collapse;font-size:13.5px;">
                <thead>
                    <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                        <th style="padding:11px 20px;text-align:left;font-weight:600;color:#64748b;">File Name</th>
                        <th style="padding:11px 20px;text-align:left;font-weight:600;color:#64748b;">Date & Time</th>
                        <th style="padding:11px 20px;text-align:center;font-weight:600;color:#64748b;">Status</th>
                        <th style="padding:11px 20px;text-align:left;font-weight:600;color:#64748b;">Errors / Details</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($uploads as $upload)
                    <tr style="border-bottom:1px solid #f1f5f9;transition:background .15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
                        <td style="padding:13px 20px;font-weight:600;color:#1e293b;">
                            {{ $upload->nama_file }}
                        </td>
                        <td style="padding:13px 20px;color:#475569;">
                            {{ $upload->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}
                        </td>
                        <td style="padding:13px 20px;text-align:center;">
                            @if($upload->status === 'berhasil')
                                <span style="background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">Success</span>
                            @elseif($upload->status === 'gagal')
                                <span style="background:#fef2f2;color:#dc2626;border:1px solid #fecaca;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">Failed</span>
                            @else
                                <span style="background:#fffbeb;color:#d97706;border:1px solid #fde68a;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">Processing</span>
                            @endif
                        </td>
                        <td style="padding:13px 20px;color:#475569;max-width:350px;">
                            @if($upload->catatan_error)
                                <div style="background:#fff5f5;border:1px solid #ffe3e3;color:#b91c1c;border-radius:6px;padding:8px 12px;font-size:12px;font-family:monospace;white-space:pre-wrap;max-height:100px;overflow-y:auto;">{{ $upload->catatan_error }}</div>
                            @else
                                <span style="color:#94a3b8;font-size:12.5px;">No errors. All rows imported successfully.</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination --}}
            @if($uploads->hasPages())
                <div style="padding:16px 20px;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
                    <div style="font-size:13px;color:#64748b;">
                        Showing {{ $uploads->firstItem() }}–{{ $uploads->lastItem() }} of {{ $uploads->total() }} uploads
                    </div>
                    <div style="display:flex;gap:6px;">
                        @if($uploads->onFirstPage())
                            <span style="padding:6px 12px;border-radius:6px;border:1px solid #e2e8f0;font-size:13px;color:#cbd5e1;cursor:default;">← Prev</span>
                        @else
                            <a href="{{ $uploads->previousPageUrl() }}" style="padding:6px 12px;border-radius:6px;border:1px solid #e2e8f0;font-size:13px;color:#3b82f6;text-decoration:none;">← Prev</a>
                        @endif
                        @if($uploads->hasMorePages())
                            <a href="{{ $uploads->nextPageUrl() }}" style="padding:6px 12px;border-radius:6px;border:1px solid #e2e8f0;font-size:13px;color:#3b82f6;text-decoration:none;">Next →</a>
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
