@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-cart me-2"></i> Purchase Orders
                    </h5>
                    <a href="{{ route('orders.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                        <i class="bi bi-plus-lg me-1"></i> New Order
                    </a>
                </div>

                <div class="card-body p-0">
                    @if (session('success'))
                         <div class="alert alert-success m-3 rounded-3 border-0 shadow-sm d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="orders-table">
                            <thead class="bg-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="ps-4">PO Number</th>
                                    <th>Party Name</th>
                                    <th>Brand Name</th>
                                    <th class="text-center">Box Image</th>
                                    <th>Order Date</th>
                                    <th class="text-end pe-4">Actions</th>
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
@endpush

@push('scripts')
<script>
$(function() {
    $('#orders-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pagingType: "simple_numbers",
        pageLength: 10,
        lengthChange: true,
        autoWidth: false,
        ajax: '{{ route('orders.data') }}',
        columns: [
            { data: 'po', name: 'po', className: 'ps-4 fw-bold text-dark' },
            { data: 'party_name', name: 'party.party_name', className: 'fw-semibold' },
            { data: 'brand_name', name: 'brand_name', className: 'text-muted' },
            { data: 'box_image', name: 'box_image', className: 'text-center' },
            { data: 'order_date', name: 'order_date' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end pe-4' }
        ],
        dom: '<"d-flex justify-content-between align-items-center m-3"l<"d-flex align-items-center gap-2"f>>t<"d-flex justify-content-between align-items-center m-3"ip>',
        language: {
            search: "",
            searchPlaceholder: "Search orders...",
            lengthMenu: "Show _MENU_ entries"
        }
    });
});
</script>
@endpush
