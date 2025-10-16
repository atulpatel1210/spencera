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
                        {{-- Hidden fields for main selection --}}
                        <input type="hidden" name="pallets[0][purchase_order_id]" class="po-id-hidden">
                        <input type="hidden" name="pallets[0][purchase_order_item_id]" class="poi-id-hidden">
                        <input type="hidden" name="pallets[0][po]" class="po-hidden">
                        <input type="hidden" name="pallets[0][design_id]" class="design-id-hidden"> {{-- Renamed to design_id to match PO Item structure --}}
                        <input type="hidden" name="pallets[0][size_id]" class="size-id-hidden"> {{-- Renamed to size_id --}}
                        <input type="hidden" name="pallets[0][finish_id]" class="finish-id-hidden"> {{-- Renamed to finish_id --}}
                        <input type="hidden" name="pallets[0][batch_id]" class="batch-id-hidden">

                        {{-- Pallet specific fields --}}
                        <div class="col-md-3">
                            <label for="pallet_size_0" class="form-label">Pallet Size</label>
                            <input type="number" class="form-control pallet-size-input" name="pallets[0][pallet_size]" id="pallet_size_0" required min="1">
                            @error('pallets.0.pallet_size')
                            <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="pallet_no_0" class="form-label">Pallet No</label>
                            <input type="number" class="form-control pallet-no-input" name="pallets[0][pallet_no]" id="pallet_no_0" required min="1">
                            @error('pallets.0.pallet_no')
                            <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="total_qty_0" class="form-label">Total Qty (Pallet)</label>
                            <input type="number" class="form-control total-qty-input" name="pallets[0][total_qty]" id="total_qty_0" readonly required min="0">
                            @error('pallets.0.total_qty')
                            <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- NEW: Design Quantity input and Add Design button --}}
                        <div class="col-md-3 d-flex align-items-end">
                            <div>
                                <label for="design_qty_0" class="form-label">Design Qty</label>
                                <input type="number" class="form-control design-qty-input" id="design_qty_0" min="1" value="1">
                            </div>
                            <button type="button" class="btn btn-outline-primary ms-2 add-design-to-pallet-btn" data-bs-toggle="tooltip" title="Add Design to Pallet">
                                <i class="fa fa-plus"></i> Add Design
                            </button>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="form-label">Designs in this Pallet</label>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm pallet-designs-table">
                                    <thead>
                                        <tr>
                                            <th>Design</th>
                                            <th>Quantity</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Design rows will be appended here by JS --}}
                                    </tbody>
                                </table>
                            </div>
                            {{-- Hidden input to store aggregated design quantities for this pallet --}}
                            <input type="hidden" name="pallets[0][design_quantities]" class="design-quantities-hidden">
                            <div class="text-danger mt-1 design-qty-error"></div> {{-- Error for designs within pallet --}}
                        </div>

                        <div class="col-md-12">
                            <label for="remark_0" class="form-label">Remark</label>
                            <textarea class="form-control" name="pallets[0][remark]" id="remark_0" rows="3"></textarea>
                            @error('pallets.0.remark')
                            <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm mt-3 remove-pallet-item">Remove Pallet</button>
                </div>
            </div>
            <button type="button" class="btn btn-secondary mt-3" id="add_pallet_btn">Add Pallet</button>
            <div class="text-danger mt-2" id="total_qty_validation_error"></div>

            <button type="submit" class="btn btn-success mt-4" id="savePalletsBtn">Save Pallets</button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
{{-- Ensure you have Font Awesome or Bootstrap Icons included for the 'fa fa-plus' icon --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
        const savePalletsBtn = document.getElementById('savePalletsBtn');
        const form = document.getElementById('pallet_form');
        const totalQtyValidationError = document.getElementById('total_qty_validation_error');

        let currentProductionQty = 0; // To store the production_qty of the selected order item
        let allOrderItemsData = []; // To store all order items fetched for the selected PO

        // Counter for pallet items
        let palletItemIndex = 0;

        // Function to update hidden IDs and selected values in all pallet repeaters
        function updateHiddenFields() {
            const currentPoId = purchaseOrderIdSelect.value;
            const currentPoItemId = purchaseOrderItemIdSelect.value;
            const currentPoNumber = purchaseOrderIdSelect.options[purchaseOrderIdSelect.selectedIndex]?.dataset.poNumber || '';
            const currentDesignId = designSelect.value; // Get ID
            const currentDesignName = designSelect.options[designSelect.selectedIndex]?.textContent || ''; // Get Name
            const currentSizeId = sizeSelect.value;
            const currentSizeName = sizeSelect.options[sizeSelect.selectedIndex]?.textContent || '';
            const currentFinishId = finishSelect.value;
            const currentFinishName = finishSelect.options[finishSelect.selectedIndex]?.textContent || '';
            const currentBatchId = batchIdSelect.value;
            const currentBatchNo = batchIdSelect.options[batchIdSelect.selectedIndex]?.textContent || '';

            document.querySelectorAll('.repeatable-pallet').forEach(palletDiv => {
                palletDiv.querySelector('.po-id-hidden').value = currentPoId;
                palletDiv.querySelector('.poi-id-hidden').value = currentPoItemId;
                palletDiv.querySelector('.po-hidden').value = currentPoNumber;
                palletDiv.querySelector('.design-id-hidden').value = currentDesignId;
                palletDiv.querySelector('.size-id-hidden').value = currentSizeId;
                palletDiv.querySelector('.finish-id-hidden').value = currentFinishId;
                palletDiv.querySelector('.batch-id-hidden').value = currentBatchId;

                // Store names as well for display if needed later, or to pass to backend
                palletDiv.dataset.designName = currentDesignName;
                palletDiv.dataset.sizeName = currentSizeName;
                palletDiv.dataset.finishName = currentFinishName;
                palletDiv.dataset.batchNo = currentBatchNo;
            });

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
            const designQtyHiddenInput = palletItemDiv.querySelector('.design-quantities-hidden');

            const palletSize = parseFloat(palletSizeInput.value) || 0;
            const palletNo = parseFloat(palletNoInput.value) || 0;

            const calculatedPalletQty = palletSize * palletNo;
            totalQtyInput.value = calculatedPalletQty;

            // Sum up quantities from the designs table
            let sumOfDesignQuantities = 0;
            palletItemDiv.querySelectorAll('.pallet-designs-table tbody tr').forEach(row => {
                const qtyCell = row.querySelector('td:nth-child(2)'); // Second td is quantity
                sumOfDesignQuantities += parseFloat(qtyCell.textContent) || 0;
            });

            const designQtyErrorDiv = palletItemDiv.querySelector('.design-qty-error');
            let isPalletQtyValid = true;
            if(sumOfDesignQuantities > calculatedPalletQty){
                designQtyErrorDiv.textContent = `Total Design Quantity (${calculatedPalletQty}) must not exceed Pallet Total Qty (${calculatedPalletQty}).`;
                isPalletQtyValid = false;
                totalQtyInput.classList.add('is-invalid');
            } else if (calculatedPalletQty > 0 && sumOfDesignQuantities !== calculatedPalletQty) {
                designQtyErrorDiv.textContent = `Total quantity of designs (${sumOfDesignQuantities}) must match Pallet Total Qty (${calculatedPalletQty}).`;
                isPalletQtyValid = false;
                totalQtyInput.classList.add('is-invalid'); // Add invalid feedback class
            } else {
                designQtyErrorDiv.textContent = '';
                totalQtyInput.classList.remove('is-invalid');
            }

            // Update the hidden input that aggregates design quantities
            // This will be a JSON string of {design_id: qty, ...}
            const designQuantitiesMap = {};
            palletItemDiv.querySelectorAll('.pallet-designs-table tbody tr').forEach(row => {
                const designId = row.dataset.designId;
                const qty = parseFloat(row.querySelector('td:nth-child(2)').textContent) || 0;
                designQuantitiesMap[designId] = {
                    quantity: qty,
                    size_id: row.querySelector('.design-size-id').value,
                    finish_id: row.querySelector('.design-finish-id').value
                };
            });
            designQtyHiddenInput.value = JSON.stringify(designQuantitiesMap);

            validateTotalQtySum(); // Validate sum of all pallet total_qty against currentProductionQty
            return isPalletQtyValid; 
        }

        // Attach event listeners for calculation to existing and future pallet items
        function attachCalculationListeners(palletItemDiv) {
            const palletSizeInput = palletItemDiv.querySelector('.pallet-size-input');
            const palletNoInput = palletItemDiv.querySelector('.pallet-no-input');

            // Only listen for 'input' on pallet_size and pallet_no
            palletSizeInput.addEventListener('input', () => calculateTotalQty(palletItemDiv));
            palletNoInput.addEventListener('input', () => calculateTotalQty(palletItemDiv));

            // Event listener for "Add Design" button within this pallet item
            const addDesignBtn = palletItemDiv.querySelector('.add-design-to-pallet-btn');
            if (addDesignBtn) {
                addDesignBtn.addEventListener('click', function() {
                    addDesignToPallet(palletItemDiv);
                });
            }
        }

        // Function to validate the sum of all Pallet Total Qty against currentProductionQty
        // function validateTotalQtySum() {
        //     let sumOfAllPalletTotalQty = 0;
        //     document.querySelectorAll('.total-qty-input').forEach(input => {
        //         sumOfAllPalletTotalQty += parseFloat(input.value) || 0;
        //     });

        //     if (currentProductionQty > 0 && sumOfAllPalletTotalQty > currentProductionQty) {
        //         totalQtyValidationError.textContent = `Overall Pallet Total Quantity (${sumOfAllPalletTotalQty}) cannot exceed Order Item Production Quantity (${currentProductionQty}).`;
        //         return false;
        //     } else {
        //         totalQtyValidationError.textContent = '';
        //         return true;
        //     }
        // }

        // Function to validate the sum of all Pallet Total Qty against currentProductionQty
        function validateTotalQtySum() {
            let sumOfAllPalletTotalQty = 0;
            
            // 1. Calculate the total sum of all pallet quantities
            document.querySelectorAll('.total-qty-input').forEach(input => {
                sumOfAllPalletTotalQty += parseFloat(input.value) || 0;
                input.classList.remove('is-invalid'); // Clear any previous invalid status
            });

            if (currentProductionQty > 0 && sumOfAllPalletTotalQty > currentProductionQty) {
                // Validation Failed:
                totalQtyValidationError.textContent = `Overall Pallet Total Quantity (${sumOfAllPalletTotalQty}) cannot exceed Order Item Production Quantity (${currentProductionQty}).`;
                
                savePalletsBtn.disabled = true; 
                
                return false;
            } else {
                // Validation Passed:
                totalQtyValidationError.textContent = '';
            
                let designQtyMismatch = false;
                document.querySelectorAll('.design-qty-error').forEach(errorDiv => {
                    if (errorDiv.textContent !== '') {
                        designQtyMismatch = true;
                    }
                });
                
                if (!designQtyMismatch) {
                    savePalletsBtn.disabled = false;
                }

                return true;
            }
        }

        // Function to add a selected design with its quantity to the current pallet's table
        function addDesignToPallet(palletItemDiv) {
            const selectedDesignId = designSelect.value;
            const selectedDesignName = designSelect.options[designSelect.selectedIndex]?.textContent;
            const designQuantityInput = palletItemDiv.querySelector('.design-qty-input');
            const designQty = parseInt(designQuantityInput.value) || 0;
            const palletDesignsTableBody = palletItemDiv.querySelector('.pallet-designs-table tbody');
            const designQtyErrorDiv = palletItemDiv.querySelector('.design-qty-error');

            const totalQtyInput = palletItemDiv.querySelector('.total-qty-input');
            const calculatedPalletQty = parseFloat(totalQtyInput.value) || 0; // Pallet Total Qty મેળવો

            // Basic validation
            if (!selectedDesignId || !designQty || designQty <= 0) {
                designQtyErrorDiv.textContent = 'Please select a design and enter a valid quantity.';
                return;
            }

            let sumOfOtherDesignQuantities = 0;
            let existingQty = 0;
            // Check for existing design in the table
            let existingRow = palletDesignsTableBody.querySelector(`tr[data-design-id="${selectedDesignId}"]`);

            if (existingRow) {
                existingQty = parseInt(existingRow.querySelector('td:nth-child(2)').textContent) || 0;
                
                // Sum all other designs (excluding the current one)
                palletDesignsTableBody.querySelectorAll('tr').forEach(row => {
                    if (row.dataset.designId !== selectedDesignId) {
                        sumOfOtherDesignQuantities += parseFloat(row.querySelector('td:nth-child(2)').textContent) || 0;
                    }
                });
            } else {
                // Sum all existing designs
                palletDesignsTableBody.querySelectorAll('tr').forEach(row => {
                    sumOfOtherDesignQuantities += parseFloat(row.querySelector('td:nth-child(2)').textContent) || 0;
                });
            }

            const newTotalDesignQty = sumOfOtherDesignQuantities + existingQty + designQty;
            if (calculatedPalletQty > 0 && newTotalDesignQty > calculatedPalletQty) {
                designQtyErrorDiv.textContent = `Adding this quantity (${designQty}) will result in Total Design Qty (${newTotalDesignQty}), which exceeds Pallet Total Qty (${calculatedPalletQty}).`;
                return;
            }

            if (existingRow) {
                // Update quantity if design already exists
                existingRow.querySelector('td:nth-child(2)').textContent = existingQty + designQty;
                // let currentQty = parseInt(existingRow.querySelector('td:nth-child(2)').textContent);
                // existingRow.querySelector('td:nth-child(2)').textContent = currentQty + designQty;
            } else {
                // Add new row if design does not exist
                const newRow = document.createElement('tr');
                newRow.dataset.designId = selectedDesignId; // Store design ID for easy lookup
                newRow.innerHTML = `
                    <td>
                        ${selectedDesignName}
                        <input type="hidden" name="design_ids[]" value="${selectedDesignId}">
                        <input type="hidden" class="design-size-id" value="${sizeSelect.value}">
                        <input type="hidden" class="design-finish-id" value="${finishSelect.value}">
                    </td>
                    <td>${designQty}</td>
                    <td><button type="button" class="btn btn-sm btn-danger remove-design-row">Remove</button></td>
                `;
                palletDesignsTableBody.appendChild(newRow);

                // Attach event listener to the new remove button
                newRow.querySelector('.remove-design-row').addEventListener('click', function() {
                    newRow.remove();
                    calculateTotalQty(palletItemDiv); // Recalculate pallet total after removing design
                });
            }

            designQtyErrorDiv.textContent = ''; // Clear error message
            calculateTotalQty(palletItemDiv); // Recalculate pallet total after adding/updating design
            designQuantityInput.value = 1; // Reset design quantity input
        }


        // Function to fetch and populate order items based on filters
        function fetchAndPopulateOrderItems() {
            const purchaseOrderId = purchaseOrderIdSelect.value;
            const designId = designSelect.value;
            const sizeId = sizeSelect.value;
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
                option.dataset.designId = item.design_detail.id; // Store design_id for filtering
                option.dataset.sizeId = item.size_detail.id; // Store size_id for filtering
                option.dataset.finishId = item.finish_detail.id; // Store finish_id for filtering
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

                        // Filter unique designs, sizes, finishes from the fetched order items
                        const uniqueDesigns = [];
                        const seenDesigns = new Set();
                        data.forEach(item => {
                            if (!seenDesigns.has(item.design_detail.id)) {
                                uniqueDesigns.push({
                                    id: item.design_detail.id,
                                    name: item.design_detail.name
                                });
                                seenDesigns.add(item.design_detail.id);
                            }
                        });

                        const uniqueSizes = [];
                        const seenSizes = new Set();
                        data.forEach(item => {
                            if (!seenSizes.has(item.size_detail.id)) {
                                uniqueSizes.push({
                                    id: item.size_detail.id,
                                    size_name: item.size_detail.size_name
                                });
                                seenSizes.add(item.size_detail.id);
                            }
                        });

                        const uniqueFinishes = [];
                        const seenFinishes = new Set();
                        data.forEach(item => {
                            if (!seenFinishes.has(item.finish_detail.id)) {
                                uniqueFinishes.push({
                                    id: item.finish_detail.id,
                                    finish_name: item.finish_detail.finish_name
                                });
                                seenFinishes.add(item.finish_detail.id);
                            }
                        });

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


        // Add Pallet button event listener
        addPalletBtn.addEventListener('click', function() {
            const template = palletsContainer.querySelector('.repeatable-pallet');
            if (template) {
                const newElement = template.cloneNode(true);
                palletItemIndex++; // Increment index for new pallet
                newElement.id = `pallet_item_${palletItemIndex}`; // Assign unique ID to the new pallet div

                newElement.querySelectorAll('input, select, textarea').forEach(input => {
                    const name = input.name;
                    if (name) {
                        input.name = name.replace(/\[\d+\]/, `[${palletItemIndex}]`);
                    }
                    if (input.id) {
                        input.id = input.id.replace(/_\d+$/, `_${palletItemIndex}`);
                    }
                    input.value = ''; // Clear values for new row
                    if (input.type === 'number') {
                        input.value = input.classList.contains('design-qty-input') ? 1 : ''; // Default design qty to 1
                    }
                    if (input.tagName === 'SELECT') {
                        input.selectedIndex = 0; // Reset dropdowns
                    }
                });

                // Clear the designs table in the cloned pallet
                const newPalletDesignsTableBody = newElement.querySelector('.pallet-designs-table tbody');
                if (newPalletDesignsTableBody) {
                    newPalletDesignsTableBody.innerHTML = '';
                }
                // Clear any error messages for the new pallet
                const newDesignQtyErrorDiv = newElement.querySelector('.design-qty-error');
                if (newDesignQtyErrorDiv) newDesignQtyErrorDiv.textContent = '';
                // Clear total qty input for the new pallet
                const newTotalQtyInput = newElement.querySelector('.total-qty-input');
                if (newTotalQtyInput) newTotalQtyInput.value = '';


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
                            if (newPalletDesignsTableBody) newPalletDesignsTableBody.innerHTML = ''; // Clear table
                            if (newDesignQtyErrorDiv) newDesignQtyErrorDiv.textContent = ''; // Clear error
                            newElement.querySelector('.pallet-size-input').value = '';
                            newElement.querySelector('.pallet-no-input').value = '';
                            newElement.querySelector('.total-qty-input').value = '';
                            newElement.querySelector('.design-qty-input').value = 1;
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
                        const singlePalletDiv = this.closest('.repeatable-pallet');
                        singlePalletDiv.querySelectorAll('input, select, textarea').forEach(input => {
                            input.value = '';
                            if (input.tagName === 'SELECT') input.selectedIndex = 0;
                        });
                        singlePalletDiv.querySelector('.pallet-designs-table tbody').innerHTML = ''; // Clear table
                        singlePalletDiv.querySelector('.design-qty-error').textContent = ''; // Clear error
                        singlePalletDiv.querySelector('.pallet-size-input').value = '';
                        singlePalletDiv.querySelector('.pallet-no-input').value = '';
                        singlePalletDiv.querySelector('.total-qty-input').value = '';
                        singlePalletDiv.querySelector('.design-qty-input').value = 1;
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
            event.preventDefault(); // Prevent default form submission

            let isValid = true;

            // Overall production quantity validation
            if (!validateTotalQtySum()) {
                isValid = false;
            }

            // Individual pallet design quantity validation
            document.querySelectorAll('.repeatable-pallet').forEach(palletDiv => {
                if (palletDiv.querySelector('.design-qty-error').textContent !== '') {
                    isValid = false;
                }
                // Also ensure pallet_size and pallet_no are filled for each pallet
                const palletSize = palletDiv.querySelector('.pallet-size-input').value;
                const palletNo = palletDiv.querySelector('.pallet-no-input').value;
                if (!palletSize || !palletNo || parseFloat(palletSize) <= 0 || parseFloat(palletNo) <= 0) {
                    palletDiv.querySelector('.pallet-size-input').classList.add('is-invalid');
                    palletDiv.querySelector('.pallet-no-input').classList.add('is-invalid');
                    isValid = false;
                } else {
                    palletDiv.querySelector('.pallet-size-input').classList.remove('is-invalid');
                    palletDiv.querySelector('.pallet-no-input').classList.remove('is-invalid');
                }
            });

            // Basic check if PO, PO Item, Batch are selected
            if (!purchaseOrderIdSelect.value || !purchaseOrderItemIdSelect.value || !batchIdSelect.value) {
                isValid = false;
                alert('Please select Purchase Order, Order Item, and Batch before saving.'); // Or display more gracefully
            }


            if (isValid) {
                form.submit(); // Submit the form if all validations pass
            } else {
                alert('Please correct the errors in the form.'); // Generic alert for remaining errors
            }
        });
    });
</script>
@endpush