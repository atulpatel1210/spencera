@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-edit me-2"></i> Edit Design
                    </h5>
                    <a href="{{ route('designs.index') }}" class="btn btn-outline-secondary rounded-pill shadow-sm px-4">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('designs.update', $design) }}" 
                          method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            {{-- Party Selection --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase">Party Name <span class="text-danger">*</span></label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                    <select name="party_id" id="party_id" class="form-select select2 @error('party_id') is-invalid @enderror" required>
                                        <option value="">Select Party</option>
                                        @foreach($parties as $party)
                                            <option value="{{ $party->id }}" 
                                                {{ old('party_id', $design->party_id) == $party->id ? 'selected' : '' }}>
                                                {{ $party->party_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('party_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Design Name --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase">Design Name <span class="text-danger">*</span></label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                    <input type="text" name="name" value="{{ old('name', $design->name) }}"
                                           class="form-control @error('name') is-invalid @enderror" placeholder="Enter design name" required>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Image Upload Section --}}
                            <div class="col-12">
                                 <label class="form-label fw-bold small text-uppercase mb-3">Design Image</label>
                                 <div class="row g-4">
                                     <div class="col-md-7">
                                        <div class="upload-zone p-4 border border-2 border-dashed rounded-lg bg-light text-center position-relative transition-all" 
                                             style="min-height: 250px; display: flex; align-items: center; justify-content: center;">
                                            <input type="file" name="image" id="image" 
                                                    class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer @error('image') is-invalid @enderror" 
                                                    accept="image/*" onchange="previewImage(event)">
                                            
                                            <div id="uploadPlaceholder" class="text-center" style="{{ $design->image ? 'display:none;' : '' }}">
                                                <div class="mb-3">
                                                    <i class="fas fa-cloud-arrow-up text-primary" style="font-size: 3.5rem;"></i>
                                                </div>
                                                <h6 class="fw-bold text-dark">Click or Drag Image Here</h6>
                                                <p class="small text-muted mb-0">High resolution JPG or PNG recommended</p>
                                            </div>

                                            <img id="imagePreview" 
                                                    src="{{ $design->image ? asset('storage/designs/'.$design->image) : '' }}" 
                                                    class="img-fluid rounded shadow" 
                                                    style="max-height: 220px; {{ $design->image ? '' : 'display:none;' }}">
                                        </div>
                                        @error('image') <div class="d-block text-danger mt-2 small"><i class="fas fa-exclamation-circle me-1"></i> {{ $message }}</div> @enderror
                                     </div>
                                     <div class="col-md-5">
                                         @if($design->image)
                                         <div class="mb-3">
                                             <label class="form-label small text-muted text-uppercase fw-bold">Current Preview</label>
                                             <div class="p-2 border rounded-lg bg-white d-inline-block">
                                                 <img src="{{ asset('storage/designs/'.$design->image) }}" class="img-fluid rounded" style="max-height: 100px;">
                                             </div>
                                         </div>
                                         @endif
                                         <div class="card bg-light border-0">
                                             <div class="card-body">
                                                 <div class="text-muted small">
                                                     <p class="mb-2 fw-bold text-dark"><i class="fas fa-info-circle text-primary me-1"></i> Note:</p>
                                                     <p class="mb-0">Uploading a new image will replace the existing one. Keep the original design file if needed.</p>
                                                 </div>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                            </div>
                        </div>

                        <div class="border-top my-5"></div>

                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('designs.index') }}" class="btn btn-light border px-4 rounded-pill">Cancel</a>
                            <button type="submit" class="btn btn-primary px-5 shadow-sm fw-bold rounded-pill">
                                <i class="fas fa-save me-2"></i> Update Design
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .upload-zone {
        border-color: #cbd5e1 !important;
    }
    .upload-zone:hover {
        background-color: #f1f5f9 !important;
        border-color: #ef7c1b !important;
    }
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
        
        output.onload = function() {
            URL.revokeObjectURL(output.src); 
        }
    }
}
</script>
@endpush
