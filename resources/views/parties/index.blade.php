@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-people-fill me-2"></i> Party Management
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('parties.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                            <i class="bi bi-plus-lg me-1"></i> Add Party
                        </a>
                        <a href="{{ route('parties.import.form') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
                            <i class="bi bi-upload me-1"></i> Import
                        </a>
                    </div>
                </div>

            <div class="card-body p-0">
                @if(session('success'))
                    <div class="alert alert-success m-3 rounded-3 border-0 shadow-sm d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="parties-table">
                        <thead class="bg-light text-secondary small text-uppercase">
                            <tr>
                                <th class="ps-4" width="5%">#</th>
                                <th>Party Name</th>
                                <th>Type</th>
                                <th>Contact Person</th>
                                <th>Email</th>     
                                <th>Contact No</th>
                                <th>Mobile No</th> 
                                <th>Address</th>   
                                <th>GST No</th>    
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(function() {
    $('#parties-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pagingType: "simple_numbers",
        pageLength: 10,
        dom: '<"d-flex justify-content-between align-items-center mx-3 mt-3"l<"ms-auto"f>>t<"d-flex justify-content-between align-items-center mx-3 mb-3"ip>',
        language: {
            search: "",
            searchPlaceholder: "Search parties...",
            lengthMenu: "Show _MENU_"
        },
        ajax: '{{ route('parties.data') }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'ps-4 fw-bold text-muted' },
            { data: 'party_name', name: 'party_name', className: 'fw-semibold text-dark' },
            { data: 'party_type', name: 'party_type', 
              render: function(data) {
                  let badgeClass = data === 'Export' ? 'bg-info text-dark' : 'bg-primary';
                  return `<span class="badge ${badgeClass} rounded-pill fw-normal px-2">${data}</span>`;
              }
            },
            { data: 'contact_person', name: 'contact_person' },
            { data: 'email', name: 'email' },         
            { data: 'contact_no', name: 'contact_no' },
            { data: 'mobile_no', name: 'mobile_no' }, 
            { data: 'address', name: 'address' },     
            { data: 'gst_no', name: 'gst_no' },       
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end pe-4' }
        ]
    });
});
</script>
@endpush
