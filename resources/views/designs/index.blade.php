@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="card-title text-primary">
                        <i class="fas fa-drafting-compass me-2"></i> Design Library
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('designs.create') }}" class="btn btn-primary px-4">
                            <i class="fas fa-plus me-1"></i> Add Design
                        </a>
                        <a href="{{ route('designs.import.form') }}" class="btn btn-light border px-4">
                            <i class="fas fa-file-import me-1"></i> Import
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
                        <table class="table table-hover align-middle mb-0" id="designs-table">
                            <thead>
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th>Image</th>
                                    <th>Party Name</th>
                                    <th>Design Name</th>
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

<style>
    .img-thumbnail-hover { transition: transform 0.2s; border-radius: 8px; }
    .img-thumbnail-hover:hover { transform: scale(1.1); }
</style>
@endsection

@push('scripts')
<script>
$(function() {
    $('#designs-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        pageLength: 10,
        dom: '<"d-flex justify-content-between align-items-center mx-3 mt-3"l<"ms-auto"f>>' +
             '<"table-scroll-container"t>' + 
             '<"d-flex justify-content-between align-items-center mx-3 mb-3"ip>',
        language: {
            search: "",
            searchPlaceholder: "Search designs...",
            lengthMenu: "Show _MENU_ entries"
        },
        ajax: '{{ route('designs.data') }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'ps-4 fw-bold text-muted' },
            { data: 'image', name: 'image', orderable: false, searchable: false },
            { data: 'party_name', name: 'party_name', className: 'fw-semibold' },
            { data: 'name', name: 'name', className: 'text-dark' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end pe-4' }
        ]
    });
});
</script>
@endpush