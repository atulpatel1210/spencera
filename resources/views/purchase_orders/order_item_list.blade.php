@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h4>Order Item List</h4>
        {{-- You might want to add a button for adding new order items if applicable --}}
        {{-- <a href="{{ route('purchase_order_items.create') }}" class="btn btn-primary btn-sm">+ Add Order Item</a> --}}
    </div>
    <div class="card-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

        <table class="table table-bordered" id="order-items-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>PO</th>
                    <th>Design</th>
                    <th>Size</th>
                    <th>Finish</th>
                    <th>Order Qty</th>
                    <th>Pending Qty</th>
                    <th>Planning Qty</th>
                    <th>Production Qty</th>
                    <th>Short/Excess Qty</th>
                    <th>Remark</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                {{-- Data will be loaded by Yajra Datatables --}}
            </tbody>
        </table>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="quantityModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Edit Quantity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="quantityForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="PATCH">
                    <input type="hidden" name="item_id" id="item_id">
                    <input type="hidden" name="type" id="type">
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" name="quantity" id="quantity">
                        <div class="text-danger" id="quantity_error"></div>
                    </div>
                    <div class="mb-3" id="batch_no_section">
                        <label for="batch_no" class="form-label">Batch No</label>
                        <input type="text" class="form-control" name="batch_no" id="batch_no">
                        <div class="text-danger" id="batch_no_error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" name="date" id="date">
                        <div class="text-danger" id="date_error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <textarea class="form-control" id="location" name="location" rows="5"></textarea>
                        <div class="text-danger" id="location_error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="remark" class="form-label">Remark</label>
                        <textarea class="form-control" id="remark" name="remark" rows="5"></textarea>
                        <div class="text-danger" id="remark_error"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="saveBtn">Save</button>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
$(function() {
    $('#order-items-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('purchase_order_item.data') }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'po', name: 'po' },
            { data: 'design_detail.name', name: 'designDetail.name' },
            { data: 'size_detail.size_name', name: 'sizeDetail.size_name' },
            { data: 'finish_detail.finish_name', name: 'finishDetail.finish_name' },
            { data: 'order_qty', name: 'order_qty' },
            { data: 'pending_qty', name: 'pending_qty' },
            { data: 'planning_qty', name: 'planning_qty' },
            { data: 'production_qty', name: 'production_qty' },
            { data: 'short_qty', name: 'short_qty' },
            { data: 'remark', name: 'remark' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
});
    $(document).ready(function () {
        const quantityModal = new bootstrap.Modal(document.getElementById('quantityModal'));
        $('#order-items-table').on('click', '.openModal', function () {
            const itemId = $(this).data('id');
            const type = $(this).data('type');

            $('#quantity_error, #batch_no_error, #remark_error').text('');
            $('#quantityForm')[0].reset();

            fetch(`order-item-data/${itemId}`)
                .then(response => {
                    if (!response.ok) throw new Error('Failed to fetch order item data');
                    return response.json();
                })
                .then(data => {
                    $('#item_id').val(itemId);
                    $('#type').val(type);
                    $('#remark').val(data.remark || '');
                    $('#modalTitle').text(`Edit ${type.charAt(0).toUpperCase() + type.slice(1)} Quantity`);
                    $('#batch_no_section').toggle(type === 'production');
                    quantityModal.show();
                })
                .catch(error => {
                    alert(error.message);
                });
        });

        $('#saveBtn').on('click', function () {
            const form = document.getElementById('quantityForm');
            const formData = new FormData(form);
            const itemId = $('#item_id').val();
            $('#quantity_error, #batch_no_error, #remark_error').text('');

            fetch(`update-order-item/${itemId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(res => res.json())
            .then(result => {
                console.log(result);
                if (result.success) {
                    quantityModal.hide();
                    $('#order-items-table').DataTable().ajax.reload();
                } else if (result.errors) {
                    if (result.errors.quantity) {
                        $('#quantity_error').text(result.errors.quantity[0]);
                    }
                    if (result.errors.batch_no) {
                        $('#batch_no_error').text(result.errors.batch_no[0]);
                    }
                    if (result.errors.date) {
                        $('#date_error').text(result.errors.date[0]);
                    }
                    if (result.errors.remark) {
                        $('#remark_error').text(result.errors.remark[0]);
                    }
                } else {
                    alert(result.message || 'Something went wrong!');
                }
            })
            .catch(err => {
                alert('Something went wrong while saving.');
            });
        });
    });
</script>
@endpush
