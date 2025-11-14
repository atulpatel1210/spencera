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
            <div class="row mb-4">
                <div class="col-md-3">
                    <label for="po" class="form-label fw-semibold">PO Number</label>
                    <input type="text" class="form-control" id="po" name="po" required value="{{ old('po') }}">
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
                    <input type="number" class="form-control" id="order_qty" min="1" value="">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Remark</label>
                    <input type="text" class="form-control" id="remark" placeholder="Optional">
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
    document.addEventListener("DOMContentLoaded", function () {
        let oldItems = @json(old('order_items'));

        if (oldItems) {
            try {
                let parsed = JSON.parse(oldItems);
                items = parsed;
                renderTable();
                if (items.length > 0) {
                    disableMainFields();
                }
            } catch (err) {
                console.error("Invalid JSON in old items", err);
            }
        }
        $('#party_id').trigger('change.select2');
    });

    $(document).ready(function() {

        $('.select2').select2({
            placeholder: "Select Party",
            allowClear: true,
            width: '100%'
        });

        // Load Designs by Party
        $('#party_id').on('change', function () {
            let partyId = $(this).val();
            $('#design_id').html('<option value="">Loading...</option>');
            if (partyId) {
                $.ajax({
                    url: "{{ route('party.designs') }}",
                    type: "GET",
                    data: { party_id: partyId },
                    success: function (res) {
                        $('#design_id').empty().append('<option value="">Select</option>');
                        $.each(res, function (index, design) {
                            $('#design_id').append(
                                `<option value="${design.id}">${design.name}</option>`
                            );
                        });
                    }
                });
            } else {
                $('#design_id').html('<option value="">Select</option>');
            }
        });

    });

    // Preview Box Image
    function previewBoxImage(event) {
        const output = document.getElementById('boxImagePreview');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.style.display = 'block';
    }

    // Disable after 1st item added
    function disableMainFields() {
        $("#po").attr("readonly", true);
        $("#brand_name").attr("readonly", true);
        $("#order_date").attr("readonly", true);

        $('#party_id').closest('div').addClass('disabled');

        // File input: prevent selecting new file
        $("#box_image").on("click", function(e) {
            e.preventDefault();
        });
    }


    // Enable (if items become zero)
    function enableMainFields() {
        $("#po").attr("readonly", false);
        $("#brand_name").attr("readonly", false);
        $("#order_date").attr("readonly", false);

        // Allow select2 again
        $('#party_id').closest('div').removeClass('disabled');

        // Allow selecting new file
        $("#box_image").off("click");
    }

    let items = [];

    function renderTable() {
        let tbody = document.querySelector('#itemsTable tbody');
        tbody.innerHTML = '';

        items.forEach((item, index) => {
            let row = `<tr>
                <td>${item.design_text}</td>
                <td>${item.size_text}</td>
                <td>${item.finish_text}</td>
                <td>${item.order_qty}</td>
                <td>${item.remark}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-warning" onclick="editItem(${index})">Edit</button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeItem(${index})">Delete</button>
                </td>
            </tr>`;
            tbody.innerHTML += row;
        });

        // hidden input
        document.getElementById('order_items').value = JSON.stringify(items);

        // check table row count using correct ID
        if ($("#itemsTable tbody tr").length > 0) {
            disableMainFields();
        } else {
            enableMainFields();
        }
    }

    // Add Item
    document.getElementById('addItemBtn').addEventListener('click', function() {
        let design = document.getElementById('design_id');
        let size = document.getElementById('size_id');
        let finish = document.getElementById('finish_id');
        let order_qty = document.getElementById('order_qty');
        let remark = document.getElementById('remark');

        if (!design.value || !size.value || !finish.value || !order_qty.value) {
            alert('Please fill all item fields.');
            return;
        }

        // CHECK UNIQUE COMBINATION (Design + Size + Finish)
        let exists = items.some(i =>
            i.design_id == design.value &&
            i.size_id == size.value &&
            i.finish_id == finish.value
        );

        if (exists) {
            alert("This item already exists!");
            return;
        }

        // Add new item
        items.push({
            design_id: design.value,
            design_text: design.options[design.selectedIndex].text,
            size_id: size.value,
            size_text: size.options[size.selectedIndex].text,
            finish_id: finish.value,
            finish_text: finish.options[finish.selectedIndex].text,
            order_qty: order_qty.value,
            remark: remark.value
        });

        renderTable();

        // Reset input values
        design.value = '';
        size.value = '';
        finish.value = '';
        order_qty.value = '';
        remark.value = '';
    });

    // Remove Item
    function removeItem(index) {
        items.splice(index, 1);
        renderTable();
    }

    // Edit Item
    function editItem(index) {
        let item = items[index];
        document.getElementById('design_id').value = item.design_id;
        document.getElementById('size_id').value = item.size_id;
        document.getElementById('finish_id').value = item.finish_id;
        document.getElementById('order_qty').value = item.order_qty;
        document.getElementById('remark').value = item.remark;
        items.splice(index, 1);
        renderTable();
    }

    // Submit Form
    document.getElementById('orderForm').addEventListener('submit', function() {
        document.getElementById('order_items').value = JSON.stringify(items);
    });
</script>
@endpush