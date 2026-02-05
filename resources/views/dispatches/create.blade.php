@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xxl-11">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-truck me-2"></i> Add Dispatch
                    </h5>
                    <a href="{{ route('dispatches.index') }}" class="btn btn-outline-secondary rounded-pill shadow-sm px-4">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>
                
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success rounded-3 shadow-sm border-0 mb-4 d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger rounded-3 shadow-sm border-0 mb-4">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('dispatches.store') }}" method="POST" id="dispatchForm" class="needs-validation" novalidate>
                        @csrf
                        
                        {{-- Global Dispatch Info --}}
                        <div class="card border-0 shadow-sm rounded-4 mb-4 border-start border-4 border-info bg-white">
                            <div class="card-body p-4">
                                <h6 class="fw-bold text-info mb-3 text-uppercase small"><i class="bi bi-geo-alt me-2"></i>Global Dispatch Information</h6>
                                <div class="row g-3">
                                    <div class="col-sm-6 col-md-4">
                                        <label for="dispatch_date" class="form-label fw-semibold small text-secondary">Dispatch Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control shadow-sm" id="global_dispatch_date" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <label for="vehicle_no" class="form-label fw-semibold small text-secondary">Vehicle No <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control shadow-sm" id="global_vehicle_no" placeholder="e.g. GJ-01-XX-0000" required>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <label for="container_no" class="form-label fw-semibold small text-secondary">Container No <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control shadow-sm" id="global_container_no" placeholder="Enter container number" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Selection Section --}}
                        <div class="card border-0 bg-light rounded-4 mb-4 shadow-sm">
                            <div class="card-body p-4">
                                <h6 class="fw-bold text-secondary mb-3 text-uppercase small"><i class="bi bi-filter-circle me-2"></i>Item Selection</h6>
                                <div class="row g-3">
                                    <div class="col-sm-6 col-md-4">
                                        <label class="form-label fw-semibold small text-secondary">Party</label>
                                        <select id="party" class="form-select border-0 shadow-sm">
                                            <option value="">-- Select Party --</option>
                                            @foreach($parties as $party)
                                            <option value="{{ $party->id }}">{{ $party->party_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                    
                                    <div class="col-sm-6 col-md-4">
                                        <label class="form-label fw-semibold small text-secondary">Purchase Order</label>
                                        <select id="po" class="form-select border-0 shadow-sm"></select>
                                    </div>
                                    
                                    <div class="col-sm-6 col-md-4">
                                        <label class="form-label fw-semibold small text-secondary">Design</label>
                                        <select id="design" class="form-select border-0 shadow-sm"></select>
                                    </div>
                    
                                    <div class="col-sm-6 col-md-4">
                                        <label class="form-label fw-semibold small text-secondary">Size</label>
                                        <select id="size" class="form-select border-0 shadow-sm"></select>
                                    </div>
                    
                                    <div class="col-sm-6 col-md-4">
                                        <label class="form-label fw-semibold small text-secondary">Finish</label>
                                        <select id="finish" class="form-select border-0 shadow-sm"></select>
                                    </div>
                    
                                    <div class="col-sm-6 col-md-4">
                                        <label class="form-label fw-semibold small text-secondary">Order Item</label>
                                        <select id="order_item" class="form-select border-0 shadow-sm"></select>
                                    </div>

                                    <div class="col-sm-6 col-md-4">
                                        <label class="form-label fw-semibold small text-secondary">Batch</label>
                                        <select id="batch" class="form-select border-0 shadow-sm"></select>
                                    </div>

                                    <div class="col-sm-6 col-md-4">
                                        <label class="form-label fw-semibold small text-secondary">Pallet (Available Qty)</label>
                                        <select id="pallet" class="form-select border-0 shadow-sm"></select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Dispatch Details Section --}}
                        <div class="card border-0 rounded-4 mb-4 shadow-sm border-start border-4 border-primary">
                            <div class="card-body p-4">
                                <h6 class="fw-bold text-primary mb-3 d-flex align-items-center">
                                    <span class="bg-primary-subtle text-primary rounded-circle p-2 me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="bi bi-box-seam fs-6"></i></span>
                                    Add Pallets to Vehicle
                                </h6>
                                <div class="row g-3">
                                    <div class="col-sm-6 col-md-4">
                                        <label for="pallet_no" class="form-label fw-semibold small text-secondary">Dispatch Pallets Count</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control shadow-sm" id="pallet_no" min="1" placeholder="Enter pallet no">
                                            <span class="input-group-text bg-light border-0 small text-muted" id="available_indicator">Available: 0</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <label for="dispatched_qty" class="form-label fw-semibold small text-secondary">Total Box Quantity</label>
                                        <input type="number" class="form-control shadow-sm bg-light" id="dispatched_qty" readonly placeholder="Calculated from pallets">
                                    </div>
                                    <div class="col-sm-12 col-md-4">
                                        <label for="remark" class="form-label fw-semibold small text-secondary">Remark (Optional)</label>
                                        <input type="text" class="form-control shadow-sm" id="remark" placeholder="Notes for this item...">
                                    </div>
                                </div>
                                <div class="text-end mt-4">
                                    <button type="button" id="addToList" class="btn btn-dark rounded-pill px-5 shadow-sm">
                                        <i class="bi bi-plus-circle me-2"></i> Add to Vehicle List
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Items Table --}}
                        <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4">
                            <div class="card-header bg-light py-3 px-4">
                                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-truck-flatbed me-2"></i> Vehicle Loading List</h6>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 align-middle">
                                    <thead class="bg-white text-secondary small text-uppercase border-bottom">
                                        <tr>
                                            <th class="ps-4">PO / Design</th>
                                            <th>Batch / Pallet Details</th>
                                            <th>Pallets</th>
                                            <th>Total Qty</th>
                                            <th class="text-end pe-4">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemsTable">
                                        <tr id="emptyRow">
                                            <td colspan="5" class="text-center py-5 text-muted">
                                                <div class="py-4">
                                                    <i class="bi bi-box-seam fs-1 d-block opacity-25 mb-2"></i>
                                                    <span class="fw-medium">No items loaded for this vehicle yet.</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div id="hiddenInputsContainer"></div>

                        <div class="d-flex justify-content-end gap-3 mt-4 pb-4">
                            <button type="button" class="btn btn-light btn-lg px-4 border rounded-pill text-secondary fw-medium" onclick="window.location.reload()">Reset Form</button>
                            <button type="submit" class="btn btn-primary btn-lg px-5 shadow fw-bold rounded-pill" id="submitBtn">
                                <i class="fas fa-save me-2"></i> Confirm & Dispatch Vehicle
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
    let dispatchIndex = 0;
    let currentAvailablePallets = 0;

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
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
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
            let party_id = $('#party').val();
            $('#design').html('');
            if (po_id && party_id) {
                $.ajax({
                    url: `/get-designs-for-dispatch`,
                    method: 'POST',
                    data: { party_id: party_id, purchase_order_id: po_id },
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function (data) {
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
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
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
            $('#finish').html('');
            if (design_id && po_id && party_id && size_id) {
                $.ajax({
                    url: `/get-finishs-for-dispatch`,
                    method: 'POST',
                    data: { party_id: party_id, purchase_order_id: po_id, design_id: design_id, size_id: size_id },
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
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
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function (data) {
                        $('#order_item').append('<option value="">-- Select Order Item --</option>');
                        $.each(data.orderItems, function (i, item) {
                            var name = item.production_qty+'-'+item.design_detail.name;
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
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
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
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function (data) {
                        $('#pallet').append('<option value="">-- Select Pallet --</option>');
                        $.each(data, function (i, item) {
                            var name = `Size: ${item.pallet_size} | Available Pallets: ${item.pallet_no}`;
                            $('#pallet').append(`<option value="${item.id}" data-size="${item.pallet_size}" data-available="${item.pallet_no}">${name}</option>`);
                        });
                    }
                });
            }
        });

        $('#pallet').on('change', function () {
            let available = $(this).find(':selected').data('available') || 0;
            currentAvailablePallets = available;
            $('#available_indicator').text(`Available: ${available}`);
            $('#pallet_no').attr('max', available);
            updateQty();
        });

        $('#pallet_no').on('input', function () {
            let val = parseInt($(this).val()) || 0;
            if (val > currentAvailablePallets) {
                $(this).addClass('is-invalid');
                alert(`Cannot exceed available pallets (${currentAvailablePallets})`);
                $(this).val(currentAvailablePallets);
            } else {
                $(this).removeClass('is-invalid');
            }
            updateQty();
        });

        function updateQty() {
            let pallet_no = $('#pallet_no').val();
            let pallet_size = $('#pallet').find(':selected').data('size');
            if (pallet_no && pallet_size) {
                $('#dispatched_qty').val(pallet_no * pallet_size);
            } else {
                $('#dispatched_qty').val('');
            }
        }

        $('#addToList').click(function() {
            let vehicle = $('#global_vehicle_no').val();
            let container = $('#global_container_no').val();
            let date = $('#global_dispatch_date').val();

            if (!vehicle || !container || !date) {
                alert('Please fill Global Dispatch Information (Vehicle, Container, Date) first.');
                return;
            }

            let party_id = $('#party').val();
            let po_id = $('#po').val();
            let design_id = $('#design').val();
            let size_id = $('#size').val();
            let finish_id = $('#finish').val();
            let order_item_id = $('#order_item').val();
            let batch_id = $('#batch').val();
            let pallet_id = $('#pallet').val();
            
            let pallet_no = $('#pallet_no').val();
            let dispatched_qty = $('#dispatched_qty').val();
            let remark = $('#remark').val();
            let po_text = $('#po option:selected').data('po');

            if (!party_id || !po_id || !design_id || !pallet_id || !pallet_no) {
                alert('Please select all item details.');
                return;
            }

            let design_txt = $('#design option:selected').text();
            let batch_txt = $('#batch option:selected').text();
            let pallet_size = $('#pallet option:selected').data('size');

            $('#emptyRow').hide();

            let row = `
            <tr id="row-${dispatchIndex}" class="border-bottom">
                <td class="ps-4">
                    <div class="fw-bold text-dark">${po_text}</div>
                    <div class="small text-muted">${design_txt}</div>
                </td>
                <td>
                    <div class="small fw-medium">Size: ${pallet_size} | Batch: ${batch_txt}</div>
                    <div class="small text-muted">${remark || '-'}</div>
                </td>
                <td class="fw-bold">${pallet_no} Pallets</td>
                <td class="fw-bold text-primary fs-5">${dispatched_qty} Boxes</td>
                <td class="text-end pe-4">
                    <button type="button" class="btn btn-action text-danger border-0" onclick="removeRow(${dispatchIndex})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>`;

            $('#itemsTable').append(row);

            let inputs = `
            <div id="hidden-${dispatchIndex}">
                <input type="hidden" name="dispatches[${dispatchIndex}][party_id]" value="${party_id}">
                <input type="hidden" name="dispatches[${dispatchIndex}][purchase_order_id]" value="${po_id}">
                <input type="hidden" name="dispatches[${dispatchIndex}][po]" value="${po_text}">
                <input type="hidden" name="dispatches[${dispatchIndex}][design_id]" value="${design_id}">
                <input type="hidden" name="dispatches[${dispatchIndex}][size_id]" value="${size_id}">
                <input type="hidden" name="dispatches[${dispatchIndex}][finish_id]" value="${finish_id}">
                <input type="hidden" name="dispatches[${dispatchIndex}][purchase_order_item_id]" value="${order_item_id}">
                <input type="hidden" name="dispatches[${dispatchIndex}][batch_id]" value="${batch_id}">
                <input type="hidden" name="dispatches[${dispatchIndex}][pallet_id]" value="${pallet_id}">
                <input type="hidden" name="dispatches[${dispatchIndex}][pallet_no]" value="${pallet_no}">
                <input type="hidden" name="dispatches[${dispatchIndex}][dispatched_qty]" value="${dispatched_qty}">
                <input type="hidden" name="dispatches[${dispatchIndex}][dispatch_date]" value="${date}">
                <input type="hidden" name="dispatches[${dispatchIndex}][vehicle_no]" value="${vehicle}">
                <input type="hidden" name="dispatches[${dispatchIndex}][container_no]" value="${container}">
                <input type="hidden" name="dispatches[${dispatchIndex}][remark]" value="${remark}">
            </div>`;

            $('#hiddenInputsContainer').append(inputs);
            dispatchIndex++;

            // Reset only detail fields
            $('#pallet_no').val('');
            $('#dispatched_qty').val('');
            $('#remark').val('');
            $('#available_indicator').text('Available: 0');
        });
    });

    function removeRow(idx) {
        $(`#row-${idx}`).remove();
        $(`#hidden-${idx}`).remove();
        if ($('#itemsTable tr').not('#emptyRow').length === 0) {
            $('#emptyRow').show();
        }
    }

    $('#dispatchForm').submit(function(e){
        if ($('#hiddenInputsContainer').children().length === 0) {
            e.preventDefault();
            alert('Please add at least one item to the vehicle load list.');
        }
    });
</script>
@endpush