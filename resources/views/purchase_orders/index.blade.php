@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Orders List</h4>
        <a href="{{ route('orders.create') }}" class="btn btn-primary">+ New Order</a>
    </div>

    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered" id="orders-table">
            <thead>
                <tr>
                    <th>PO</th>
                    <th>Party Name</th>
                    <th>Brand Name</th>
                    <th>Box Image</th>
                    <th>Order Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                {{-- Data will be loaded by Yajra Datatables --}}
            </tbody>
        </table>
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
        scrollX: true,
        paging: true,
        pageLength: 10,
        lengthChange: true,
        autoWidth: false,
        scrollCollapse: true,
        ajax: '{{ route('orders.data') }}',
        columns: [
            { data: 'po', name: 'po' },
            { data: 'party_name', name: 'party.party_name', orderable: true, searchable: true }, // Use party_name column
            { data: 'brand_name', name: 'brand_name' },
            { data: 'box_image', name: 'box_image' },
            { data: 'order_date', name: 'order_date' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
});
</script>
@endpush
