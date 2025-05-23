@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Add Purchase Order Pallets</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('purchase_order_pallets.store') }}" method="POST" id="pallet_form">
            @csrf

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label for="purchase_order_id" class="form-label">Purchase Order</label>
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
                <div class="col-md-4">
                    <label for="design" class="form-label">Design</label>
                    <select class="form-select" id="design" name="design" required>
                        <option value="">Select Design</option>
                        </select>
                    @error('design')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="size" class="form-label">Size</label>
                    <select class="form-select" id="size" name="size" required>
                        <option value="">Select Size</option>
                        </select>
                    @error('size')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="finish" class="form-label">Finish</label>
                    <select class="form-select" id="finish" name="finish" required>
                        <option value="">Select Finish</option>
                        </select>
                    @error('finish')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="purchase_order_item_id" class="form-label">Order Item</label>
                    <select class="form-select" id="purchase_order_item_id" name="purchase_order_item_id" required>
                        <option value="">Select Order Item</option>
                        </select>
                    @error('purchase_order_item_id')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                 <div class="col-md-4">
                    <label for="batch_id" class="form-label">Batch No</label>
                    <select class="form-select" id="batch_id" name="batch_id" required>
                        <option value="">Select Batch</option>
                    </select>
                    @error('batch_id')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="packing_date" class="form-label">Pallet Packing Date</label>
                    <input type="date" class="form-control" id="packing_date" name="packing_date" value="{{ old('order_date', date('Y-m-d')) }}" required>
                    @error('packing_date')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr>

            <h5>Pallet Details</h5>
            <div id="pallets_container">
                <div class="repeatable-pallet mb-3 p-3 border rounded">
                    <div class="row g-3">
                        <input type="hidden" name="pallets[0][purchase_order_id]" class="po-id-hidden">
                        <input type="hidden" name="pallets[0][purchase_order_item_id]" class="poi-id-hidden">
                        <input type="hidden" name="pallets[0][po]" class="po-hidden">
                        <input type="hidden" name="pallets[0][design]" class="design-hidden">
                        <input type="hidden" name="pallets[0][size]" class="size-hidden">
                        <input type="hidden" name="pallets[0][finish]" class="finish-hidden">
                        <input type="hidden" name="pallets[0][batch_id]" class="batch-id-hidden">


                        <div class="col-md-3">
                            <label for="pallet_size_0" class="form-label">Pallet Size</label>
                            <input type="number" class="form-control pallet-size-input" name="pallets[0][pallet_size]" id="pallet_size_0" required min="0">
                            @error('pallets.0.pallet_size')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="pallet_no_0" class="form-label">Pallet No</label>
                            <input type="number" class="form-control pallet-no-input" name="pallets[0][pallet_no]" id="pallet_no_0" required min="0">
                            @error('pallets.0.pallet_no')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="total_qty_0" class="form-label">Total Qty</label>
                            <input type="number" class="form-control total-qty-input" name="pallets[0][total_qty]" id="total_qty_0" readonly required min="0">
                            @error('pallets.0.total_qty')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="remark_0" class="form-label">Remark</label>
                            <textarea class="form-control" name="pallets[0][remark]" id="remark_0" rows="3"></textarea>
                            @error('pallets.0.remark')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm mt-3 remove-pallet-item" data-repeat-remove="repeatable-pallet">Remove Pallet</button>
                </div>
            </div>
            <button type="button" class="btn btn-secondary mt-3" id="add_pallet_btn" data-repeat-add="repeatable-pallet">Add Pallet</button>
            <div class="text-danger mt-2" id="total_qty_validation_error"></div>

            <button type="button" class="btn btn-success mt-4" id="savePalletsBtn">Save Pallets</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const purchaseOrderIdSelect = document.getElementById('purchase_order_id');
    const purchaseOrderItemIdSelect = document.getElementById('purchase_order_item_id');
    const designSelect = document.getElementById('design');
    const sizeSelect = document.getElementById('size');
    const finishSelect = document.getElementById('finish');
    const batchIdSelect = document.getElementById('batch_id');
    const palletsContainer = document.getElementById('pallets_container');
    const addPalletBtn = document.getElementById('add_pallet_btn');
    const savePalletsBtn = document.getElementById('savePalletsBtn'); // Get the new button by ID
    const form = document.getElementById('pallet_form');
    const totalQtyValidationError = document.getElementById('total_qty_validation_error');

    let currentProductionQty = 0; // To store the production_qty of the selected order item
    let allOrderItemsData = []; // To store all order items fetched for the selected PO

    // Function to update hidden IDs and selected values in all pallet repeaters
    function updateHiddenFields() {
        const currentPoId = purchaseOrderIdSelect.value;
        const currentPoItemId = purchaseOrderItemIdSelect.value;
        const currentPoNumber = purchaseOrderIdSelect.options[purchaseOrderIdSelect.selectedIndex]?.dataset.poNumber || '';
        const currentDesign = designSelect.value;
        const currentSize = sizeSelect.value;
        const currentFinish = finishSelect.value;
        const currentBatchId = batchIdSelect.value;

        document.querySelectorAll('.po-id-hidden').forEach(input => { input.value = currentPoId; });
        document.querySelectorAll('.poi-id-hidden').forEach(input => { input.value = currentPoItemId; });
        document.querySelectorAll('.po-hidden').forEach(input => { input.value = currentPoNumber; });
        document.querySelectorAll('.design-hidden').forEach(input => { input.value = currentDesign; });
        document.querySelectorAll('.size-hidden').forEach(input => { input.value = currentSize; });
        document.querySelectorAll('.finish-hidden').forEach(input => { input.value = currentFinish; });
        document.querySelectorAll('.batch-id-hidden').forEach(input => { input.value = currentBatchId; });

        validateTotalQtySum(); // Re-validate sum after hidden fields update
    }

    // Clear and populate a select element
    function populateSelect(selectElement, data, valueKey, textKey) {
        selectElement.innerHTML = '<option value="">Select ' + selectElement.previousElementSibling.textContent.replace(':', '') + '</option>';
        data.forEach(item => {
            const option = document.createElement('option');
            option.value = item[valueKey];
            option.textContent = item[textKey];
            selectElement.appendChild(option);
        });
    }

    // Function to calculate Total Qty for a given pallet item row
    function calculateTotalQty(palletItemDiv) {
        const palletSizeInput = palletItemDiv.querySelector('.pallet-size-input');
        const palletNoInput = palletItemDiv.querySelector('.pallet-no-input');
        const totalQtyInput = palletItemDiv.querySelector('.total-qty-input');

        const palletSize = parseFloat(palletSizeInput.value) || 0;
        const palletNo = parseFloat(palletNoInput.value) || 0;

        const totalQty = palletSize * palletNo;
        totalQtyInput.value = totalQty;

        validateTotalQtySum(); // Validate sum after individual total qty changes
    }

    // Attach event listeners for calculation to existing and future pallet items
    function attachCalculationListeners(palletItemDiv) {
        const palletSizeInput = palletItemDiv.querySelector('.pallet-size-input');
        const palletNoInput = palletItemDiv.querySelector('.pallet-no-input');

        palletSizeInput.addEventListener('input', () => calculateTotalQty(palletItemDiv));
        palletNoInput.addEventListener('input', () => calculateTotalQty(palletItemDiv));
    }

    // Function to validate the sum of all Total Qty against currentProductionQty
    function validateTotalQtySum() {
        let sumOfTotalQty = 0;
        document.querySelectorAll('.total-qty-input').forEach(input => {
            sumOfTotalQty += parseFloat(input.value) || 0;
        });

        if (currentProductionQty > 0 && sumOfTotalQty > currentProductionQty) {
            totalQtyValidationError.textContent = `Total Quantity (${sumOfTotalQty}) cannot exceed Production Quantity (${currentProductionQty}).`;
            return false;
        } else {
            totalQtyValidationError.textContent = '';
            return true;
        }
    }

    // Function to fetch and populate order items based on filters
    function fetchAndPopulateOrderItems() {
        const purchaseOrderId = purchaseOrderIdSelect.value;
        const designId = designSelect.value;
        const sizeId = sizeSelect.value; // Corrected: Was designSelect.value
        const finishId = finishSelect.value;

        // Clear dependent dropdowns before fetching
        populateSelect(purchaseOrderItemIdSelect, [], 'id', 'id');
        populateSelect(batchIdSelect, [], 'id', 'batch_no');
        currentProductionQty = 0; // Reset production qty
        totalQtyValidationError.textContent = ''; // Clear previous error
        validateTotalQtySum(); // Re-validate after clearing

        if (purchaseOrderId) {
            let url = `/get-order-items?purchase_order_id=${purchaseOrderId}`;
            if (designId) url += `&design_id=${designId}`;
            if (sizeId) url += `&size_id=${sizeId}`;
            if (finishId) url += `&finish_id=${finishId}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    allOrderItemsData = data; // Store all fetched order items
                    populateOrderItemSelect(data);
                    updateHiddenFields();
                })
                .catch(error => console.error('Error fetching order items:', error));
        }
    }

    // Custom populate function for Order Item dropdown
    function populateOrderItemSelect(data) {
        purchaseOrderItemIdSelect.innerHTML = '<option value="">Select Order Item</option>';
        data.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = `Prod Qty: ${item.production_qty} - Design: ${item.design_detail.name} - Size: ${item.size_detail.size_name} - Finish: ${item.finish_detail.finish_name}`;
            option.dataset.productionQty = item.production_qty; // Store production_qty in dataset
            purchaseOrderItemIdSelect.appendChild(option);
        });
    }

    // Load Order Items, Designs, Sizes, Finishes when Purchase Order changes
    purchaseOrderIdSelect.addEventListener('change', function() {
        const purchaseOrderId = this.value;

        // Clear all dependent dropdowns and reset production qty
        populateSelect(purchaseOrderItemIdSelect, [], 'id', 'id');
        populateSelect(designSelect, [], 'id', 'name');
        populateSelect(sizeSelect, [], 'id', 'size_name');
        populateSelect(finishSelect, [], 'id', 'finish_name');
        populateSelect(batchIdSelect, [], 'id', 'batch_no');
        currentProductionQty = 0;
        totalQtyValidationError.textContent = ''; // Clear previous error
        validateTotalQtySum();

        if (purchaseOrderId) {
            fetch(`/get-order?purchase_order_id=${purchaseOrderId}`)
                .then(response => response.json())
                .then(data => {
                    allOrderItemsData = data; // Store all fetched order items
                    populateOrderItemSelect(data);

                    const uniqueDesigns = [...new Set(data.map(item => ({ id: item.design_detail.id, name: item.design_detail.name })))];
                    const uniqueSizes = [...new Set(data.map(item => ({ id: item.size_detail.id, size_name: item.size_detail.size_name })))];
                    const uniqueFinishes = [...new Set(data.map(item => ({ id: item.finish_detail.id, finish_name: item.finish_detail.finish_name })))];

                    populateSelect(designSelect, uniqueDesigns, 'id', 'name');
                    populateSelect(sizeSelect, uniqueSizes, 'id', 'size_name');
                    populateSelect(finishSelect, uniqueFinishes, 'id', 'finish_name');

                    updateHiddenFields();
                })
                .catch(error => console.error('Error fetching order items:', error));
        }
    });

    // Listeners for Design, Size, Finish changes to re-filter Order Items
    designSelect.addEventListener('change', fetchAndPopulateOrderItems);
    sizeSelect.addEventListener('change', fetchAndPopulateOrderItems);
    finishSelect.addEventListener('change', fetchAndPopulateOrderItems);


    // Load Batches when Order Item changes
    purchaseOrderItemIdSelect.addEventListener('change', function() {
        const purchaseOrderItemId = this.value;
        populateSelect(batchIdSelect, [], 'id', 'batch_no'); // Clear batches
        currentProductionQty = 0; // Reset production qty
        totalQtyValidationError.textContent = ''; // Clear previous error

        if (purchaseOrderItemId) {
            // Set currentProductionQty from the selected option's dataset
            const selectedOption = purchaseOrderItemIdSelect.options[purchaseOrderItemIdSelect.selectedIndex];
            if (selectedOption && selectedOption.dataset.productionQty !== undefined) {
                currentProductionQty = parseFloat(selectedOption.dataset.productionQty);
            }

            fetch(`/get-batches?purchase_order_item_id=${purchaseOrderItemId}`)
                .then(response => response.json())
                .then(data => {
                    populateSelect(batchIdSelect, data, 'id', 'batch_no');
                    updateHiddenFields();
                    validateTotalQtySum(); // Validate after production_qty is updated
                })
                .catch(error => console.error('Error fetching batches:', error));
        }
    });

    // Listen for changes on batch_id to update hidden fields immediately
    batchIdSelect.addEventListener('change', updateHiddenFields);


    // Initialize repeat.js for pallets
    addPalletBtn.addEventListener('click', function() {
        const template = document.querySelector('.repeatable-pallet');
        if (template) {
            const newElement = template.cloneNode(true);
            const index = palletsContainer.querySelectorAll('.repeatable-pallet').length;

            newElement.querySelectorAll('input, select, textarea').forEach(input => {
                const name = input.name;
                if (name) {
                    // Update name attribute for array indexing
                    input.name = name.replace(/\[\d+\]/, `[${index}]`);
                }
                // Update ID attribute for uniqueness
                if (input.id) {
                    input.id = input.id.replace(/_\d+$/, `_${index}`);
                }
                input.value = ''; // Clear values for new row
            });

            // Re-attach remove event listener for the new button
            const removeButton = newElement.querySelector('.remove-pallet-item');
            if (removeButton) {
                removeButton.addEventListener('click', function() {
                    if (palletsContainer.querySelectorAll('.repeatable-pallet').length > 1) {
                        newElement.remove();
                    } else {
                        // If only one item, clear fields instead of removing
                        newElement.querySelectorAll('input, select, textarea').forEach(input => {
                            input.value = '';
                            if (input.tagName === 'SELECT') input.selectedIndex = 0;
                        });
                        // Also clear calculation fields if only one item remains and it's cleared
                        calculateTotalQty(newElement);
                    }
                    validateTotalQtySum(); // Re-validate sum after removal
                });
            }

            palletsContainer.appendChild(newElement);
            attachCalculationListeners(newElement); // Attach listeners to the newly added pallet item
            updateHiddenFields(); // Ensure new repeater item also gets updated hidden IDs
            validateTotalQtySum(); // Re-validate sum after adding new item
        }
    });

    // Initial setup for remove buttons and calculation listeners
    document.querySelectorAll('.repeatable-pallet').forEach(palletDiv => {
        const removeButton = palletDiv.querySelector('.remove-pallet-item');
        if (removeButton) {
            removeButton.addEventListener('click', function() {
                if (palletsContainer.querySelectorAll('.repeatable-pallet').length > 1) {
                    this.closest('.repeatable-pallet').remove();
                } else {
                    // If only one item, clear fields instead of removing
                    this.closest('.repeatable-pallet').querySelectorAll('input, select, textarea').forEach(input => {
                        input.value = '';
                        if (input.tagName === 'SELECT') input.selectedIndex = 0;
                    });
                    // Also clear calculation fields if only one item remains and it's cleared
                    calculateTotalQty(this.closest('.repeatable-pallet'));
                }
                validateTotalQtySum(); // Re-validate sum after removal
            });
        }
        attachCalculationListeners(palletDiv); // Attach listeners to the initial pallet item
    });


    // Initial update of hidden IDs in the first repeater item
    updateHiddenFields();

    // Form submission validation
    savePalletsBtn.addEventListener('click', function(event) {
        if (validateTotalQtySum()) {
            form.submit();
        }
    });
});
</script>
@endsection
