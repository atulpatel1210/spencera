@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Finishes</h4>
        <div>
            <a href="{{ route('finishes.create') }}" class="btn btn-primary">+ Add Finish</a>
            <a href="{{ route('finishes.import.form') }}" class="btn btn-primary">Import Design</a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

        <table class="table table-bordered" id="finishes-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
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
    $('#finishes-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        scrollX: true,
        paging: true,
        pageLength: 10,
        lengthChange: true,
        autoWidth: false,
        scrollCollapse: true, 
        ajax: '{{ route('finishes.data') }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'finish_name', name: 'finish_name' },
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
