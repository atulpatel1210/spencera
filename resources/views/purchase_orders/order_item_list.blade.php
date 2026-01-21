@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-white py-3 px-4 border-bottom">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-list-check me-2"></i> Order Item List
                    </h5>
                </div>
                
                <div class="card-body p-0">
                    @if(session('success')) 
                        <div class="alert alert-success m-3 rounded-3 border-0 shadow-sm d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                            {{ session('success') }}
                        </div> 
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="order-items-table">
                            <thead class="bg-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="ps-4">#</th>
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
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Data will be loaded by Yajra Datatables --}}
                            </tbody>
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
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-light border-bottom-0 py-3">
                <h5 class="modal-title fw-bold text-dark" id="modalTitle">Edit Quantity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="quantityForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="PATCH">
                    <input type="hidden" name="item_id" id="item_id">
                    <input type="hidden" name="type" id="type">
                    
                    <div class="mb-4">
                        <label for="quantity" class="form-label fw-semibold text-secondary small text-uppercase">Quantity</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-123"></i></span>
                            <input type="number" class="form-control border-start-0 ps-0" name="quantity" id="quantity" min="0" step="1" inputmode="numeric">
                        </div>
                        <div class="text-danger small mt-1" id="quantity_error"></div>
                    </div>

                    <div id="batch_no_section" class="mb-4" style="display:none;">
                        <label for="batch_no" class="form-label fw-semibold text-secondary small text-uppercase">Batch No</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-upc-scan"></i></span>
                            <input type="text" class="form-control border-start-0 ps-0" name="batch_no" id="batch_no">
                        </div>
                        <div class="text-danger small mt-1" id="batch_no_error"></div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="date" class="form-label fw-semibold text-secondary small text-uppercase">Date</label>
                         <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-calendar-date"></i></span>
                            <input type="date" class="form-control border-start-0 ps-0" name="date" id="date">
                        </div>
                        <div class="text-danger small mt-1" id="date_error"></div>
                    </div>

                    <div id="location_section" class="mb-4" style="display:none;">
                        <label for="location" class="form-label fw-semibold text-secondary small text-uppercase">Location</label>
                        <textarea class="form-control" id="location" name="location" rows="3"></textarea>
                        <div class="text-danger small mt-1" id="location_error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="remark" class="form-label fw-semibold text-secondary small text-uppercase">Remark</label>
                        <textarea class="form-control" id="remark" name="remark" rows="3"></textarea>
                        <div class="text-danger small mt-1" id="remark_error"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 d-grid pb-4 px-4">
                <button type="button" class="btn btn-primary rounded-pill shadow-sm fw-bold" id="saveBtn">
                    <i class="bi bi-check2-circle me-2"></i> Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Premium Table Styling */
    #order-items-table thead th {
        font-weight: 600;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #eaeaea;
        padding-top: 1rem;
        padding-bottom: 1rem;
        white-space: nowrap;
    }
    #order-items-table tbody td {
        padding: 0.75rem 0.5rem;
        color: #495057;
        border-bottom: 1px solid #f1f1f1;
    }
    #order-items-table tbody tr:hover {
        background-color: #f8f9fa;
        transition: background-color 0.2s ease-in-out;
    }
    #order-items-table_wrapper .dataTables_length select {
        border-radius: 0.5rem;
        padding: 0.375rem 2.25rem 0.375rem 0.75rem;
        border: 1px solid #dee2e6;
    }
    #order-items-table_wrapper .dataTables_filter input {
        border-radius: 0.5rem;
        border: 1px solid #dee2e6;
        padding: 0.375rem 0.75rem;
    }
</style>
@endsection
@push('scripts')
<script>
$(function() {
    $('#order-items-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        scrollX: true,
        paging: true,
        pageLength: 10,
        lengthChange: true,
        autoWidth: false,
        scrollCollapse: true, 
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
                    $('#batch_no_section, #location_section').toggle(type === 'production');
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
                    if (result.errors.location) {
                        $('#location_error').text(result.errors.location[0]);
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
