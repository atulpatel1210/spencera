@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-user-plus me-2"></i> Create New User
                    </h5>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary rounded-pill shadow-sm px-4">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('users.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        
                        <h6 class="fw-bold text-dark mb-4 border-start border-4 border-primary ps-3">Account Information</h6>
                        
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary small text-uppercase">Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0 bg-white"><i class="fas fa-user text-muted"></i></span>
                                    <input type="text" name="name" class="form-control border-start-0 @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Enter full name" required>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary small text-uppercase">Email <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0 bg-white"><i class="fas fa-envelope text-muted"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0 @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Enter email address" required>
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary small text-uppercase">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0 bg-white"><i class="fas fa-lock text-muted"></i></span>
                                    <input type="password" name="password" class="form-control border-start-0 @error('password') is-invalid @enderror" placeholder="Create a password" required>
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <h6 class="fw-bold text-dark mb-4 border-start border-4 border-success ps-3 mt-5">Role Assignment</h6>
                        <div class="row g-4 mb-4">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold text-secondary small text-uppercase">Assign Roles <span class="text-danger">*</span></label>
                                <select name="roles[]" class="form-select select2 @error('roles') is-invalid @enderror" multiple required style="width: 100%;">
                                    @foreach($roles as $role)
                                        <option value="{{ $role }}">{{ $role }}</option>
                                    @endforeach
                                </select>
                                @error('roles') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5 pt-3 border-top">
                            <a href="{{ route('users.index') }}" class="btn btn-light btn-lg px-4 border fw-medium">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-lg px-5 shadow fw-bold">
                                <i class="fas fa-save me-2"></i> Save User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap-5',
        placeholder: "Select an option",
        allowClear: true
    });
});
</script>
@endpush
