@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="card-title text-primary">
                        <i class="fas fa-users me-2"></i> Party Management
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('parties.create') }}" class="btn btn-primary px-4">
                            <i class="fas fa-plus me-1"></i> Add Party
                        </a>
                        <a href="{{ route('parties.import.form') }}" class="btn btn-light border px-4">
                            <i class="fas fa-file-import me-1"></i> Import
                        </a>
                    </div>
                </div>

                <div class="card-body p-0">
                    @if(session('success'))
                        <div class="alert alert-success m-3 rounded-lg border-0 shadow-sm d-flex align-items-center">
                            <i class="fas fa-check-circle me-2 fs-5"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="parties-table">
                            <thead>
                                <tr>
                                    <th class="ps-4">#</th>
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
</div>
@endsection

@push('scripts')
<script>
$(function() {
    $('#parties-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        pageLength: 10,
        dom: '<"d-flex justify-content-between align-items-center mx-3 mt-3"l<"ms-auto"f>>' +
             '<"table-scroll-container"t>' + 
             '<"d-flex justify-content-between align-items-center mx-3 mb-3"ip>',
        language: {
            search: "",
            searchPlaceholder: "Search parties...",
            lengthMenu: "Show _MENU_ entries"
        },
        ajax: '{{ route('parties.data') }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'ps-4 fw-bold text-muted' },
            { data: 'party_name', name: 'party_name', className: 'fw-semibold text-dark' },
            { data: 'party_type', name: 'party_type', 
              render: function(data) {
                  let badgeClass = data === 'Export' ? 'bg-info text-dark' : 'bg-primary';
                  return `<span class="badge ${badgeClass} fw-normal px-2">${data}</span>`;
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
