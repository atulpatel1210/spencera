@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Add Dispatch</h4>
    </div>
    <div class="card-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        <form action="{{ route('dispatches.store') }}" method="POST">
            @csrf
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Party</label>
                    <select name="party_id" id="party" class="form-select @error('party_id') is-invalid @enderror">
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
                    <label class="form-label">Purchase Order</label>
                    <select name="purchase_order_id" id="po" class="form-select @error('purchase_order_id') is-invalid @enderror"></select>
                    @error('purchase_order_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <input type="hidden" name="po" id="po_number" value="">
                <div class="col-md-4">
                    <label class="form-label">Design</label>
                    <select name="design" id="design" class="form-select @error('design') is-invalid @enderror"></select>
                    @error('design')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Size</label>
                    <select name="size" id="size" class="form-select @error('size') is-invalid @enderror"></select>
                    @error('size')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Finish</label>
                    <select name="finish" id="finish" class="form-select @error('finish') is-invalid @enderror"></select>
                    @error('finish')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Order Item</label>
                    <select name="purchase_order_item_id" id="order_item" class="form-select @error('purchase_order_item_id') is-invalid @enderror"></select>
                    @error('purchase_order_item_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Batch</label>
                    <select name="batch_id" id="batch" class="form-select @error('batch_id') is-invalid @enderror"></select>
                    @error('batch_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Pallet</label>
                    <select name="pallet_id" id="pallet" class="form-select @error('pallet_id') is-invalid @enderror" aria-describedby="palletIdHelp"></select>
                    @error('pallet_id')
                        <div id="palletIdHelp" class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="pallet_no" class="form-label">Pallet No</label>
                    <input type="number" class="form-control @error('pallet_no') is-invalid @enderror" id="pallet_no" name="pallet_no" value="{{ old('pallet_no') }}" required min="1" aria-describedby="palletNoHelp" placeholder="Enter pallet no">
                    @error('pallet_no')
                        <div id="palletNoHelp" class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="dispatched_qty" class="form-label">Dispatched Quantity</label>
                    <input type="number" class="form-control @error('dispatched_qty') is-invalid @enderror" id="dispatched_qty" name="dispatched_qty" value="{{ old('dispatched_qty') }}" required min="1" aria-describedby="dispatchedQtyHelp" placeholder="Enter quantity" readonly>
                    @error('dispatched_qty')
                    <div id="dispatchedQtyHelp" class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="dispatch_date" class="form-label">Dispatch Date</label>
                    <input type="date" class="form-control @error('dispatch_date') is-invalid @enderror" id="dispatch_date" name="dispatch_date" value="{{ old('dispatch_date', date('Y-m-d')) }}" required aria-label="Dispatch Date">
                    @error('dispatch_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="vehicle_no" class="form-label">Vehicle No</label>
                    <input type="text" class="form-control @error('vehicle_no') is-invalid @enderror" id="vehicle_no" name="vehicle_no" value="{{ old('vehicle_no') }}" aria-label="Vehicle No">
                    @error('vehicle_no')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="container_no" class="form-label">Container No</label>
                    <input type="text" class="form-control @error('container_no') is-invalid @enderror" id="container_no" name="container_no" value="{{ old('container_no') }}" aria-label="Container No">
                    @error('container_no')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-12">
                    <label for="remark" class="form-label">Remark</label>
                    <textarea class="form-control @error('remark') is-invalid @enderror" id="remark" name="remark" rows="3" aria-label="Remark">{{ old('remark') }}</textarea>
                    @error('remark')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <button type="submit" class="btn btn-success mt-4">Add Dispatch</button>
            <a href="{{ route('dispatches.index') }}" class="btn btn-secondary mt-4">Back to List</a>
        </form>
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