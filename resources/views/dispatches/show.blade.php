@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xxl-10">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-info-circle me-2"></i> Dispatch Details (ID: {{ $dispatch->id }})
                    </h5>
                    <a href="{{ route('dispatches.index') }}" class="btn btn-outline-secondary rounded-pill shadow-sm px-4">
                        <i class="bi bi-arrow-left me-1"></i> Back to List
                    </a>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4">
                        {{-- Party & PO Info --}}
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-4 h-100 border-start border-4 border-primary shadow-sm">
                                <h6 class="text-uppercase text-secondary fw-bold small mb-3">Order Information</h6>
                                <div class="mb-2">
                                    <span class="text-muted small d-block">Party Name</span>
                                    <span class="fw-bold fs-6 text-dark">{{ $dispatch->party->party_name ?? 'N/A' }}</span>
                                </div>
                                <div class="mb-2">
                                    <span class="text-muted small d-block">Purchase Order (PO)</span>
                                    <span class="fw-bold fs-6 text-primary">{{ $dispatch->purchaseOrder->po ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-muted small d-block">Order Item (Design / Size / Finish)</span>
                                    <span class="fw-medium text-dark">
                                        @if($dispatch->purchaseOrderItem)
                                            {{ $dispatch->purchaseOrderItem->designDetail->name ?? 'N/A' }} /
                                            {{ $dispatch->purchaseOrderItem->sizeDetail->size_name ?? 'N/A' }} /
                                            {{ $dispatch->purchaseOrderItem->finishDetail->finish_name ?? 'N/A' }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Dispatch Logistics --}}
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-4 h-100 border-start border-4 border-success shadow-sm">
                                <h6 class="text-uppercase text-secondary fw-bold small mb-3">Logistics & Quantity</h6>
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <span class="text-muted small d-block">Batch No</span>
                                        <span class="fw-bold text-dark">{{ $dispatch->batch->batch_no ?? 'N/A' }}</span>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <span class="text-muted small d-block">Pallet No</span>
                                        <span class="fw-bold text-dark">{{ $dispatch->stockPallet->pallet_no ?? 'N/A' }}</span>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <span class="text-muted small d-block">Dispatched Quantity</span>
                                        <span class="fw-bold text-success fs-5">{{ $dispatch->dispatched_qty }}</span>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <span class="text-muted small d-block">Dispatch Date</span>
                                        <span class="fw-bold text-dark">{{ $dispatch->dispatch_date }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Transport Details --}}
                        <div class="col-12">
                            <div class="p-3 bg-light rounded-4 h-100 border-start border-4 border-info shadow-sm">
                                <h6 class="text-uppercase text-secondary fw-bold small mb-3">Transport & Additional Notes</h6>
                                <div class="row text-center text-md-start">
                                    <div class="col-md-3 mb-3 mb-md-0">
                                        <span class="text-muted small d-block">Vehicle No</span>
                                        <span class="fw-bold text-dark">{{ $dispatch->vehicle_no ?? 'N/A' }}</span>
                                    </div>
                                    <div class="col-md-3 mb-3 mb-md-0">
                                        <span class="text-muted small d-block">Container No</span>
                                        <span class="fw-bold text-dark">{{ $dispatch->container_no ?? 'N/A' }}</span>
                                    </div>
                                    <div class="col-md-6">
                                        <span class="text-muted small d-block">Remark</span>
                                        <span class="text-dark fst-italic">{{ $dispatch->remark ?? 'No remarks provided.' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-5">
                       <a href="{{ route('dispatches.index') }}" class="btn btn-secondary rounded-pill px-4 shadow-sm">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                        <a href="{{ route('dispatches.edit', $dispatch->id) }}" class="btn btn-warning text-white fw-bold rounded-pill px-4 shadow-sm">
                            <i class="bi bi-pencil me-1"></i> Edit Dispatch
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
