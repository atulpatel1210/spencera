@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Dispatch Details (ID: {{ $dispatch->id }})</h4>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Party Name:</strong> {{ $dispatch->party->party_name ?? 'N/A' }}
            </div>
            <div class="col-md-6">
                <strong>Purchase Order (PO):</strong> {{ $dispatch->purchaseOrder->po ?? 'N/A' }}
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Order Item:</strong>
                @if($dispatch->purchaseOrderItem)
                    {{ $dispatch->purchaseOrderItem->designDetail->name ?? 'N/A' }} /
                    {{ $dispatch->purchaseOrderItem->sizeDetail->size_name ?? 'N/A' }} /
                    {{ $dispatch->purchaseOrderItem->finishDetail->finish_name ?? 'N/A' }}
                @else
                    N/A
                @endif
            </div>
            <div class="col-md-6">
                <strong>Batch No:</strong> {{ $dispatch->batch->batch_no ?? 'N/A' }}
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Pallet No:</strong> {{ $dispatch->stockPallet->pallet_no ?? 'N/A' }}
            </div>
            <div class="col-md-6">
                <strong>Dispatched Quantity:</strong> {{ $dispatch->dispatched_qty }}
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Dispatch Date:</strong> {{ $dispatch->dispatch_date }}
            </div>
            <div class="col-md-6">
                <strong>Vehicle No:</strong> {{ $dispatch->vehicle_no ?? 'N/A' }}
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Container No:</strong> {{ $dispatch->container_no ?? 'N/A' }}
            </div>
            <div class="col-md-6">
                <strong>Remark:</strong> {{ $dispatch->remark ?? 'N/A' }}
            </div>
        </div>

        <hr>
        <a href="{{ route('dispatches.index') }}" class="btn btn-secondary mt-3">Back to Dispatch List</a>
        <a href="{{ route('dispatches.edit', $dispatch->id) }}" class="btn btn-warning mt-3">Edit Dispatch</a>
    </div>
</div>
@endsection
