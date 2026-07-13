@extends('pic.layout')

@section('title', 'Edit Submission')
@section('page-title', 'Edit Submission')
@section('page-subtitle', 'Update and re-submit this entry for admin review')

@section('topbar-actions')
    <a href="{{ route('pic.data.index') }}" class="btn btn-outline" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;">
        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back
    </a>
@endsection

@section('content')

@if($actual->status === 'draft' && $actual->catatan)
    <div class="alert alert-error" style="margin-bottom:20px;">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <strong>Returned by admin:</strong> {{ $actual->catatan }}
        </div>
    </div>
@endif

<div class="card" style="max-width:620px;">
    <div class="card-header">
        <span class="card-title">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline-block;vertical-align:middle;margin-right:6px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit Entry
        </span>
        <span style="font-size:12px;color:#94a3b8;">ID #{{ $actual->id }}</span>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('pic.data.update', $actual) }}" data-confirm="Are you sure you want to save changes and re-submit this entry?" data-confirm-title="Update Submission" data-confirm-btn="Save" data-confirm-type="info">
            @csrf @method('PUT')

            <div class="form-grid" style="gap:20px;">

                {{-- Metric --}}
                <div class="form-group">
                    <label class="form-label" for="metric_id">
                        Metric <span class="required">*</span>
                    </label>
                    <select name="metric_id" id="metric_id" class="form-select" required>
                        <option value="">— Select a metric —</option>
                        @foreach($metrics as $metric)
                            <option value="{{ $metric->id }}"
                                data-satuan="{{ $metric->satuan }}"
                                data-arah="{{ $metric->arah_target }}"
                                {{ (old('metric_id', $actual->metric_id) == $metric->id) ? 'selected' : '' }}>
                                {{ $metric->nama_item }}
                                @if($metric->satuan) ({{ $metric->satuan }}) @endif
                            </option>
                        @endforeach
                    </select>
                    @error('metric_id')
                        <span class="form-error">{{ $message }}</span>
                    @enderror

                    <div id="metric-info" style="display:none;margin-top:8px;padding:10px 14px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;font-size:12.5px;color:#1d4ed8;">
                        <span id="metric-info-text"></span>
                    </div>
                </div>

                {{-- Period --}}
                <div class="form-group">
                    <label class="form-label" for="periode">
                        Period <span class="required">*</span>
                    </label>
                    <input type="month" name="periode" id="periode" class="form-input"
                           value="{{ old('periode', \Carbon\Carbon::parse($actual->periode)->format('Y-m')) }}" required>
                    <span class="form-hint">Select the month and year of the data</span>
                    @error('periode')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Actual Value --}}
                <div class="form-group">
                    <label class="form-label" for="nilai_actual">
                        Actual Value <span class="required">*</span>
                    </label>
                    <div style="position:relative;">
                        <input type="number" name="nilai_actual" id="nilai_actual" class="form-input"
                               value="{{ old('nilai_actual', $actual->nilai_actual) }}"
                               step="0.01" min="0" placeholder="Enter numeric value" required
                               style="padding-right: 60px;">
                        <span id="satuan-label" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);font-size:12.5px;color:#94a3b8;pointer-events:none;"></span>
                    </div>
                    @error('nilai_actual')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Notes --}}
                <div class="form-group">
                    <label class="form-label" for="catatan">
                        Notes <span class="optional">(optional)</span>
                    </label>
                    <textarea name="catatan" id="catatan" class="form-input" rows="3"
                              placeholder="Add any relevant notes or context..." maxlength="500"
                              style="resize:vertical;">{{ old('catatan', $actual->catatan) }}</textarea>
                    <span class="form-hint">Max 500 characters. Submitting will change status back to Pending.</span>
                    @error('catatan')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <div style="display:flex;gap:10px;margin-top:24px;padding-top:20px;border-top:1px solid #f1f5f9;">
                <button type="submit" class="btn btn-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Update & Re-submit
                </button>
                <a href="{{ route('pic.data.index') }}" class="btn btn-outline">Cancel</a>
            </div>

        </form>
    </div>
</div>

@push('scripts')
<script>
const selectEl    = document.getElementById('metric_id');
const infoBox     = document.getElementById('metric-info');
const infoText    = document.getElementById('metric-info-text');
const satuanLabel = document.getElementById('satuan-label');

function updateMetricInfo() {
    const opt = selectEl.options[selectEl.selectedIndex];
    if (!opt || !opt.value) {
        infoBox.style.display = 'none';
        satuanLabel.textContent = '';
        return;
    }
    const satuan = opt.dataset.satuan || '';
    const arah   = opt.dataset.arah   || '';
    satuanLabel.textContent = satuan;
    const trendUpSvg = `<svg width="14" height="14" fill="none" stroke="#16a34a" stroke-width="2.5" viewBox="0 0 24 24" style="display:inline-block;vertical-align:middle;margin-right:4px;"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>`;
    const trendDownSvg = `<svg width="14" height="14" fill="none" stroke="#dc2626" stroke-width="2.5" viewBox="0 0 24 24" style="display:inline-block;vertical-align:middle;margin-right:4px;"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline></svg>`;
    const arahLabel = arah === 'naik' ? (trendUpSvg + 'Higher is better') : (trendDownSvg + 'Lower is better');
    infoText.innerHTML = (satuan ? `Unit: ${satuan}  ·  ` : '') + arahLabel;
    infoBox.style.display = 'block';
}

selectEl?.addEventListener('change', updateMetricInfo);
updateMetricInfo();
</script>
@endpush

@endsection
