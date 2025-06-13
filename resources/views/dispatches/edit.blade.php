@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Edit Dispatch (ID: {{ $dispatch->id }})</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('dispatches.update', $dispatch->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label for="party_id" class="form-label">Party</label>
                    <select class="form-select @error('party_id') is-invalid @enderror" id="party_id" name="party_id" required>
                        <option value="">Select Party</option>
                        @foreach ($parties as $party)
                            <option value="{{ $party->id }}" {{ old('party_id', $dispatch->party_id) == $party->id ? 'selected' : '' }}>{{ $party->party_name }}</option>
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
                            <option value="{{ $po->id }}" {{ old('purchase_order_id', $dispatch->purchase_order_id) == $po->id ? 'selected' : '' }}>{{ $po->po }}</option>
                        @endforeach
                    </select>
                    @error('purchase_order_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="purchase_order_item_id" class="form-label">Order Item (Design/Size/Finish)</label>
                    <select class="form-select @error('purchase_order_item_id') is-invalid @enderror" id="purchase_order_item_id" name="purchase_order_item_id" required>
                        <option value="">Select Order Item</option>
                        @foreach($purchaseOrderItems as $item)
                            <option value="{{ $item->id }}" {{ old('purchase_order_item_id', $dispatch->purchase_order_item_id) == $item->id ? 'selected' : '' }}>
                                {{ $item->designDetail->name ?? 'N/A' }} / {{ $item->sizeDetail->size_name ?? 'N/A' }} / {{ $item->finishDetail->finish_name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                    @error('purchase_order_item_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="batch_id" class="form-label">Batch No</label>
                    <select class="form-select @error('batch_id') is-invalid @enderror" id="batch_id" name="batch_id">
                        <option value="">Select Batch (Optional)</option>
                        @foreach($batches as $batch)
                            <option value="{{ $batch->id }}" {{ old('batch_id', $dispatch->batch_id) == $batch->id ? 'selected' : '' }}>{{ $batch->batch_no }}</option>
                        @endforeach
                    </select>
                    @error('batch_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                 <div class="col-md-4">
                    <label for="pallet_id" class="form-label">Pallet No (Available Qty)</label>
                    <select class="form-select @error('pallet_id') is-invalid @enderror" id="pallet_id" name="pallet_id" required>
                        <option value="">Select Pallet</option>
                        @foreach($stockPallets as $pallet)
                            <option value="{{ $pallet->id }}" {{ old('pallet_id', $dispatch->pallet_id) == $pallet->id ? 'selected' : '' }} data-current-qty="{{ $pallet->current_qty }}">
                                {{ $pallet->pallet_no }} (Qty: {{ $pallet->current_qty }})
                            </option>
                        @endforeach
                    </select>
                    @error('pallet_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="dispatched_qty" class="form-label">Dispatched Quantity</label>
                    <input type="number" class="form-control @error('dispatched_qty') is-invalid @enderror" id="dispatched_qty" name="dispatched_qty" value="{{ old('dispatched_qty', $dispatch->dispatched_qty) }}" required min="1">
                    @error('dispatched_qty')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="dispatch_date" class="form-label">Dispatch Date</label>
                    <input type="date" class="form-control @error('dispatch_date') is-invalid @enderror" id="dispatch_date" name="dispatch_date" value="{{ old('dispatch_date', $dispatch->dispatch_date) }}" required>
                    @error('dispatch_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="vehicle_no" class="form-label">Vehicle No</label>
                    <input type="text" class="form-control @error('vehicle_no') is-invalid @enderror" id="vehicle_no" name="vehicle_no" value="{{ old('vehicle_no', $dispatch->vehicle_no) }}">
                    @error('vehicle_no')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="container_no" class="form-label">Container No</label>
                    <input type="text" class="form-control @error('container_no') is-invalid @enderror" id="container_no" name="container_no" value="{{ old('container_no', $dispatch->container_no) }}">
                    @error('container_no')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-12">
                    <label for="remark" class="form-label">Remark</label>
                    <textarea class="form-control @error('remark') is-invalid @enderror" id="remark" name="remark" rows="3">{{ old('remark', $dispatch->remark) }}</textarea>
                    @error('remark')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-success mt-4">Update Dispatch</button>
            <a href="{{ route('dispatches.index') }}" class="btn btn-secondary mt-4">Back to List</a>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const purchaseOrderIdSelect = document.getElementById('purchase_order_id');
    const purchaseOrderItemIdSelect = document.getElementById('purchase_order_item_id');
    const batchIdSelect = document.getElementById('batch_id');
    const palletIdSelect = document.getElementById('pallet_id');
    const dispatchedQtyInput = document.getElementById('dispatched_qty');

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

    // Function to load dependent dropdowns
    function loadDependentDropdowns(poId, selectedOrderItemId = null, selectedBatchId = null, selectedPalletId = null) {
        // Load Order Items
        fetch(`/get-order-items-for-dispatch?purchase_order_id=${poId}`)
            .then(response => response.json())
            .then(data => {
                const formattedData = data.map(item => ({
                    id: item.id,
                    display_text: (item.design_detail?.name || 'N/A') + ' / ' +
                                  (item.size_detail?.size_name || 'N/A') + ' / ' +
                                  (item.finish_detail?.finish_name || 'N/A')
                }));
                clearAndPopulateSelect(purchaseOrderItemIdSelect, formattedData, 'id', 'display_text');
                if (selectedOrderItemId) {
                    purchaseOrderItemIdSelect.value = selectedOrderItemId;
                }
                // Trigger change to load batches
                purchaseOrderItemIdSelect.dispatchEvent(new Event('change'));
            })
            .catch(error => console.error('Error fetching order items:', error));
    }

    purchaseOrderIdSelect.addEventListener('change', function() {
        loadDependentDropdowns(this.value);
    });

    purchaseOrderItemIdSelect.addEventListener('change', function() {
        const purchaseOrderItemId = this.value;
        clearAndPopulateSelect(batchIdSelect, [], 'id', 'batch_no');
        clearAndPopulateSelect(palletIdSelect, [], 'id', 'pallet_no');

        if (purchaseOrderItemId) {
            fetch(`/get-batches-for-dispatch?purchase_order_item_id=${purchaseOrderItemId}`)
                .then(response => response.json())
                .then(data => {
                    clearAndPopulateSelect(batchIdSelect, data, 'id', 'batch_no');
                    if (dispatchedQtyInput.dataset.initialBatchId) { // For initial load
                        batchIdSelect.value = dispatchedQtyInput.dataset.initialBatchId;
                        delete dispatchedQtyInput.dataset.initialBatchId; // Clean up
                    }
                    // Trigger change to load pallets
                    batchIdSelect.dispatchEvent(new Event('change'));
                })
                .catch(error => console.error('Error fetching batches:', error));
        }
    });

    batchIdSelect.addEventListener('change', function() {
        const purchaseOrderItemId = purchaseOrderItemIdSelect.value;
        const batchId = this.value;
        clearAndPopulateSelect(palletIdSelect, [], 'id', 'pallet_no');

        if (purchaseOrderItemId && batchId) {
            fetch(`/get-pallets-for-dispatch?purchase_order_item_id=${purchaseOrderItemId}&batch_id=${batchId}`)
                .then(response => response.json())
                .then(data => {
                    const formattedData = data.map(pallet => ({
                        id: pallet.id,
                        display_text: pallet.pallet_no + ' (Qty: ' + pallet.current_qty + ')',
                        current_qty: pallet.current_qty
                    }));
                    clearAndPopulateSelect(palletIdSelect, formattedData, 'id', 'display_text', 'data-current-qty', 'current_qty');
                    if (dispatchedQtyInput.dataset.initialPalletId) { // For initial load
                        palletIdSelect.value = dispatchedQtyInput.dataset.initialPalletId;
                        delete dispatchedQtyInput.dataset.initialPalletId; // Clean up
                    }
                    // Manually trigger change on pallet select to update dispatchedQty max and placeholder
                    palletIdSelect.dispatchEvent(new Event('change'));
                })
                .catch(error => console.error('Error fetching pallets:', error));
        }
    });

    palletIdSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        // For edit, current_qty should include the original dispatched quantity to allow saving same quantity
        const originalDispatchedQty = parseFloat("{{ $dispatch->dispatched_qty ?? 0 }}");
        const availableQtyOnPallet = selectedOption ? parseFloat(selectedOption.getAttribute('data-current-qty')) : 0;
        let effectiveMaxQty = availableQtyOnPallet;

        // If the same pallet is selected, add the original dispatched quantity back for validation
        if (selectedOption && selectedOption.value == "{{ $dispatch->pallet_id }}") {
            effectiveMaxQty = availableQtyOnPallet + originalDispatchedQty;
        }

        dispatchedQtyInput.dataset.availableQty = effectiveMaxQty; // Store for `input` event listener
        dispatchedQtyInput.setAttribute('max', effectiveMaxQty);

        if (effectiveMaxQty === 0 && selectedOption.value !== '') {
            dispatchedQtyInput.placeholder = 'No stock available';
            dispatchedQtyInput.setAttribute('disabled', 'true');
        } else {
            dispatchedQtyInput.placeholder = `Enter quantity (Max: ${effectiveMaxQty})`;
            dispatchedQtyInput.removeAttribute('disabled');
        }

        // Re-validate current value on pallet change
        const currentDispatchedQty = parseFloat(dispatchedQtyInput.value) || 0;
        if (currentDispatchedQty > effectiveMaxQty) {
            dispatchedQtyInput.classList.add('is-invalid');
            dispatchedQtyInput.nextElementSibling.textContent = `Dispatched quantity cannot exceed available pallet quantity (${effectiveMaxQty}).`;
        } else {
            dispatchedQtyInput.classList.remove('is-invalid');
            dispatchedQtyInput.nextElementSibling.textContent = '';
        }
    });

    dispatchedQtyInput.addEventListener('input', function() {
        const currentQty = parseFloat(this.value) || 0;
        const maxQty = parseFloat(this.dataset.availableQty) || 0;
        const errorDiv = this.nextElementSibling;

        if (currentQty > maxQty) {
            this.classList.add('is-invalid');
            if (errorDiv) {
                errorDiv.textContent = `Dispatched quantity cannot exceed available pallet quantity (${maxQty}).`;
            }
        } else {
            this.classList.remove('is-invalid');
            if (errorDiv) {
                errorDiv.textContent = '';
            }
        }
    });

    // Initial load for edit form
    const initialPoId = "{{ $dispatch->purchase_order_id }}";
    const initialOrderItemId = "{{ $dispatch->purchase_order_item_id }}";
    const initialBatchId = "{{ $dispatch->batch_id }}"; // This might be null
    const initialPalletId = "{{ $dispatch->pallet_id }}";
    const initialDispatchedQty = parseFloat("{{ $dispatch->dispatched_qty ?? 0 }}");


    if (initialPoId) {
        // Set initial values on dataset for cascading updates
        purchaseOrderIdSelect.value = initialPoId;
        dispatchedQtyInput.dataset.initialOrderItemId = initialOrderItemId;
        dispatchedQtyInput.dataset.initialBatchId = initialBatchId;
        dispatchedQtyInput.dataset.initialPalletId = initialPalletId;
        dispatchedQtyInput.dataset.initialDispatchedQty = initialDispatchedQty; // Store original dispatched qty

        // Trigger change event to load dependent dropdowns
        purchaseOrderIdSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush