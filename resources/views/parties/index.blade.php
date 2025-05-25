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

        {{-- Datatables CSS --}}
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

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

{{-- jQuery (if not already loaded by layouts.app) --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
{{-- Datatables JS --}}
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

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
@endsection
