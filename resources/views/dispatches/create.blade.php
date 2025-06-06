@extends('layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            <h4>Add New Dispatch</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('dispatches.store') }}" method="POST" novalidate>
                @csrf

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label for="party_id" class="form-label">Party</label>
                        <select class="form-select @error('party_id') is-invalid @enderror" id="party_id" name="party_id" required>
                            <option value="">Select Party</option>
                            @foreach ($parties as $party)
                                <option value="{{ $party->id }}" {{ old('party_id') == $party->id ? 'selected' : '' }}>{{ $party->party_name }}</option>
                            @endforeach
                        </select>
                        @error('party_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="purchase_order_id" class="form-label">Purchase Order</label>
                        <select class="form-select @error('purchase_order_id') is-invalid @enderror" id="purchase_order_id" name="purchase_order_id" required>
                            <option value="">Select Purchase Order</option>
                            @foreach ($purchaseOrders as $po)
                                <option value="{{ $po->id }}" {{ old('purchase_order_id') == $po->id ? 'selected' : '' }}>{{ $po->po }}</option>
                            @endforeach
                        </select>
                        @error('purchase_order_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Design, Size, Finish dropdowns for filtering -->
                    <div class="col-md-4">
                        <label for="design_id" class="form-label">Design</label>
                        <select class="form-select @error('design_id') is-invalid @enderror" id="design_id" name="design_id" aria-label="Filter by Design">
                            <option value="">Select Design (Optional)</option>
                            @foreach ($designs as $design)
                                <option value="{{ $design->id }}" {{ old('design_id') == $design->id ? 'selected' : '' }}>{{ $design->name }}</option>
                            @endforeach
                        </select>
                        @error('design_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="size_id" class="form-label">Size</label>
                        <select class="form-select @error('size_id') is-invalid @enderror" id="size_id" name="size_id" aria-label="Filter by Size">
                            <option value="">Select Size (Optional)</option>
                            @foreach ($sizes as $size)
                                <option value="{{ $size->id }}" {{ old('size_id') == $size->id ? 'selected' : '' }}>{{ $size->size_name }}</option>
                            @endforeach
                        </select>
                        @error('size_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="finish_id" class="form-label">Finish</label>
                        <select class="form-select @error('finish_id') is-invalid @enderror" id="finish_id" name="finish_id" aria-label="Filter by Finish">
                            <option value="">Select Finish (Optional)</option>
                            @foreach ($finishes as $finish)
                                <option value="{{ $finish->id }}" {{ old('finish_id') == $finish->id ? 'selected' : '' }}>{{ $finish->finish_name }}</option>
                            @endforeach
                        </select>
                        @error('finish_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="purchase_order_item_id" class="form-label">Order Item (Design/Size/Finish)</label>
                        <select class="form-select @error('purchase_order_item_id') is-invalid @enderror" id="purchase_order_item_id" name="purchase_order_item_id" required aria-label="Select Order Item">
                            <option value="">Select Order Item</option>
                            {{-- Options will be loaded via AJAX --}}
                        </select>
                        @error('purchase_order_item_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="batch_id" class="form-label">Batch No</label>
                        <select class="form-select @error('batch_id') is-invalid @enderror" id="batch_id" name="batch_id" aria-label="Select Batch (Optional)">
                            <option value="">Select Batch (Optional)</option>
                            {{-- Options will be loaded via AJAX --}}
                        </select>
                        @error('batch_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                     <div class="col-md-4">
                        <label for="pallet_id" class="form-label">Pallet No (Available Qty)</label>
                        <select class="form-select @error('pallet_id') is-invalid @enderror" id="pallet_id" name="pallet_id" required aria-label="Select Pallet">
                            <option value="">Select Pallet</option>
                            {{-- Options will be loaded via AJAX --}}
                        </select>
                        @error('pallet_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="dispatched_qty" class="form-label">Dispatched Quantity</label>
                        <input type="number" class="form-control @error('dispatched_qty') is-invalid @enderror" id="dispatched_qty" name="dispatched_qty" value="{{ old('dispatched_qty') }}" required min="1" aria-describedby="dispatchedQtyHelp" placeholder="Enter quantity">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const partyIdSelect = document.getElementById('party_id');
    const purchaseOrderIdSelect = document.getElementById('purchase_order_id');
    const designIdSelect = document.getElementById('design_id');
    const sizeIdSelect = document.getElementById('size_id');
    const finishIdSelect = document.getElementById('finish_id');
    const purchaseOrderItemIdSelect = document.getElementById('purchase_order_item_id');
    const batchIdSelect = document.getElementById('batch_id');
    const palletIdSelect = document.getElementById('pallet_id');
    const dispatchedQtyInput = document.getElementById('dispatched_qty');

    let allOrderItemsForCurrentPo = []; // Stores all order items for the currently selected PO for filtering

    function clearAndPopulateSelect(selectElement, data, valueKey, textKey, additionalAttr = null, additionalValueKey = null) {
        selectElement.innerHTML = '<option value="">Select ' + selectElement.previousElementSibling.textContent.replace(/\(.*\)|:/g, '').trim() + '</option>';
        data.forEach(item => {
            const option = document.createElement('option');
            option.value = item[valueKey];
            option.textContent = item[textKey];
            if (additionalAttr && additionalValueKey && item[additionalValueKey] !== undefined) {
                option.setAttribute(additionalAttr, item[additionalValueKey]);
            }
            selectElement.appendChild(option);
        });
    }

    function resetDependentDropdowns(exclude = []) {
        if (!exclude.includes('design_id')) clearAndPopulateSelect(designIdSelect, [], 'id', 'name');
        if (!exclude.includes('size_id')) clearAndPopulateSelect(sizeIdSelect, [], 'id', 'size_name');
        if (!exclude.includes('finish_id')) clearAndPopulateSelect(finishIdSelect, [], 'id', 'finish_name');
        if (!exclude.includes('purchase_order_item_id')) clearAndPopulateSelect(purchaseOrderItemIdSelect, [], 'id', 'id');
        if (!exclude.includes('batch_id')) clearAndPopulateSelect(batchIdSelect, [], 'id', 'batch_no');
        if (!exclude.includes('pallet_id')) clearAndPopulateSelect(palletIdSelect, [], 'id', 'pallet_no');
        dispatchedQtyInput.value = '';
        dispatchedQtyInput.classList.remove('is-invalid');
        dispatchedQtyInput.removeAttribute('max');
        dispatchedQtyInput.removeAttribute('disabled');
        dispatchedQtyInput.placeholder = 'Enter quantity';
        const errorDiv = dispatchedQtyInput.nextElementSibling;
        if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
            errorDiv.textContent = '';
        }
    }

    function fetchAndFilterOrderItems(initialLoad = false) {
        const purchaseOrderId = purchaseOrderIdSelect.value;

        clearAndPopulateSelect(purchaseOrderItemIdSelect, [], 'id', 'id');
        clearAndPopulateSelect(batchIdSelect, [], 'id', 'batch_no');
        clearAndPopulateSelect(palletIdSelect, [], 'id', 'pallet_no');
        dispatchedQtyInput.value = '';
        dispatchedQtyInput.classList.remove('is-invalid');
        dispatchedQtyInput.removeAttribute('max');
        dispatchedQtyInput.removeAttribute('disabled');
        dispatchedQtyInput.placeholder = 'Enter quantity';

        if (!purchaseOrderId) {
            allOrderItemsForCurrentPo = [];
            return;
        }

        if (initialLoad || allOrderItemsForCurrentPo.length === 0 || purchaseOrderIdSelect.dataset.lastPoId !== purchaseOrderId) {
            purchaseOrderIdSelect.dataset.lastPoId = purchaseOrderId;

            fetch(`/get-order-items-for-dispatch?purchase_order_id=${purchaseOrderId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    allOrderItemsForCurrentPo = data.orderItems;
                    populateFilteringDropdowns(data.designs, data.sizes, data.finishes);
                    filterAndPopulateOrderItemDropdown(initialLoad);
                })
                .catch(error => {
                    console.error('Error fetching order items:', error);
                    alert('Failed to load order items. Please try again later.');
                });
        } else {
            filterAndPopulateOrderItemDropdown(initialLoad);
        }
    }

    function populateFilteringDropdowns(designs, sizes, finishes) {
        clearAndPopulateSelect(designIdSelect, designs, 'id', 'name');
        clearAndPopulateSelect(sizeIdSelect, sizes, 'id', 'size_name');
        clearAndPopulateSelect(finishIdSelect, finishes, 'id', 'finish_name');

        // Restore old selected values if they exist
        if (oldDesignId) designIdSelect.value = oldDesignId;
        if (oldSizeId) sizeIdSelect.value = oldSizeId;
        if (oldFinishId) finishIdSelect.value = oldFinishId;
    }

    function filterAndPopulateOrderItemDropdown(initialLoad = false) {
        const selectedDesignId = designIdSelect.value;
        const selectedSizeId = sizeIdSelect.value;
        const selectedFinishId = finishIdSelect.value;

        let filteredItems = allOrderItemsForCurrentPo;

        if (selectedDesignId) {
            filteredItems = filteredItems.filter(item => item.design_id == selectedDesignId);
        }
        if (selectedSizeId) {
            filteredItems = filteredItems.filter(item => item.size_id == selectedSizeId);
        }
        if (selectedFinishId) {
            filteredItems = filteredItems.filter(item => item.finish_id == selectedFinishId);
        }

        const formattedItems = filteredItems.map(item => ({
            id: item.id,
            display_text: `Design: ${item.designDetail?.name || 'N/A'} / Size: ${item.sizeDetail?.size_name || 'N/A'} / Finish: ${item.finishDetail?.finish_name || 'N/A'}`
        }));
        clearAndPopulateSelect(purchaseOrderItemIdSelect, formattedItems, 'id', 'display_text');

        // Restore old selected value for PO Item if it exists
        if (initialLoad && oldPurchaseOrderItemId) {
            purchaseOrderItemIdSelect.value = oldPurchaseOrderItemId;
            purchaseOrderItemIdSelect.dispatchEvent(new Event('change'));
        } else if (!initialLoad && purchaseOrderItemIdSelect.value) {
            purchaseOrderItemIdSelect.dispatchEvent(new Event('change'));
        }
    }

    // Event listeners for primary filters
    purchaseOrderIdSelect.addEventListener('change', function() {
        resetDependentDropdowns(['design_id', 'size_id', 'finish_id']); // Keep design, size, finish populated by fetch.
        allOrderItemsForCurrentPo = [];
        fetchAndFilterOrderItems();
    });

    designIdSelect.addEventListener('change', () => filterAndPopulateOrderItemDropdown());
    sizeIdSelect.addEventListener('change', () => filterAndPopulateOrderItemDropdown());
    finishIdSelect.addEventListener('change', () => filterAndPopulateOrderItemDropdown());

    purchaseOrderItemIdSelect.addEventListener('change', function() {
        const purchaseOrderItemId = this.value;
        clearAndPopulateSelect(batchIdSelect, [], 'id', 'batch_no');
        clearAndPopulateSelect(palletIdSelect, [], 'id', 'pallet_no');
        dispatchedQtyInput.value = '';
        dispatchedQtyInput.classList.remove('is-invalid');
        dispatchedQtyInput.removeAttribute('max');
        dispatchedQtyInput.removeAttribute('disabled');
        dispatchedQtyInput.placeholder = 'Enter quantity';

        if (purchaseOrderItemId) {
            fetch(`/get-batches-for-dispatch?purchase_order_item_id=${purchaseOrderItemId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    clearAndPopulateSelect(batchIdSelect, data, 'id', 'batch_no');
                    if (oldBatchId) {
                        batchIdSelect.value = oldBatchId;
                        batchIdSelect.dispatchEvent(new Event('change'));
                    }
                })
                .catch(error => {
                    console.error('Error fetching batches:', error);
                    alert('Failed to load batches. Please try again later.');
                });
        }
    });

    batchIdSelect.addEventListener('change', function() {
        const purchaseOrderItemId = purchaseOrderItemIdSelect.value;
        const batchId = this.value;
        clearAndPopulateSelect(palletIdSelect, [], 'id', 'pallet_no');
        dispatchedQtyInput.value = '';
        dispatchedQtyInput.classList.remove('is-invalid');
        dispatchedQtyInput.removeAttribute('max');
        dispatchedQtyInput.removeAttribute('disabled');
        dispatchedQtyInput.placeholder = 'Enter quantity';

        if (purchaseOrderItemId && batchId) {
            fetch(`/get-pallets-for-dispatch?purchase_order_item_id=${purchaseOrderItemId}&batch_id=${batchId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const formattedData = data.map(pallet => ({
                        id: pallet.id,
                        display_text: pallet.pallet_no + ' (Qty: ' + pallet.current_qty + ')',
                        current_qty: pallet.current_qty
                    }));
                    clearAndPopulateSelect(palletIdSelect, formattedData, 'id', 'display_text', 'data-current-qty', 'current_qty');
                    if (oldPalletId) {
                        palletIdSelect.value = oldPalletId;
                        palletIdSelect.dispatchEvent(new Event('change'));
                    }
                })
                .catch(error => {
                    console.error('Error fetching pallets:', error);
                    alert('Failed to load pallets. Please try again later.');
                });
        }
    });

    palletIdSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const availableQty = selectedOption ? parseInt(selectedOption.getAttribute('data-current-qty')) : 0;

        dispatchedQtyInput.dataset.availableQty = availableQty;

        dispatchedQtyInput.value = '';
        dispatchedQtyInput.classList.remove('is-invalid');
        const errorDiv = dispatchedQtyInput.nextElementSibling;
        if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
            errorDiv.textContent = '';
        }

        if (availableQty === 0 && selectedOption.value !== '') {
            dispatchedQtyInput.placeholder = 'No stock available';
            dispatchedQtyInput.setAttribute('disabled', 'true');
        } else {
            dispatchedQtyInput.placeholder = 'Enter quantity (Max: ' + availableQty + ')';
            dispatchedQtyInput.removeAttribute('disabled');
        }
        dispatchedQtyInput.setAttribute('max', availableQty);

        if (selectedOption && selectedOption.value === oldPalletId) {
            dispatchedQtyInput.value = oldDispatchedQty;
        }
    });

    dispatchedQtyInput.addEventListener('input', function() {
        const dispatchedQty = parseInt(this.value) || 0;
        const availableQty = parseInt(this.dataset.availableQty) || 0;
        const errorDiv = this.nextElementSibling;

        if (dispatchedQty > availableQty) {
            this.classList.add('is-invalid');
            if (errorDiv) {
                errorDiv.textContent = `Dispatched quantity cannot exceed available pallet quantity (${availableQty}).`;
            }
        } else {
            this.classList.remove('is-invalid');
            if (errorDiv) {
                errorDiv.textContent = '';
            }
        }
    });

    // Handle old input values on page load
    const oldPartyId = "{{ old('party_id') }}";
    const oldPurchaseOrderId = "{{ old('purchase_order_id') }}";
    const oldDesignId = "{{ old('design_id') }}";
    const oldSizeId = "{{ old('size_id') }}";
    const oldFinishId = "{{ old('finish_id') }}";
    const oldPurchaseOrderItemId = "{{ old('purchase_order_item_id') }}";
    const oldBatchId = "{{ old('batch_id') }}";
    const oldPalletId = "{{ old('pallet_id') }}";
    const oldDispatchedQty = "{{ old('dispatched_qty') }}";

    if (oldPurchaseOrderId) {
        purchaseOrderIdSelect.value = oldPurchaseOrderId;
        if (oldDesignId) designIdSelect.value = oldDesignId;
        if (oldSizeId) sizeIdSelect.value = oldSizeId;
        if (oldFinishId) finishIdSelect.value = oldFinishId;

        fetchAndFilterOrderItems(true); // initial load
    }
});
</script>

@endsection
