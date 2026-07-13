@extends('pic.layout')

@section('title', 'Submit New Data')
@section('page-title', 'Submit New Data')
@section('page-subtitle', "Enter the actual performance data for your group's metrics")

@section('topbar-actions')
    <a href="{{ route('pic.data.index') }}" class="btn btn-outline" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;">
        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back
    </a>
@endsection

@section('content')

<div class="card" style="max-width:620px;">
    <div class="card-header">
        <span class="card-title">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline-block;vertical-align:middle;margin-right:6px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Data Entry Form
        </span>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('pic.data.store') }}" data-confirm="Are you sure you want to submit this data?" data-confirm-title="Submit Data" data-confirm-btn="Submit" data-confirm-type="info">
            @csrf

            <div class="form-grid" style="gap:20px;">

                {{-- Metric --}}
                <div class="form-group">
                    <label class="form-label" for="metric_id">
                        Metric <span class="required">*</span>
                    </label>
                    @if($metrics->isEmpty())
                        <div style="padding:12px;background:#fff7ed;border:1px solid #fed7aa;border-radius:8px;font-size:13px;color:#9a3412;">
                            No active metrics found for your group. Please contact the admin.
                        </div>
                    @else
                        <select name="metric_id" id="metric_id" class="form-select" required>
                            <option value="">— Select a metric —</option>
                            @foreach($metrics as $metric)
                                <option value="{{ $metric->id }}"
                                    data-satuan="{{ $metric->satuan }}"
                                    data-arah="{{ $metric->arah_target }}"
                                    {{ old('metric_id') == $metric->id ? 'selected' : '' }}>
                                    {{ $metric->nama_item }}
                                    @if($metric->satuan) ({{ $metric->satuan }}) @endif
                                </option>
                            @endforeach
                        </select>
                    @endif
                    @error('metric_id')
                        <span class="form-error">{{ $message }}</span>
                    @enderror

                    {{-- Metric info box (populated via JS) --}}
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
                           value="{{ old('periode', date('Y-m')) }}" required>
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
                               value="{{ old('nilai_actual') }}" step="0.01" min="0"
                               placeholder="Enter numeric value" required
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
                              placeholder="Add any relevant notes or context for this entry..." maxlength="500"
                              style="resize:vertical;">{{ old('catatan') }}</textarea>
                    <span class="form-hint">Max 500 characters</span>
                    @error('catatan')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <div style="display:flex;gap:10px;margin-top:24px;padding-top:20px;border-top:1px solid #f1f5f9;">
                <button type="submit" class="btn btn-primary" @if($metrics->isEmpty()) disabled @endif>
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Submit Data
                </button>
                <a href="{{ route('pic.data.index') }}" class="btn btn-outline">Cancel</a>
            </div>

        </form>
    </div>
</div>

@push('scripts')
<script>
const selectEl     = document.getElementById('metric_id');
const infoBox      = document.getElementById('metric-info');
const infoText     = document.getElementById('metric-info-text');
const satuanLabel  = document.getElementById('satuan-label');

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
