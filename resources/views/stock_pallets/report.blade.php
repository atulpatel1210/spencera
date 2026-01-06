@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Stock Pallet Report</h4>
    </div>

    <div class="card-body">
        {{-- Filter Section --}}
        <div class="mb-4 p-3 bg-light rounded shadow-sm">
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
                    <div class="col-md-2">
                        <label for="{{ $id }}" class="form-label fw-semibold">{{ $filter['label'] }}</label>
                        <select id="{{ $id }}" class="form-select form-select-sm">
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
            <table class="table table-bordered table-striped table-sm align-middle text-center" id="stockPalletTable" style="width: 100%;">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
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
            </table>
        </div>
    </div>
</div>
@endsection

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
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'party_id', name: 'party_id' },
            { data: 'po', name: 'po' },
            { data: 'design', name: 'design' },
            { data: 'size', name: 'size' },
            { data: 'finish', name: 'finish' },
            { data: 'pallet_size', name: 'pallet_size' },
            { data: 'pallet_no', name: 'pallet_no' },
            { data: 'current_qty', name: 'current_qty' },
            { data: 'remark', name: 'remark' },
        ],
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
    });

    $('#party_id, #po, #design, #size, #finish, #pallet_size').on('change', function () {
        table.draw();
    });
});
</script>
@endpush