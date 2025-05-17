@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Edit Pallet</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('pallet.update', $pallet->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="pallet_name" class="form-label">Pallet Name</label>
                <input type="text" name="pallet_name" class="form-control @error('pallet_name') is-invalid @enderror" id="pallet_name" value="{{ old('pallet_name', $pallet->pallet_name) }}">
                @error('pallet_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">Update</button>
            <a href="{{ route('pallet.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>
@endsection
