@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header"><h4>{{ isset($design) ? 'Edit' : 'Add' }} Design</h4></div>
    <div class="card-body">
        <form action="{{ isset($design) ? route('designs.update', $design) : route('designs.store') }}" method="POST">
            @csrf
            @if(isset($design)) @method('PUT') @endif

            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" value="{{ old('name', $design->name ?? '') }}"
                       class="form-control @error('name') is-invalid @enderror" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <button class="btn btn-success">{{ isset($design) ? 'Update' : 'Create' }}</button>
            <a href="{{ route('designs.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>
@endsection
