@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-shield-alt me-2"></i> Create New Role
                    </h5>
                    <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary rounded-pill shadow-sm px-4">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('roles.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        
                        <h6 class="fw-bold text-dark mb-4 border-start border-4 border-primary ps-3">Role Details</h6>
                        
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary small text-uppercase">Role Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0 bg-white"><i class="fas fa-tag text-muted"></i></span>
                                    <input type="text" name="name" class="form-control border-start-0 @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Enter role name" required>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <h6 class="fw-bold text-dark mb-4 border-start border-4 border-success ps-3 mt-5">Assign Permissions</h6>
                        <div class="mb-4">
                            @foreach($permissions as $moduleName => $modulePermissions)
                                <div class="p-4 rounded-3 mb-4 border border-light shadow-sm">
                                    <div class="d-flex align-items-center mb-3">
                                        <h6 class="text-primary mb-0 fw-bold me-3 text-capitalize"><i class="fas fa-layer-group me-2"></i>{{ $moduleName ?: 'General' }}</h6>
                                        <hr class="flex-grow-1 opacity-25 m-0">
                                    </div>
                                    <div class="row g-3">
                                        @foreach($modulePermissions as $permission)
                                        <div class="col-md-3 col-sm-6">
                                            <div class="form-check custom-checkbox">
                                                <input class="form-check-input mt-1" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="perm_{{ $permission->id }}">
                                                <label class="form-check-label user-select-none text-dark fw-medium" for="perm_{{ $permission->id }}">
                                                    {{ ucfirst(explode(' ', $permission->name)[0]) }}
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5 pt-3 border-top">
                            <a href="{{ route('roles.index') }}" class="btn btn-light btn-lg px-4 border fw-medium">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-lg px-5 shadow fw-bold">
                                <i class="fas fa-save me-2"></i> Save Role
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.custom-checkbox .form-check-input {
    width: 1.2em;
    height: 1.2em;
    border-color: #cbd5e1;
    cursor: pointer;
}
.custom-checkbox .form-check-input:checked {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
}
.custom-checkbox .form-check-label {
    padding-left: 0.3em;
    cursor: pointer;
    padding-top: 0.1rem;
}
</style>
@endsection
