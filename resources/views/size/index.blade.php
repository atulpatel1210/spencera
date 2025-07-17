@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Size List</h4>
        <div>
            <a href="{{ route('sizes.create') }}" class="btn btn-primary">+ Add Size</a>
            <a href="{{ route('sizes.import.form') }}" class="btn btn-primary">Import Sizes</a>
        </div>
    </div>

    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered" id="sizes-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Size Name</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody>
                {{-- Data will be loaded by Yajra Datatables --}}
            </tbody>
        </table>
    </div>
</div>
@endsection
@push('scripts')
<script>
$(function() {
    $('#sizes-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('sizes.data') }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'size_name', name: 'size_name' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
});
</script>
@endpush
