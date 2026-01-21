@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Add Purchase Order Pallets</h4>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('purchase_order_pallets.store') }}" id="palletForm">
            @csrf

            {{-- PO SELECTION --}}
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="purchase_order_id" class="form-label fw-semibold">Purchase Order</label>
                    <select class="form-select" id="purchase_order_id" name="purchase_order_id" required>
                        <option value="">Select Purchase Order</option>
                        @foreach ($purchaseOrders as $po)
                        <option value="{{ $po->id }}" data-po-number="{{ $po->po }}">{{ $po->po }}</option>
                        @endforeach
                    </select>
                    @error('purchase_order_id')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3">
                     <label for="packing_date" class="form-label fw-semibold">Packing Date</label>
                     <input type="date" class="form-control" name="packing_date" value="{{ date('Y-m-d') }}" required>
                </div>
            </div>
            <hr>
            <h5 class="fw-semibold mb-3 text-primary">Order Item</h5>
            <div class="row g-3 align-items-end" id="itemForm">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>Design</label>
                        <select id="design_id" class="form-control">
                            <option value="">Select</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label>Size</label>
                        <select id="size_id" class="form-control">
                            <option value="">Select</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label>Finish</label>
                        <select id="finish_id" class="form-control">
                            <option value="">Select</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>Batch No</label>
                        <select id="batch_id" class="form-control">
                            <option value="">Select</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label>Total Qty (Boxes)</label>
                        <input type="text" id="total_qty" class="form-control" readonly>
                    </div>

                    <div class="col-md-3">
                        <label>Remark</label>
                        <input type="text" id="remark" class="form-control">
                    </div>
                </div>

                {{-- PALLET BLOCK --}}
                <div id="palletSection" style="border:1px dashed #ccc; padding:12px; border-radius:8px; margin-top:15px;">
                    <div id="palletContainer">
                        <div class="row g-3 mt-3 pallet-row">
                            <div class="col-md-3">
                                <label>Box / Pallet</label>
                                <input type="number" class="form-control box_per_pallet" min="1">
                            </div>

                            <div class="col-md-3">
                                <label>Total Pallet</label>
                                <input type="number" class="form-control total_pallet" min="1">
                            </div>

                            <div class="col-md-3">
                                <label>Total Boxes</label>
                                <input type="number" class="form-control total_boxes" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end mt-2">
                        <button type="button" id="addMorePalletBtn" class="btn btn-dark btn-sm add-more">
                            + Add More
                        </button>
                    </div>
                </div>
                <br>
                <div class="col-md-2 text-end">
                    <button type="button" class="btn btn-primary w-100" id="addRow">Add</button>
                </div>
            </div>

            <hr>

            {{-- Items Table --}}
            <h5 class="fw-semibold text-primary">Order Pallets</h5>
            {{-- TABLE --}}
            <div class="table-responsive mt-4">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 15%">Design</th>
                            <th style="width: 10%">Size</th>
                            <th style="width: 10%">Finish</th>
                            <th style="width: 10%">Batch</th>
                            <th style="width: 15%">Remark</th>
                            <th style="width: 40%">Pallet Details</th>
                        </tr>
                    </thead>
                    <tbody id="itemsTable"></tbody>
                </table>
            </div>

            {{-- Container for Dynamic Hidden Inputs --}}
            <div id="hiddenInputsContainer"></div>

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-success px-4" id="submitBtn">Save Pallet</button>
                <a href="{{ route('purchase_order_pallets.index') }}" class="btn btn-secondary ms-2">Back</a>
            </div>
        </form>
    </div>
</div>
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

        if (!po_id) return

        $.ajax({
            url: '/get-order?purchase_order_id=' + po_id,
            type: 'GET',
            success: function(res) {
                poItemsData = res; // Store for local lookup
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

    $(document).on('click', '.add-more', function() {
        let row = `
        <div class="row g-3 mt-3 pallet-row">
            <div class="col-md-3">
                <input type="number" class="form-control box_per_pallet" min="1">
            </div>
            <div class="col-md-3">
                <input type="number" class="form-control total_pallet" min="1">
            </div>
            <div class="col-md-3">
                <input type="number" class="form-control total_boxes" readonly>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="button" class="btn btn-danger remove-pallet">Remove</button>
            </div>
        </div>`
        $('#palletContainer').append(row)
    })

    $(document).on('click', '.remove-pallet', function() {
        $(this).closest('.pallet-row').remove()
        calculateTotal()
    })

    $('#addRow').click(function() {
        // validate main selection
        let designId = $('#design_id').val();
        let sizeId = $('#size_id').val();
        let finishId = $('#finish_id').val();
        let batchId = $('#batch_id').val();
        let poText = $('#purchase_order_id option:selected').data('po-number');
        let poId = $('#purchase_order_id').val();
        let remarkVal = $('#remark').val() || ''; // Capture Remark

        if(!designId || !sizeId || !finishId || !batchId) {
            alert('Please select Design, Size, Finish and Batch.');
            return;
        }

        let designTxt = $('#design_id option:selected').text();
        let sizeTxt = $('#size_id option:selected').text();
        let finishTxt = $('#finish_id option:selected').text();
        let batchTxt = $('#batch_id option:selected').text();

        // Unique Group Key includes Remark to separate groups with different remarks
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

        let addedAny = false;

        $('.pallet-row').each(function() {
            let row = $(this);
            let box = row.find('.box_per_pallet').val();
            let pal = row.find('.total_pallet').val();
            let tot = row.find('.total_boxes').val();

            if (!box || !pal || box <= 0 || pal <= 0) return;
            
            addedAny = true;

            // Generate JSON for design quantities
            let designQtyObj = {};
            designQtyObj[designId] = {
                quantity: tot,
                size_id: sizeId,
                finish_id: finishId
            };
            let designQtyJson = JSON.stringify(designQtyObj);

            // Check if Main Row exists for this Group
            let mainRow = $(`tr[data-group-key="${groupKey}"]`);
            
            if (mainRow.length === 0) {
                // Create New Main Row
                let newMainRow = `
                <tr data-group-key="${groupKey}">
                    <td class="align-middle">${designTxt}</td>
                    <td class="align-middle">${sizeTxt}</td>
                    <td class="align-middle">${finishTxt}</td>
                    <td class="align-middle">${batchTxt}</td>
                    <td class="align-middle">${remarkVal}</td>
                    <td class="p-2">
                        <table class="table table-bordered table-sm mb-0 table-secondary" style="background:#f8f9fa;">
                            <thead>
                                <tr class="table-secondary">
                                    <th style="width:25%">Box/Pallet</th>
                                    <th style="width:25%">Total Pallet</th>
                                    <th style="width:30%">Total Boxes</th>
                                    <th style="width:20%">Action</th>
                                </tr>
                            </thead>
                            <tbody class="sub-body">
                            </tbody>
                        </table>
                    </td>
                </tr>`;
                $('#itemsTable').append(newMainRow);
                mainRow = $(`tr[data-group-key="${groupKey}"]`);
            }

            // Append Pallet Sub-Row
            let subRow = `
            <tr>
                <td>${box}</td>
                <td>${pal}</td>
                <td>${tot}</td>
                <td><button type="button" class="btn btn-danger btn-sm remove-row" data-index="${palletIndex}">X</button></td>
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

        if(!addedAny) {
            alert('Please add at least one valid pallet row with Box and Pallet Count.');
            return;
        }

        // Reset input inputs (optional, keep selection for ease of use?)
        $('#palletContainer').find('.pallet-row').not(':first').remove();
        $('#palletContainer').find('input').val('');
        $('#total_qty').val('');
        $('#remark').val(''); // Reset remark
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
            subBody.closest('tr').remove();
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