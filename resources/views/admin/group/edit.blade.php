@extends('admin.layout')

@section('title', 'Edit Group')
@section('page-title', 'Edit Group')
@section('page-subtitle', 'Update details for Group ' . $group->kode_group)

@section('content')

<div class="card">
    <div class="card-header">
        <div style="display:flex;align-items:center;gap:10px;">
            <span class="badge badge-blue" style="font-size:13px;padding:4px 12px;">Group {{ $group->kode_group }}</span>
            @if($group->nama_group)
                <span style="font-size:13.5px;color:#64748b;">{{ $group->nama_group }}</span>
            @endif
        </div>
        <a href="{{ route('admin.group.index') }}" class="btn btn-outline btn-sm">← Back</a>
    </div>
    <div class="card-body" style="padding:18px 22px;">
        <form method="POST" action="{{ route('admin.group.update', $group) }}" data-confirm="Are you sure you want to save changes to this group?" data-confirm-title="Save Changes" data-confirm-btn="Save" data-confirm-type="info">
            @csrf @method('PUT')

            <div class="form-grid" style="gap:14px;">

                {{-- Row 1: Code | Name | PIC count | Metric count | Preview (5 equal cols) --}}
                <div style="display:grid;grid-template-columns:1fr 2fr 1fr 1fr 2fr;gap:14px;align-items:start;">
                    <div class="form-group">
                        <label class="form-label" for="kode_group">Group Code <span class="required">*</span></label>
                        <input type="text" id="kode_group" name="kode_group" class="form-input"
                            value="{{ old('kode_group', $group->kode_group) }}" maxlength="10"
                            required style="text-transform:uppercase;">
                        <span class="form-hint">Max 10 chars</span>
                        @error('kode_group')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="nama_group">Group Name <span class="optional">(optional)</span></label>
                        <input type="text" id="nama_group" name="nama_group" class="form-input"
                            value="{{ old('nama_group', $group->nama_group) }}" maxlength="100" placeholder="e.g. Production">
                        @error('nama_group')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">PIC Accounts</label>
                        <div style="padding:9px 12px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;min-height:38px;display:flex;align-items:center;gap:6px;">
                            <span style="font-size:18px;font-weight:800;color:#1e293b;">{{ $group->users()->count() }}</span>
                            <span style="font-size:12px;color:#64748b;">assigned</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Metrics</label>
                        <div style="padding:9px 12px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;min-height:38px;display:flex;align-items:center;gap:6px;">
                            <span style="font-size:18px;font-weight:800;color:#1e293b;">{{ $group->metrics()->count() }}</span>
                            <span style="font-size:12px;color:#64748b;">total</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Preview</label>
                        <div style="padding:9px 12px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;font-size:13px;color:#64748b;min-height:38px;display:flex;align-items:center;gap:8px;">
                            Will be displayed as:
                            <span id="preview-badge" style="background:#dbeafe;color:#1d4ed8;padding:3px 10px;border-radius:12px;font-size:12px;font-weight:600;">Group {{ $group->kode_group }}</span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="form-actions" style="margin-top:18px;padding-top:16px;border-top:1px solid #f1f5f9;">
                <button type="submit" class="btn btn-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Changes
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
