@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Purchase Order Details</h4>
    </div>
    <div class="card-body">
        <h5>Order Items</h5>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Design</th>
                        <th>Size</th>
                        <th>Finish</th>
                        <th>Order Qty</th>
                        <th>Pending</th>
                        <th>Planning</th>
                        <th>Production</th>
                        <th>Short/Excess</th>
                        <th>Remark</th>
                        <!-- <th>Actions</th> -->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->designDetail->name }}</td>
                        <td>{{ $item->sizeDetail->size_name }}</td>
                        <td>{{ $item->finishDetail->finish_name }}</td>
                        <td>{{ $item->order_qty }}</td>
                        <td>{{ $item->pending_qty }}</td>
                        <td>{{ $item->planning_qty }}</td>
                        <td>{{ $item->production_qty }}</td>
                        <td>{{ $item->short_qty }}</td>
                        <td>{{ $item->remark }}</td>
                        <!-- <td>
                            <div class="btn-group">
                                <button
                                    class="btn btn-sm btn-outline-info openModal {{ $item->pending_qty > 0 ? '' : 'disabled' }}"
                                    data-id="{{ $item->id }}"
                                    data-type="planning"
                                    {{ $item->pending_qty <= 0 ? 'disabled' : '' }}>
                                    Planning
                                </button>
                                <button
                                    class="btn btn-sm btn-outline-primary openModal {{ $item->planning_qty > 0 ? '' : 'disabled' }}"
                                    data-id="{{ $item->id }}"
                                    data-type="production"
                                    {{ $item->planning_qty <= 0 ? 'disabled' : '' }}>
                                    Production
                                </button>
                            </div>
                        </td> -->
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary mt-3">Back to Orders</a>
    </div>
</div>

<!-- <div class="modal fade" id="quantityModal" tabindex="-1">
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
                        <div class="text-danger" id="errorMsg"></div>
                    </div>
                    <div class="mb-3" id="batch_no_section">
                        <label for="batch_no" class="form-label">Batch No</label>
                        <input type="text" class="form-control" name="batch_no" id="batch_no">
                        <div class="text-danger" id="errorMsg"></div>
                    </div>
                    <div class="mb-3">
                        <label for="remark" class="form-label">Remark</label>
                        <textarea class="form-control" id="remark" name="remark" rows="5"></textarea>
                        <div class="text-danger" id="errorMsg"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="saveBtn">Save</button>
            </div>
        </div>
    </div>
</div> -->

<!-- <script>
    document.addEventListener("DOMContentLoaded", function() {
        const quantityModal = new bootstrap.Modal(document.getElementById('quantityModal'));

        document.querySelectorAll('.openModal').forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.dataset.id;
                const type = this.dataset.type;

                fetch(`order-item-data/${itemId}`)
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => {
                                console.error("Error Response:", text);
                                throw new Error('Failed to fetch order item data');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        document.getElementById('item_id').value = itemId;
                        document.getElementById('type').value = type;
                        document.getElementById('remark').value = data.remark;
                        document.getElementById('modalTitle').textContent = `Edit ${type.charAt(0).toUpperCase() + type.slice(1)} Quantity`;
                        document.getElementById('batch_no_section').style.display = 'none';
                        if (type == 'production') {
                            document.getElementById('batch_no_section').style.display = 'block';
                        }
                        quantityModal.show();
                    })
                    .catch(error => {
                        alert(error.message);
                    });
            });
        });

        document.getElementById('saveBtn').addEventListener('click', function() {
            const form = document.getElementById('quantityForm');
            const formData = new FormData(form);
            const quantity = parseInt(formData.get('quantity'));
            const type = formData.get('type');
            const error = document.getElementById('errorMsg');
            const itemId = document.getElementById('item_id').value;

            fetch(`update-order-item/${itemId}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        quantityModal.hide();
                        location.reload();
                    } else {
                        error.textContent = result.message || 'Error occurred!';
                    }
                })
                .catch(err => {
                    error.textContent = 'Something went wrong!';
                });
        });
    });
</script> -->
@endsection