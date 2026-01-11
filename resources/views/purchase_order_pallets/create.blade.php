@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Add Purchase Order Pallets</h4>
    </div>

    <div class="card-body">
        <form method="POST" action="#">
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
                </div>

                {{-- PALLET BLOCK --}}
                <div id="palletSection" style="border:1px dashed #ccc; padding:12px; border-radius:8px; margin-top:15px;">
                    <div id="palletContainer">
                        <div class="row g-3 mt-3 pallet-row">
                            <div class="col-md-3">
                                <label>Box / Pallet</label>
                                <input type="number" class="form-control box_per_pallet">
                            </div>

                            <div class="col-md-3">
                                <label>Total Pallet</label>
                                <input type="number" class="form-control total_pallet">
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
                            <th>Design</th>
                            <th>Size</th>
                            <th>Finish</th>
                            <th>Batch</th>
                            <th>Box/Pallet</th>
                            <th>Total Pallet</th>
                            <th>Total Boxes</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="itemsTable"></tbody>
                </table>
            </div>

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-success px-4">Save Pallet</button>
                <a href="{{ route('orders.index') }}" class="btn btn-secondary ms-2">Back</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
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

        if (!po_id) return

        $.ajax({
            url: '/get-order?purchase_order_id=' + po_id,
            type: 'GET',
            success: function(res) {

                let designs = [],
                    sizes = [],
                    finishes = [],
                    batches = []

                res.forEach(item => {
                    if (item.design_detail) designs.push(item.design_detail)
                    if (item.size_detail && !Array.isArray(item.size_detail)) sizes.push(item.size_detail)
                    if (item.finish_detail) finishes.push(item.finish_detail)
                    if (item.batch_detail) batches.push(item.batch_detail)
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
        let box = row.find('.box_per_pallet').val() || 0
        let pal = row.find('.total_pallet').val() || 0
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
            <input type="number" class="form-control box_per_pallet">
        </div>
        <div class="col-md-3">
            <input type="number" class="form-control total_pallet">
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

        $('.pallet-row').each(function() {

            let box = $(this).find('.box_per_pallet').val()
            let pal = $(this).find('.total_pallet').val()
            let tot = $(this).find('.total_boxes').val()

            if (!box || !pal) return

            let tr = `
        <tr>
            <td>${$('#design_id option:selected').text()}</td>
            <td>${$('#size_id option:selected').text()}</td>
            <td>${$('#finish_id option:selected').text()}</td>
            <td>${$('#batch_id option:selected').text()}</td>
            <td>${box}</td>
            <td>${pal}</td>
            <td>${tot}</td>
            <td><button type="button" class="btn btn-danger btn-sm remove">X</button></td>
        </tr>`
            $('#itemsTable').append(tr)
        })

        $('#palletContainer').html('')
        $('#total_qty').val('')
    })

    $(document).on('click', '.remove', function() {
        $(this).closest('tr').remove()
    })
</script>
@endpush