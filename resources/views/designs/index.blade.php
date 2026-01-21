@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-palette-fill me-2"></i> Design Library
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('designs.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                            <i class="bi bi-plus-lg me-1"></i> Add Design
                        </a>
                        <a href="{{ route('designs.import.form') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
                            <i class="bi bi-upload me-1"></i> Import
                        </a>
                    </div>
                </div>

            <div class="card-body p-0">
                @if(session('success')) 
                    <div class="alert alert-success m-3 rounded-3 border-0 shadow-sm d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                        {{ session('success') }}
                    </div> 
                @endif
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="designs-table">
                        <thead class="bg-light text-secondary small text-uppercase">
                            <tr>
                                <th class="ps-4" width="5%">#</th>
                                <th width="15%">Image</th>
                                <th width="25%">Party Name</th>
                                <th width="35%">Design Name</th>
                                <th class="text-end pe-4" width="20%">Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    table#designs-table thead th { border-bottom: 2px solid #f0f2f5; font-weight: 600; }
    table#designs-table tbody td { padding-top: 1rem; padding-bottom: 1rem; }
    .img-thumbnail-hover { transition: transform 0.2s; }
    .img-thumbnail-hover:hover { transform: scale(1.1); }
</style>
@endsection

@push('scripts')
<script>
$(function() {
    $('#designs-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pagingType: "simple_numbers",
        pageLength: 10,
        dom: '<"d-flex justify-content-between align-items-center mx-3 mt-3"l<"ms-auto"f>>t<"d-flex justify-content-between align-items-center mx-3 mb-3"ip>',
        language: {
            search: "",
            searchPlaceholder: "Search designs...",
            lengthMenu: "Show _MENU_"
        },
        ajax: '{{ route('designs.data') }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'ps-4 fw-bold text-muted' },
            { data: 'image', name: 'image', orderable: false, searchable: false, 
              render: function(data, type, row) {
                 return data; // Assuming 'image' returns HTML <img> tag, if strict URL, wrap in img.
              }
            },
            { data: 'party_name', name: 'party_name', className: 'fw-semibold' },
            { data: 'name', name: 'name', className: 'text-dark' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end pe-4' }
        ]
    });
});
</script>
@endpush