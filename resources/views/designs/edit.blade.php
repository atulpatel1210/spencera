@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header"><h4>{{ isset($design) ? 'Edit' : 'Add' }} Design</h4></div>
    <div class="card-body">
        <form action="{{ isset($design) ? route('designs.update', $design) : route('designs.store') }}" 
              method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($design)) @method('PUT') @endif

            {{-- Party --}}
            <div class="mb-3">
                <label>Party</label>
                <select name="party_id" id="party_id" class="form-control select2 @error('party_id') is-invalid @enderror" required>
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

            {{-- Design Name --}}
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" value="{{ old('name', $design->name ?? '') }}"
                       class="form-control @error('name') is-invalid @enderror" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Image Upload --}}
            <div class="col-md-6">
                <label class="form-label fw-semibold">Upload Design Image</label>
                <div class="border rounded-3 p-3 text-center bg-light" style="height: 210px;">
                    <label for="image" class="d-block mb-2 fw-medium text-secondary">
                        <i class="bi bi-cloud-arrow-up fs-3 text-primary"></i><br>
                        <small>Click or drop your image here</small>
                    </label>
                    <input type="file" name="image" id="image" 
                            class="form-control d-none @error('image') is-invalid @enderror" 
                            accept="image/*" onchange="previewImage(event)">
                    @error('image') 
                        <div class="invalid-feedback d-block">{{ $message }}</div> 
                    @enderror
                    <img id="imagePreview" 
                            src="{{ isset($design->image) ? asset('storage/designs/'.$design->image) : '' }}" 
                            class="img-thumbnail mt-3 shadow-sm" 
                            style="max-width: 150px; max-height: 150px; {{ isset($design->image) ? '' : 'display:none;' }}">
                </div>
            </div>

            <button class="btn btn-success">{{ isset($design) ? 'Update' : 'Create' }}</button>
            <a href="{{ route('designs.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>
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
    output.src = URL.createObjectURL(event.target.files[0]);
    output.style.display = 'block';
}
</script>
@endpush
