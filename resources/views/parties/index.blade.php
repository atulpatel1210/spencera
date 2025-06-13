@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Party List</h4>
        <a href="{{ route('parties.create') }}" class="btn btn-primary btn-sm">Add New Party</a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered table-hover" id="parties-table">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Party Name</th>
                    <th>Party Type</th>
                    <th>Contact Person</th>
                    <th>Email</th>     
                    <th>Contact No</th>
                    <th>Mobile No</th> 
                    <th>Address</th>   
                    <th>GST No</th>    
                    <th style="width: 150px;">Actions</th>
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
    $('#parties-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('parties.data') }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'party_name', name: 'party_name' },
            { data: 'party_type', name: 'party_type' },
            { data: 'contact_person', name: 'contact_person' },
            { data: 'email', name: 'email' },         
            { data: 'contact_no', name: 'contact_no' },
            { data: 'mobile_no', name: 'mobile_no' }, 
            { data: 'address', name: 'address' },     
            { data: 'gst_no', name: 'gst_no' },       
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
