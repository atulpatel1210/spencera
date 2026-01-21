@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-paint-bucket me-2"></i> {{ isset($finish) ? 'Edit' : 'Add' }} Finish
                    </h5>
                    <a href="{{ route('finishes.index') }}" class="btn btn-outline-secondary rounded-pill shadow-sm px-4">
                        <i class="bi bi-arrow-left me-1"></i> Back to List
                    </a>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ isset($finish) ? route('finishes.update', $finish) : route('finishes.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        @if(isset($finish)) @method('PUT') @endif

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary small text-uppercase">Finish Name <span class="text-danger">*</span></label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-tag"></i></span>
                                <input type="text" 
                                       class="form-control border-start-0 bg-light shadow-sm @error('finish_name') is-invalid @enderror" 
                                       name="finish_name" 
                                       value="{{ old('finish_name', $finish->finish_name ?? '') }}" 
                                       placeholder="Enter finish name (e.g., Glossy, Matte)"
                                       required autofocus>
                                @error('finish_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-text text-muted small mt-1">Provide a unique name for the finish type.</div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-5">
                            <a href="{{ route('finishes.index') }}" class="btn btn-light border rounded-pill px-4 fw-medium text-secondary hover-bg-gray">
                                <i class="bi bi-x-circle me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm fw-bold transition-all">
                                <i class="bi bi-save me-1"></i> {{ isset($finish) ? 'Update' : 'Save Finish' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
