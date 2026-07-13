@extends('pic.layout')

@section('title', 'Edit Profile')
@section('page-title', 'Edit Profile')
@section('page-subtitle', 'Update your account name and password')

@section('content')

<div style="display:grid;grid-template-columns:1fr 320px;gap:20px;align-items:start;">

    <div class="card">
        <div class="card-header">
            <div class="card-title">Account Information</div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('pic.profile.update') }}" data-confirm="Are you sure you want to save changes to your profile?" data-confirm-title="Save Profile" data-confirm-btn="Save Changes" data-confirm-type="info">
                @csrf @method('PATCH')

                <div class="form-grid">
                    <!-- Read-only fields -->
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="text" class="form-input" value="{{ $user->email }}" readonly>
                        <div class="form-hint">Email cannot be changed. Contact Admin if changes are required.</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Group</label>
                        <input type="text" class="form-input"
                            value="{{ $user->group ? 'Group ' . $user->group->kode_group . ($user->group->nama_group ? ' (' . $user->group->nama_group . ')' : '') : '—' }}"
                            readonly>
                        <div class="form-hint">Group is managed by the Admin.</div>
                    </div>

                    <!-- Editable: Name -->
                    <div class="form-group">
                        <label class="form-label" for="name">Full Name <span class="required">*</span></label>
                        <input type="text" id="name" name="name" class="form-input"
                            value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <hr style="border:none;border-top:1px solid #e2e8f0;margin:4px 0;">

                    <!-- Change Password -->
                    <div>
                        <div style="font-size:13px;font-weight:600;color:#1e293b;margin-bottom:14px;">
                            Change Password
                            <span style="font-weight:400;color:#64748b;font-size:12px;">(leave blank if you do not want to change)</span>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label" for="password_lama">Old Password</label>
                                <input type="password" id="password_lama" name="password_lama" class="form-input"
                                    placeholder="Enter old password">
                                @error('password_lama')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-grid form-grid-2">
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
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display:flex;gap:10px;margin-top:24px;">
                    <button type="submit" class="btn btn-primary">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Changes
                    </button>
                    <a href="{{ route('pic.dashboard') }}" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Card -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Account Info</div>
        </div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">
            <div style="text-align:center;padding:16px 0;">
                <div style="width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,#3b82f6,#7c3aed);display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:800;color:#fff;margin:0 auto 12px;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div style="font-size:16px;font-weight:700;">{{ $user->name }}</div>
                <div style="font-size:13px;color:#64748b;margin-top:3px;">{{ $user->email }}</div>
            </div>

            <div style="border-top:1px solid #e2e8f0;padding-top:14px;display:flex;flex-direction:column;gap:10px;">
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-size:12.5px;color:#64748b;">Role</span>
                    <span style="font-size:12.5px;font-weight:600;background:#dbeafe;color:#1d4ed8;padding:2px 9px;border-radius:20px;">PIC</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-size:12.5px;color:#64748b;">Group</span>
                    <span style="font-size:12.5px;font-weight:600;">{{ $user->group ? 'Group ' . $user->group->kode_group : '—' }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-size:12.5px;color:#64748b;">Status</span>
                    <span style="font-size:12.5px;font-weight:600;background:#dcfce7;color:#15803d;padding:2px 9px;border-radius:20px;">Active</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-size:12.5px;color:#64748b;">Joined At</span>
                    <span style="font-size:12.5px;font-weight:600;">{{ $user->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
