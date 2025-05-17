@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Edit Size</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('sizes.update', $size->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="size_name" class="form-label">Size Name</label>
                <input type="text" name="size_name" class="form-control @error('size_name') is-invalid @enderror" id="size_name" value="{{ old('size_name', $size->size_name) }}">
                @error('size_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">Update</button>
            <a href="{{ route('sizes.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>
@endsection
