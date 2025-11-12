@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Designs</h4>
        <div>
            <a href="{{ route('designs.create') }}" class="btn btn-primary">+ Add Design</a>
            <a href="{{ route('designs.import.form') }}" class="btn btn-primary">Import Design</a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        <table class="table table-bordered" id="designs-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Party</th>
                    <th>Name</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection
@push('scripts')
<script>
$(function() {
    $('#designs-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('designs.data') }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'image', name: 'image', orderable: false, searchable: false },
            { data: 'party_name', name: 'party_name' },
            { data: 'name', name: 'name' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
    });
});
</script>
@endpush