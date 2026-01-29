@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="card-title text-primary">
                        <i class="fas fa-shopping-cart me-2"></i> Purchase Orders
                    </h5>
                    <a href="{{ route('orders.create') }}" class="btn btn-primary px-4">
                        <i class="fas fa-plus me-1"></i> New Order
                    </a>
                </div>

                <div class="card-body p-0">
                    @if (session('success'))
                         <div class="alert alert-success m-3 rounded-lg border-0 shadow-sm d-flex align-items-center">
                            <i class="fas fa-check-circle me-2 fs-5"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="orders-table">
                            <thead>
                                <tr>
                                    <th class="ps-4">PO Number</th>
                                    <th>Party Name</th>
                                    <th>Brand Name</th>
                                    <th class="text-center">Box Image</th>
                                    <th>Order Date</th>
                                    <th class="text-end pe-4 text-nowrap">Actions</th>
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
    $('#orders-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        pageLength: 10,
        dom: '<"d-flex justify-content-between align-items-center mx-3 mt-3"l<"ms-auto"f>>' +
             '<"table-scroll-container"t>' + 
             '<"d-flex justify-content-between align-items-center mx-3 mb-3"ip>',
        language: {
            search: "",
            searchPlaceholder: "Search orders...",
            lengthMenu: "Show _MENU_ entries"
        },
        ajax: '{{ route('orders.data') }}',
        columns: [
            { data: 'po_number', name: 'po', className: 'ps-4' },
            { data: 'party_name', name: 'party.party_name', className: 'fw-semibold' },
            { data: 'brand_name', name: 'brand_name', className: 'text-muted' },
            { data: 'box_image', name: 'box_image', className: 'text-center' },
            { data: 'order_date', name: 'order_date' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end pe-4' }
        ]
    });
});
</script>
@endpush
