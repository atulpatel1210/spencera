@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="card-title text-primary">
                        <i class="fas fa-users-cog me-2"></i> User Management
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('users.create') }}" class="btn btn-primary px-4">
                            <i class="fas fa-plus me-1"></i> Add User
                        </a>
                    </div>
                </div>

                <div class="card-body p-0">
                    @if(session('success'))
                        <div class="alert alert-success m-3 rounded-lg border-0 shadow-sm d-flex align-items-center">
                            <i class="fas fa-check-circle me-2 fs-5"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="users-table">
                            <thead>
                                <tr>
                                    <th class="ps-4">ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Company</th>
                                    <th>Roles</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <div class="d-flex align-items-center gap-2 text-danger">
                    <div class="bg-danger bg-opacity-10 p-2 rounded-circle">
                        <i class="fas fa-exclamation-triangle fs-5"></i>
                    </div>
                    <h5 class="modal-title fw-bold">Delete User</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <p class="mb-0 fs-5 text-center text-secondary">
                    Are you sure you want to delete this user?<br>
                    <small class="text-danger fw-medium mt-2 d-block">This action cannot be undone.</small>
                </p>
                <form id="deleteForm" method="POST" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
            <div class="modal-footer border-top-0 pt-0 d-flex gap-2">
                <button type="button" class="btn btn-light flex-grow-1" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger flex-grow-1" onclick="submitDelete()">
                    <i class="fas fa-trash me-2"></i>Delete User
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        pageLength: 10,
        dom: '<"d-flex justify-content-between align-items-center mx-3 mt-3"l<"ms-auto"f>>' +
             '<"table-scroll-container"t>' + 
             '<"d-flex justify-content-between align-items-center mx-3 mb-3"ip>',
        language: {
            search: "",
            searchPlaceholder: "Search users...",
            lengthMenu: "Show _MENU_ entries"
        },
        ajax: '{{ route('users.data') }}',
        columns: [
            { data: 'id', name: 'id', className: 'ps-4 fw-bold text-muted', width: '80px' },
            { data: 'name', name: 'name', className: 'fw-semibold text-dark' },
            { data: 'email', name: 'email', className: 'text-secondary' },
            { data: 'company', name: 'company', className: 'text-secondary' },
            { data: 'roles', name: 'roles', orderable: false, 
                render: function(data) {
                    if (!data) return '<span class="text-muted small">No roles</span>';
                    return data.split(', ').map(r => `<span class="badge text-primary">${r}</span>`).join('');
                }
            },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end pe-4', width: '120px' }
        ]
    });
});

let deleteUrl = '';
function deleteRecord(url) {
    deleteUrl = url;
    $('#deleteModal').modal('show');
}
function submitDelete() {
    $.ajax({
        url: deleteUrl,
        type: 'DELETE',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            $('#deleteModal').modal('hide');
            if(response.success) {
                $('#users-table').DataTable().ajax.reload();
            } else {
                alert(response.message);
            }
        },
        error: function(error) {
            $('#deleteModal').modal('hide');
            alert('Something went wrong. Please try again.');
        }
    });
}
</script>
@endpush
