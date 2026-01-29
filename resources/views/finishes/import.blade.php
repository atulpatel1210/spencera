@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-file-import me-2"></i> Import Finishes
                    </h5>
                    <a href="{{ route('finishes.index') }}" class="btn btn-outline-secondary rounded-pill shadow-sm px-4">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    {{-- Success and Error Summary --}}
                    @if(session('successCount') || session('errors'))
                    <div class="alert alert-info border-0 shadow-sm rounded-lg mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-info-circle me-2 fs-5"></i>
                            <strong class="fs-6">Import Summary</strong>
                        </div>
                        <div class="ps-4">
                            <div class="text-success fw-semibold">{{ session('successCount') ?? 0 }} records imported successfully.</div>
                            <div class="text-danger fw-semibold">{{ count(session('errors') ?? []) }} errors found.</div>
                        </div>
                    </div>
                    @endif

                    {{-- Errors List --}}
                    @if(session('errors'))
                    <div class="alert alert-danger border-0 shadow-sm rounded-lg mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-exclamation-triangle me-2 fs-5"></i>
                            <strong class="fs-6">Import Errors</strong>
                        </div>
                        <ul class="mb-0 small ps-4">
                            @foreach(session('errors') as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    {{-- Import Form --}}
                    <form action="{{ route('finishes.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="dragdrop-div mb-4 p-5 text-center border-2 border-dashed rounded-4 bg-light" style="cursor: pointer;" onclick="document.getElementById('import_file').click();">
                            <input type="file" name="import_file" id="import_file" style="display:none;" onchange="document.getElementById('file-name').innerText = this.files[0].name;">
                            <div class="py-3">
                                <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                                <h4 class="fw-bold mb-2">Upload Excel File</h4>
                                <p class="text-muted mb-0" id="file-name">Drag and drop your file here or click to browse</p>
                            </div>
                        </div>

                        <div class="d-grid gap-3 d-md-flex justify-content-md-center mt-5 pt-3">
                            <a href="{{ route('finishes.download-sample') }}" class="btn btn-outline-secondary btn-lg px-4 border fw-medium rounded-pill">
                                <i class="fas fa-download me-2"></i> Download Sample
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg px-5 shadow fw-bold rounded-pill">
                                <i class="fas fa-check-circle me-2"></i> Confirm Import
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection