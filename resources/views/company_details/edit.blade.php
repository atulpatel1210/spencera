@extends('layouts.app') 

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-9">
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h4 class="mb-0 fw-bold text-primary display-6 fs-4">
                    <i class="fas fa-building me-2"></i> Company Details (Manufacturer)
                </h4>
            </div>
            
            <div class="card-body p-4 p-md-5">
                @if(session('success'))
                    <div class="alert alert-success rounded-3 shadow-sm border-0 mb-4 d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                        {{ session('success') }}
                    </div>
                @endif
                
                <form action="{{ route('company.update') }}" method="POST">
                    @csrf
                    @method('PUT') 

                    {{-- Company Info Section --}}
                    <div class="mb-5">
                        <h5 class="fw-bold text-dark mb-4 border-start border-4 border-primary ps-3">General Information</h5>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold text-secondary small text-uppercase">Company Name (Manufacturer) <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg shadow-sm">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-building"></i></span>
                                    <input type="text" class="form-control border-start-0 bg-white" id="name" name="name" 
                                           value="{{ old('name', $company->name) }}" required placeholder="Enter Company Name">
                                </div>
                                @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold text-secondary small text-uppercase">Phone</label>
                                <div class="input-group input-group-lg shadow-sm">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-telephone"></i></span>
                                    <input type="text" class="form-control border-start-0 bg-white" id="phone" name="phone" 
                                           value="{{ old('phone', $company->phone) }}" placeholder="Enter Phone Number">
                                </div>
                                @error('phone')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="pan_number" class="form-label fw-semibold text-secondary small text-uppercase">PAN Number</label>
                                <div class="input-group input-group-lg shadow-sm">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-card-heading"></i></span>
                                    <input type="text" class="form-control border-start-0 bg-white" id="pan_number" name="pan_number" 
                                           value="{{ old('pan_number', $company->pan_number) }}" placeholder="Enter PAN Number">
                                </div>
                                @error('pan_number')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="gst_no" class="form-label fw-semibold text-secondary small text-uppercase">GST No.</label>
                                <div class="input-group input-group-lg shadow-sm">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-receipt"></i></span>
                                    <input type="text" class="form-control border-start-0 bg-white" id="gst_no" name="gst_no" 
                                           value="{{ old('gst_no', $company->gst_no) }}" placeholder="Enter GST Number">
                                </div>
                                @error('gst_no')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-top my-5"></div>
                    
                    {{-- Address Section --}}
                    <div class="mb-4">
                        <h5 class="fw-bold text-dark mb-4 border-start border-4 border-success ps-3">Address Details</h5>
                        <div class="row g-4 bg-light p-4 rounded-4 border border-light-subtle">
                            <div class="col-md-6">
                                <label for="address_line1" class="form-label fw-semibold text-secondary small text-uppercase">Address Line 1</label>
                                <input type="text" class="form-control form-control-lg shadow-sm border-0 bg-white" id="address_line1" name="address_line1" 
                                       value="{{ old('address_line1', $company->address_line1) }}" placeholder="Street Address, P.O. Box">
                                @error('address_line1')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="address_line2" class="form-label fw-semibold text-secondary small text-uppercase">Address Line 2 (Optional)</label>
                                <input type="text" class="form-control form-control-lg shadow-sm border-0 bg-white" id="address_line2" name="address_line2" 
                                       value="{{ old('address_line2', $company->address_line2) }}" placeholder="Apartment, Suite, Unit, etc.">
                                @error('address_line2')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label for="city" class="form-label fw-semibold text-secondary small text-uppercase">City <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg shadow-sm border-0 bg-white" id="city" name="city" 
                                       value="{{ old('city', $company->city) }}" required placeholder="Enter City">
                                @error('city')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label for="state" class="form-label fw-semibold text-secondary small text-uppercase">State <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg shadow-sm border-0 bg-white" id="state" name="state" 
                                       value="{{ old('state', $company->state) }}" required placeholder="Enter State">
                                @error('state')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label for="zip" class="form-label fw-semibold text-secondary small text-uppercase">ZIP Code</label>
                                <input type="text" class="form-control form-control-lg shadow-sm border-0 bg-white" id="zip" name="zip" 
                                       value="{{ old('zip', $company->zip) }}" placeholder="Enter ZIP Code">
                                @error('zip')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end pt-4 border-top">
                        <button type="submit" class="btn btn-primary btn-lg px-5 shadow rounded-pill fw-bold">
                            <i class="fas fa-save me-2"></i> Update Details
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-scale { transition: transform 0.2s; }
    .hover-scale:hover { transform: scale(1.02); }
</style>
@endsection