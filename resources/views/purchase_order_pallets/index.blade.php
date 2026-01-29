@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="card-title text-primary">
                        <i class="fas fa-boxes-packing me-2"></i> Purchase Order Pallets
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('purchase_order_pallets.create') }}" class="btn btn-primary px-4">
                            <i class="fas fa-plus me-1"></i> Add Pallet
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
                        <table class="table table-hover align-middle mb-0" id="pallets-table">
                            <thead>
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th>PO Number</th>
                                    <th>Design</th>
                                    <th>Size</th>
                                    <th>Finish</th>
                                    <th class="text-center">Pallet Size</th>
                                    <th class="text-center">Pallet No</th>
                                    <th class="text-center">Total Qty</th>
                                    <th>Remark</th>
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
    $('#pallets-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        pageLength: 10,
        dom: '<"d-flex justify-content-between align-items-center mx-3 mt-3"l<"ms-auto"f>>' +
             '<"table-scroll-container"t>' + 
             '<"d-flex justify-content-between align-items-center mx-3 mb-3"ip>',
        language: {
            search: "",
            searchPlaceholder: "Search pallets...",
            lengthMenu: "Show _MENU_ entries"
        },
        ajax: '{{ route('purchase_order_pallets.data') }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'ps-4 fw-bold text-muted' },
            { data: 'po_number', name: 'po' },
            { data: 'design_detail.name', name: 'design_detail.name', className: 'fw-semibold' },
            { data: 'size_detail.size_name', name: 'size_detail.size_name' },
            { data: 'finish_detail.finish_name', name: 'finish_detail.finish_name' },
            { data: 'pallet_size', name: 'pallet_size', className: 'text-center' },
            { data: 'pallet_no', name: 'pallet_no', className: 'text-center' },
            { data: 'total_qty', name: 'total_qty', className: 'text-center fw-bold text-success' },
            { data: 'remark', name: 'remark', className: 'text-muted small' },
        ]
    });
});
</script>
@endpush