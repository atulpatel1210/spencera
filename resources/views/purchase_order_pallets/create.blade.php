@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xxl-11">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-boxes me-2"></i> Add Purchase Order Pallets</h5>
                    <a href="{{ route('purchase_order_pallets.index') }}" class="btn btn-outline-secondary rounded-pill shadow-sm px-4">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('purchase_order_pallets.store') }}" id="palletForm" class="needs-validation" novalidate>
                        @csrf
                        
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

                        {{-- PO SELECTION --}}
                        <div class="card border-0 shadow-sm bg-light rounded-4 mb-4">
                            <div class="card-body p-4">
                                <h6 class="fw-bold text-secondary mb-3 text-uppercase small"><i class="bi bi-clipboard-data me-2"></i>Order Selection</h6>
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label for="purchase_order_id" class="form-label fw-semibold text-secondary small text-uppercase">Purchase Order <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-cart"></i></span>
                                            <select class="form-select border-start-0 bg-white" id="purchase_order_id" name="purchase_order_id" required>
                                                <option value="">Select Purchase Order</option>
                                                @foreach ($purchaseOrders as $po)
                                                <option value="{{ $po->id }}" data-po-number="{{ $po->po }}">{{ $po->po }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('purchase_order_id')
                                        <div class="text-danger mt-1 small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-3">
                                         <label for="packing_date" class="form-label fw-semibold text-secondary small text-uppercase">Packing Date <span class="text-danger">*</span></label>
                                         <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-calendar"></i></span>
                                            <input type="date" class="form-control border-start-0 bg-white" name="packing_date" value="{{ date('Y-m-d') }}" required>
                                         </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Order Item Selection Box --}}
                        <div class="card border-0 shadow-sm rounded-4 mb-4 input-section border-start border-4 border-primary">
                            <div class="card-body p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="fw-bold mb-0 d-flex align-items-center" style="color: #fd7e14;">
            <span class="bg-primary-subtle rounded-circle p-2 me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; color: #fd7e14;"><i class="bi bi-plus-lg fs-6"></i></span> 
            New Pallet Entry
        </h6>
        <div class="form-check form-switch fs-5">
            <input class="form-check-input" type="checkbox" id="is_mix_pallet">
            <label class="form-check-label fw-bold small text-secondary mt-1" for="is_mix_pallet">Is Mix Pallet?</label>
        </div>
    </div>
                                <div class="row g-3 mb-4">
                                    <div class="col-sm-6 col-md-3">
                                        <label class="form-label fw-semibold small text-secondary">Design</label>
                                        <select id="design_id" class="form-select bg-light border-0">
                                            <option value="">Select</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-6 col-md-2">
                                        <label class="form-label fw-semibold small text-secondary">Size</label>
                                        <select id="size_id" class="form-select bg-light border-0">
                                            <option value="">Select</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-6 col-md-2">
                                        <label class="form-label fw-semibold small text-secondary">Finish</label>
                                        <select id="finish_id" class="form-select bg-light border-0">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-sm-6 col-md-3">
                                        <label class="form-label fw-semibold small text-secondary">Batch No</label>
                                        <select id="batch_id" class="form-select bg-light border-0">
                                            <option value="">Select</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-6 col-md-2">
                                        <label class="form-label fw-semibold small text-secondary">Batch Qty</label>
                                        <input type="text" id="batch_qty" class="form-control fw-bold" style="color: #fd7e14; border-color: #ffdeb3; background-color: #fff9f0;" readonly value="0">
                                        <div id="remaining_qty_text" class="text-muted fw-bold" style="font-size: 11px; margin-top: 2px;"></div>
                                    </div>
                                    
                                    <div class="col-sm-6 col-md-2 mix-only" style="display: none;">
                                        <label class="form-label fw-semibold small text-secondary">Box / Pallet</label>
                                        <input type="number" id="mix_box_per_pallet" class="form-control bg-light border-0" placeholder="Box">
                                    </div>
                                    <div class="col-sm-6 col-md-2 mix-only" style="display: none;">
                                        <label class="form-label fw-semibold small text-secondary">Total Pallets</label>
                                        <input type="number" id="mix_total_pallet" class="form-control bg-light border-0" placeholder="Pallet">
                                    </div>
                                    <div class="col-sm-6 col-md-2 mix-only" style="display: none;">
                                        <label class="form-label fw-semibold small text-secondary">Total Boxes</label>
                                        <input type="number" id="mix_item_qty" class="form-control bg-primary-subtle border-0 fw-bold text-primary" readonly placeholder="0">
                                    </div>
                                    <div class="col-sm-6 col-md-2 mix-only" style="display: none;">
                                        <div class="d-flex align-items-end h-100 pb-1">
                                            <button type="button" class="btn btn-outline-primary w-100" id="addMixItemBtn">
                                                <i class="bi bi-plus"></i> Add Item
                                            </button>
                                        </div>
                                    </div>
                                    
                                   <div class="col-md-12">
                                        <label class="form-label fw-semibold small text-secondary">Remark</label>
                                        <input type="text" id="remark" class="form-control bg-light border-0" placeholder="Optional notes...">
                                    </div>
                                </div>

                                {{-- Mix Items Table --}}
                                <div class="mb-4 mix-only" style="display: none;">
                                    <label class="form-label fw-bold text-dark mb-2">Mix Items List</label>
                                    <div class="table-responsive border rounded-3 bg-white">
                                        <table class="table table-sm table-hover mb-0">
                                            <thead class="bg-light small text-muted text-uppercase fw-bold">
                                                <tr>
                                                    <th class="py-3 ps-3 border-0">Design</th>
                                                    <th class="py-3 border-0">Size</th>
                                                    <th class="py-3 border-0">Finish</th>
                                                    <th class="py-3 border-0">Batch</th>
                                                    <th class="py-3 border-0 text-center">Box/Pallet</th>
                                                    <th class="py-3 border-0 text-center">Total Pallets</th>
                                                    <th class="py-3 border-0 text-center">Total Boxes</th>
                                                    <th class="py-3 pe-3 border-0 text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="mixItemsBody">
                                                <tr id="emptyMixRow">
                                                    <td colspan="8" class="text-center text-muted py-4">No items added to mix.</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                {{-- Pallet Configuration Sub-Section --}}
                                <div id="normalPalletSection" class="bg-white p-4 rounded-3 border">
                                    <label class="form-label fw-bold text-dark mb-3"><i class="bi bi-gear me-1"></i> Pallet Configuration</label>
                                    
                                    <div id="palletContainer">
                                        <div class="row g-3 align-items-center pallet-row mb-3">
                                            <div class="col-12 col-md-3">
                                                <label class="small text-secondary mb-1 text-uppercase fw-bold">Box / Pallet</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-0"><i class="bi bi-box"></i></span>
                                                    <input type="number" class="form-control border-0 bg-light box_per_pallet" min="1" placeholder="Enter boxes per pallet">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-3">
                                                <label class="small text-secondary mb-1 text-uppercase fw-bold">Total Pallets</label>
                                                 <div class="input-group">
                                                    <span class="input-group-text bg-light border-0"><i class="bi bi-layers"></i></span>
                                                    <input type="number" class="form-control border-0 bg-light total_pallet" min="1" placeholder="Enter number of pallets">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-3">
                                                <label class="small text-secondary mb-1 text-uppercase fw-bold">Total Boxes</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-primary-subtle border-0 text-primary"><i class="bi bi-calculator"></i></span>
                                                    <input type="number" class="form-control bg-primary-subtle border-0 fw-bold total_boxes text-primary" readonly placeholder="0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 pt-3 border-top d-flex flex-column align-items-end gap-3">
                                        <div class="d-flex align-items-center bg-white px-4 py-2 rounded-3 border border-light" style="min-width: 320px; justify-content: space-between;">
                                            <span class="fw-bold text-secondary small text-uppercase">Section Total:</span>
                                            <input type="text" id="total_qty" class="form-control form-control-sm w-auto fw-bold text-end border-0 bg-transparent fs-5" style="color: #fd7e14;" readonly value="0">
                                        </div>
                                        
                                        <button type="button" id="addMorePalletBtn" class="btn btn-outline-primary btn-sm rounded-pill px-4 py-2 fw-bold bg-white">
                                            <i class="bi bi-plus-circle me-1"></i> Add Configuration Row
                                        </button>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12 text-end">
                                        <button type="button" class="btn btn-dark rounded-pill px-5 shadow-sm" id="addRow">
                                            <i class="bi bi-arrow-down-circle me-2"></i> Add to List
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Items Table --}}
                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-list-check me-2"></i> Pallets to Save</h5>
                                    <div class="text-muted small">Review and save pallet details before proceeding.</div>
                                </div>
                            </div>
                            
                            <div class="card border-0 bg-transparent shadow-none">
                                <!-- Header Row -->
                                <div class="d-flex text-secondary small text-uppercase fw-bold pb-3 border-bottom mb-3 px-2">
                                    <div style="width: 23%" class="ps-2">Design</div>
                                    <div style="width: 12%">Size</div>
                                    <div style="width: 11%">Finish</div>
                                    <div style="width: 12%">Batch</div>
                                    <div style="width: 7%">Remark</div>
                                    <div style="width: 35%" class="text-center">Pallet Details</div>
                                </div>
                                
                                <div id="itemsTable" class="d-flex flex-column gap-3">
                                    <div id="emptyRow" class="card border border-light shadow-sm bg-white rounded-3">
                                        <div class="card-body text-center py-5 text-muted">
                                            <div class="py-4">
                                                <i class="bi bi-inbox fs-1 d-block opacity-25 mb-2"></i>
                                                <span class="fw-medium">No pallets added yet. Use the form above to add items.</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Container for Dynamic Hidden Inputs --}}
                        <div id="hiddenInputsContainer"></div>

                        <div class="d-flex justify-content-end gap-3 mt-5 pb-4">
                            <a href="{{ route('purchase_order_pallets.index') }}" class="btn btn-light btn-lg px-4 border rounded-pill text-secondary fw-medium hover-bg-gray">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-lg px-5 shadow fw-bold rounded-pill" id="submitBtn">
                                <i class="fas fa-save me-2"></i> Save Pallets
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .pallet-row { position: relative; }
</style>
@endsection

@push('scripts')
<script>
    let poItemsData = [];
    let palletIndex = 0;
    let mixItems = [];

    $('#is_mix_pallet').change(function() {
        if ($(this).is(':checked')) {
            $('.mix-only').show();
            $('#normalPalletSection').hide(); 
        } else {
            $('.mix-only').hide();
            $('#normalPalletSection').show();
        }
    });

    $(document).on('input', '#mix_box_per_pallet, #mix_total_pallet', function() {
        let box = parseFloat($('#mix_box_per_pallet').val()) || 0;
        let pal = parseFloat($('#mix_total_pallet').val()) || 0;
        $('#mix_item_qty').val(box * pal);
        updateBatchRemainingDisplay();
    });

    function getAddedBatchQuantity(batchIdToCheck) {
        let qty = 0;
        $('#hiddenInputsContainer div').each(function() {
            let designQtyJson = $(this).find('[name*="[design_quantities]"]').val();
            if (designQtyJson) {
                try {
                    let arr = JSON.parse(designQtyJson);
                    arr.forEach(item => {
                        if (item.batch_id == batchIdToCheck) {
                            qty += parseFloat(item.quantity) || 0;
                        }
                    });
                } catch(e) {}
            }
        });
        return qty;
    }

    $('#addMixItemBtn').click(function() {
        let designId = $('#design_id').val();
        let sizeId = $('#size_id').val();
        let finishId = $('#finish_id').val();
        let batchId = $('#batch_id').val();
        
        let box = parseFloat($('#mix_box_per_pallet').val()) || 0;
        let pal = parseFloat($('#mix_total_pallet').val()) || 0;
        let qty = parseFloat($('#mix_item_qty').val()) || 0;

        if(!designId || !sizeId || !finishId || !batchId) {
            alert('Please select Design, Size, Finish and Batch.');
            return;
        }

        if (box <= 0 || pal <= 0 || qty <= 0) {
            alert('Please enter valid Box/Pallet and Total Pallets.');
            return;
        }

        let opt = $('#batch_id option:selected');
        let initialRem = parseFloat(opt.data('rem')) || 0;
        
        let localQty = getAddedBatchQuantity(batchId);
        
        mixItems.forEach(mi => {
            if (mi.batchId == batchId) localQty += mi.qty;
        });

        if (qty > (initialRem - localQty)) {
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

        mixItems.push({
            designId, sizeId, finishId, batchId, itemId: matchedItem.id,
            designTxt, sizeTxt, finishTxt, batchTxt,
            box, pal, qty
        });

        renderMixItems();

        $('#batch_id').val('').trigger('change');
        $('#mix_box_per_pallet, #mix_total_pallet, #mix_item_qty').val('');
    });

    function renderMixItems() {
        let tbody = $('#mixItemsBody');
        tbody.empty();
        
        if (mixItems.length === 0) {
            tbody.append('<tr id="emptyMixRow"><td colspan="8" class="text-center text-muted py-4">No items added to mix.</td></tr>');
            return;
        }

        mixItems.forEach((mi, index) => {
            tbody.append(`
                <tr>
                    <td class="ps-3 py-3">${mi.designTxt}</td>
                    <td class="py-3">${mi.sizeTxt}</td>
                    <td class="py-3">${mi.finishTxt}</td>
                    <td class="py-3"><span class="badge bg-secondary-subtle text-secondary border border-secondary">${mi.batchTxt}</span></td>
                    <td class="text-center py-3">${mi.box}</td>
                    <td class="text-center py-3">${mi.pal}</td>
                    <td class="text-center fw-bold text-success py-3">${mi.qty}</td>
                    <td class="text-center pe-3 py-3">
                        <button type="button" class="btn btn-outline-danger btn-sm py-0 px-1 border-0" onclick="removeMixItem(${index})"><i class="bi bi-x"></i></button>
                    </td>
                </tr>
            `);
        });
    }

    window.removeMixItem = function(index) {
        mixItems.splice(index, 1);
        renderMixItems();
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
        
        let options = $('#design_id option').not('[value=""]');
        if (options.length === 1) {
            $('#design_id').val(options.val()).trigger('change');
        } else {
            $('#design_id').val('');
            clearSelect($('#size_id'));
            clearSelect($('#finish_id'));
            clearSelect($('#batch_id'));
            $('#batch_qty').val('0');
            $('#remaining_qty_text').text('');
        }
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

        let options = $('#size_id option').not('[value=""]');
        if (options.length === 1) {
            $('#size_id').val(options.val()).trigger('change');
        } else {
            clearSelect($('#finish_id'));
            clearSelect($('#batch_id'));
            $('#batch_qty').val('0');
            $('#remaining_qty_text').text('');
        }
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

        let options = $('#finish_id option').not('[value=""]');
        if (options.length === 1) {
            $('#finish_id').val(options.val()).trigger('change');
        } else {
            clearSelect($('#batch_id'));
            $('#batch_qty').val('0');
            $('#remaining_qty_text').text('');
        }
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

        let options = $('#batch_id option').not('[value=""]');
        if (options.length === 1) {
            $('#batch_id').val(options.val()).trigger('change');
        } else {
            $('#batch_qty').val('0');
            $('#remaining_qty_text').text('');
        }
    });

    $('#purchase_order_id').change(function() {
        let po_id = $(this).val()
        
        clearSelect($('#design_id'));
        clearSelect($('#size_id'));
        clearSelect($('#finish_id'));
        clearSelect($('#batch_id'));
        
        poItemsData = [];
        // Clear inputs on PO change
        $('#palletContainer').find('.pallet-row').not(':first').remove();
        $('#palletContainer').find('input').val('');
        $('#total_qty').val('0');
        $('#batch_qty').val('0');
        $('#remaining_qty_text').text('');

        if (!po_id) return

        $.ajax({
            url: '/get-order?purchase_order_id=' + po_id,
            type: 'GET',
            beforeSend: function() {
                $('#design_id, #size_id, #finish_id, #batch_id').prop('disabled', true);
            },
            success: function(res) {
                poItemsData = res; 
                updateDesignOptions();
                
                if (typeof window.isRestoring !== 'undefined' && window.isRestoring) {
                    restoreOldPallets();
                    window.isRestoring = false;
                }
            },
            complete: function() {
                 $('#design_id, #size_id, #finish_id, #batch_id').prop('disabled', false);
            }
        })
    })

    $('#batch_id').change(function() {
        let opt = $(this).find('option:selected');
        let qty = opt.data('qty') || 0;
        $('#batch_qty').val(qty);
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
        
        // Calculate local quantity for this batch in the table
        let localQty = getAddedBatchQuantity(batchId);
        
        // Also subtract what's currently in mix list
        mixItems.forEach(mi => {
            if (mi.batchId == batchId) localQty += mi.qty;
        });

        // ALSO subtract what is currently entered in the configuration rows above
        let currentEntryQty = 0;
        if (!$('#is_mix_pallet').is(':checked')) {
            currentEntryQty = parseFloat($('#total_qty').val()) || 0;
        } else {
            currentEntryQty = parseFloat($('#mix_item_qty').val()) || 0;
        }

        let currentRem = initialRem - localQty - currentEntryQty;
        $('#remaining_qty_text').text('Remaining: ' + currentRem);
        
        if (currentRem < 0) {
            $('#remaining_qty_text').addClass('text-danger').removeClass('text-muted');
            $('#batch_qty').removeClass('text-warning').addClass('text-danger');
        } else {
            $('#remaining_qty_text').addClass('text-muted').removeClass('text-danger');
            $('#batch_qty').removeClass('text-danger').addClass('text-warning');
        }
    }

    $(document).on('input', '.box_per_pallet, .total_pallet', function() {
        let row = $(this).closest('.pallet-row')
        let box = parseFloat(row.find('.box_per_pallet').val()) || 0
        let pal = parseFloat(row.find('.total_pallet').val()) || 0
        row.find('.total_boxes').val(box * pal)
        calculateTotal()
        updateBatchRemainingDisplay()
    })

    function calculateTotal() {
        let sum = 0;
        $('#palletContainer .total_boxes').each(function() {
            let val = parseFloat($(this).val());
            if (!isNaN(val)) sum += val;
        });
        $('#total_qty').val(sum);
    }

    $(document).on('click', '.add-more', function() { // Legacy class support
        addPalletRow();
    });
    
    $('#addMorePalletBtn').click(function(){
        addPalletRow();
    });

    function addPalletRow() {
        let row = `
        <div class="row g-3 align-items-center pallet-row mb-4 p-3 bg-white rounded-3 shadow-sm border border-light">
            <div class="col-12 col-md-3">
                <label class="small text-secondary mb-1 text-uppercase fw-bold">Box / Pallet</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-box"></i></span>
                    <input type="number" class="form-control border-0 bg-light box_per_pallet" min="1" placeholder="Enter boxes per pallet">
                </div>
            </div>
            <div class="col-12 col-md-3">
                <label class="small text-secondary mb-1 text-uppercase fw-bold">Total Pallets</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-layers"></i></span>
                    <input type="number" class="form-control border-0 bg-light total_pallet" min="1" placeholder="Enter number of pallets">
                </div>
            </div>
            <div class="col-12 col-md-3">
                <label class="small text-secondary mb-1 text-uppercase fw-bold">Total Boxes</label>
                <div class="input-group">
                    <span class="input-group-text bg-primary-subtle border-0 text-primary"><i class="bi bi-calculator"></i></span>
                    <input type="number" class="form-control bg-primary-subtle border-0 fw-bold total_boxes text-primary" readonly placeholder="0">
                </div>
            </div>
            <div class="col-12 col-md-3">
                <label></label>
                <button type="button" class="btn btn-danger d-block remove-pallet">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>`
        $('#palletContainer').append(row)
    }

    $(document).on('click', '.remove-pallet', function() {
        $(this).closest('.pallet-row').remove()
        calculateTotal()
        updateBatchRemainingDisplay()
    })

    $('#addRow').click(function() {
        let isMix = $('#is_mix_pallet').is(':checked');
        
        let designId = $('#design_id').val();
        let sizeId = $('#size_id').val();
        let finishId = $('#finish_id').val();
        let batchId = $('#batch_id').val();
        let poText = $('#purchase_order_id option:selected').data('po-number');
        let poId = $('#purchase_order_id').val();
        let remarkVal = $('#remark').val() || '';

        if (!isMix) {
            if(!designId || !sizeId || !finishId || !batchId) {
                alert('Please select Design, Size, Finish and Batch.');
                return;
            }
        } else {
            if (mixItems.length === 0) {
                alert('Please add at least one item to the mix.');
                return;
            }
        }
        
        let hasData = false;
        let singleTot = 0;
        let mainRowsToProcess = [];

        if (!isMix) {
            $('.pallet-row').each(function() {
                let box = parseFloat($(this).find('.box_per_pallet').val());
                let pal = parseFloat($(this).find('.total_pallet').val());
                if(box > 0 && pal > 0) {
                    hasData = true;
                    singleTot = parseInt($(this).find('.total_boxes').val());
                    mainRowsToProcess.push({box, pal, tot: singleTot});
                }
            });
            
            if(!hasData) {
                 alert('Please enter at least one Box/Pallet and Total Pallet quantity.');
                 return;
            }
            
            let opt = $('#batch_id option:selected');
            let initialRem = parseFloat(opt.data('rem')) || 0;
            let localQty = getAddedBatchQuantity(batchId);
            
            let totalToAdd = 0;
            mainRowsToProcess.forEach(r => totalToAdd += r.tot);
            
            if (totalToAdd > (initialRem - localQty)) {
                alert('Total quantity exceeds remaining batch quantity.');
                return;
            }
        }

        let mainDesignTxt, mainSizeTxt, mainFinishTxt, mainBatchTxt;
        let designQtyArr = [];
        let matchedItem = null;
        let groupKey = isMix ? 'MIX-' + Date.now() : `${designId}-${sizeId}-${finishId}-${batchId}-${remarkVal.replace(/\s+/g, '_')}`;

        if (isMix) {
            let firstItem = mixItems[0];
            designId = firstItem.designId;
            sizeId = firstItem.sizeId;
            finishId = firstItem.finishId;
            batchId = firstItem.batchId;
            
            let sumMixBoxes = 0;
            let mixPalletSize = 0;
            let mixPalletNo = mixItems.length > 0 ? parseFloat(mixItems[0].pal) || 0 : 0;
            mixItems.forEach(mi => {
                designQtyArr.push({
                    design_id: mi.designId,
                    size_id: mi.sizeId,
                    finish_id: mi.finishId,
                    batch_id: mi.batchId,
                    purchase_order_item_id: mi.itemId,
                    quantity: mi.qty,
                    pallet_size: mi.box,
                    pallet_no: mi.pal,
                    total_qty: mi.qty
                });
                sumMixBoxes += mi.qty;
                mixPalletSize += parseFloat(mi.box) || 0;
            });
            
            matchedItem = poItemsData.find(i => i.id == firstItem.itemId);
            
            // For Mix, we process ONE main configuration which contains all mix items inside design_quantities
            mainRowsToProcess.push({box: mixPalletSize, pal: mixPalletNo, tot: sumMixBoxes});
        } else {
            let mainDesignTxt = $('#design_id option:selected').text();
            let mainSizeTxt = $('#size_id option:selected').text();
            let mainFinishTxt = $('#finish_id option:selected').text();
            let mainBatchTxt = $('#batch_id option:selected').text();
            
            matchedItem = poItemsData.find(i => 
                i.design_id == designId && 
                i.size_id == sizeId && 
                i.finish_id == finishId
            );

            if (!matchedItem) {
                alert('This combination (Design/Size/Finish) does not match any Item in the selected PO.');
                return;
            }
        }

        $('#emptyRow').hide();

        mainRowsToProcess.forEach(config => {
            let box = config.box;
            let pal = config.pal;
            let tot = config.tot;

            if (!isMix) {
                designQtyArr = [{
                    design_id: designId,
                    size_id: sizeId,
                    finish_id: finishId,
                    batch_id: batchId,
                    purchase_order_item_id: matchedItem.id,
                    quantity: tot,
                    pallet_size: box,
                    pallet_no: pal,
                    total_qty: tot
                }];
            }
            
            let designQtyJson = JSON.stringify(designQtyArr);
            let mainRow = $(`div[data-group-key="${groupKey}"]`);
            
            if (mainRow.length === 0) {
                let itemsToRender = isMix ? mixItems : [];

                let newMainRow = `
                <div class="card border border-light rounded-3 shadow-sm overflow-hidden bg-white mb-3" data-group-key="${groupKey}">
                    ${isMix ? `
                    <div class="bg-success-subtle text-success fw-bold px-4 py-2 border-bottom d-flex align-items-center">
                        <div class="bg-success text-white rounded p-1 me-2 d-flex align-items-center justify-content-center"><i class="bi bi-boxes" style="font-size: 1.1rem; line-height: 1;"></i></div>
                        MIX PALLET
                    </div>
                    ` : ''}
                    
                    <div class="card-body p-0">
                        <!-- Sub-table headers (only on the right) -->
                        <div class="d-flex align-items-stretch border-bottom bg-white">
                            <div style="width: 65%;"></div>
                            <div class="d-flex text-muted small fw-bold text-center bg-white border-start" style="width: 35%; font-size: 0.75rem;">
                                <div class="flex-fill py-2 border-end" style="width: 25%">BOX/PALLET</div>
                                <div class="flex-fill py-2 border-end" style="width: 25%">TOTAL PALLET</div>
                                <div class="flex-fill py-2 border-end" style="width: 30%">TOTAL BOXES</div>
                                <div class="flex-fill py-2" style="width: 20%">ACTION</div>
                            </div>
                        </div>

                        <!-- Rows Container -->
                        <div class="item-rows-container">
                            ${isMix ? itemsToRender.map((mi, idx, arr) => `
                            <div class="d-flex align-items-stretch ${idx < arr.length - 1 ? 'border-bottom' : ''}">
                                <div class="px-2 py-3 d-flex align-items-center border-end" style="width: 23%;">
                                    <span class="fw-bold text-dark me-2">${idx + 1}.</span>
                                    <span class="fw-bold text-dark text-truncate">${mi.designTxt}</span>
                                    <span class="text-muted ms-1 small text-nowrap">(${mi.qty} box)</span>
                                </div>
                                <div class="px-2 py-3 d-flex align-items-center border-end text-wrap" style="width: 12%;">
                                    ${mi.sizeTxt}
                                </div>
                                <div class="px-2 py-3 d-flex align-items-center border-end" style="width: 11%;">
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 text-truncate" style="max-width: 100%;">${mi.finishTxt}</span>
                                </div>
                                <div class="px-2 py-3 d-flex align-items-center border-end" style="width: 12%;">
                                    ${mi.batchTxt !== '-' && mi.batchTxt !== 'N/A' && mi.batchTxt !== '' ? `<span class="badge bg-secondary-subtle text-secondary border border-secondary rounded-pill px-2 text-truncate" style="max-width: 100%;">${mi.batchTxt}</span>` : '-'}
                                </div>
                                <div class="px-2 py-3 d-flex align-items-center border-end" style="width: 7%;">
                                    <span class="text-muted small text-truncate"><em>${idx === 0 ? (remarkVal || '-') : '-'}</em></span>
                                </div>
                                
                                <!-- Pallet Details (Right Side) -->
                                <div class="d-flex text-center align-items-stretch" style="width: 35%;">
                                    <div class="flex-fill p-3 border-end d-flex align-items-center justify-content-center" style="width: 25%">${mi.box}</div>
                                    <div class="flex-fill p-3 border-end d-flex align-items-center justify-content-center" style="width: 25%">${mi.pal}</div>
                                    <div class="flex-fill p-3 border-end text-success fw-bold d-flex align-items-center justify-content-center" style="width: 30%">${mi.qty}</div>
                                    <div class="flex-fill p-3 d-flex align-items-center justify-content-center text-muted small" style="width: 20%">
                                        Mix Item
                                    </div>
                                </div>
                            </div>
                            `).join('') : ''}
                        </div>

                        <!-- Total row (Only for mix pallets) -->
                        ${isMix ? `
                        <div class="d-flex align-items-stretch border-top bg-light">
                            <div style="width: 65%;"></div>
                            <div class="d-flex text-center align-items-stretch" style="width: 35%;">
                                <div class="flex-fill p-3 border-end d-flex align-items-center justify-content-end" style="width: 80%">
                                    <div class="bg-success-subtle text-success p-1 rounded me-2 d-flex align-items-center justify-content-center"><i class="bi bi-boxes" style="font-size: 0.9rem;"></i></div>
                                    <span class="fw-bold me-2 text-dark">Total Mix Boxes:</span>
                                    <span class="fw-bold text-success fs-5">${tot}</span>
                                </div>
                                <div class="flex-fill p-3 d-flex align-items-center justify-content-center" style="width: 20%">
                                    <button type="button" class="btn btn-outline-danger btn-sm p-1 px-2 remove-row text-danger border-0 bg-transparent" title="Remove Mix" data-index="${palletIndex}"><i class="bi bi-x-lg"></i></button>
                                </div>
                            </div>
                        </div>
                        ` : ''}
                    </div>
                </div>
                `;
                $('#itemsTable').append(newMainRow);
                mainRow = $(`div[data-group-key="${groupKey}"]`);
            }

            if (!isMix) {
                // Ensure previous rows have border-bottom if we are adding a new one
                mainRow.find('.item-rows-container > div').addClass('border-bottom');
                
                let isFirstRow = mainRow.find('.item-rows-container > div').length === 0;
                let designTxt = $('#design_id option:selected').text();
                let sizeTxt = $('#size_id option:selected').text();
                let finishTxt = $('#finish_id option:selected').text();
                let batchTxt = $('#batch_id option:selected').text();

                let normalRow = `
                <div class="d-flex align-items-stretch pallet-item-row" data-row-index="${palletIndex}">
                    <div class="px-2 py-3 d-flex align-items-center border-end" style="width: 23%;">
                        ${isFirstRow ? `
                        <div class="bg-primary-subtle text-primary p-2 rounded me-2 d-flex align-items-center justify-content-center">
                            <i class="bi bi-box"></i>
                        </div>
                        <span class="fw-bold text-dark text-truncate">${designTxt}</span>
                        ` : ''}
                    </div>
                    <div class="px-2 py-3 d-flex align-items-center border-end text-wrap" style="width: 12%;">
                        ${isFirstRow ? sizeTxt : ''}
                    </div>
                    <div class="px-2 py-3 d-flex align-items-center border-end" style="width: 11%;">
                        ${isFirstRow ? `<span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 text-truncate" style="max-width: 100%;">${finishTxt}</span>` : ''}
                    </div>
                    <div class="px-2 py-3 d-flex align-items-center border-end" style="width: 12%;">
                        ${isFirstRow ? (batchTxt !== '-' && batchTxt !== 'N/A' && batchTxt !== '' ? `<span class="badge bg-secondary-subtle text-secondary border border-secondary rounded-pill px-2 text-truncate" style="max-width: 100%;">${batchTxt}</span>` : '-') : ''}
                    </div>
                    <div class="px-2 py-3 d-flex align-items-center border-end" style="width: 7%;">
                        ${isFirstRow ? `<span class="text-muted small text-truncate"><em>${remarkVal || '-'}</em></span>` : ''}
                    </div>
                    
                    <!-- Pallet Details (Right Side) -->
                    <div class="d-flex text-center align-items-stretch" style="width: 35%;">
                        <div class="flex-fill p-3 border-end d-flex align-items-center justify-content-center" style="width: 25%">${box}</div>
                        <div class="flex-fill p-3 border-end d-flex align-items-center justify-content-center" style="width: 25%">${pal}</div>
                        <div class="flex-fill p-3 border-end text-success fw-bold d-flex align-items-center justify-content-center" style="width: 30%">${tot}</div>
                        <div class="flex-fill p-3 d-flex align-items-center justify-content-center" style="width: 20%">
                            <button type="button" class="btn btn-outline-danger btn-sm p-1 px-2 remove-row rounded-circle border-0" title="Remove" data-index="${palletIndex}"><i class="bi bi-x-lg"></i></button>
                        </div>
                    </div>
                </div>
                `;
                mainRow.find('.item-rows-container').append(normalRow);
            }
            
            let isMixVal = isMix ? 1 : 0;

            let inputs = `
            <div class="hidden-group-${palletIndex}">
                <input type="hidden" name="pallets[${palletIndex}][purchase_order_id]" value="${poId}">
                <input type="hidden" name="pallets[${palletIndex}][purchase_order_item_id]" value="${matchedItem.id}">
                <input type="hidden" name="pallets[${palletIndex}][design_id]" value="${designId}">
                <input type="hidden" name="pallets[${palletIndex}][size_id]" value="${sizeId}">
                <input type="hidden" name="pallets[${palletIndex}][finish_id]" value="${finishId}">
                <input type="hidden" name="pallets[${palletIndex}][batch_id]" value="${batchId}">
                <input type="hidden" name="pallets[${palletIndex}][is_mix_pallet]" value="${isMixVal}">
                <input type="hidden" name="pallets[${palletIndex}][pallet_size]" value="${box}">
                <input type="hidden" name="pallets[${palletIndex}][pallet_no]" value="${pal}"> 
                <input type="hidden" name="pallets[${palletIndex}][total_qty]" value="${tot}">
                <input type="hidden" name="pallets[${palletIndex}][po]" value="${poText}">
                <input type="hidden" name="pallets[${palletIndex}][remark]" value="${remarkVal}">
                <textarea style="display:none" name="pallets[${palletIndex}][design_quantities]">${designQtyJson}</textarea>
            </div>
            `;
            $('#hiddenInputsContainer').append(inputs);

            palletIndex++;
        });

        $('#palletContainer').find('.pallet-row').not(':first').remove();
        $('#palletContainer').find('input').val('');
        $('#remark').val('');
        
        if (isMix) {
            mixItems = [];
            renderMixItems();
            $('#is_mix_pallet').prop('checked', false).trigger('change');
        }
        
        calculateTotal();
        
    });

    $(document).on('click', '.remove-row', function() {
        let idx = $(this).data('index');
        
        let card = $(this).closest('.card[data-group-key]');
        
        if ($(this).attr('title') === 'Remove Mix') {
            // Remove the whole mix card
            card.remove();
            $(`[class^="hidden-group-"]`).filter(function() {
                return $(this).closest(`div.hidden-group-${idx}`).length > 0 || $(this).hasClass(`hidden-group-${idx}`);
            }).remove();
        } else {
            // Remove normal pallet row
            let row = $(this).closest('.pallet-item-row');
            row.remove();
            $(`.hidden-group-${idx}`).remove();
            
            // If card is empty, remove it
            let container = card.find('.item-rows-container');
            if (container.children().length === 0) {
                card.remove();
            } else {
                // Remove border-bottom from the last child
                container.children().last().removeClass('border-bottom');
            }
        }

        // Update Remaining Display
        updateBatchRemainingDisplay();

        // Check if main container is empty
        if ($('#itemsTable').children('.card[data-group-key]').length === 0) {
            $('#emptyRow').show();
        }
        
        calculateTotal();
    });
    
    $('#palletForm').submit(function(e){
        // Ensure at least one hidden group exists
        if ($('#hiddenInputsContainer').children().length === 0) {
            e.preventDefault();
            alert('Please add at least one pallet to the table before saving.');
        }
    });
    
    // RESTORE OLD DATA
    let oldPalletsData = {!! json_encode(old('pallets', [])) !!};
    let oldPoId = "{{ old('purchase_order_id') }}";
    window.isRestoring = false;

    if (oldPoId && oldPalletsData && Object.keys(oldPalletsData).length > 0) {
        window.isRestoring = true;
        // Wait for DOM to finish then trigger PO change
        setTimeout(() => {
            $('#purchase_order_id').val(oldPoId).trigger('change');
        }, 100);
    }
    
    function restoreOldPallets() {
        if (!oldPalletsData || Object.keys(oldPalletsData).length === 0) return;
        $('#emptyRow').hide();
        
        let oldPalletsArray = Object.values(oldPalletsData);
        
        oldPalletsArray.forEach(pallet => {
            let isMix = pallet.is_mix_pallet == "1";
            let poText = pallet.po || '';
            let poId = pallet.purchase_order_id;
            let remarkVal = pallet.remark || '';
            let designQtyJson = pallet.design_quantities || '[]';
            
            let designQtyArr = [];
            try { designQtyArr = JSON.parse(designQtyJson); } catch(e) {}
            if (designQtyArr.length === 0) return;
            
            let firstItem = designQtyArr[0];
            let groupKey = isMix ? 'MIX-' + Date.now() + Math.random() : `${firstItem.design_id}-${firstItem.size_id}-${firstItem.finish_id}-${firstItem.batch_id}-${remarkVal.replace(/\s+/g, '_')}`;
            
            let itemsToRender = [];
            if (isMix) {
                designQtyArr.forEach(mi => {
                    let matched = poItemsData.find(i => i.id == mi.purchase_order_item_id);
                    if (matched) {
                        itemsToRender.push({
                            designTxt: matched.design_detail ? matched.design_detail.name : '-',
                            sizeTxt: matched.size_detail ? matched.size_detail.size_name : '-',
                            finishTxt: matched.finish_detail ? matched.finish_detail.finish_name : '-',
                            batchTxt: matched.batch_detail ? (matched.batch_detail.find(b => b.id == mi.batch_id)?.batch_no || '-') : '-',
                            box: mi.pallet_size,
                            pal: mi.pallet_no,
                            qty: mi.quantity
                        });
                    }
                });
            } else {
                let matched = poItemsData.find(i => i.id == firstItem.purchase_order_item_id);
                if (matched) {
                    itemsToRender.push({
                        designTxt: matched.design_detail ? matched.design_detail.name : '-',
                        sizeTxt: matched.size_detail ? matched.size_detail.size_name : '-',
                        finishTxt: matched.finish_detail ? matched.finish_detail.finish_name : '-',
                        batchTxt: matched.batch_detail ? (matched.batch_detail.find(b => b.id == firstItem.batch_id)?.batch_no || '-') : '-',
                        box: pallet.pallet_size,
                        pal: pallet.pallet_no,
                        qty: pallet.total_qty
                    });
                }
            }
            
            if (itemsToRender.length === 0) return;

            let mainRow = $(`div[data-group-key="${groupKey}"]`);
            if (mainRow.length === 0) {
                let newMainRow = `
                <div class="card border border-light rounded-3 shadow-sm overflow-hidden bg-white mb-3" data-group-key="${groupKey}">
                    ${isMix ? `
                    <div class="bg-success-subtle text-success fw-bold px-4 py-2 border-bottom d-flex align-items-center">
                        <div class="bg-success text-white rounded p-1 me-2 d-flex align-items-center justify-content-center"><i class="bi bi-boxes" style="font-size: 1.1rem; line-height: 1;"></i></div>
                        MIX PALLET
                    </div>
                    ` : ''}
                    
                    <div class="card-body p-0">
                        <div class="d-flex align-items-stretch border-bottom bg-white">
                            <div style="width: 65%;"></div>
                            <div class="d-flex text-muted small fw-bold text-center bg-white border-start" style="width: 35%; font-size: 0.75rem;">
                                <div class="flex-fill py-2 border-end" style="width: 25%">BOX/PALLET</div>
                                <div class="flex-fill py-2 border-end" style="width: 25%">TOTAL PALLET</div>
                                <div class="flex-fill py-2 border-end" style="width: 30%">TOTAL BOXES</div>
                                <div class="flex-fill py-2" style="width: 20%">ACTION</div>
                            </div>
                        </div>
                        <div class="item-rows-container">
                            ${isMix ? itemsToRender.map((mi, idx, arr) => `
                            <div class="d-flex align-items-stretch ${idx < arr.length - 1 ? 'border-bottom' : ''}">
                                <div class="px-2 py-3 d-flex align-items-center border-end" style="width: 23%;">
                                    <span class="fw-bold text-dark me-2">${idx + 1}.</span>
                                    <span class="fw-bold text-dark text-truncate">${mi.designTxt}</span>
                                    <span class="text-muted ms-1 small text-nowrap">(${mi.qty} box)</span>
                                </div>
                                <div class="px-2 py-3 d-flex align-items-center border-end text-wrap" style="width: 12%;">${mi.sizeTxt}</div>
                                <div class="px-2 py-3 d-flex align-items-center border-end" style="width: 11%;">
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 text-truncate" style="max-width: 100%;">${mi.finishTxt}</span>
                                </div>
                                <div class="px-2 py-3 d-flex align-items-center border-end" style="width: 12%;">
                                    ${mi.batchTxt !== '-' && mi.batchTxt !== 'N/A' && mi.batchTxt !== '' ? `<span class="badge bg-secondary-subtle text-secondary border border-secondary rounded-pill px-2 text-truncate" style="max-width: 100%;">${mi.batchTxt}</span>` : '-'}
                                </div>
                                <div class="px-2 py-3 d-flex align-items-center border-end" style="width: 7%;">
                                    <span class="text-muted small text-truncate"><em>${idx === 0 ? (remarkVal || '-') : '-'}</em></span>
                                </div>
                                <div class="d-flex text-center align-items-stretch" style="width: 35%;">
                                    <div class="flex-fill p-3 border-end d-flex align-items-center justify-content-center" style="width: 25%">${mi.box}</div>
                                    <div class="flex-fill p-3 border-end d-flex align-items-center justify-content-center" style="width: 25%">${mi.pal}</div>
                                    <div class="flex-fill p-3 border-end text-success fw-bold d-flex align-items-center justify-content-center" style="width: 30%">${mi.qty}</div>
                                    <div class="flex-fill p-3 d-flex align-items-center justify-content-center text-muted small" style="width: 20%">Mix Item</div>
                                </div>
                            </div>
                            `).join('') : ''}
                        </div>
                        ${isMix ? `
                        <div class="d-flex align-items-stretch border-top bg-light">
                            <div style="width: 65%;"></div>
                            <div class="d-flex text-center align-items-stretch" style="width: 35%;">
                                <div class="flex-fill p-3 border-end d-flex align-items-center justify-content-end" style="width: 80%">
                                    <div class="bg-success-subtle text-success p-1 rounded me-2 d-flex align-items-center justify-content-center"><i class="bi bi-boxes" style="font-size: 0.9rem;"></i></div>
                                    <span class="fw-bold me-2 text-dark">Total Mix Boxes:</span>
                                    <span class="fw-bold text-success fs-5">${pallet.total_qty}</span>
                                </div>
                                <div class="flex-fill p-3 d-flex align-items-center justify-content-center" style="width: 20%">
                                    <button type="button" class="btn btn-outline-danger btn-sm p-1 px-2 remove-row text-danger border-0 bg-transparent" title="Remove Mix" data-index="${palletIndex}"><i class="bi bi-x-lg"></i></button>
                                </div>
                            </div>
                        </div>
                        ` : ''}
                    </div>
                </div>
                `;
                $('#itemsTable').append(newMainRow);
                mainRow = $(`div[data-group-key="${groupKey}"]`);
            }
            
            if (!isMix) {
                mainRow.find('.item-rows-container > div').addClass('border-bottom');
                let isFirstRow = mainRow.find('.item-rows-container > div').length === 0;
                let mi = itemsToRender[0];
                
                let normalRow = `
                <div class="d-flex align-items-stretch pallet-item-row" data-row-index="${palletIndex}">
                    <div class="px-2 py-3 d-flex align-items-center border-end" style="width: 23%;">
                        ${isFirstRow ? `
                        <div class="bg-primary-subtle text-primary p-2 rounded me-2 d-flex align-items-center justify-content-center"><i class="bi bi-box"></i></div>
                        <span class="fw-bold text-dark text-truncate">${mi.designTxt}</span>
                        ` : ''}
                    </div>
                    <div class="px-2 py-3 d-flex align-items-center border-end text-wrap" style="width: 12%;">${isFirstRow ? mi.sizeTxt : ''}</div>
                    <div class="px-2 py-3 d-flex align-items-center border-end" style="width: 11%;">
                        ${isFirstRow ? `<span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 text-truncate" style="max-width: 100%;">${mi.finishTxt}</span>` : ''}
                    </div>
                    <div class="px-2 py-3 d-flex align-items-center border-end" style="width: 12%;">
                        ${isFirstRow ? (mi.batchTxt !== '-' && mi.batchTxt !== 'N/A' && mi.batchTxt !== '' ? `<span class="badge bg-secondary-subtle text-secondary border border-secondary rounded-pill px-2 text-truncate" style="max-width: 100%;">${mi.batchTxt}</span>` : '-') : ''}
                    </div>
                    <div class="px-2 py-3 d-flex align-items-center border-end" style="width: 7%;">
                        ${isFirstRow ? `<span class="text-muted small text-truncate"><em>${remarkVal || '-'}</em></span>` : ''}
                    </div>
                    <div class="d-flex text-center align-items-stretch" style="width: 35%;">
                        <div class="flex-fill p-3 border-end d-flex align-items-center justify-content-center" style="width: 25%">${mi.box}</div>
                        <div class="flex-fill p-3 border-end d-flex align-items-center justify-content-center" style="width: 25%">${mi.pal}</div>
                        <div class="flex-fill p-3 border-end text-success fw-bold d-flex align-items-center justify-content-center" style="width: 30%">${mi.qty}</div>
                        <div class="flex-fill p-3 d-flex align-items-center justify-content-center" style="width: 20%">
                            <button type="button" class="btn btn-outline-danger btn-sm p-1 px-2 remove-row rounded-circle border-0" title="Remove" data-index="${palletIndex}"><i class="bi bi-x-lg"></i></button>
                        </div>
                    </div>
                </div>
                `;
                mainRow.find('.item-rows-container').append(normalRow);
            }
            
            let isMixVal = isMix ? 1 : 0;
            let inputs = `
            <div class="hidden-group-${palletIndex}">
                <input type="hidden" name="pallets[${palletIndex}][purchase_order_id]" value="${poId}">
                <input type="hidden" name="pallets[${palletIndex}][purchase_order_item_id]" value="${firstItem.purchase_order_item_id}">
                <input type="hidden" name="pallets[${palletIndex}][design_id]" value="${firstItem.design_id}">
                <input type="hidden" name="pallets[${palletIndex}][size_id]" value="${firstItem.size_id}">
                <input type="hidden" name="pallets[${palletIndex}][finish_id]" value="${firstItem.finish_id}">
                <input type="hidden" name="pallets[${palletIndex}][batch_id]" value="${firstItem.batch_id}">
                <input type="hidden" name="pallets[${palletIndex}][is_mix_pallet]" value="${isMixVal}">
                <input type="hidden" name="pallets[${palletIndex}][pallet_size]" value="${pallet.pallet_size}">
                <input type="hidden" name="pallets[${palletIndex}][pallet_no]" value="${pallet.pallet_no}"> 
                <input type="hidden" name="pallets[${palletIndex}][total_qty]" value="${pallet.total_qty}">
                <input type="hidden" name="pallets[${palletIndex}][po]" value="${poText}">
                <input type="hidden" name="pallets[${palletIndex}][remark]" value="${remarkVal}">
                <textarea style="display:none" name="pallets[${palletIndex}][design_quantities]">${designQtyJson}</textarea>
            </div>
            `;
            $('#hiddenInputsContainer').append(inputs);
            
            palletIndex++;
        });
        
        calculateTotal();
        updateBatchRemainingDisplay();
    }
</script>
@endpush
