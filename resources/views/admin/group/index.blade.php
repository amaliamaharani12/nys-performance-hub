@extends('admin.layout')

@section('title', 'Manage Groups')
@section('page-title', 'Manage Groups')
@section('page-subtitle', 'Create and manage performance groups')

@section('topbar-actions')
    <a href="{{ route('admin.group.create') }}" class="btn btn-primary">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Add Group
    </a>
@endsection

@section('content')

    <div class="card">
        <div class="card-header">
            <div class="card-title">Group List ({{ $groups->count() }} {{ Str::plural('group', $groups->count()) }})</div>
        </div>

        @if($groups->isEmpty())
            <div class="empty-state">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <h3>No groups yet</h3>
                <p>Create the first group to start assigning PIC accounts and metrics.</p>
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Group</th>
                            <th>Name</th>
                            <th style="text-align:center;">PIC Accounts</th>
                            <th style="text-align:center;">Metrics</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groups as $i => $group)
                            <tr>
                                <td style="color:#94a3b8;font-size:12px;">{{ $i + 1 }}</td>
                                <td>
                                    <span class="badge badge-blue">Group {{ $group->kode_group }}</span>
                                </td>
                                <td style="font-size:13.5px;color:#1e293b;">
                                    {{ $group->nama_group ?: '—' }}
                                </td>
                                <td style="text-align:center;">
                                    <span style="font-size:13.5px;font-weight:600;color:#1e293b;">{{ $group->users_count }}</span>
                                    <span style="font-size:12px;color:#94a3b8;"> PIC{{ $group->users_count != 1 ? 's' : '' }}</span>
                                </td>
                                <td style="text-align:center;">
                                    <span style="font-size:13.5px;font-weight:600;color:#1e293b;">{{ $group->metrics_count }}</span>
                                    <span style="font-size:12px;color:#94a3b8;">
                                        metric{{ $group->metrics_count != 1 ? 's' : '' }}</span>
                                </td>
                                <td>
                                    <div class="btn-actions">
                                        <a href="{{ route('admin.group.edit', $group) }}" class="btn btn-outline btn-sm">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </a>
                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                            onclick="openDeleteModal({{ $group->id }}, '{{ addslashes($group->kode_group) }}', {{ $group->users_count }}, {{ $group->metrics_count }})">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-backdrop" id="deleteModal">
        <div class="modal">
            <h3 style="color:#ef4444;display:flex;align-items:center;gap:8px;">
                <svg width="18" height="18" fill="none" stroke="#ef4444" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                Delete Group
            </h3>
            <p id="deleteModalText">Are you sure you want to delete this group? This action cannot be undone.</p>
            <div id="deleteModalWarning"
                style="display:none;margin-top:12px;padding:10px;background:#fef2f2;border:1px solid #fecaca;border-radius:6px;font-size:12.5px;color:#b91c1c;">
                This group has active PICs or metrics and cannot be deleted until they are reassigned.
            </div>
            <div class="modal-actions" style="margin-top:20px;">
                <button class="btn btn-outline" onclick="closeDeleteModal()">Cancel</button>
                <form id="deleteForm" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" id="deleteSubmitBtn" class="btn btn-danger">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function openDeleteModal(id, code, usersCount, metricsCount) {
            const textEl = document.getElementById('deleteModalText');
            const warningEl = document.getElementById('deleteModalWarning');
            const submitBtn = document.getElementById('deleteSubmitBtn');

            textEl.textContent = 'Are you sure you want to delete "Group ' + code + '"? This action cannot be undone.';

            if (usersCount > 0 || metricsCount > 0) {
                warningEl.style.display = 'block';
                warningEl.textContent = 'Cannot delete Group ' + code + ' because it still has ' +
                    (usersCount > 0 ? usersCount + ' PIC account(s) ' : '') +
                    (usersCount > 0 && metricsCount > 0 ? 'and ' : '') +
                    (metricsCount > 0 ? metricsCount + ' metric(s) ' : '') + 'assigned.';
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.5';
                submitBtn.style.cursor = 'not-allowed';
            } else {
                warningEl.style.display = 'none';
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor = 'pointer';
            }

            document.getElementById('deleteForm').action = '/admin/group/' + id;
            document.getElementById('deleteModal').classList.add('open');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('open');
        }

        document.getElementById('deleteModal').addEventListener('click', function (e) {
            if (e.target === this) closeDeleteModal();
        });
    </script>
@endpush