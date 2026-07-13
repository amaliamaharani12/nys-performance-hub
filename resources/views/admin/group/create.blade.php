@extends('admin.layout')

@section('title', 'Create Group')
@section('page-title', 'Create Group')
@section('page-subtitle', 'Add a new performance group')

@section('content')

<div class="card">
    <div class="card-header">
        <div class="card-title">Group Details</div>
        <a href="{{ route('admin.group.index') }}" class="btn btn-outline btn-sm">← Back</a>
    </div>
    <div class="card-body" style="padding:18px 22px;">
        <form method="POST" action="{{ route('admin.group.store') }}" data-confirm="Are you sure you want to create this performance group?" data-confirm-title="Create Group" data-confirm-btn="Create" data-confirm-type="info">
            @csrf
            <div class="form-grid" style="gap:14px;">

                {{-- Row 1: Code | Name | Preview (3 cols) --}}
                <div style="display:grid;grid-template-columns:1fr 2fr 2fr;gap:14px;align-items:start;">
                    <div class="form-group">
                        <label class="form-label" for="kode_group">Group Code <span class="required">*</span></label>
                        <input type="text" id="kode_group" name="kode_group" class="form-input"
                            value="{{ old('kode_group') }}" placeholder="e.g. A" maxlength="10"
                            required autofocus style="text-transform:uppercase;">
                        <span class="form-hint">Max 10 characters</span>
                        @error('kode_group')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="nama_group">Group Name <span class="optional">(optional)</span></label>
                        <input type="text" id="nama_group" name="nama_group" class="form-input"
                            value="{{ old('nama_group') }}" placeholder="e.g. Production" maxlength="100">
                        @error('nama_group')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Preview</label>
                        <div style="padding:9px 12px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;font-size:13px;color:#64748b;min-height:38px;display:flex;align-items:center;gap:8px;">
                            Will be displayed as:
                            <span id="preview-badge" style="background:#dbeafe;color:#1d4ed8;padding:3px 10px;border-radius:12px;font-size:12px;font-weight:600;">Group —</span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="form-actions" style="margin-top:18px;padding-top:16px;border-top:1px solid #f1f5f9;">
                <button type="submit" class="btn btn-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Create Group
                </button>
                <a href="{{ route('admin.group.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
const kodeInput = document.getElementById('kode_group');
const preview   = document.getElementById('preview-badge');
kodeInput.addEventListener('input', function () {
    preview.textContent = 'Group ' + (this.value.toUpperCase() || '—');
    this.value = this.value.toUpperCase();
});
</script>
@endpush

@endsection
