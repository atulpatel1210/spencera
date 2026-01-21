@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-person-plus-fill me-2"></i> Create New Party
                    </h5>
                    <a href="{{ route('parties.index') }}" class="btn btn-outline-secondary rounded-pill shadow-sm px-4">
                        <i class="bi bi-arrow-left me-1"></i> Back to List
                    </a>
                </div>
            
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('parties.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    
                    <h6 class="fw-bold text-dark mb-4 border-start border-4 border-primary ps-3">Basic Information</h6>
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small text-uppercase">Party Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-building"></i></span>
                                <input type="text" class="form-control border-start-0 bg-light @error('party_name') is-invalid @enderror" id="party_name" name="party_name" value="{{ old('party_name') }}" placeholder="Enter party name" required autofocus>
                                @error('party_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small text-uppercase">Party Type</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-bookmarks"></i></span>
                                <select class="form-select border-start-0 bg-light @error('party_type') is-invalid @enderror" id="party_type" name="party_type">
                                    <option value="">Select Type</option>
                                    <option value="Export" {{ old('party_type') == 'Export' ? 'selected' : '' }}>Export</option>
                                    <option value="Job Work" {{ old('party_type') == 'Merchant' ? 'selected' : '' }}>Merchant</option>
                                </select>
                                @error('party_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                         <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small text-uppercase">GST Number</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-receipt"></i></span>
                                <input type="text" class="form-control border-start-0 bg-light @error('gst_no') is-invalid @enderror" id="gst_no" name="gst_no" value="{{ old('gst_no') }}" placeholder="e.g. 24ABCDE1234F1Z5">
                                @error('gst_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold text-dark mb-4 border-start border-4 border-success ps-3 mt-5">Contact Details</h6>
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small text-uppercase">Contact Person</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control border-start-0 bg-light @error('contact_person') is-invalid @enderror" id="contact_person" name="contact_person" value="{{ old('contact_person') }}" placeholder="Full Name">
                                @error('contact_person') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small text-uppercase">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control border-start-0 bg-light @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="name@example.com">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small text-uppercase">Contact No</label>
                             <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-telephone"></i></span>
                                <input type="text" class="form-control border-start-0 bg-light @error('contact_no') is-invalid @enderror" id="contact_no" name="contact_no" value="{{ old('contact_no') }}" placeholder="Landline / Other">
                                @error('contact_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small text-uppercase">Mobile No</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-phone"></i></span>
                                <input type="text" class="form-control border-start-0 bg-light @error('mobile_no') is-invalid @enderror" id="mobile_no" name="mobile_no" value="{{ old('mobile_no') }}" placeholder="Mobile Number">
                                @error('mobile_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                         <div class="col-12">
                            <label class="form-label fw-semibold text-secondary small text-uppercase">Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-geo-alt"></i></span>
                                <textarea class="form-control border-start-0 bg-light @error('address') is-invalid @enderror" id="address" name="address" rows="3" placeholder="Full Billing Address">{{ old('address') }}</textarea>
                                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5 pt-3 border-top">
                        <a href="{{ route('parties.index') }}" class="btn btn-light btn-lg px-4 border fw-medium">Cancel</a>
                        <button type="submit" class="btn btn-success btn-lg px-5 shadow fw-bold">
                            <i class="bi bi-check-lg me-2"></i> Save Party
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
