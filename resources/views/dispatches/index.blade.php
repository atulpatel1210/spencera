@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Dispatch List</h4>
        <a href="{{ route('dispatches.create') }}" class="btn btn-primary">+ New Dispatch</a>
    </div>

    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered" id="dispatches-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Party Name</th>
                    <th>PO No.</th>
                    <th>Item Details</th>
                    <th>Batch No.</th>
                    <!-- <th>Pallet No.</th> -->
                    <th>Dispatched Qty</th>
                    <th>Dispatch Date</th>
                    <th>Vehicle No.</th>
                    <th>Container No.</th>
                    <th>Remark</th>
                    <th width="150px">Actions</th>
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
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'party_name', name: 'party.party_name' },
            { data: 'po_number', name: 'purchaseOrder.po' },
            { data: 'item_details', name: 'item_details', orderable: false, searchable: false },
            { data: 'batch_no', name: 'batch.batch_no' },
            // { data: 'pallet_no', name: 'stockPallet.pallet_no' },
            { data: 'dispatched_qty', name: 'dispatched_qty' },
            { data: 'dispatch_date', name: 'dispatch_date' },
            { data: 'vehicle_no', name: 'vehicle_no' },
            { data: 'container_no', name: 'container_no' },
            { data: 'remark', name: 'remark' },
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
