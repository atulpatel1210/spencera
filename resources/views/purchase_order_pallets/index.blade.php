@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h4>Purchase Order Pallets</h4> {{-- Changed title --}}
        <a href="{{ route('purchase_order_pallets.create') }}" class="btn btn-primary btn-sm">+ Add Pallet</a> {{-- Changed route --}}
    </div>
    <div class="card-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>PO ID</th>
                    <th>PO Number</th>
                    <th>PO Item ID</th>
                    <th>Design</th>
                    <th>Size</th>
                    <th>Finish</th>
                    <th>Batch ID</th> 
                    <th>Pallet Size</th>
                    <th>Pallet No</th>
                    <th>Total Qty</th>
                    <th>Remark</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pallets as $pallet)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $pallet->purchase_order_id }}</td>
                        <td>{{ $pallet->po }}</td>
                        <td>{{ $pallet->purchase_order_item_id }}</td>
                        <td>{{ $pallet->design }}</td>
                        <td>{{ $pallet->size }}</td>
                        <td>{{ $pallet->finish }}</td>
                        <td>{{ $pallet->batch_id }}</td>
                        <td>{{ $pallet->pallet_size }}</td>
                        <td>{{ $pallet->pallet_no }}</td>
                        <td>{{ $pallet->total_qty }}</td>
                        <td>{{ $pallet->remark }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="text-center">No purchase order pallets found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $pallets->links() }}
    </div>
</div>
@endsection
