@extends('admin.layout')

@section('title', 'Manage PIC Accounts')
@section('page-title', 'Manage PIC Accounts')
@section('page-subtitle', 'Create, edit, activate/deactivate, and delete PIC accounts')

@section('topbar-actions')
    <a href="{{ route('admin.pic.create') }}" class="btn btn-primary">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Create PIC Account
    </a>
@endsection

@section('content')

    <div class="card">
        <div class="card-header">
            <div class="card-title">PIC List ({{ $pics->count() }} {{ Str::plural('account', $pics->count()) }})</div>
        </div>

        @if($pics->isEmpty())
            <div class="empty-state">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0" />
                </svg>
                <h3>No PIC accounts yet</h3>
                <p>Create the first PIC account using the button above.</p>
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Group</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pics as $i => $pic)
                            <tr>
                                <td style="color:#94a3b8;font-size:12px;">{{ $i + 1 }}</td>
                                <td>
                                    <div style="font-weight:600;">{{ $pic->name }}</div>
                                </td>
                                <td style="color:#64748b;">{{ $pic->email }}</td>
                                <td>
                                    @if($pic->group)
                                        <span class="badge badge-blue">Group {{ $pic->group->kode_group }}</span>
                                    @else
                                        <span style="color:#94a3b8;font-size:12px;">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($pic->is_aktif)
                                        <span class="badge badge-green">● Active</span>
                                    @else
                                        <span class="badge badge-red">● Inactive</span>
                                    @endif
                                </td>
                                <td style="color:#64748b;font-size:12.5px;">
                                    {{ $pic->created_at->format('d M Y') }}
                                </td>
                                <td>
                                    <div class="btn-actions">
                                        <a href="{{ route('admin.pic.edit', $pic) }}" class="btn btn-outline btn-sm">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </a>

                                        <!-- Toggle Active -->
                                        <form method="POST" action="{{ route('admin.pic.toggle-aktif', $pic) }}"
                                            style="display:inline;"
                                            data-confirm="Are you sure you want to {{ $pic->is_aktif ? 'deactivate' : 'activate' }} the account of {{ $pic->name }}?"
                                            data-confirm-title="{{ $pic->is_aktif ? 'Deactivate' : 'Activate' }} Account"
                                            data-confirm-btn="{{ $pic->is_aktif ? 'Deactivate' : 'Activate' }}"
                                            data-confirm-type="{{ $pic->is_aktif ? 'warning' : 'info' }}">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="btn btn-sm {{ $pic->is_aktif ? 'btn-outline-danger' : 'btn-success' }}">
                                                {{ $pic->is_aktif ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>

                                        <!-- Delete -->
                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                            onclick="openConfirmModal({ title: 'Delete PIC Account', message: 'Are you sure you want to delete the account for &quot;{{ addslashes($pic->name) }}&quot;? This action cannot be undone.', btnText: 'Yes, Delete', type: 'danger', onConfirm: function(){ var f=document.getElementById('deletePicForm_{{ $pic->id }}'); f.submit(); } })">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Delete
                                        </button>
                                        <form id="deletePicForm_{{ $pic->id }}" method="POST" action="{{ route('admin.pic.destroy', $pic) }}" style="display:none;">
                                            @csrf @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

@endsection