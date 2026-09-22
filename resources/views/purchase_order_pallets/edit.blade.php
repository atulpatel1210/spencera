@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xxl-11">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-edit me-2"></i> Edit Pallet Entry
                    </h5>
                    <a href="{{ route('purchase_order_pallets.index') }}" class="btn btn-outline-secondary rounded-pill shadow-sm px-4">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('purchase_order_pallets.update', $pallet->id) }}" method="POST" class="needs-validation" id="editPalletForm" novalidate>
                        @csrf
                        @method('PUT')
                        
                        @if ($errors->any())
                            <div class="alert alert-danger rounded-3 shadow-sm mb-4">
                                <div class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i>Please fix the following errors:</div>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row g-4 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-uppercase">Pallet Size <span class="text-danger">*</span></label>
                                <div class="input-group shadow-sm input-group-sm">
                                    <input type="text" id="pallet_size" name="pallet_size" value="{{ old('pallet_size', $pallet->pallet_size) }}" class="form-control @error('pallet_size') is-invalid @enderror form-control-sm" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-uppercase">Pallet No <span class="text-danger">*</span></label>
                                <div class="input-group shadow-sm input-group-sm">
                                    <input type="number" id="pallet_no" name="pallet_no" value="{{ old('pallet_no', $pallet->pallet_no) }}" class="form-control @error('pallet_no') is-invalid @enderror form-control-sm" required min="0">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-uppercase">Total Quantity</label>
                                <div class="input-group shadow-sm input-group-sm">
                                    <input type="number" id="total_qty" name="total_qty" value="{{ old('total_qty', $pallet->total_qty) }}" class="form-control fw-bold text-success @error('total_qty') is-invalid @enderror form-control-sm" readonly>
                                </div>
                            </div>
                        </div>

                        @if($pallet->is_mix_pallet)
                        {{-- Mix Pallet Edit Section --}}
                        <div class="card border-0 shadow-sm rounded-4 mb-4 input-section border-start border-4 border-primary">
                            <div class="card-body p-4">
                                <h6 class="fw-bold mb-4 d-flex align-items-center" style="color: #fd7e14;">
                                    <span class="bg-primary-subtle rounded-circle p-2 me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; color: #fd7e14;"><i class="bi bi-pencil-square fs-6"></i></span> 
                                    Edit Mix Pallet Items
                                </h6>
                                
                                <div class="row g-3 mb-4">
                                    <!-- Selection inputs for new mix items -->
                                    <div class="col-sm-6 col-md-3">
                                        <label class="form-label fw-semibold small text-secondary">Design</label>
                                        <select id="design_id" class="form-select bg-light border-0 form-select-sm">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 col-md-2">
                                        <label class="form-label fw-semibold small text-secondary">Size</label>
                                        <select id="size_id" class="form-select bg-light border-0 form-select-sm">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 col-md-2">
                                        <label class="form-label fw-semibold small text-secondary">Finish</label>
                                        <select id="finish_id" class="form-select bg-light border-0 form-select-sm">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 col-md-3">
                                        <label class="form-label fw-semibold small text-secondary">Batch No</label>
                                        <select id="batch_id" class="form-select bg-light border-0 form-select-sm">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 col-md-2">
                                        <label class="form-label fw-semibold small text-secondary">Batch Qty</label>
                                        <input type="text" id="batch_qty" class="form-control fw-bold form-control-sm" style="color: #fd7e14; border-color: #ffdeb3; background-color: #fff9f0;" readonly value="0">
                                        <div id="remaining_qty_text" class="text-muted fw-bold" style="font-size: 11px; margin-top: 2px;"></div>
                                    </div>
                                    
                                    <div class="col-sm-6 col-md-3">
                                        <label class="form-label fw-semibold small text-secondary">Quantity to Add</label>
                                        <input type="number" id="mix_item_qty" class="form-control bg-light border-0 form-control-sm" placeholder="Quantity">
                                    </div>
                                    <div class="col-sm-6 col-md-2">
                                        <div class="d-flex align-items-end h-100 pb-1">
                                            <button type="button" class="btn btn-outline-primary w-100" id="addMixItemBtn">
                                                <i class="bi bi-plus"></i> Add Item
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {{-- Mix Items Table --}}
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark mb-2">Mix Items List</label>
                                    <div class="table-responsive border rounded-3 bg-white">
                                        <table class="table table-sm table-hover mb-0">
                                            <thead class="bg-light small text-muted text-uppercase fw-bold">
                                                <tr>
                                                    <th class="py-3 ps-3 border-0">Design</th>
                                                    <th class="py-3 border-0">Size</th>
                                                    <th class="py-3 border-0">Finish</th>
                                                    <th class="py-3 border-0">Batch</th>
                                                    <th class="py-3 border-0 text-center">Total Quantity</th>
                                                    <th class="py-3 pe-3 border-0 text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="mixItemsBody">
                                                <tr id="emptyMixRow">
                                                    <td colspan="6" class="text-center text-muted py-4">No items in mix.</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <input type="hidden" name="design_quantities" id="design_quantities_input">
                            </div>
                        </div>
                        @endif

                        <div class="col-md-12 mb-4">
                            <label class="form-label fw-bold small text-uppercase">Remark</label>
                            <div class="input-group shadow-sm input-group-sm">
                                <textarea name="remark" class="form-control @error('remark') is-invalid @enderror form-control-sm" rows="3">{{ old('remark', $pallet->remark) }}</textarea>
                                @error('remark') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="border-top my-5"></div>

                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('purchase_order_pallets.index') }}" class="btn btn-light border px-4 rounded-pill">Cancel</a>
                            <button type="button" id="submitBtn" class="btn btn-primary px-5 shadow-sm fw-bold rounded-pill">
                                <i class="fas fa-save me-2"></i> Update Pallet
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
    let isMixPallet = {{ $pallet->is_mix_pallet ? 'true' : 'false' }};
    let poId = {{ $pallet->purchase_order_id }};
    let poItemsData = [];
    let mixItems = [];

    @if($pallet->is_mix_pallet)
        // Pre-load existing mix items
        @foreach($pallet->purchaseOrderPalletDesigns as $design)
            mixItems.push({
                designId: '{{ $design->design_id }}',
                sizeId: '{{ $design->size_id }}',
                finishId: '{{ $design->finish_id }}',
                batchId: '{{ $design->batch_id }}',
                itemId: '{{ $design->purchase_order_item_id }}',
                designTxt: '{{ $design->design->name ?? "" }}',
                sizeTxt: '{{ $design->size->size_name ?? "" }}',
                finishTxt: '{{ $design->finish->finish_name ?? "" }}',
                batchTxt: '{{ $design->batch->batch_no ?? "" }}',
                qty: parseFloat('{{ $design->quantity }}')
            });
        @endforeach
    @endif

    $(document).ready(function() {
        if (isMixPallet) {
            renderMixItems();
            loadPOItems();
        }
    });

    $('#submitBtn').click(function() {
        if (isMixPallet) {
            if (mixItems.length === 0) {
                alert('Mix pallet must contain at least one item.');
                return;
            }
            $('#design_quantities_input').val(JSON.stringify(mixItems.map(item => ({
                design_id: item.designId,
                size_id: item.sizeId,
                finish_id: item.finishId,
                batch_id: item.batchId,
                purchase_order_item_id: item.itemId,
                quantity: item.qty
            }))));
        }
        $('#editPalletForm').submit();
    });

    function renderMixItems() {
        let tbody = $('#mixItemsBody');
        tbody.empty();
        
        let total = 0;

        if (mixItems.length === 0) {
            tbody.append('<tr id="emptyMixRow"><td colspan="6" class="text-center text-muted py-4">No items added to mix.</td></tr>');
            $('#total_qty').val(0);
            return;
        }

        mixItems.forEach((mi, index) => {
            total += mi.qty;
            tbody.append(`
                <tr>
                    <td class="ps-3 py-3">${mi.designTxt}</td>
                    <td class="py-3">${mi.sizeTxt}</td>
                    <td class="py-3">${mi.finishTxt}</td>
                    <td class="py-3"><span class="badge bg-secondary-subtle text-secondary border border-secondary">${mi.batchTxt}</span></td>
                    <td class="text-center fw-bold text-success py-3">${mi.qty}</td>
                    <td class="text-center pe-3 py-3">
                        <button type="button" class="btn btn-outline-danger btn-sm py-0 px-1 border-0" onclick="removeMixItem(${index})"><i class="bi bi-x"></i></button>
                    </td>
                </tr>
            `);
        });

        $('#total_qty').val(total);
        if (typeof updateBatchRemainingDisplay === 'function') {
            updateBatchRemainingDisplay();
        }
    }

    window.removeMixItem = function(index) {
        mixItems.splice(index, 1);
        renderMixItems();
    }

    function loadPOItems() {
        $.ajax({
            url: '/get-order?purchase_order_id=' + poId,
            type: 'GET',
            success: function(res) {
                poItemsData = res; 
                updateDesignOptions();
            }
        });
    }

    function populateSelect($select, items, valueKey, textKey, qtyKey = null, remKey = null) {
        let added = []
        $select.html('<option value="">Select</option>')
        items.forEach(item => {
            if (!item) return
            let val = item[valueKey]
            let txt = item[textKey]
            if (!val || !txt) return
            if (added.includes(val)) return
            added.push(val)
            let qtyAttr = qtyKey ? ` data-qty="${item[qtyKey]}"` : ''
            let remAttr = remKey ? ` data-rem="${item[remKey]}"` : ''
            $select.append(`<option value="${val}"${qtyAttr}${remAttr}>${txt}</option>`)
        })
    }

    function clearSelect($select) {
        $select.html('<option value="">Select</option>').val('');
    }

    function updateDesignOptions() {
        let designs = [];
        poItemsData.forEach(item => {
            if (item.design_detail) {
                designs.push(item.design_detail);
            }
        });
        
        populateSelect($('#design_id'), designs, 'id', 'name');
    }

    $('#design_id').change(function() {
        let designId = $(this).val();
        if (!designId) {
            clearSelect($('#size_id'));
            clearSelect($('#finish_id'));
            clearSelect($('#batch_id'));
            $('#batch_qty').val('0');
            $('#remaining_qty_text').text('');
            return;
        }

        let filteredItems = poItemsData.filter(item => item.design_id == designId);
        let sizes = [];
        filteredItems.forEach(item => {
            if (item.size_detail && !Array.isArray(item.size_detail)) {
                sizes.push(item.size_detail);
            }
        });

        populateSelect($('#size_id'), sizes, 'id', 'size_name');
    });

    $('#size_id').change(function() {
        let designId = $('#design_id').val();
        let sizeId = $(this).val();
        if (!designId || !sizeId) {
            clearSelect($('#finish_id'));
            clearSelect($('#batch_id'));
            $('#batch_qty').val('0');
            $('#remaining_qty_text').text('');
            return;
        }

        let filteredItems = poItemsData.filter(item => item.design_id == designId && item.size_id == sizeId);
        let finishes = [];
        filteredItems.forEach(item => {
            if (item.finish_detail) {
                finishes.push(item.finish_detail);
            }
        });

        populateSelect($('#finish_id'), finishes, 'id', 'finish_name');
    });

    $('#finish_id').change(function() {
        let designId = $('#design_id').val();
        let sizeId = $('#size_id').val();
        let finishId = $(this).val();

        if (!designId || !sizeId || !finishId) {
            clearSelect($('#batch_id'));
            $('#batch_qty').val('0');
            $('#remaining_qty_text').text('');
            return;
        }

        let filteredItems = poItemsData.filter(item => 
            item.design_id == designId && 
            item.size_id == sizeId && 
            item.finish_id == finishId
        );

        let batches = [];
        filteredItems.forEach(item => {
            if (item.batch_detail && Array.isArray(item.batch_detail)) {
                item.batch_detail.forEach(b => batches.push(b));
            }
        });

        populateSelect($('#batch_id'), batches, 'id', 'batch_no', 'qty', 'remaining_qty');
    });

    $('#batch_id').change(function() {
        let opt = $(this).find('option:selected');
        let qty = opt.data('qty') || 0;
        $('#batch_qty').val(qty);
        updateBatchRemainingDisplay();
    });

    $(document).on('input', '#mix_item_qty', function() {
        updateBatchRemainingDisplay();
    });

    function updateBatchRemainingDisplay() {
        let batchId = $('#batch_id').val();
        if (!batchId) {
            $('#remaining_qty_text').text('');
            return;
        }

        let opt = $('#batch_id option:selected');
        let initialRem = parseFloat(opt.data('rem')) || 0;
        
        let localQty = 0;
        mixItems.forEach(mi => {
            if (mi.batchId == batchId) localQty += mi.qty;
        });

        // Add the quantity that is being reverted
        let oldQty = 0;
        @if($pallet->is_mix_pallet)
            @foreach($pallet->purchaseOrderPalletDesigns as $design)
                if ('{{ $design->batch_id }}' == batchId) {
                    oldQty += parseFloat('{{ $design->quantity }}');
                }
            @endforeach
        @endif

        // The true remaining qty during edit is (initialRem + oldQty)
        let currentEntryQty = parseFloat($('#mix_item_qty').val()) || 0;
        let currentRem = initialRem + oldQty - localQty - currentEntryQty;
        
        $('#remaining_qty_text').text('Remaining: ' + currentRem);
        
        if (currentRem < 0) {
            $('#remaining_qty_text').addClass('text-danger').removeClass('text-muted');
            $('#batch_qty').removeClass('text-warning').addClass('text-danger');
        } else {
            $('#remaining_qty_text').addClass('text-muted').removeClass('text-danger');
            $('#batch_qty').removeClass('text-danger').addClass('text-warning');
        }
    }

    $('#addMixItemBtn').click(function() {
        let designId = $('#design_id').val();
        let sizeId = $('#size_id').val();
        let finishId = $('#finish_id').val();
        let batchId = $('#batch_id').val();
        let qty = parseFloat($('#mix_item_qty').val()) || 0;

        if(!designId || !sizeId || !finishId || !batchId) {
            alert('Please select Design, Size, Finish and Batch.');
            return;
        }

        if (qty <= 0) {
            alert('Please enter valid Quantity.');
            return;
        }

        let opt = $('#batch_id option:selected');
        let initialRem = parseFloat(opt.data('rem')) || 0;
        
        let localQty = 0;
        mixItems.forEach(mi => {
            if (mi.batchId == batchId) localQty += mi.qty;
        });

        // Add the quantity that is being reverted
        let oldQty = 0;
        @if($pallet->is_mix_pallet)
            @foreach($pallet->purchaseOrderPalletDesigns as $design)
                if ('{{ $design->batch_id }}' == batchId) {
                    oldQty += parseFloat('{{ $design->quantity }}');
                }
            @endforeach
        @endif

        if (qty > (initialRem + oldQty - localQty)) {
            alert('Quantity exceeds remaining batch quantity.');
            return;
        }

        let designTxt = $('#design_id option:selected').text();
        let sizeTxt = $('#size_id option:selected').text();
        let finishTxt = $('#finish_id option:selected').text();
        let batchTxt = $('#batch_id option:selected').text();

        let matchedItem = poItemsData.find(i => 
            i.design_id == designId && 
            i.size_id == sizeId && 
            i.finish_id == finishId
        );

        if (!matchedItem) {
            alert('This combination does not match any Item in the selected PO.');
            return;
        }

        let existingItemIndex = mixItems.findIndex(mi => 
            mi.designId == designId && 
            mi.sizeId == sizeId && 
            mi.finishId == finishId && 
            mi.batchId == batchId
        );

        if (existingItemIndex !== -1) {
            mixItems[existingItemIndex].qty += qty;
        } else {
            mixItems.push({
                designId, sizeId, finishId, batchId, itemId: matchedItem.id,
                designTxt, sizeTxt, finishTxt, batchTxt,
                qty
            });
        }

        renderMixItems();

        $('#batch_id').val('').trigger('change');
        $('#mix_item_qty').val('');
    });
</script>
@endpush
