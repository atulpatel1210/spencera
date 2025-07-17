@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h4>Purchase Order Pallets</h4>
        <a href="{{ route('purchase_order_pallets.create') }}" class="btn btn-primary btn-sm">+ Add Pallet</a>
    </div>
    <div class="card-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        
        <table class="table table-bordered" id="pallets-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>PO Number</th>
                    <th>Design</th>
                    <th>Size</th>
                    <th>Finish</th>
                    <th>Pallet Size</th>
                    <th>Pallet No</th>
                    <th>Total Qty</th>
                    <th>Remark</th>
                    <!-- <th>Actions</th> -->
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
    $('#pallets-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('purchase_order_pallets.data') }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'po', name: 'po' },
            { data: 'design_detail.name', name: 'design_detail.name' },
            { data: 'size_detail.size_name', name: 'size_detail.size_name' },
            { data: 'finish_detail.finish_name', name: 'finish_detail.finish_name' },
            { data: 'pallet_size', name: 'pallet_size' },
            { data: 'pallet_no', name: 'pallet_no' },
            { data: 'total_qty', name: 'total_qty' },
            { data: 'remark', name: 'remark' },
            // { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
});
</script>
@endpush