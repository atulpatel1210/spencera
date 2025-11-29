@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="mb-0">Edit Order: {{ $order->po }}</h4>
    </div>

    <div class="card-body">
        <form id="orderForm" action="{{ route('orders.update', $order->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') 

            {{-- Order Info --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="row mb-4">
                <div class="col-md-3">
                    <label for="po" class="form-label fw-semibold">PO Number</label>
                    <input type="text" class="form-control" id="po" name="po" required value="{{ old('po', $order->po) }}">
                </div>
                <div class="col-md-3">
                    <label for="party_id" class="form-label fw-semibold">Party</label>
                    <select class="form-select select2" id="party_id" name="party_id" required>
                        <option value="">Select Party</option>
                        @foreach ($parties as $p)
                        <option value="{{ $p->id }}"
                            {{ old('party_id', $order->party_id) == $p->id ? 'selected' : '' }}>
                            {{ $p->party_name }}
                        </option>
                        @endforeach
                    </select>

                </div>
                <div class="col-md-3">
                    <label for="brand_name" class="form-label fw-semibold">Brand Name</label>
                    <input type="text" class="form-control" id="brand_name" name="brand_name" placeholder="Enter brand name" value="{{ old('brand_name', $order->brand_name) }}">
                </div>
                <div class="col-md-3">
                    <label for="order_date" class="form-label fw-semibold">Order Date</label>
                    <input type="date" class="form-control" id="order_date" name="order_date" value="{{ old('order_date', \Carbon\Carbon::parse($order->order_date)->format('Y-m-d')) }}" required>
                </div>
            </div>

            {{-- Box Image --}}
            <div class="row mb-4">
                <div class="col-md-4">
                    <label for="box_image" class="form-label fw-semibold">Box Image (Leave blank to keep old image)</label>
                    <input type="file" name="box_image" id="box_image" class="form-control" accept="image/*" onchange="previewBoxImage(event)">
                    <div class="mt-2">
                        @php
                            $imageUrl = $order->box_image ? asset('storage/' . $order->box_image) : '';
                        @endphp
                        <img id="boxImagePreview" 
                            src="{{ isset($order->box_image) ? asset('storage/box_images/'.$order->box_image) : '' }}" 
                            class="img-thumbnail mt-3 shadow-sm" 
                            style="max-width: 150px; max-height: 150px; {{ isset($order->box_image) ? '' : 'display:none;' }}">
                            
                        @if ($order->box_image)
                            <small class="d-block mt-1 text-muted">Current Image</small>
                        @endif
                    </div>
                </div>
            </div>
            <hr>
            <h5 class="fw-semibold mb-3 text-primary">Add/Edit Order Item</h5>
            <div class="row g-3 align-items-end" id="itemForm">
                <div class="col-md-2">
                    <label class="form-label">Design</label>
                    <select class="form-select" id="design_id">
                        <option value="">Select</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Size</label>
                    <select class="form-select" id="size_id">
                        <option value="">Select</option>
                        @foreach ($sizes as $s)
                        <option value="{{ $s->id }}">{{ $s->size_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Finish</label>
                    <select class="form-select" id="finish_id">
                        <option value="">Select</option>
                        @foreach ($finishes as $f)
                        <option value="{{ $f->id }}">{{ $f->finish_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Order Qty</label>
                    <input type="number" class="form-control" id="order_qty" min="1" value="" placeholder="Order qty" readonly>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Remark</label>
                    <input type="text" class="form-control" id="remark" placeholder="Remark">
                </div>
                {{-- PALLET SECTION --}}
                <div id="palletSection" style="border:1px dashed #ccc; padding:12px; border-radius:8px; margin-top:15px;">
                    <div id="palletWrapper">
                        <div class="row g-3 pallet-row">
                            <div class="col-md-3">
                                <label class="form-label">Box / Pallet</label>
                                <input type="number" class="form-control box_pallet" min="1" oninput="calculatePalletRowTotal(this);" placeholder="Box Per Pallet">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Total Pallet</label>
                                <input type="number" class="form-control total_pallet" min="1" oninput="calculatePalletRowTotal(this);" placeholder="Total Pallet">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Total Boxes</label>
                                <input type="number" class="form-control total_boxe_pallets" placeholder="Total Boxes (Pallet)" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end mt-2">
                        <button type="button" id="addMorePalletBtn" class="btn btn-dark btn-sm">
                            + Add More
                        </button>
                    </div>
                </div>
                <div class="col-md-4 text-end d-flex">
                    <button type="button" class="btn btn-primary w-50" id="addItemBtn" data-mode="add" data-index="-1">Add</button>
                    <button type="button" class="btn btn-secondary w-50 me-2 ms-1" id="cancelItemBtn" style="display:none;" onclick="cancelEdit()">Cancel</button>
                </div>
            </div>

            <hr>

            {{-- Items Table --}}
            <h5 class="fw-semibold text-primary">Order Items</h5>
            <table class="table table-bordered table-striped mt-3 align-middle" id="itemsTable">
                <thead class="table-light">
                    <tr>
                        <th>Design</th>
                        <th>Size</th>
                        <th>Finish</th>
                        <th>Order Qty</th>
                        <th>Remark</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

            <input type="hidden" name="order_items" id="order_items">

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-success px-4">Update Order</button>
                <a href="{{ route('orders.index') }}" class="btn btn-secondary ms-2">Back</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let items = [];
    let isEditing = false;
    let editingItemId = null;

    document.addEventListener("DOMContentLoaded", function() {
        let oldItems = @json(old('order_items'));
        let initialItems = {!! isset($orderItemsJson) ? $orderItemsJson : '[]' !!};
        
        if (oldItems) {
            try {
                let parsed = JSON.parse(oldItems);
                items = parsed; 
            } catch (err) {
                items = initialItems;
            }
        } else {
            items = initialItems;
        }
        
        if (items.length > 0) {
            renderTable();
            disableMainFields();
        }
        let initialPartyId = $('#party_id').val();
        if (initialPartyId) {
            fetchDesigns(initialPartyId); 
        }
        $('#party_id').trigger('change.select2');
        
        const boxImagePreview = document.getElementById('boxImagePreview');
        if (boxImagePreview.src && boxImagePreview.src.indexOf('http') === 0) {
            boxImagePreview.style.display = 'block';
        }
    });

    function fetchDesigns(partyId) {
        $('#design_id').html('<option value="">Loading...</option>');
        $.ajax({
            url: "{{ route('party.designs') }}",
            type: "GET",
            data: {
                party_id: partyId
            },
            success: function(res) {
                $('#design_id').empty().append('<option value="">Select</option>');
                $.each(res, function(index, design) {
                    $('#design_id').append(
                        `<option value="${design.id}">${design.name}</option>`
                    );
                });
            },
            error: function() {
                 $('#design_id').html('<option value="">Error loading designs</option>');
            }
        });
    }

    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select Party",
            allowClear: true,
            width: '100%'
        });
        $('#party_id').on('change', function() {
            let partyId = $(this).val();
            $('#design_id').html('<option value="">Loading...</option>');
            if (partyId) {
                fetchDesigns(partyId);
            } else {
                $('#design_id').html('<option value="">Select</option>');
            }
        });
    });
    
    document.addEventListener("click", function(e) {
        if (e.target && e.target.id === "addMorePalletBtn") {
            addPalletRow({});
        }
    });
    
    document.addEventListener("click", function(e) {
        if (e.target && e.target.classList.contains("removePallet")) {
            e.target.closest(".pallet-row").remove();
            updateOrderQty();
        }
    });
    
    function addPalletRow(pallet = {}) {
        const palletWrapper = document.getElementById('palletWrapper');
        const isFirstRow = palletWrapper.children.length === 0;
        const palletId = pallet.id || '';
        const boxPalletValue = pallet.box_pallet || '';
        const totalPalletValue = pallet.total_pallet || '';
        const totalBoxesValue = pallet.total_boxe_pallets || ''; 

        const newRow = document.createElement('div');
        newRow.className = 'row g-3 pallet-row mt-2';
        const removeButtonHtml = isFirstRow ? '' : `<div class="col-md-2 d-flex align-items-center"><button type="button" class="btn btn-danger btn-sm removePallet">X</button></div>`;
        newRow.innerHTML = `
            <div class="col-md-3">
                <input type="number" class="form-control box_pallet" oninput="calculatePalletRowTotal(this);" placeholder="Box Per Pallet" value="${boxPalletValue}" min="1">
            </div>
            <div class="col-md-3">
                <input type="number" class="form-control total_pallet" oninput="calculatePalletRowTotal(this);" placeholder="Total Pallet" value="${totalPalletValue}" min="1">
            </div>
            <div class="col-md-3">
                <input type="number" class="form-control total_boxe_pallets" placeholder="Total Boxes (Pallet)" value="${totalBoxesValue}" readonly>
            </div>
            <input type="hidden" class="pallet_id" value="${palletId}">
            ${removeButtonHtml}
        `;
        palletWrapper.appendChild(newRow);
    }

    function renderTable() {
        let tbody = document.querySelector('#itemsTable tbody');
        tbody.innerHTML = '';
        items.forEach((item, index) => {
            let palletHtml = "";
            if (item.pallets && item.pallets.length > 0) {
                palletHtml += `
                    <table class="table table-bordered table-sm mt-2 table-secondary" style="background:#f8f9fa;">
                        <thead>
                            <tr class="table-secondary">
                                <th>Pallet</th>
                                <th>Box/Pallet</th>
                                <th>Total Pallet</th>
                                <th>Total Boxes</th>
                            </tr>
                        </thead>
                        <tbody>`;
                            let totalBoxesSum = 0;
                            item.pallets.forEach((p, i) => {
                                let totalBoxes = p.total_boxe_pallets || (p.box_pallet * p.total_pallet); 
                                totalBoxesSum += totalBoxes;
                                palletHtml += `
                                <tr>
                                    <td>${i + 1}</td>
                                    <td>${p.box_pallet}</td>
                                    <td>${p.total_pallet}</td>
                                    <td>${totalBoxes}</td>
                                </tr>`;
                            });
                    palletHtml += `</tbody>
                    </table>`;
            }

            const disabledAttr = isEditing ? 'disabled' : '';

            let row = `<tr>
                <td>${item.design_text}</td>
                <td>${item.size_text}</td>
                <td>${item.finish_text}</td>
                <td>${item.order_qty} ${palletHtml}</td>
                <td>${item.remark}</td>
                <td>
                    <button type="button" class="btn btn-sm text-primary" onclick="editItem(${index})" ${disabledAttr}><i class="fa fa-edit fa-fw fa-lg"></i></button>
                    <button type="button" class="btn btn-sm text-danger" onclick="removeItem(${index})" ${disabledAttr}><i class="fa fa-trash fa-fw fa-lg"></i></button>
                </td>
            </tr>`;
            tbody.innerHTML += row;
        });
        document.getElementById('order_items').value = JSON.stringify(items);
        if (items.length > 0) {
            disableMainFields();
        } else {
            enableMainFields();
        }
        
        const addItemBtn = document.getElementById('addItemBtn');
    }

    function resetItemForm() {
        $('#design_id').val('');
        $('#size_id').val('');
        $('#finish_id').val('');
        $('#order_qty').val('');
        $('#remark').val('');
        
        editingItemId = null; 

        document.getElementById("palletWrapper").innerHTML = '';
        addPalletRow({});

        document.getElementById('addItemBtn').textContent = 'Add/Update';
        document.getElementById('addItemBtn').dataset.mode = 'add';
        document.getElementById('addItemBtn').dataset.index = '-1';
        document.getElementById('cancelItemBtn').style.display = 'none';
        document.getElementById('addItemBtn').classList.remove('btn-success');
        document.getElementById('addItemBtn').classList.add('btn-primary');
    }

    function calculatePalletRowTotal(element) {
        const row = element.closest('.pallet-row');
        if (!row) return;
        const boxInput = row.querySelector('.box_pallet');
        const totalInput = row.querySelector('.total_pallet');
        const totalBoxesOutput = row.querySelector('.total_boxe_pallets');
        const box = parseInt(boxInput.value) || 0;
        const total = parseInt(totalInput.value) || 0;
        const rowTotalBoxes = box * total;
        totalBoxesOutput.value = rowTotalBoxes > 0 ? rowTotalBoxes : '';
        updateOrderQty();
    }

    function updateOrderQty() {
        let grandTotalBoxesSum = 0;
        document.querySelectorAll("#palletWrapper .total_boxe_pallets").forEach(input => {
            let rowTotal = parseInt(input.value) || 0;
            grandTotalBoxesSum += rowTotal;
        });
        document.getElementById("order_qty").value = grandTotalBoxesSum > 0 ? grandTotalBoxesSum : '';
    }

    function getPalletData(orderQty) {
        let pallets = [];
        let palletTotalQty = 0;
        
        let rows = document.querySelectorAll("#palletWrapper .pallet-row");
        if (rows.length === 0) {
            return [];
        }
        
        let isValid = true;

        rows.forEach(row => {
            let boxInput = row.querySelector(".box_pallet");
            let totalInput = row.querySelector(".total_pallet");
            let totalBoxesInput = row.querySelector(".total_boxe_pallets"); 
            let palletIdInput = row.querySelector(".pallet_id");
            
            let box = parseInt(boxInput.value) || 0;
            let total = parseInt(totalInput.value) || 0;
            let calculatedTotalBoxes = parseInt(totalBoxesInput.value) || 0;
            
            if (box > 0 && total > 0) {
                if (box * total !== calculatedTotalBoxes) {
                    alert("Pallet boxes calculation error. Please check your inputs.");
                    isValid = false;
                    return; 
                }

                pallets.push({
                    id: palletIdInput ? palletIdInput.value : null,
                    box_pallet: box,
                    total_pallet: total,
                    total_boxe_pallets: calculatedTotalBoxes 
                });
                palletTotalQty += calculatedTotalBoxes;
            }
        });
        
        if (!isValid) {
            return null;
        }

        if (pallets.length > 0) {
            let requiredQty = parseInt(order_qty.value);
            if (palletTotalQty !== requiredQty) {
                alert(`Pallet total boxes (${palletTotalQty}) must equal Order Qty (${requiredQty}).`);
                return null;
            }
        }        
        return pallets;
    }

    document.getElementById('addItemBtn').addEventListener('click', function() {
        let po = $('#po').val();
        let party_id = $('#party_id').val();
        let brand_name = $('#brand_name').val();
        let order_date = $('#order_date').val();
        let design = document.getElementById('design_id');
        let size = document.getElementById('size_id');
        let finish = document.getElementById('finish_id');
        let order_qty = document.getElementById('order_qty');
        let remark = document.getElementById('remark');
        
        const mode = this.dataset.mode;
        const index = parseInt(this.dataset.index);

        if (!po || !party_id || !brand_name || !order_date) {
            alert("Please fill all order fields (PO Number, Party, Brand Name, Order Date).");
            return;
        }

        if (!design.value || !size.value || !finish.value || !order_qty.value || parseInt(order_qty.value) <= 0) {
            alert("Please fill all item fields correctly (Design, Size, Finish, Qty > 0).");
            return;
        }

        if (mode === 'add') {
            let exists = items.some(i =>
                i.design_id == design.value &&
                i.size_id == size.value &&
                i.finish_id == finish.value
            );
            if (exists) {
                alert("This item already exists in the list! Please edit the existing item or change the combination.");
                return;
            }
        } else if (mode === 'update') {
             let exists = items.some((i, idx) =>
                idx !== index &&
                i.design_id == design.value &&
                i.size_id == size.value &&
                i.finish_id == finish.value
            );
            if (exists) {
                alert("This item combination already exists in the list! Please use a unique combination.");
                return;
            }
        }
        
        let pallets = getPalletData(order_qty.value);
        if (pallets === null) {
            return;
        }

        const newItem = {
            id: editingItemId,
            design_id: design.value,
            design_text: design.options[design.selectedIndex].text,
            size_id: size.value,
            size_text: size.options[size.selectedIndex].text,
            finish_id: finish.value,
            finish_text: finish.options[finish.selectedIndex].text,
            order_qty: order_qty.value,
            remark: remark.value,
            pallets: pallets || []
        };
        
        if (mode === 'add') {
            items.push(newItem);
        } else if (mode === 'update') {
            items[index] = newItem;
            isEditing = false;
        }
        renderTable();
        resetItemForm(); 
    });

    function removeItem(index) {
        if (isEditing) {
            alert("Please finish editing the current item before deleting another one.");
            return;
        }
        items.splice(index, 1);
        renderTable();
    }
    
    function editItem(index) {
        if (isEditing) {
            alert("An item is already being edited. Please finish or cancel the current edit.");
            return;
        }
        
        let item = items[index];
        isEditing = true;
        
        editingItemId = item.id || null; 
        
        document.getElementById('design_id').value = item.design_id;
        document.getElementById('size_id').value = item.size_id;
        document.getElementById('finish_id').value = item.finish_id;
        document.getElementById('order_qty').value = item.order_qty;
        document.getElementById('remark').value = item.remark;
        
        let hasPallet = item.pallets && item.pallets.length > 0;
        let palletSection = document.getElementById("palletSection");
        let wrapper = document.getElementById("palletWrapper");
        
        wrapper.innerHTML = ''; 
        
        if (hasPallet) {
            palletSection.style.display = "block";
            item.pallets.forEach(p => {
                addPalletRow(p); 
            });
        } else {
            palletSection.style.display = "none";
            addPalletRow({});
        }


        const addItemBtn = document.getElementById('addItemBtn');
        addItemBtn.textContent = 'Update Item';
        addItemBtn.dataset.mode = 'update';
        addItemBtn.dataset.index = index;
        addItemBtn.classList.remove('btn-primary');
        addItemBtn.classList.add('btn-success');
        document.getElementById('cancelItemBtn').style.display = 'inline-block';
        renderTable(); 
    }
    
    function cancelEdit() {
        if (!isEditing) return;
        
        isEditing = false;
        resetItemForm();
        renderTable();
    }
    
    function disableMainFields() {
        $("#po").attr("readonly", true);
        $("#brand_name").attr("readonly", true);
        $("#order_date").attr("readonly", true);
        $('#party_id').closest('div').addClass('disabled');
    }
    
    function enableMainFields() {
        $("#po").attr("readonly", false);
        $("#brand_name").attr("readonly", false);
        $("#order_date").attr("readonly", false);
        $('#party_id').closest('div').removeClass('disabled');
    } 
    
    function previewBoxImage(event) {
        const output = document.getElementById('boxImagePreview');
        if (event.target.files.length > 0) {
            output.src = URL.createObjectURL(event.target.files[0]);
            output.style.display = 'block';
        }
    }
</script>
@endpush