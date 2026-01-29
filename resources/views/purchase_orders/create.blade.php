@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xxl-11">
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 fw-bold text-primary display-6 fs-4">
                        <i class="fas fa-shopping-cart me-2"></i> Create New Order
                    </h4>
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary rounded-pill shadow-sm px-4">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>
            </div>

            <div class="card-body p-4 p-md-5">
                <form id="orderForm" action="{{ route('orders.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger rounded-3 shadow-sm border-0 mb-4">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Order Information Section --}}
                    <div class="mb-5">
                        <h5 class="fw-bold text-dark mb-4 border-start border-4 border-primary ps-3">Order Information</h5>
                        <div class="row g-4">
                            <div class="col-md-6 col-lg-3">
                                <label for="po" class="form-label fw-semibold text-secondary small text-uppercase">PO Number <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg shadow-sm">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-hash"></i></span>
                                    <input type="text" class="form-control border-start-0 bg-white" id="po" name="po" required value="{{ old('po') }}" placeholder="Ex: PO-2024-001">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label for="party_id" class="form-label fw-semibold text-secondary small text-uppercase">Party Name <span class="text-danger">*</span></label>
                                <select class="form-select form-select-lg select2 shadow-sm" id="party_id" name="party_id" required>
                                    <option value="">Select Party</option>
                                    @foreach ($parties as $p)
                                    <option value="{{ $p->id }}" {{ old('party_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->party_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label for="brand_name" class="form-label fw-semibold text-secondary small text-uppercase">Brand Name</label>
                                <div class="input-group input-group-lg shadow-sm">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-tag"></i></span>
                                    <input type="text" class="form-control border-start-0 bg-white" id="brand_name" name="brand_name" placeholder="Enter Brand" value="{{ old('brand_name') }}">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label for="order_date" class="form-label fw-semibold text-secondary small text-uppercase">Order Date <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg shadow-sm">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-calendar-event"></i></span>
                                    <input type="date" class="form-control border-start-0 bg-white" id="order_date" name="order_date" value="{{ old('order_date', date('Y-m-d')) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                             <div class="col-md-6">
                                <label for="box_image" class="form-label fw-semibold text-secondary small text-uppercase">Upload Box Image</label>
                                <div class="p-4 border rounded-3 bg-light text-center position-relative upload-zone">
                                    <input type="file" name="box_image" id="box_image" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" accept="image/*" onchange="previewBoxImage(event)">
                                    <div id="uploadPrompt">
                                        <i class="bi bi-cloud-arrow-up fs-1 text-primary mb-3 opacity-50"></i>
                                        <p class="mb-0 text-muted fw-medium">Drag & Drop or Click to Upload</p>
                                    </div>
                                    <img id="boxImagePreview" src="" class="img-fluid rounded shadow-sm mt-3" style="max-height: 150px; display:none;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-top my-5"></div>

                    {{-- Item Entry Section --}}
                    <div class="bg-white p-4 rounded-4 shadow-sm mb-5 border border-light-subtle">
                        <h5 class="fw-bold text-dark mb-4 ps-2 border-start border-4 border-primary"><i class="bi bi-box-seam text-primary me-2"></i> Add Order Items</h5>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-muted small">Design</label>
                                <select class="form-select form-select-lg shadow-sm bg-light border-0" id="design_id">
                                    <option value="">Select</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-muted small">Size</label>
                                <select class="form-select form-select-lg shadow-sm bg-light border-0" id="size_id">
                                    <option value="">Select</option>
                                    @foreach ($sizes as $s)
                                    <option value="{{ $s->id }}">{{ $s->size_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-muted small">Finish</label>
                                <select class="form-select form-select-lg shadow-sm bg-light border-0" id="finish_id">
                                    <option value="">Select</option>
                                    @foreach ($finishes as $f)
                                    <option value="{{ $f->id }}">{{ $f->finish_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                             <div class="col-md-3">
                                <label class="form-label fw-semibold text-muted small">Remark</label>
                                <input type="text" class="form-control form-control-lg shadow-sm bg-light border-0" id="remark" placeholder="Optional remark">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold text-muted small">Total Order Qty</label>
                                <input type="number" class="form-control form-control-lg shadow-sm bg-white border border-primary font-monospace fw-bold text-primary" id="order_qty" min="1" value="" placeholder="Calculated from pallets..." readonly>
                            </div>
                        </div>

                        {{-- Pallet Configuration Sub-Section --}}
                        <div class="p-4 bg-light rounded-4 border border-light-subtle">
                             <label class="form-label fw-bold text-dark mb-3 small text-uppercase letter-spacing-1">Pallet Configuration</label>
                            
                             <div id="palletWrapper">
                                <div class="row g-2 pallet-row mb-2 align-items-center">
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <input type="number" class="form-control box_pallet bg-white border-0 shadow-sm" oninput="calculatePalletRowTotal(this);" placeholder="0">
                                            <label>Box / Pallet</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                         <div class="form-floating">
                                            <input type="number" class="form-control total_pallet bg-white border-0 shadow-sm" oninput="calculatePalletRowTotal(this);" placeholder="0">
                                            <label>Total Pallet</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                         <div class="form-floating">
                                            <input type="number" class="form-control total_boxe_pallets bg-secondary bg-opacity-10 border-0 fw-bold text-dark shadow-sm" placeholder="0" readonly>
                                            <label>Total Boxes</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                         <button type="button" class="btn btn-outline-danger w-100 h-100 py-3 removePallet rounded-3 shadow-sm">
                                            <i class="bi bi-x-lg"></i>
                                         </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-3 text-center">
                                <button type="button" id="addMorePalletBtn" class="btn btn-outline-primary btn-sm rounded-pill px-4 shadow-sm">
                                    <i class="bi bi-plus-lg me-1"></i> Add Another Pallet Size
                                </button>
                            </div>
                        </div>

                         <div class="row mt-4">
                            <div class="col-12 text-end">
                                <button type="button" class="btn btn-dark btn-lg px-5 rounded-pill shadow-sm hover-elevate" id="addItemBtn">
                                    <i class="bi bi-plus-circle me-2"></i> Add Item to Order
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Items Table --}}
                    <div class="mb-5">
                        <h5 class="fw-bold text-dark border-start border-4 border-success ps-3 mb-3">Review Order Items</h5>
                        <div class="table-responsive rounded-4 shadow-sm border bg-white">
                            <table class="table table-hover mb-0 align-middle" id="itemsTable">
                                <thead class="bg-light">
                                    <tr class="text-uppercase small text-secondary">
                                        <th class="py-3 ps-4">Design / Size / Finish</th>
                                        <th class="py-3">Order Qty</th>
                                        <th class="py-3">Remark</th>
                                        <th class="py-3 text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="emptyRowPlaceholder">
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="bi bi-clipboard-x fs-1 mb-3 opacity-25"></i>
                                            <p>No items added yet. Please add items from the section above.</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <input type="hidden" name="order_items" id="order_items">

                    <div class="d-flex justify-content-end gap-3 mt-5 pb-4">
                        <a href="{{ route('orders.index') }}" class="btn btn-light btn-lg px-4 border rounded-pill text-secondary fw-medium hover-bg-gray">Cancel</a>
                        <button type="submit" class="btn btn-primary btn-lg px-5 shadow fw-bold rounded-pill">
                            <i class="fas fa-save me-2"></i> Create Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
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