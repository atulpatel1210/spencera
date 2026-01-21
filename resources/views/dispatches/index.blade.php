@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-truck-front me-2"></i> Dispatch Management
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('dispatches.create') }}" class="btn btn-primary rounded-pill shadow-sm px-4">
                            <i class="bi bi-plus-lg me-1"></i> New Dispatch
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
                        <table class="table table-hover align-middle mb-0" id="dispatches-table">
                            <thead class="bg-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="ps-4" width="5%">#</th>
                                    <th>Party Name</th>
                                    <th>PO No.</th>
                                    <th>Item Details</th>
                                    <th>Batch No.</th>
                                    <th>Qty</th>
                                    <th>Date</th>
                                    <th>Vehicle No.</th>
                                    <th>Container No.</th>
                                    <th>Remark</th>
                                    <th width="10%">Actions</th>
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
    #dispatches-table thead th {
        font-weight: 600;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #eaeaea;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
    #dispatches-table tbody td {
        padding: 1rem 1rem;
        color: #495057;
        border-bottom: 1px solid #f1f1f1;
    }
    #dispatches-table tbody tr:hover {
        background-color: #f8f9fa;
        transition: background-color 0.2s ease-in-out;
    }
    #dispatches-table_wrapper .dataTables_length select {
        border-radius: 0.5rem;
        padding: 0.375rem 2.25rem 0.375rem 0.75rem;
        border: 1px solid #dee2e6;
    }
    #dispatches-table_wrapper .dataTables_filter input {
        border-radius: 0.5rem;
        border: 1px solid #dee2e6;
        padding: 0.375rem 0.75rem;
    }
    #dispatches-table_wrapper .dataTables_paginate .paginate_button.current {
        background: #0d6efd !important;
        color: white !important;
        border: none !important;
        border-radius: 0.375rem !important;
    }
    #dispatches-table_wrapper .dataTables_paginate .paginate_button:hover {
        background: #e9ecef !important;
        color: #0d6efd !important;
        border: none !important;
    }
</style>
@endpush

@push('scripts')
<script>
$(function() {
    $('#dispatches-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        scrollX: true,
        paging: true,
        pageLength: 10,
        lengthChange: true,
        autoWidth: false,
        scrollCollapse: true,
        ajax: '{{ route('dispatches.data') }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'ps-4 fw-medium text-secondary' },
            { data: 'party_name', name: 'party.party_name', className: 'fw-bold text-dark' },
            { data: 'po_number', name: 'purchaseOrder.po', className: 'text-primary' },
            { data: 'item_details', name: 'item_details', orderable: false, searchable: false, className: 'small' },
            { data: 'batch_no', name: 'batch.batch_no', className: 'text-center' },
            { data: 'dispatched_qty', name: 'dispatched_qty', className: 'fw-bold text-success text-center' },
            { data: 'dispatch_date', name: 'dispatch_date', className: 'text-nowrap' },
            { data: 'vehicle_no', name: 'vehicle_no' },
            { data: 'container_no', name: 'container_no' },
            { data: 'remark', name: 'remark', className: 'text-truncate', width: '10%' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end pe-4' }
        ],
        dom: '<"d-flex justify-content-between align-items-center m-3"l<"d-flex align-items-center gap-2"f>>t<"d-flex justify-content-between align-items-center m-3"ip>',
        language: {
            search: "",
            searchPlaceholder: "Search dispatches...",
            lengthMenu: "Show _MENU_ entries"
        }
    });
});
</script>
@endpush
