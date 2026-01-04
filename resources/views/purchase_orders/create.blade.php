@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="mb-0">Create Order</h4>
    </div>

    <div class="card-body">
        <form id="orderForm" action="{{ route('orders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

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
                    <input type="text" class="form-control" id="po" name="po" required value="{{ old('po') }}" placeholder="PO Number">
                </div>
                <div class="col-md-3">
                    <label for="party_id" class="form-label fw-semibold">Party</label>
                    <select class="form-select select2" id="party_id" name="party_id" required>
                        <option value="">Select Party</option>
                        @foreach ($parties as $p)
                        <option value="{{ $p->id }}"
                            {{ old('party_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->party_name }}
                        </option>
                        @endforeach
                    </select>

                </div>
                <div class="col-md-3">
                    <label for="brand_name" class="form-label fw-semibold">Brand Name</label>
                    <input type="text" class="form-control" id="brand_name" name="brand_name" placeholder="Enter brand name" value="{{ old('brand_name') }}">
                </div>
                <div class="col-md-3">
                    <label for="order_date" class="form-label fw-semibold">Order Date</label>
                    <input type="date" class="form-control" id="order_date" name="order_date" value="{{ old('order_date', date('Y-m-d')) }}" required>
                </div>
            </div>

            {{-- Box Image --}}
            <div class="row mb-4">
                <div class="col-md-4">
                    <label for="box_image" class="form-label fw-semibold">Box Image</label>
                    <input type="file" name="box_image" id="box_image" class="form-control" accept="image/*" onchange="previewBoxImage(event)">
                    <div class="mt-2">
                        <img id="boxImagePreview" src="" width="120" height="120" style="display:none;" class="border rounded">
                    </div>
                </div>
            </div>

            <hr>

            {{-- Item Entry --}}
            <h5 class="fw-semibold mb-3 text-primary">Add Order Item</h5>
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
                    <input type="number" class="form-control" id="order_qty" min="1" value="" placeholder="Order Qty" readonly>
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
                                <input type="number" class="form-control box_pallet" oninput="calculatePalletRowTotal(this);" placeholder="Box Per Pallet">  
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Total Pallet</label>
                                <input type="number" class="form-control total_pallet" oninput="calculatePalletRowTotal(this);" placeholder="Total Pallet">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Total Boxes (Pallet)</label>
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
                <div class="col-md-2 text-end">
                    <button type="button" class="btn btn-primary w-100" id="addItemBtn">Add</button>
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
                <tbody></tbody>
            </table>

            <input type="hidden" name="order_items" id="order_items">

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-success px-4">Save Order</button>
                <a href="{{ route('orders.index') }}" class="btn btn-secondary ms-2">Back</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let items = [];
    document.addEventListener("DOMContentLoaded", function() {
        let oldItems = @json(old('order_items'));
        if (oldItems) {
            try {
                items = typeof oldItems === 'string' ? JSON.parse(oldItems) : oldItems;
                renderTable();
            } catch (err) {
                console.error("Invalid JSON in old items", err);
            }
        }
        let initialPartyId = $('#party_id').val();
        let oldDesignId = "{{ old('design_id') }}";
        if(initialPartyId) {
            loadDesigns(initialPartyId, oldDesignId);
        }
    });
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select Party",
            allowClear: true,
            width: '100%'
        });
        $('#party_id').on('change', function() {
            let partyId = $(this).val();
            loadDesigns(partyId);
        });
    });
    function calculatePalletRowTotal(element) {
        const row = element.closest('.pallet-row');
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
    document.addEventListener("click", function(e) {
        if (e.target && e.target.id === "addMorePalletBtn") {
            let row = `
            <div class="row g-3 pallet-row mt-2">
                <div class="col-md-3">
                    <input type="number" class="form-control box_pallet" oninput="calculatePalletRowTotal(this);" placeholder="Box Per Pallet">
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control total_pallet" oninput="calculatePalletRowTotal(this);" placeholder="Total Pallet">
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control total_boxe_pallets" placeholder="Total Boxes (Pallet)" readonly>
                </div>
                <div class="col-md-2 d-flex align-items-center">
                    <button type="button" class="btn btn-danger btn-sm removePallet">X</button>
                </div>
            </div>`;
            document.getElementById("palletWrapper").insertAdjacentHTML("beforeend", row);
        }
    });
    document.addEventListener("click", function(e) {
        if (e.target && e.target.classList.contains("removePallet")) {
            e.target.closest(".pallet-row").remove();
            updateOrderQty(); 
        }
    });
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
                            item.pallets.forEach((p, i) => {
                                let totalBoxes = p.box_pallet * p.total_pallet;
                                palletHtml += `
                                <tr>
                                    <td>${i + 1}</td>
                                    <td>${p.box_pallet}</td>
                                    <td>${p.total_pallet}</td>
                                    <td>${totalBoxes}</td>
                                </tr>`;
                            });
                            palletHtml += `
                        </tbody>
                    </table>`;
            }

            let row = `<tr>
                <td>${item.design_text}</td>
                <td>${item.size_text}</td>
                <td>${item.finish_text}</td>
                <td>${item.order_qty} ${palletHtml}</td>
                <td>${item.remark}</td>
                <td>
                    <button type="button" class="btn btn-sm text-primary" onclick="editItem(${index})"><i class="fa fa-edit fa-fw fa-lg"></i></button>
                    <button type="button" class="btn btn-sm text-danger" onclick="removeItem(${index})"><i class="fa fa-trash fa-fw fa-lg"></i></button>
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
    }
    function resetItemForm() {
        $('#design_id').val('');
        $('#size_id').val('');
        $('#finish_id').val('');
        $('#order_qty').val('');
        $('#remark').val('');
        document.querySelectorAll("#palletWrapper .pallet-row:not(:first-child)").forEach(r => r.remove());
        let firstRow = document.querySelector("#palletWrapper .pallet-row");
        if(firstRow) {
            firstRow.querySelector(".box_pallet").value = '';
            firstRow.querySelector(".total_pallet").value = '';
            firstRow.querySelector(".total_boxe_pallets").value = '';
        }
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
        
        if (!po || !party_id || !brand_name || !order_date) {
            alert("Please fill all order fields (PO Number, Party, Brand Name, Order Date).");
            return;
        }
        if (!design.value || !size.value || !finish.value || !order_qty.value || parseInt(order_qty.value) <= 0) {
            alert("Please fill all item fields correctly (Design, Size, Finish, Qty > 0).");
            return;
        }
        
        let exists = items.some(i =>
            i.design_id == design.value &&
            i.size_id == size.value &&
            i.finish_id == finish.value
        );
        if (exists) {
            alert("This item already exists in the list!");
            return;
        }
        
        let pallets = [];
        let palletTotalQty = 0;
        
        let isValid = true;
        document.querySelectorAll("#palletWrapper .pallet-row").forEach(row => {
            let boxInput = row.querySelector(".box_pallet");
            let totalInput = row.querySelector(".total_pallet");
            let boxpalletInput = row.querySelector(".total_boxe_pallets");
            
            let box = parseInt(boxInput.value) || 0;
            let total = parseInt(totalInput.value) || 0;
            
            if (box > 0 && total > 0) {
                pallets.push({
                    box_pallet: box,
                    total_pallet: total,
                    total_boxe_pallets: box * total
                });
                palletTotalQty += (box * total);
            }
        });
        if (pallets.length > 0) {
            let requiredQty = parseInt(order_qty.value);
            if (palletTotalQty !== requiredQty) {
                alert(`Pallet total boxes (${palletTotalQty}) must equal Order Qty (${requiredQty}).`);
                return;
            }
        }
        items.push({
            design_id: design.value,
            design_text: design.options[design.selectedIndex].text,
            size_id: size.value,
            size_text: size.options[size.selectedIndex].text,
            finish_id: finish.value,
            finish_text: finish.options[finish.selectedIndex].text,
            order_qty: order_qty.value,
            remark: remark.value,
            pallets: pallets
        });
        renderTable();
        resetItemForm(); 
    });

    function removeItem(index) {
        items.splice(index, 1);
        renderTable();
    }

    function loadDesigns(partyId, selectedDesignId = null) {
        let designSelect = $('#design_id');
        
        if (!partyId) {
            designSelect.html('<option value="">Select</option>');
            return;
        }

        $.ajax({
            url: "{{ route('party.designs') }}",
            type: "GET",
            data: { party_id: partyId },
            success: function(res) {
                designSelect.empty().append('<option value="">Select</option>');
                $.each(res, function(index, design) {
                    designSelect.append(
                        `<option value="${design.id}">${design.name}</option>`
                    );
                });
                if (selectedDesignId) {
                    designSelect.val(selectedDesignId).trigger('change'); 
                }
            },
            error: function() {
                console.error("Failed to load designs");
            }
        });
    }
    
    function editItem(index) {
        let item = items[index];
        let currentPartyId = $('#party_id').val();
        loadDesigns(currentPartyId, item.design_id);
        document.getElementById('size_id').value = item.size_id;
        document.getElementById('finish_id').value = item.finish_id;
        document.getElementById('order_qty').value = item.order_qty;
        document.getElementById('remark').value = item.remark;
        let wrapper = document.getElementById("palletWrapper");
        wrapper.querySelectorAll(".pallet-row:not(:first-child)").forEach(r => r.remove());
        let firstRow = wrapper.querySelector(".pallet-row");
        let hasPallet = item.pallets && item.pallets.length > 0;

        if (hasPallet) {
            if (item.pallets[0]) {
                let p1 = item.pallets[0];
                firstRow.querySelector(".box_pallet").value = p1.box_pallet;
                firstRow.querySelector(".total_pallet").value = p1.total_pallet;
                let total1 = (parseFloat(p1.box_pallet) || 0) * (parseFloat(p1.total_pallet) || 0);
                firstRow.querySelector(".total_boxe_pallets").value = total1 > 0 ? total1 : '';
            }
            for (let i = 1; i < item.pallets.length; i++) {
                let p = item.pallets[i];
                let rowTotal = (parseFloat(p.box_pallet) || 0) * (parseFloat(p.total_pallet) || 0);
                let finalTotal = rowTotal > 0 ? rowTotal : '';

                let row = `
                <div class="row g-3 pallet-row mt-2">
                    <div class="col-md-3">
                        <input type="number" class="form-control box_pallet" oninput="calculatePalletRowTotal(this);" placeholder="Box Per Pallet" value="${p.box_pallet}">
                    </div>
                    <div class="col-md-3">
                        <input type="number" class="form-control total_pallet" oninput="calculatePalletRowTotal(this);" placeholder="Total Pallet" value="${p.total_pallet}">
                    </div>
                    <div class="col-md-3">
                        <input type="number" class="form-control total_boxe_pallets" placeholder="Total Boxes (Pallet)" value="${finalTotal}" readonly>
                    </div>
                    <div class="col-md-2 d-flex align-items-center">
                        <button type="button" class="btn btn-danger btn-sm removePallet">X</button>
                    </div>
                </div>`;
                wrapper.insertAdjacentHTML("beforeend", row);
            }
        } else {
            if(firstRow) {
                firstRow.querySelector(".box_pallet").value = '';
                firstRow.querySelector(".total_pallet").value = '';
                firstRow.querySelector(".total_boxe_pallets").value = '';
            }
        }
        items.splice(index, 1);
        renderTable();
    }
    
    function disableMainFields() {
        $('#party_id').closest('div').addClass('disabled');
    }
    function enableMainFields() {
        $('#party_id').closest('div').removeClass('disabled');
    } 
    function previewBoxImage(event) {
        const output = document.getElementById('boxImagePreview');
        if (event.target.files.length > 0) {
            output.src = URL.createObjectURL(event.target.files[0]);
            output.style.display = 'block';
        } else {
            output.style.display = 'none';
            output.src = '';
        }
    }
</script>
@endpush