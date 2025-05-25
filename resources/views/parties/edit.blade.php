@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Edit Party</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('parties.update', $party->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6 mb-3">
                    <label for="party_name" class="form-label">Party Name</label>
                    <input type="text" class="form-control @error('party_name') is-invalid @enderror" id="party_name" name="party_name" value="{{ old('party_name', $party->party_name) }}" required autofocus>
                    @error('party_name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="party_type" class="form-label">Party Type</label>
                    <input type="text" class="form-control @error('party_type') is-invalid @enderror" id="party_type" name="party_type" value="{{ old('party_type', $party->party_type) }}">
                    @error('party_type')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="contact_person" class="form-label">Contact Person</label>
                    <input type="text" class="form-control @error('contact_person') is-invalid @enderror" id="contact_person" name="contact_person" value="{{ old('contact_person', $party->contact_person) }}">
                    @error('contact_person')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $party->email) }}">
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="contact_no" class="form-label">Contact No</label>
                    <input type="text" class="form-control @error('contact_no') is-invalid @enderror" id="contact_no" name="contact_no" value="{{ old('contact_no', $party->contact_no) }}">
                    @error('contact_no')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="mobile_no" class="form-label">Mobile No</label>
                    <input type="text" class="form-control @error('mobile_no') is-invalid @enderror" id="mobile_no" name="mobile_no" value="{{ old('mobile_no', $party->mobile_no) }}">
                    @error('mobile_no')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-12 mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $party->address) }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="gst_no" class="form-label">GST No</label>
                    <input type="text" class="form-control @error('gst_no') is-invalid @enderror" id="gst_no" name="gst_no" value="{{ old('gst_no', $party->gst_no) }}">
                    @error('gst_no')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <button type="submit" class="btn btn-success mt-3">Update Party</button>
            <a href="{{ route('parties.index') }}" class="btn btn-secondary mt-3">Back to List</a>
        </form>
    </div>
</div>
@endsection
