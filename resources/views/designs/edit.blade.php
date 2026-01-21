@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-palette-fill me-2"></i> {{ isset($design) ? 'Edit' : 'Add New' }} Design
                    </h5>
                    <a href="{{ route('designs.index') }}" class="btn btn-outline-secondary rounded-pill shadow-sm px-4">
                        <i class="bi bi-arrow-left me-1"></i> Back to List
                    </a>
                </div>
            <div class="card-body p-4 p-md-5">
                <form action="{{ isset($design) ? route('designs.update', $design) : route('designs.store') }}" 
                      method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf
                    @if(isset($design)) @method('PUT') @endif

                    <div class="row g-4">
                        {{-- Party Selection --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small text-uppercase">Party Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-people"></i></span>
                                <select name="party_id" id="party_id" class="form-select border-start-0 bg-light select2 @error('party_id') is-invalid @enderror" required>
                                    <option value="">Select Party</option>
                                    @foreach($parties as $party)
                                        <option value="{{ $party->id }}" 
                                            {{ old('party_id', $design->party_id ?? '') == $party->id ? 'selected' : '' }}>
                                            {{ $party->party_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('party_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Design Name --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small text-uppercase">Design Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-tag"></i></span>
                                <input type="text" name="name" value="{{ old('name', $design->name ?? '') }}"
                                       class="form-control border-start-0 bg-light @error('name') is-invalid @enderror" placeholder="Enter design name" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Image Upload Section --}}
                        <div class="col-12">
                             <label class="form-label fw-semibold text-secondary small text-uppercase mb-3">Design Image</label>
                             <div class="row">
                                 <div class="col-md-8">
                                    <div class="p-4 border border-2 border-dashed rounded-3 bg-light text-center position-relative hover-shadow transition-all" style="min-height: 200px; display: flex; align-items: center; justify-content: center;">
                                        <input type="file" name="image" id="image" 
                                                class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer @error('image') is-invalid @enderror" 
                                                accept="image/*" onchange="previewImage(event)">
                                        
                                        <div class="text-center" id="uploadPlaceholder" style="{{ isset($design->image) ? 'display:none;' : '' }}">
                                            <i class="bi bi-cloud-arrow-up text-primary" style="font-size: 3rem;"></i>
                                            <p class="mb-1 fw-bold text-dark mt-2">Click or Drop Image Here</p>
                                            <small class="text-muted">Supported formats: JPG, PNG, JPEG</small>
                                        </div>

                                        <img id="imagePreview" 
                                                src="{{ isset($design->image) ? asset('storage/designs/'.$design->image) : '' }}" 
                                                class="img-fluid rounded shadow-sm" 
                                                style="max-height: 180px; {{ isset($design->image) ? '' : 'display:none;' }}">
                                    </div>
                                    @error('image') <div class="d-block text-danger mt-1 small">{{ $message }}</div> @enderror
                                 </div>
                                 <div class="col-md-4 d-flex align-items-center justify-content-center text-muted text-center p-3">
                                     <small><i class="bi bi-info-circle me-1"></i> Upload a clear image of the design pattern for better identification.</small>
                                 </div>
                             </div>
                        </div>
                    </div>

                    <div class="border-top my-4"></div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('designs.index') }}" class="btn btn-light border px-4 fw-medium">Cancel</a>
                        <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                            <i class="bi bi-check-lg me-1"></i> {{ isset($design) ? 'Update Design' : 'Save Design' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .transition-all { transition: all 0.3s ease; }
    .hover-shadow:hover { background-color: #fff !important; border-color: #0d6efd !important; box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1); }
    .cursor-pointer { cursor: pointer; }
</style>
@endsection
@push('scripts')
<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: "Select Party",
        allowClear: true,
        width: '100%'
    });
});
function previewImage(event) {
    const output = document.getElementById('imagePreview');
    const placeholder = document.getElementById('uploadPlaceholder');
    
    if (event.target.files.length > 0) {
        output.src = URL.createObjectURL(event.target.files[0]);
        output.style.display = 'block';
        placeholder.style.display = 'none';
    } else {
        output.style.display = 'none';
        // Only show placeholder if no existing image
        if (!output.src.includes('storage')) {
           placeholder.style.display = 'block';
        }
    }
}
</script>
@endpush
