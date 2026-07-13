@extends('admin.layout')

@section('title', 'Create PIC Account')
@section('page-title', 'Create PIC Account')
@section('page-subtitle', 'Fill in the form below to create a new PIC account')

@section('content')

<div class="card">
    <div class="card-header">
        <div class="card-title">Account Information</div>
        <a href="{{ route('admin.pic.index') }}" class="btn btn-outline btn-sm">← Back</a>
    </div>
    <div class="card-body" style="padding:18px 22px;">
        <form method="POST" action="{{ route('admin.pic.store') }}" data-confirm="Are you sure you want to create this PIC account?" data-confirm-title="Create Account" data-confirm-btn="Create" data-confirm-type="info">
            @csrf
            <div class="form-grid" style="gap:14px;">

                {{-- Row 1: Name | Email | Group --}}
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;">
                    <div class="form-group">
                        <label class="form-label" for="name">Full Name <span class="required">*</span></label>
                        <input type="text" id="name" name="name" class="form-input"
                            value="{{ old('name') }}" placeholder="PIC Full Name" required autofocus>
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="email">Email <span class="required">*</span></label>
                        <input type="email" id="email" name="email" class="form-input"
                            value="{{ old('email') }}" placeholder="pic@example.com" required>
                        @error('email')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="group_id">Group <span class="optional">(optional)</span></label>
                        <select id="group_id" name="group_id" class="form-select">
                            <option value="">— No group —</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>
                                    Group {{ $group->kode_group }}{{ $group->nama_group ? ' ('.$group->nama_group.')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('group_id')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Row 2: Password | Confirm Password | (empty) --}}
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;">
                    <div class="form-group">
                        <label class="form-label" for="password">Password <span class="required">*</span></label>
                        <input type="password" id="password" name="password" class="form-input"
                            placeholder="Min. 8 characters" required>
                        @error('password')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Confirm Password <span class="required">*</span></label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="form-input" placeholder="Repeat password" required>
                    </div>
                    <div></div>{{-- spacer --}}
                </div>

            </div>

            <div class="form-actions" style="margin-top:18px;padding-top:16px;border-top:1px solid #f1f5f9;">
                <button type="submit" class="btn btn-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Create Account
                </button>
                <a href="{{ route('admin.pic.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
