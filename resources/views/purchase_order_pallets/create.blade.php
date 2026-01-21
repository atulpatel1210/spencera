@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xxl-11">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-boxes me-2"></i> Add Purchase Order Pallets</h5>
                    <a href="{{ route('purchase_order_pallets.index') }}" class="btn btn-outline-secondary rounded-pill shadow-sm px-4">
                        <i class="bi bi-arrow-left me-1"></i> Back to List
                    </a>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('purchase_order_pallets.store') }}" id="palletForm" class="needs-validation" novalidate>
                        @csrf

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
                                <h6 class="fw-bold mb-4 text-primary d-flex align-items-center">
                                    <span class="bg-primary-subtle text-primary rounded-circle p-2 me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="bi bi-plus-lg fs-6"></i></span> 
                                    New Pallet Entry
                                </h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold small text-secondary">Design</label>
                                        <select id="design_id" class="form-select bg-light border-0">
                                            <option value="">Select</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold small text-secondary">Size</label>
                                        <select id="size_id" class="form-select bg-light border-0">
                                            <option value="">Select</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold small text-secondary">Finish</label>
                                        <select id="finish_id" class="form-select bg-light border-0">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold small text-secondary">Batch No</label>
                                        <select id="batch_id" class="form-select bg-light border-0">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    
                                   <div class="col-md-12">
                                        <label class="form-label fw-semibold small text-secondary">Remark</label>
                                        <input type="text" id="remark" class="form-control bg-light border-0" placeholder="Optional notes...">
                                    </div>
                                </div>

                                {{-- Pallet Configuration Sub-Section --}}
                                <div class="bg-light p-4 rounded-4 border border-dashed">
                                    <label class="form-label fw-bold text-dark mb-3"><i class="bi bi-gear me-1"></i> Pallet Configuration</label>
                                    
                                    <div id="palletContainer">
                                        <div class="row g-2 align-items-end pallet-row mb-3">
                                            <div class="col-md-3">
                                                <label class="small text-muted mb-1 text-uppercase fw-bold">Box / Pallet</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-box"></i></span>
                                                    <input type="number" class="form-control border-start-0 bg-white box_per_pallet" min="1" placeholder="0">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="small text-muted mb-1 text-uppercase fw-bold">Total Pallet</label>
                                                 <div class="input-group">
                                                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-layers"></i></span>
                                                    <input type="number" class="form-control border-start-0 bg-white total_pallet" min="1" placeholder="0">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="small text-muted mb-1 text-uppercase fw-bold">Total Boxes</label>
                                                <input type="number" class="form-control bg-transparent total_boxes border-0 fw-bold fs-5 text-dark" readonly placeholder="0">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                        <button type="button" id="addMorePalletBtn" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                            <i class="bi bi-plus-circle me-1"></i> Add Configuration Row
                                        </button>
                                        
                                        <div class="d-flex align-items-center bg-white px-3 py-2 rounded-3 shadow-sm">
                                            <span class="me-2 fw-bold text-secondary small text-uppercase">Section Total:</span>
                                            <input type="text" id="total_qty" class="form-control form-control-sm w-auto fw-bold text-center border-0 bg-transparent text-primary fs-6" readonly value="0">
                                        </div>
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
                        <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4">
                            <div class="card-header bg-light py-3 px-4">
                                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-list-check me-2"></i> Pallets to Save</h6>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 align-middle">
                                    <thead class="bg-white text-secondary small text-uppercase border-bottom">
                                        <tr>
                                            <th style="width: 15%" class="fw-semibold ps-4">Design</th>
                                            <th style="width: 10%" class="fw-semibold">Size</th>
                                            <th style="width: 10%" class="fw-semibold">Finish</th>
                                            <th style="width: 10%" class="fw-semibold">Batch</th>
                                            <th style="width: 15%" class="fw-semibold">Remark</th>
                                            <th style="width: 40%" class="fw-semibold pe-4">Pallet Details</th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemsTable">
                                        <tr id="emptyRow">
                                            <td colspan="6" class="text-center py-5 text-muted">
                                                <div class="py-4">
                                                    <i class="bi bi-inbox fs-1 d-block opacity-25 mb-2"></i>
                                                    <span class="fw-medium">No pallets added yet. Use the form above to add items.</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Container for Dynamic Hidden Inputs --}}
                        <div id="hiddenInputsContainer"></div>

                        <div class="d-flex justify-content-end gap-3 mt-5 pb-4">
                            <a href="{{ route('purchase_order_pallets.index') }}" class="btn btn-light btn-lg px-4 border rounded-pill text-secondary fw-medium hover-bg-gray">Cancel</a>
                            <button type="submit" class="btn btn-success btn-lg px-5 shadow fw-bold rounded-pill transition-all" id="submitBtn">
                                <i class="bi bi-check2-circle me-2"></i> Save Pallets
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

    function populateSelect($select, items, valueKey, textKey) {
        let added = []
        $select.html('<option value="">Select</option>')
        items.forEach(item => {
            if (!item) return
            let val = item[valueKey]
            let txt = item[textKey]
            if (!val || !txt) return
            if (added.includes(val)) return
            added.push(val)
            $select.append(`<option value="${val}">${txt}</option>`)
        })
    }

    $('#purchase_order_id').change(function() {
        let po_id = $(this).val()
        populateSelect($('#design_id'), [], '', '')
        populateSelect($('#size_id'), [], '', '')
        populateSelect($('#finish_id'), [], '', '')
        populateSelect($('#batch_id'), [], '', '')
        poItemsData = [];
        // Clear inputs on PO change
        $('#palletContainer').find('.pallet-row').not(':first').remove();
        $('#palletContainer').find('input').val('');
        $('#total_qty').val('0');

        if (!po_id) return

        $.ajax({
            url: '/get-order?purchase_order_id=' + po_id,
            type: 'GET',
            beforeSend: function() {
                // Optional: show loader
                $('#design_id, #size_id, #finish_id, #batch_id').prop('disabled', true);
            },
            success: function(res) {
                poItemsData = res; 
                let designs = [], sizes = [], finishes = [], batches = []

                res.forEach(item => {
                    if (item.design_detail) designs.push(item.design_detail)
                    if (item.size_detail && !Array.isArray(item.size_detail)) sizes.push(item.size_detail)
                    if (item.finish_detail) finishes.push(item.finish_detail)
                    if (item.batch_detail && Array.isArray(item.batch_detail)) {
                         item.batch_detail.forEach(b => batches.push(b));
                    }
                })

                populateSelect($('#design_id'), designs, 'id', 'name')
                populateSelect($('#size_id'), sizes, 'id', 'size_name')
                populateSelect($('#finish_id'), finishes, 'id', 'finish_name')
                populateSelect($('#batch_id'), batches, 'id', 'batch_no')
            },
            complete: function() {
                 $('#design_id, #size_id, #finish_id, #batch_id').prop('disabled', false);
            }
        })
    })

    $(document).on('input', '.box_per_pallet, .total_pallet', function() {
        let row = $(this).closest('.pallet-row')
        let box = parseFloat(row.find('.box_per_pallet').val()) || 0
        let pal = parseFloat(row.find('.total_pallet').val()) || 0
        row.find('.total_boxes').val(box * pal)
        calculateTotal()
    })

    function calculateTotal() {
        let sum = 0
        $('.total_boxes').each(function() {
            sum += Number($(this).val()) || 0
        })
        $('#total_qty').val(sum)
    }

    $(document).on('click', '.add-more', function() { // Legacy class support
        addPalletRow();
    });
    
    $('#addMorePalletBtn').click(function(){
        addPalletRow();
    });

    function addPalletRow() {
        let row = `
        <div class="row g-2 align-items-end pallet-row mb-3">
            <div class="col-md-3">
               <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-box"></i></span>
                    <input type="number" class="form-control border-start-0 bg-white box_per_pallet" min="1" placeholder="0">
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-layers"></i></span>
                    <input type="number" class="form-control border-start-0 bg-white total_pallet" min="1" placeholder="0">
                </div>
            </div>
            <div class="col-md-3">
                <input type="number" class="form-control bg-transparent total_boxes border-0 fw-bold fs-5 text-dark" readonly placeholder="0">
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-outline-danger btn-sm remove-pallet w-100 rounded-pill">
                    <i class="bi bi-trash"></i> Remove
                </button>
            </div>
        </div>`
        $('#palletContainer').append(row)
    }

    $(document).on('click', '.remove-pallet', function() {
        $(this).closest('.pallet-row').remove()
        calculateTotal()
    })

    $('#addRow').click(function() {
        // Validation
        let designId = $('#design_id').val();
        let sizeId = $('#size_id').val();
        let finishId = $('#finish_id').val();
        let batchId = $('#batch_id').val();
        let poText = $('#purchase_order_id option:selected').data('po-number');
        let poId = $('#purchase_order_id').val();
        let remarkVal = $('#remark').val() || '';

        if(!designId || !sizeId || !finishId || !batchId) {
            alert('Please select Design, Size, Finish and Batch.');
            return;
        }
        
        // Ensure at least one pallet row has data
        let hasData = false;
        $('.pallet-row').each(function() {
            let box = $(this).find('.box_per_pallet').val();
            let pal = $(this).find('.total_pallet').val();
            if(box > 0 && pal > 0) hasData = true;
        });
        
        if(!hasData) {
             alert('Please enter at least one Box/Pallet and Total Pallet quantity.');
             return;
        }

        let designTxt = $('#design_id option:selected').text();
        let sizeTxt = $('#size_id option:selected').text();
        let finishTxt = $('#finish_id option:selected').text();
        let batchTxt = $('#batch_id option:selected').text();

        // Unique Group Key
        let groupKey = `${designId}-${sizeId}-${finishId}-${batchId}-${remarkVal.replace(/\s+/g, '_')}`;

        // Find Order Item ID
        let matchedItem = poItemsData.find(i => 
            i.design_id == designId && 
            i.size_id == sizeId && 
            i.finish_id == finishId
        );

        if (!matchedItem) {
            alert('This combination (Design/Size/Finish) does not match any Item in the selected PO.');
            return;
        }

        // Hide empty state row
        $('#emptyRow').hide();

        $('.pallet-row').each(function() {
            let row = $(this);
            let box = row.find('.box_per_pallet').val();
            let pal = row.find('.total_pallet').val();
            let tot = row.find('.total_boxes').val();

            if (!box || !pal || box <= 0 || pal <= 0) return;

            // Generate JSON for design quantities
            let designQtyObj = {};
            designQtyObj[designId] = {
                quantity: tot,
                size_id: sizeId,
                finish_id: finishId
            };
            let designQtyJson = JSON.stringify(designQtyObj);

            // Check if Main Row exists
            let mainRow = $(`tr[data-group-key="${groupKey}"]`);
            
            if (mainRow.length === 0) {
                // Create New Main Row
                let newMainRow = `
                <tr data-group-key="${groupKey}" class="align-middle border-bottom">
                    <td class="fw-medium ps-4 text-dark">${designTxt}</td>
                    <td>${sizeTxt}</td>
                    <td>${finishTxt}</td>
                    <td><span class="badge bg-secondary-subtle text-secondary border border-secondary">${batchTxt}</span></td>
                    <td class="text-muted small"><em>${remarkVal}</em></td>
                    <td class="p-3 pe-4">
                        <div class="card border-0 shadow-sm">
                        <table class="table table-bordered table-sm mb-0 rounded overflow-hidden">
                            <thead class="bg-dark text-white small text-uppercase">
                                <tr>
                                    <th style="width:25%" class="py-2">Box/Pallet</th>
                                    <th style="width:25%" class="py-2">Total Pallet</th>
                                    <th style="width:30%" class="py-2">Total Boxes</th>
                                    <th style="width:20%" class="py-2">Action</th>
                                </tr>
                            </thead>
                            <tbody class="sub-body bg-white text-center">
                            </tbody>
                        </table>
                        </div>
                    </td>
                </tr>`;
                $('#itemsTable').append(newMainRow);
                mainRow = $(`tr[data-group-key="${groupKey}"]`);
            }

            // Append Pallet Sub-Row
            let subRow = `
            <tr>
                <td class="py-2">${box}</td>
                <td class="py-2">${pal}</td>
                <td class="fw-bold text-success py-2">${tot}</td>
                <td class="py-2"><button type="button" class="btn btn-outline-danger btn-sm p-1 px-2 remove-row rounded-circle border-0" title="Remove" data-index="${palletIndex}"><i class="bi bi-x-lg"></i></button></td>
            </tr>`;
            
            mainRow.find('.sub-body').append(subRow);

            // Hidden Inputs
            let inputs = `
            <div class="hidden-group-${palletIndex}">
                <input type="hidden" name="pallets[${palletIndex}][purchase_order_id]" value="${poId}">
                <input type="hidden" name="pallets[${palletIndex}][purchase_order_item_id]" value="${matchedItem.id}">
                <input type="hidden" name="pallets[${palletIndex}][design_id]" value="${designId}">
                <input type="hidden" name="pallets[${palletIndex}][size_id]" value="${sizeId}">
                <input type="hidden" name="pallets[${palletIndex}][finish_id]" value="${finishId}">
                <input type="hidden" name="pallets[${palletIndex}][batch_id]" value="${batchId}">
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

        // Reset input inputs
        $('#palletContainer').find('.pallet-row').not(':first').remove();
        $('#palletContainer').find('input').val('');
        $('#total_qty').val('0');
        $('#remark').val('');
        
    });

    $(document).on('click', '.remove-row', function() {
        let idx = $(this).data('index');
        
        let subRow = $(this).closest('tr');
        let subBody = subRow.closest('.sub-body');
        
        // Remove visual row
        subRow.remove();
        // Remove hidden inputs
        $(`.hidden-group-${idx}`).remove();

        // Check if sub-body is empty, if so remove main row
        if (subBody.children().length === 0) {
            subBody.closest('table').closest('div').closest('td').closest('tr').remove();
            
            // Show empty state if no rows left
            if($('#itemsTable tr').not('#emptyRow').length === 0) {
                $('#emptyRow').show();
            }
        }
    });
    
    $('#palletForm').submit(function(e){
        // Ensure at least one hidden group exists
        if ($('#hiddenInputsContainer').children().length === 0) {
            e.preventDefault();
            alert('Please add at least one pallet to the table before saving.');
        }
    });
</script>
@endpush