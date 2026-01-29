@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="card-title text-primary">
                        <i class="fas fa-truck-fast me-2"></i> Dispatch Management
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('dispatches.create') }}" class="btn btn-primary px-4">
                            <i class="fas fa-plus me-1"></i> New Dispatch
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
                        <table class="table table-hover align-middle mb-0" id="dispatches-table">
                            <thead>
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th>Party Name</th>
                                    <th>PO No.</th>
                                    <th>Item Details</th>
                                    <th>Batch No.</th>
                                    <th class="text-center">Qty</th>
                                    <th>Date</th>
                                    <th>Vehicle No.</th>
                                    <th>Container No.</th>
                                    <th>Remark</th>
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
@endsection

@push('scripts')
<script>
$(function() {
    $('#dispatches-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        pageLength: 10,
        dom: '<"d-flex justify-content-between align-items-center mx-3 mt-3"l<"ms-auto"f>>' +
             '<"table-scroll-container"t>' + 
             '<"d-flex justify-content-between align-items-center mx-3 mb-3"ip>',
        language: {
            search: "",
            searchPlaceholder: "Search dispatches...",
            lengthMenu: "Show _MENU_ entries"
        },
        ajax: '{{ route('dispatches.data') }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'ps-4 fw-bold text-muted' },
            { data: 'party_name', name: 'party.party_name', className: 'fw-semibold' },
            { data: 'po_number', name: 'purchaseOrder.po' },
            { data: 'item_details', name: 'item_details', orderable: false, searchable: false, className: 'small' },
            { data: 'batch_no', name: 'batch.batch_no' },
            { data: 'dispatched_qty', name: 'dispatched_qty', className: 'fw-bold text-success text-center' },
            { data: 'dispatch_date', name: 'dispatch_date', className: 'text-nowrap' },
            { data: 'vehicle_no', name: 'vehicle_no' },
            { data: 'container_no', name: 'container_no' },
            { data: 'remark', name: 'remark', className: 'text-truncate small', width: '10%' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end pe-4' }
        ]
    });
});
</script>
@endpush
