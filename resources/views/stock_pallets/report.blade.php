@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-bar-chart-line me-2"></i> Stock Pallet Report
                    </h5>
                    <button class="btn btn-muted btn-sm border rounded-pill shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#filterSection" aria-expanded="true" aria-controls="filterSection">
                        <i class="bi bi-funnel me-1"></i> Toggle Filters
                    </button>
                </div>

                <div class="card-body p-0">
                    {{-- Filter Section --}}
                    <div class="collapse show bg-light border-bottom p-4" id="filterSection">
                        <div class="row mb-3 pb-3 border-bottom">
                            <div class="col-lg-3 col-md-4">
                                <label for="report_type" class="form-label fw-bold small text-primary"><i class="bi bi-funnel-fill me-1"></i> Report Filter Type</label>
                                <select id="report_type" class="form-select border-0 shadow-sm rounded-3 fw-semibold text-primary form-select-sm">
                                    <option value="all">All Filters</option>
                                    <option value="design">Design Wise</option>
                                    <option value="size">Size Wise</option>
                                    <option value="finish">Finish Wise</option>
                                    <option value="party">Party Wise</option>
                                    <option value="po">PO Wise</option>
                                    <option value="design_size">Design + Size Wise</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-3" id="dynamicFilters">
                            @php
                                $filters = [
                                    'party_id' => ['label' => 'Party', 'data' => $partyList],
                                    'po' => ['label' => 'PO', 'data' => $poList],
                                    'design' => ['label' => 'Design', 'data' => $designList],
                                    'size' => ['label' => 'Size', 'data' => $sizeList],
                                    'finish' => ['label' => 'Finish', 'data' => $finishList],
                                    'pallet_size' => ['label' => 'Pallet Size', 'data' => $palletSizeList],
                                ];
                            @endphp

                            @foreach ($filters as $id => $filter)
                                <div class="col-lg-2 col-md-4 col-sm-6 filter-container" data-filter-id="{{ $id }}">
                                    <label for="{{ $id }}" class="form-label fw-semibold small text-secondary">{{ $filter['label'] }}</label>
                                    <select id="{{ $id }}" class="form-select border-0 shadow-sm rounded-3 form-select-sm">
                                        <option value="">All {{ $filter['label'] }}s</option>
                                        @foreach ($filter['data'] as $key => $val)
                                            <option value="{{ $key }}">{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Table Section --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="stockPalletTable" style="width: 100%;">
                            <thead class="bg-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th>Party ID</th>
                                    <th>PO</th>
                                    <th>Design</th>
                                    <th>Size</th>
                                    <th>Finish</th>
                                    <th>Pallet Size</th>
                                    <th>Pallet No</th>
                                    <th>Loose Boxes</th>
                                    <th>Current Qty</th>
                                    <th>Remark</th>
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
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light border-bottom px-4 py-3">
                    <h5 class="modal-title fw-bold text-primary d-flex align-items-center" id="detailsModalLabel">
                        <i class="bi bi-list-ul fs-4 me-2"></i> Pallet Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive p-3">
                        <table class="table table-hover align-middle mb-0" id="detailsTable" style="width: 100%;">
                            <thead class="bg-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="ps-3">#</th>
                                    <th>Party</th>
                                    <th>PO</th>
                                    <th>Design</th>
                                    <th>Size</th>
                                    <th>Finish</th>
                                    <th>Pallet Size</th>
                                    <th>Pallet No</th>
                                    <th>Loose Boxes</th>
                                    <th>Current Qty</th>
                                    <th>Remark</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Premium Table Styling */
    #stockPalletTable thead th, #detailsTable thead th {
        font-weight: 600;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #eaeaea;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
    #stockPalletTable tbody td, #detailsTable tbody td {
        padding: 1rem 1rem;
        color: #495057;
        border-bottom: 1px solid #f1f1f1;
    }
    #stockPalletTable tbody tr:hover, #detailsTable tbody tr:hover {
        background-color: #f8f9fa;
        transition: background-color 0.2s ease-in-out;
    }
    .dataTables_length select {
        border-radius: 0.5rem;
        padding: 0.375rem 2.25rem 0.375rem 0.75rem;
        border: 1px solid #dee2e6;
    }
    .dataTables_filter input {
        border-radius: 0.5rem;
        border: 1px solid #dee2e6;
        padding: 0.375rem 0.75rem;
    }
</style>
@endpush

@push('scripts')
<script>
$(function() {
    let table;
    let detailsTable;

    function initMainTable() {
        let type = $('#report_type').val();
        
        if (table) {
            table.destroy();
            $('#stockPalletTable').empty(); 
        }
        
        let thead = '';
        let cols = [];
        
        if (type === 'all') {
            thead = `
                <thead class="bg-light text-secondary small text-uppercase">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Party ID</th>
                        <th>PO</th>
                        <th>Design</th>
                        <th>Size</th>
                        <th>Finish</th>
                        <th>Pallet Size</th>
                        <th>Pallet No</th>
                        <th>Loose Boxes</th>
                        <th>Current Qty</th>
                        <th>Remark</th>
                    </tr>
                </thead>
            `;
            cols = [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'ps-4 fw-bold text-muted' },
                { data: 'party_id', name: 'party_id', className: 'fw-semibold' },
                { data: 'po_number', name: 'po' },
                { data: 'design_id', name: 'design_id' },
                { data: 'size_id', name: 'size_id' },
                { data: 'finish_id', name: 'finish_id' },
                { data: 'pallet_size', name: 'pallet_size', className: 'text-center' },
                { data: 'pallet_no', name: 'pallet_no', className: 'text-center' },
                { data: 'loos_box', name: 'loos_box', className: 'fw-bold text-warning text-center' },
                { data: 'current_qty', name: 'current_qty', className: 'fw-bold text-success text-center' },
                { data: 'remark', name: 'remark', className: 'small text-muted text-truncate', width: '10%' },
            ];
        } else {
            thead = `
                <thead class="bg-light text-secondary small text-uppercase">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Name</th>
                        <th class="text-center">Total Loose Boxes</th>
                        <th class="text-center">Total Current Qty</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
            `;
            cols = [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'ps-4 fw-bold text-muted' },
                { data: 'group_name', name: 'group_name', className: 'fw-bold text-dark' },
                { data: 'total_loos_box', name: 'total_loos_box', className: 'fw-bold text-warning text-center' },
                { data: 'total_current_qty', name: 'total_current_qty', className: 'fw-bold text-success text-center' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' },
            ];
        }
        
        $('#stockPalletTable').html(thead + '<tbody></tbody>');
        
        table = $('#stockPalletTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: false,
            scrollX: true,
            paging: true,
            pageLength: 10,
            lengthChange: true,
            autoWidth: false,
            scrollCollapse: true, 
            ajax: {
                url: '{{ route("stock-pallets.report.data") }}',
                data: function(d) {
                    d.report_type = type;
                    d.party_id = $('#party_id').val();
                    d.po = $('#po').val();
                    d.design = $('#design').val();
                    d.size = $('#size').val();
                    d.finish = $('#finish').val();
                    d.pallet_size = $('#pallet_size').val();
                }
            },
            columns: cols,
            dom: '<"d-flex justify-content-between align-items-center m-3"l<"d-flex align-items-center gap-2"f>>' +
                 '<"table-scroll-container"t>' + 
                 '<"d-flex justify-content-between align-items-center m-3"ip>',
            language: {
                search: "",
                searchPlaceholder: "Search report...",
                lengthMenu: "Show _MENU_ entries"
            }
        });
    }

    $('#party_id, #po, #design, #size, #finish, #pallet_size').on('change', function () {
        if (table) table.draw();
    });

    $('#report_type').on('change', function() {
        let type = $(this).val();
        
        // Hide all filter containers
        $('.filter-container').hide();
        
        // Reset values
        $('#party_id, #po, #design, #size, #finish, #pallet_size').val('');

        if (type === 'all') {
            $('.filter-container').show();
        } else if (type === 'design') {
            $('.filter-container[data-filter-id="design"]').show();
        } else if (type === 'size') {
            $('.filter-container[data-filter-id="size"]').show();
        } else if (type === 'finish') {
            $('.filter-container[data-filter-id="finish"]').show();
        } else if (type === 'party') {
            $('.filter-container[data-filter-id="party_id"]').show();
        } else if (type === 'po') {
            $('.filter-container[data-filter-id="po"]').show();
        } else if (type === 'design_size') {
            $('.filter-container[data-filter-id="design"]').show();
            $('.filter-container[data-filter-id="size"]').show();
        }
        
        initMainTable();
    });

    // Initialize with selected type
    $('#report_type').trigger('change');

    // Details Modal Logic
    $(document).on('click', '.view-details-btn', function() {
        let d = $(this).data();
        
        if (detailsTable) {
            detailsTable.destroy();
        }
        
        detailsTable = $('#detailsTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: false,
            scrollX: true,
            paging: true,
            pageLength: 10,
            ajax: {
                url: '{{ route("stock-pallets.report.data") }}',
                data: function(req) {
                    req.report_type = 'all';
                    
                    // Always include main filters too, so the details match the grouped filtered view
                    let mainParty = $('#party_id').val();
                    let mainPo = $('#po').val();
                    let mainDesign = $('#design').val();
                    let mainSize = $('#size').val();
                    let mainFinish = $('#finish').val();
                    let mainPalletSize = $('#pallet_size').val();

                    if (mainParty) req.party_id = mainParty;
                    if (mainPo) req.po = mainPo;
                    if (mainDesign) req.design = mainDesign;
                    if (mainSize) req.size = mainSize;
                    if (mainFinish) req.finish = mainFinish;
                    if (mainPalletSize) req.pallet_size = mainPalletSize;
                    
                    // Override with the clicked row's data
                    if (d.design) req.design = d.design;
                    if (d.size) req.size = d.size;
                    if (d.finish) req.finish = d.finish;
                    if (d.party) req.party_id = d.party;
                    if (d.po) req.po = d.po;
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'ps-3 fw-bold text-muted' },
                { data: 'party_id', name: 'party_id', className: 'fw-semibold' },
                { data: 'po_number', name: 'po' },
                { data: 'design_id', name: 'design_id' },
                { data: 'size_id', name: 'size_id' },
                { data: 'finish_id', name: 'finish_id' },
                { data: 'pallet_size', name: 'pallet_size', className: 'text-center' },
                { data: 'pallet_no', name: 'pallet_no', className: 'text-center' },
                { data: 'loos_box', name: 'loos_box', className: 'fw-bold text-warning text-center' },
                { data: 'current_qty', name: 'current_qty', className: 'fw-bold text-success text-center' },
                { data: 'remark', name: 'remark', className: 'small text-muted text-truncate', width: '10%' }
            ],
            dom: '<"d-flex justify-content-between align-items-center mb-3"l<"d-flex align-items-center gap-2"f>>' +
                 '<"table-scroll-container"t>' + 
                 '<"d-flex justify-content-between align-items-center mt-3"ip>',
            language: {
                search: "",
                searchPlaceholder: "Search details...",
            }
        });
        
        $('#detailsModal').modal('show');
    });
});
</script>
@endpush