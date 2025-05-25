@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h4>Order Item List</h4>
        {{-- You might want to add a button for adding new order items if applicable --}}
        {{-- <a href="{{ route('purchase_order_items.create') }}" class="btn btn-primary btn-sm">+ Add Order Item</a> --}}
    </div>
    <div class="card-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

        {{-- Datatables CSS --}}
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

        <table class="table table-bordered" id="order-items-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>PO</th>
                    <th>Design</th>
                    <th>Size</th>
                    <th>Finish</th>
                    <th>Order Qty</th>
                    <th>Planning Qty</th>
                    <th>Production Qty</th>
                    <th>Short/Excess Qty</th>
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

{{-- jQuery (if not already loaded by layouts.app) --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
{{-- Datatables JS --}}
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script>
$(function() {
    $('#order-items-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('purchase_order_item.data') }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'po', name: 'po' },
            { data: 'design_detail.name', name: 'designDetail.name' },
            { data: 'size_detail.size_name', name: 'sizeDetail.size_name' },
            { data: 'finish_detail.finish_name', name: 'finishDetail.finish_name' },
            { data: 'order_qty', name: 'order_qty' },
            { data: 'planning_qty', name: 'planning_qty' },
            { data: 'production_qty', name: 'production_qty' },
            { data: 'short_qty', name: 'short_qty' },
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
@endsection
