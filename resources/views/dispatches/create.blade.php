@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xxl-11">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-truck me-2"></i> Add Dispatch
                    </h5>
                    <a href="{{ route('dispatches.index') }}" class="btn btn-outline-secondary rounded-pill shadow-sm px-4">
                        <i class="bi bi-arrow-left me-1"></i> Back to List
                    </a>
                </div>
                
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success rounded-3 shadow-sm border-0 mb-4 d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif

                    <form action="{{ route('dispatches.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        
                        {{-- Selection Section --}}
                        <div class="card border-0 bg-light rounded-4 mb-4 shadow-sm">
                            <div class="card-body p-4">
                                <h6 class="fw-bold text-secondary mb-3 text-uppercase small"><i class="bi bi-filter-circle me-2"></i>Selection Criteria</h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold small text-secondary">Party</label>
                                        <select name="party_id" id="party" class="form-select border-0 shadow-sm @error('party_id') is-invalid @enderror">
                                            <option value="">-- Select Party --</option>
                                            @foreach($parties as $party)
                                            <option value="{{ $party->id }}">{{ $party->party_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('party_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                    
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold small text-secondary">Purchase Order</label>
                                        <select name="purchase_order_id" id="po" class="form-select border-0 shadow-sm @error('purchase_order_id') is-invalid @enderror"></select>
                                        @error('purchase_order_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <input type="hidden" name="po" id="po_number" value="">
                                    
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold small text-secondary">Design</label>
                                        <select name="design" id="design" class="form-select border-0 shadow-sm @error('design') is-invalid @enderror"></select>
                                        @error('design')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                    
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold small text-secondary">Size</label>
                                        <select name="size" id="size" class="form-select border-0 shadow-sm @error('size') is-invalid @enderror"></select>
                                        @error('size')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                    
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold small text-secondary">Finish</label>
                                        <select name="finish" id="finish" class="form-select border-0 shadow-sm @error('finish') is-invalid @enderror"></select>
                                        @error('finish')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                    
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold small text-secondary">Order Item</label>
                                        <select name="purchase_order_item_id" id="order_item" class="form-select border-0 shadow-sm @error('purchase_order_item_id') is-invalid @enderror"></select>
                                        @error('purchase_order_item_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold small text-secondary">Batch</label>
                                        <select name="batch_id" id="batch" class="form-select border-0 shadow-sm @error('batch_id') is-invalid @enderror"></select>
                                        @error('batch_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold small text-secondary">Pallet</label>
                                        <select name="pallet_id" id="pallet" class="form-select border-0 shadow-sm @error('pallet_id') is-invalid @enderror" aria-describedby="palletIdHelp"></select>
                                        @error('pallet_id')
                                            <div id="palletIdHelp" class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Dispatch Details Section --}}
                        <div class="card border-0 rounded-4 mb-4 shadow-sm border-start border-4 border-primary">
                            <div class="card-body p-4">
                                <h6 class="fw-bold text-primary mb-3 d-flex align-items-center">
                                    <span class="bg-primary-subtle text-primary rounded-circle p-2 me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="bi bi-box-seam fs-6"></i></span>
                                    Dispatch Details
                                </h6>
                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <label for="pallet_no" class="form-label fw-semibold small text-secondary">Pallet No</label>
                                        <input type="number" class="form-control shadow-sm @error('pallet_no') is-invalid @enderror" id="pallet_no" name="pallet_no" value="{{ old('pallet_no') }}" required min="1" aria-describedby="palletNoHelp" placeholder="Enter pallet no">
                                        @error('pallet_no')
                                            <div id="palletNoHelp" class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="dispatched_qty" class="form-label fw-semibold small text-secondary">Dispatched Quantity</label>
                                        <input type="number" class="form-control shadow-sm bg-light @error('dispatched_qty') is-invalid @enderror" id="dispatched_qty" name="dispatched_qty" value="{{ old('dispatched_qty') }}" required min="1" aria-describedby="dispatchedQtyHelp" placeholder="Calculated/Enter quantity" readonly>
                                        @error('dispatched_qty')
                                        <div id="dispatchedQtyHelp" class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="dispatch_date" class="form-label fw-semibold small text-secondary">Dispatch Date</label>
                                        <input type="date" class="form-control shadow-sm @error('dispatch_date') is-invalid @enderror" id="dispatch_date" name="dispatch_date" value="{{ old('dispatch_date', date('Y-m-d')) }}" required>
                                        @error('dispatch_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="vehicle_no" class="form-label fw-semibold small text-secondary">Vehicle No</label>
                                        <input type="text" class="form-control shadow-sm @error('vehicle_no') is-invalid @enderror" id="vehicle_no" name="vehicle_no" value="{{ old('vehicle_no') }}" placeholder="Enter vehicle number">
                                        @error('vehicle_no')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="container_no" class="form-label fw-semibold small text-secondary">Container No</label>
                                        <input type="text" class="form-control shadow-sm @error('container_no') is-invalid @enderror" id="container_no" name="container_no" value="{{ old('container_no') }}" placeholder="Enter container number">
                                        @error('container_no')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-12">
                                        <label for="remark" class="form-label fw-semibold small text-secondary">Remark</label>
                                        <textarea class="form-control shadow-sm @error('remark') is-invalid @enderror" id="remark" name="remark" rows="2" placeholder="Optional notes...">{{ old('remark') }}</textarea>
                                        @error('remark')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3 mt-4">
                            <a href="{{ route('dispatches.index') }}" class="btn btn-light btn-lg px-4 border rounded-pill text-secondary fw-medium hover-bg-gray">Cancel</a>
                            <button type="submit" class="btn btn-success btn-lg px-5 shadow fw-bold rounded-pill transition-all">
                                <i class="bi bi-save me-2"></i> Add Dispatch
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function() {
        // Party → PO
        $('#party').on('change', function () {
            let party_id = $(this).val();
            $('#po').html('');
            if (party_id) {
                $.ajax({
                    url: `/get-purchases-for-dispatch`,
                    method: 'POST',
                    data: { party_id: party_id },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('#po').append('<option value="">-- Select PO --</option>');
                        $.each(data.orderItems, function (i, item) {
                            $('#po').append(`<option value="${item.id}" data-po="${item.po}">${item.po}</option>`);
                        });
                    }
                });
            }
        });

        // PO → Design
        $('#po').on('change', function () {
            let po_id = $(this).val();
            let po = $('#po').find(':selected').data('po');;
            let party_id = $('#party').val();
            $('#design').html('');
            if (po_id && party_id) {
                $.ajax({
                    url: `/get-designs-for-dispatch`,
                    method: 'POST',
                    data: { party_id: party_id, purchase_order_id: po_id },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('#po_number').val(po);
                        $('#design').append('<option value="">-- Select Design --</option>');
                        $.each(data.orderItems, function (i, item) {
                            $('#design').append(`<option value="${item.id}">${item.name}</option>`);
                        });
                    }
                });
            }
        });

        // Design → Size
        $('#design').on('change', function () {
            let design_id = $(this).val();
            let po_id = $('#po').val();
            let party_id = $('#party').val();
            $('#size').html('');
            if (design_id && po_id && party_id) {
                $.ajax({
                    url: `/get-sizes-for-dispatch`,
                    method: 'POST',
                    data: { party_id: party_id, purchase_order_id: po_id, design_id: design_id },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('#size').append('<option value="">-- Select Size --</option>');
                        $.each(data.orderItems, function (i, item) {
                            $('#size').append(`<option value="${item.id}">${item.size_name}</option>`);
                        });
                    }
                });
            }
        });

        // Size → Finish
        $('#size').on('change', function () {
            let size_id = $(this).val();
            let party_id = $('#party').val();
            let po_id = $('#po').val();
            let design_id = $('#design').val();
            $('#finishs').html('');
            if (design_id && po_id && party_id && size_id) {
                $.ajax({
                    url: `/get-finishs-for-dispatch`,
                    method: 'POST',
                    data: { party_id: party_id, purchase_order_id: po_id, design_id: design_id, size_id: size_id },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('#finish').append('<option value="">-- Select Finish --</option>');
                        $.each(data.orderItems, function (i, item) {
                            $('#finish').append(`<option value="${item.id}">${item.finish_name}</option>`);
                        });
                    }
                });
            }
        });

        // Finish → Order Item
        $('#finish').on('change', function () {
            let finish_id = $(this).val();
            let size_id = $('#size').val();
            let party_id = $('#party').val();
            let po_id = $('#po').val();
            let design_id = $('#design').val();
            $('#order_item').html('');
            if (design_id && po_id && party_id && size_id && finish_id) {
                $.ajax({
                    url: `/get-order-items-for-dispatch`,
                    method: 'POST',
                    data: { party_id: party_id, purchase_order_id: po_id, design_id: design_id, size_id: size_id, finish_id: finish_id },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('#order_item').append('<option value="">-- Select Order Item --</option>');
                        $.each(data.orderItems, function (i, item) {
                            var name = item.production_qty+'-'+item.design_detail.name+'-'+item.size_detail.size_name+'-'+item.finish_detail.finish_name;
                            $('#order_item').append(`<option value="${item.id}">${name}</option>`);
                        });
                    }
                });
            }
        });

        // Order Item → Batch
        $('#order_item').on('change', function () {
            let order_item_id = $(this).val();
            let party_id = $('#party').val();
            let po_id = $('#po').val();
            $('#batch').html('');
            if (order_item_id && party_id && po_id) {
                $.ajax({
                    url: `/get-batches-for-dispatch`,
                    method: 'POST',
                    data: { party_id: party_id, purchase_order_id: po_id, purchase_order_item_id: order_item_id },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('#batch').append('<option value="">-- Select Batch --</option>');
                        $.each(data, function (i, item) {
                            $('#batch').append(`<option value="${item.id}">${item.batch_no}</option>`);
                        });
                    }
                });
            }
        });

        // Batch → Pallet
        $('#batch').on('change', function () {
            let batch_id = $(this).val();
            let order_item_id = $('#order_item').val();
            let party_id = $('#party').val();
            let po_id = $('#po').val();
            let finish_id = $('#finish').val();
            let size_id = $('#size').val();
            let design_id = $('#design').val();
            $('#pallet').html('');
            if (order_item_id && party_id && po_id && design_id && size_id && finish_id) {
                $.ajax({
                    url: `/get-pallets-for-dispatch`,
                    method: 'POST',
                    data: { party_id: party_id, purchase_order_id: po_id, purchase_order_item_id: order_item_id, design_id:design_id, size_id:size_id, finish_id:finish_id, batch_id:batch_id },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('#pallet').append('<option value="">-- Select Pallet --</option>');
                        $.each(data, function (i, item) {
                            var name = item.pallet_size+' X '+item.pallet_no+' = '+item.total_qty;
                            $('#pallet').append(`<option value="${item.id}" data-size="${item.pallet_size}">${name}</option>`);
                        });
                    }
                });
            }
        });
    });
    $('#pallet_no').on('change', function () {
        let pallet_no = $(this).val();
        let pallet_size = $('#pallet').find(':selected').data('size');
        let qty = pallet_no * pallet_size;
        $('#dispatched_qty').val(qty);
    });
</script>
@endpush