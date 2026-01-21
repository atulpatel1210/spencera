@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-box-seam me-2"></i> Purchase Order Pallets
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('purchase_order_pallets.create') }}" class="btn btn-primary rounded-pill shadow-sm px-4">
                            <i class="bi bi-plus-lg me-1"></i> Add Pallet
                        </a>
                    </div>
                </div>

                <div class="card-body p-0">
                    @if(session('success'))
                        <div class="alert alert-success m-4 rounded-3 shadow-sm border-0 d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="pallets-table">
                            <thead class="bg-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="ps-4" width="5%">#</th>
                                    <th>PO Number</th>
                                    <th>Design</th>
                                    <th>Size</th>
                                    <th>Finish</th>
                                    <th>Pallet Size</th>
                                    <th>Pallet No</th>
                                    <th>Total Qty</th>
                                    <th>Remark</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Data will be loaded by Yajra Datatables --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Premium Table Styling */
    #pallets-table thead th {
        font-weight: 600;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #eaeaea;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
    #pallets-table tbody td {
        padding: 1rem 1rem;
        color: #495057;
        border-bottom: 1px solid #f1f1f1;
    }
    #pallets-table tbody tr:hover {
        background-color: #f8f9fa;
        transition: background-color 0.2s ease-in-out;
    }
    #pallets-table_wrapper .dataTables_length select {
        border-radius: 0.5rem;
        padding: 0.375rem 2.25rem 0.375rem 0.75rem;
        border: 1px solid #dee2e6;
    }
    #pallets-table_wrapper .dataTables_filter input {
        border-radius: 0.5rem;
        border: 1px solid #dee2e6;
        padding: 0.375rem 0.75rem;
    }
    #pallets-table_wrapper .dataTables_paginate .paginate_button.current {
        background: #0d6efd !important;
        color: white !important;
        border: none !important;
        border-radius: 0.375rem !important;
    }
    #pallets-table_wrapper .dataTables_paginate .paginate_button:hover {
        background: #e9ecef !important;
        color: #0d6efd !important;
        border: none !important;
    }
</style>
@endpush

@push('scripts')
<script>
$(function() {
    $('#pallets-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        scrollX: true,
        paging: true,
        pageLength: 10,
        lengthChange: true,
        autoWidth: false,
        scrollCollapse: true,
        ajax: '{{ route('purchase_order_pallets.data') }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'ps-4 fw-medium text-secondary' },
            { data: 'po', name: 'po', className: 'fw-bold text-primary' },
            { data: 'design_detail.name', name: 'design_detail.name', className: 'fw-semibold' },
            { data: 'size_detail.size_name', name: 'size_detail.size_name' },
            { data: 'finish_detail.finish_name', name: 'finish_detail.finish_name' },
            { data: 'pallet_size', name: 'pallet_size', className: 'text-center' },
            { data: 'pallet_no', name: 'pallet_no', className: 'text-center' },
            { data: 'total_qty', name: 'total_qty', className: 'text-center fw-bold text-success' },
            { data: 'remark', name: 'remark', className: 'text-muted fst-italic' },
        ],
        dom: '<"d-flex justify-content-between align-items-center m-3"l<"d-flex align-items-center gap-2"f>>t<"d-flex justify-content-between align-items-center m-3"ip>',
        language: {
            search: "",
            searchPlaceholder: "Search pallets...",
            lengthMenu: "Show _MENU_ entries"
        }
    });
});
</script>
@endpush