@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Import Sizes</h4>
    </div>
    <div class="card-body">
        {{-- Success and Error Summary --}}
        @if(session('successCount') || session('errors'))
        <div class="alert alert-info">
            <strong>Import Summary:</strong><br>
            {{ session('successCount') ?? 0 }} records imported successfully.<br>
            {{ count(session('errors') ?? []) }} errors found.
        </div>
        @endif

        {{-- Errors List --}}
        @if(session('errors'))
        <div class="alert alert-danger">
            <strong>Import Errors:</strong>
            <ul>
                @foreach(session('errors') as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Import Form --}}
        <form action="{{ route('sizes.import') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="dragdrop-div">
                <input type="file" name="import_file" id="import_file" style="display:none;" onchange="document.getElementById('file-name').innerText = this.files[0].name;">
                <label for="import_file" style="cursor: pointer;">
                    <i class="fas fa-cloud-upload-alt fa-3x text-gray-600 mb-2"></i><br>
                    <strong id="file-name">Choose a file</strong> or drag it here.
                </label>
            </div>

            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary">Import</button>
                <a href="{{ route('sizes.download-sample') }}" class="btn btn-secondary">Download Sample File (.xlsx)</a>
            </div>
        </form>
    </div>
</div>
@endsection