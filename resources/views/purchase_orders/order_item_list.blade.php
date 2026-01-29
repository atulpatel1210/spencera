@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="card-title text-primary">
                        <i class="fas fa-tasks me-2"></i> Production & Planning
                    </h5>
                </div>
                
                <div class="card-body p-0">
                    @if(session('success')) 
                        <div class="alert alert-success m-3 rounded-lg border-0 shadow-sm d-flex align-items-center">
                            <i class="fas fa-check-circle me-2 fs-5"></i>
                            <div>{{ session('success') }}</div>
                        </div> 
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="order-items-table">
                            <thead>
                                <tr>
                                    <th class="ps-4"> #</th>
                                    <th>PO</th>
                                    <th>Design</th>
                                    <th>Size</th>
                                    <th>Finish</th>
                                    <th class="text-center">Order Qty</th>
                                    <th class="text-center text-warning">Pending</th>
                                    <th class="text-center text-info">Planning</th>
                                    <th class="text-center text-success">Production</th>
                                    <th class="text-center text-danger">Diff</th>
                                    <th>Remark</th>
                                    <th class="text-end pe-4 text-nowrap">Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="quantityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-lg overflow-hidden">
            <div class="modal-header bg-light border-0 py-3">
                <h5 class="fw-bold mb-0" id="modalTitle">Edit Quantity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="quantityForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="PATCH">
                    <input type="hidden" name="item_id" id="item_id">
                    <input type="hidden" name="type" id="type">
                    
                    <div class="mb-4">
                        <label for="quantity" class="form-label small fw-bold text-uppercase">Quantity</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calculator"></i></span>
                            <input type="number" class="form-control" name="quantity" id="quantity" min="0" step="1" inputmode="numeric">
                        </div>
                        <div class="text-danger small mt-1" id="quantity_error"></div>
                    </div>

                    <div id="batch_no_section" class="mb-4" style="display:none;">
                        <label for="batch_no" class="form-label small fw-bold text-uppercase">Batch No</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                            <input type="text" class="form-control" name="batch_no" id="batch_no">
                        </div>
                        <div class="text-danger small mt-1" id="batch_no_error"></div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="date" class="form-label small fw-bold text-uppercase">Date</label>
                         <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                            <input type="date" class="form-control" name="date" id="date">
                        </div>
                        <div class="text-danger small mt-1" id="date_error"></div>
                    </div>

                    <div id="location_section" class="mb-4" style="display:none;">
                        <label for="location" class="form-label small fw-bold text-uppercase">Location</label>
                        <textarea class="form-control" id="location" name="location" rows="2"></textarea>
                        <div class="text-danger small mt-1" id="location_error"></div>
                    </div>

                    <div class="mb-0">
                        <label for="remark" class="form-label small fw-bold text-uppercase">Remark</label>
                        <textarea class="form-control" id="remark" name="remark" rows="2"></textarea>
                        <div class="text-danger small mt-1" id="remark_error"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-light border px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary px-4 fw-bold" id="saveBtn">
                    <i class="fas fa-save me-2"></i> Save Changes
                </button>
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
        responsive: false,
        pageLength: 10,
        dom: '<"d-flex justify-content-between align-items-center mx-3 mt-3"l<"ms-auto"f>>' +
             '<"table-scroll-container"t>' + 
             '<"d-flex justify-content-between align-items-center mx-3 mb-3"ip>',
        language: {
            search: "",
            searchPlaceholder: "Search items...",
            lengthMenu: "Show _MENU_ entries"
        },
        ajax: '{{ route('purchase_order_item.data') }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'ps-4 fw-bold text-muted' },
            { data: 'po_number', name: 'po' },
            { data: 'design_detail.name', name: 'designDetail.name', className: 'fw-semibold' },
            { data: 'size_detail.size_name', name: 'sizeDetail.size_name' },
            { data: 'finish_detail.finish_name', name: 'finishDetail.finish_name' },
            { data: 'order_qty', name: 'order_qty', className: 'text-center' },
            { data: 'pending_qty', name: 'pending_qty', className: 'text-center text-warning fw-bold' },
            { data: 'planning_qty', name: 'planning_qty', className: 'text-center text-info' },
            { data: 'production_qty', name: 'production_qty', className: 'text-center text-success' },
            { data: 'short_qty', name: 'short_qty', className: 'text-center text-danger fw-bold' },
            { data: 'remark', name: 'remark', className: 'small' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end pe-4' }
        ]
    });

    const quantityModal = new bootstrap.Modal(document.getElementById('quantityModal'));
    
    $('#order-items-table').on('click', '.openModal', function () {
        const itemId = $(this).data('id');
        const type = $(this).data('type');

        $('.text-danger').text('');
        $('#quantityForm')[0].reset();

        fetch(`order-item-data/${itemId}`)
            .then(response => response.json())
            .then(data => {
                $('#item_id').val(itemId);
                $('#type').val(type);
                $('#remark').val(data.remark || '');
                $('#modalTitle').text(`Edit ${type.charAt(0).toUpperCase() + type.slice(1)} Quantity`);
                $('#batch_no_section, #location_section').toggle(type === 'production');
                quantityModal.show();
            });
    });

    $('#saveBtn').on('click', function () {
        const itemId = $('#item_id').val();
        const formData = new FormData(document.getElementById('quantityForm'));
        $('.text-danger').text('');

        fetch(`update-order-item/${itemId}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
        })
        .then(res => res.json())
        .then(result => {
            if (result.success) {
                quantityModal.hide();
                $('#order-items-table').DataTable().ajax.reload();
            } else if (result.errors) {
                Object.keys(result.errors).forEach(key => {
                    $(`#${key}_error`).text(result.errors[key][0]);
                });
            }
        });
    });
});
</script>
@endpush
