@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-ruler-combined me-2"></i> Edit Size
                    </h5>
                    <a href="{{ route('sizes.index') }}" class="btn btn-outline-secondary rounded-pill shadow-sm px-4">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('sizes.update', $size->id) }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary small text-uppercase">Size Name <span class="text-danger">*</span></label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-ruler-horizontal"></i></span>
                                <input type="text" 
                                       class="form-control border-start-0 bg-light shadow-sm @error('size_name') is-invalid @enderror" 
                                       name="size_name"
                                       id="size_name" 
                                       value="{{ old('size_name', $size->size_name) }}" 
                                       placeholder="Enter size (e.g., 600x600, 12x12)"
                                       required autofocus>
                                @error('size_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-text text-muted small mt-1">Specify dimension format consistently.</div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-5">
                            <a href="{{ route('sizes.index') }}" class="btn btn-light border rounded-pill px-4 fw-medium text-secondary">
                                <i class="fas fa-times-circle me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm fw-bold">
                                <i class="fas fa-save me-1"></i> Update Size
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection