@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header"><h4>{{ isset($finish) ? 'Edit' : 'Add' }} Finish</h4></div>
    <div class="card-body">
        <form action="{{ isset($finish) ? route('finishes.update', $finish) : route('finishes.store') }}" method="POST">
            @csrf
            @if(isset($finish)) @method('PUT') @endif

            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="finish_name" value="{{ old('finish_name', $finish->finish_name ?? '') }}"
                       class="form-control @error('finish_name') is-invalid @enderror" required>
                @error('finish_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <button class="btn btn-success">{{ isset($finish) ? 'Update' : 'Create' }}</button>
            <a href="{{ route('finishes.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>
@endsection
