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
                        <h6 class="fw-bold text-secondary text-uppercase small mb-3"><i class="bi bi-filter-circle me-2"></i>Filter Options</h6>
                        <div class="row g-3">
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
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <label for="{{ $id }}" class="form-label fw-semibold small text-secondary">{{ $filter['label'] }}</label>
                                    <select id="{{ $id }}" class="form-select border-0 shadow-sm rounded-3">
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
</div>
@endsection

@push('styles')
<style>
    /* Premium Table Styling */
    #stockPalletTable thead th {
        font-weight: 600;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #eaeaea;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
    #stockPalletTable tbody td {
        padding: 1rem 1rem;
        color: #495057;
        border-bottom: 1px solid #f1f1f1;
    }
    #stockPalletTable tbody tr:hover {
        background-color: #f8f9fa;
        transition: background-color 0.2s ease-in-out;
    }
    #stockPalletTable_wrapper .dataTables_length select {
        border-radius: 0.5rem;
        padding: 0.375rem 2.25rem 0.375rem 0.75rem;
        border: 1px solid #dee2e6;
    }
    #stockPalletTable_wrapper .dataTables_filter input {
        border-radius: 0.5rem;
        border: 1px solid #dee2e6;
        padding: 0.375rem 0.75rem;
    }
</style>
@endpush

@push('scripts')
<script>
$(function() {
    let table = $('#stockPalletTable').DataTable({
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
                d.party_id = $('#party_id').val();
                d.po = $('#po').val();
                d.design = $('#design').val();
                d.size = $('#size').val();
                d.finish = $('#finish').val();
                d.pallet_size = $('#pallet_size').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'ps-4 fw-medium text-secondary' },
            { data: 'party_id', name: 'party_id', className: 'fw-bold text-dark' },
            { data: 'po', name: 'po', className: 'text-primary' },
            { data: 'design', name: 'design' },
            { data: 'size', name: 'size' },
            { data: 'finish', name: 'finish' },
            { data: 'pallet_size', name: 'pallet_size', className: 'text-center' },
            { data: 'pallet_no', name: 'pallet_no', className: 'text-center' },
            { data: 'current_qty', name: 'current_qty', className: 'fw-bold text-success text-center' },
            { data: 'remark', name: 'remark', className: 'small text-muted text-truncate' },
        ],
        dom: '<"d-flex justify-content-between align-items-center m-3"l<"d-flex align-items-center gap-2"f>>t<"d-flex justify-content-between align-items-center m-3"ip>',
        language: {
            search: "",
            searchPlaceholder: "Search report...",
            lengthMenu: "Show _MENU_ entries"
        }
    });

    $('#party_id, #po, #design, #size, #finish, #pallet_size').on('change', function () {
        table.draw();
    });
});
</script>
@endpush