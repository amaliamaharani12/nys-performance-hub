@extends('admin.layout')

@section('title', 'Edit PIC Account')
@section('page-title', 'Edit PIC Account')
@section('page-subtitle', 'Update account information for: ' . $pic->name)

@section('content')

    <div class="card">
        <div class="card-header" style="padding:14px 22px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div
                    style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#3b82f6,#7c3aed);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:13px;flex-shrink:0;">
                    {{ strtoupper(substr($pic->name, 0, 1)) }}
                </div>
                <div>
                    <div style="font-size:14px;font-weight:700;color:#1e293b;">{{ $pic->name }}</div>
                    <div style="font-size:11.5px;color:#64748b;">{{ $pic->email }}</div>
                </div>
            </div>
            <a href="{{ route('admin.pic.index') }}" class="btn btn-outline btn-sm">← Back</a>
        </div>
        <div class="card-body" style="padding:18px 22px;">
            <form method="POST" action="{{ route('admin.pic.update', $pic) }}" data-confirm="Are you sure you want to save changes to this PIC account?" data-confirm-title="Save Changes" data-confirm-btn="Save" data-confirm-type="info">
                @csrf @method('PUT')

                <div class="form-grid" style="gap:14px;">

                    {{-- Row 1: Name | Email | Group | Status (4 cols) --}}
                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:14px;">
                        <div class="form-group">
                            <label class="form-label" for="name">Full Name <span class="required">*</span></label>
                            <input type="text" id="name" name="name" class="form-input"
                                value="{{ old('name', $pic->name) }}" required>
                            @error('name')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="email">Email <span class="required">*</span></label>
                            <input type="email" id="email" name="email" class="form-input"
                                value="{{ old('email', $pic->email) }}" required>
                            @error('email')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="group_id">Group <span class="optional">(optional)</span></label>
                            <select id="group_id" name="group_id" class="form-select">
                                <option value="">— No group —</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}" {{ old('group_id', $pic->group_id) == $group->id ? 'selected' : '' }}>
                                        Group
                                        {{ $group->kode_group }}{{ $group->nama_group ? ' (' . $group->nama_group . ')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('group_id')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Account Status</label>
                            <div
                                style="display:flex;align-items:center;gap:8px;padding:9px 12px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;min-height:38px;">
                                @if($pic->is_aktif)
                                    <span class="badge badge-green">● Active</span>
                                    <span style="font-size:12px;color:#64748b;">Use list page to deactivate</span>
                                @else
                                    <span class="badge badge-red">● Inactive</span>
                                    <span style="font-size:12px;color:#64748b;">PIC cannot log in</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Change Password --}}
                    <div>
                        <div
                            style="font-size:12px;font-weight:600;color:#64748b;margin-bottom:10px;padding-bottom:6px;border-bottom:1px solid #f1f5f9;text-transform:uppercase;letter-spacing:0.4px;">
                            Change Password <span
                                style="font-weight:400;text-transform:none;letter-spacing:0;font-size:11.5px;">(leave blank
                                to keep current)</span>
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:14px;">
                            <div class="form-group">
                                <label class="form-label" for="password">New Password</label>
                                <input type="password" id="password" name="password" class="form-input"
                                    placeholder="Min. 8 characters">
                                @error('password')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="password_confirmation">Confirm Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-input" placeholder="Repeat new password">
                            </div>
                            <div></div>
                            <div></div>{{-- spacers --}}
                        </div>
                    </div>

                </div>

                <div class="form-actions" style="margin-top:18px;padding-top:16px;border-top:1px solid #f1f5f9;">
                    <button type="submit" class="btn btn-primary">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save Changes
                    </button>
                    <a href="{{ route('admin.pic.index') }}" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>

@endsection