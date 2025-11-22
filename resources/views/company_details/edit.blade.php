@extends('layouts.app') 

@section('content')
<div class="container">
    <h2>Edit Company Details (Manufacturer)</h2>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <form action="{{ route('company.update') }}" method="POST">
        @csrf
        @method('PUT') 

        <div class="card p-4">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Company Name (Manufacturer)</label>
                    <input type="text" class="form-control" id="name" name="name" 
                           value="{{ old('name', $company->name) }}" required>
                    @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" 
                           value="{{ old('phone', $company->phone) }}">
                    @error('phone')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="pan_number" class="form-label">PAN Number</label>
                    <input type="text" class="form-control" id="pan_number" name="pan_number" 
                           value="{{ old('pan_number', $company->pan_number) }}">
                    @error('pan_number')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="gst_no" class="form-label">GST No.</label>
                    <input type="text" class="form-control" id="gst_no" name="gst_no" 
                           value="{{ old('gst_no', $company->gst_no) }}">
                    @error('gst_no')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
            </div>
            
            <hr>
            
            <h4>Address Details</h4>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="address_line1" class="form-label">Address Line 1</label>
                    <input type="text" class="form-control" id="address_line1" name="address_line1" 
                           value="{{ old('address_line1', $company->address_line1) }}">
                    @error('address_line1')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="address_line2" class="form-label">Address Line 2 (Optional)</label>
                    <input type="text" class="form-control" id="address_line2" name="address_line2" 
                           value="{{ old('address_line2', $company->address_line2) }}">
                    @error('address_line2')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="city" class="form-label">City</label>
                    <input type="text" class="form-control" id="city" name="city" 
                           value="{{ old('city', $company->city) }}" required>
                    @error('city')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="state" class="form-label">State</label>
                    <input type="text" class="form-control" id="state" name="state" 
                           value="{{ old('state', $company->state) }}" required>
                    @error('state')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="zip" class="form-label">ZIP Code</label>
                    <input type="text" class="form-control" id="zip" name="zip" 
                           value="{{ old('zip', $company->zip) }}">
                    @error('zip')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Update Company Details</button>
            </div>
        </div>
    </form>
</div>
@endsection