@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-primary text-white overflow-hidden" style="background: linear-gradient(135deg, #ef7c1b 0%, #ff9d4d 100%) !important;">
                <div class="card-body p-4 p-md-5 position-relative">
                    <div class="position-relative z-1">
                        <h2 class="fw-bold mb-2">Welcome back, {{ Auth::user()->name }}!</h2>
                        <p class="lead mb-0 opacity-75">Here is what's happening with Spencera Ceramica today.</p>
                    </div>
                    <i class="fas fa-chart-line position-absolute bottom-0 end-0 opacity-10" style="font-size: 10rem; margin-right: -2rem; margin-bottom: -2rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Stat Cards --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 bg-primary-light rounded-lg p-3 me-3">
                            <i class="fas fa-users text-primary fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-0 small text-uppercase fw-bold">Total Parties</h6>
                            <h3 class="fw-bold mb-0">{{ $stats['total_parties'] ?? 0 }}</h3>
                        </div>
                    </div>
                    <a href="{{ route('parties.index') }}" class="small text-primary text-decoration-none fw-bold">View Detail <i class="fas fa-chevron-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 bg-info-light rounded-lg p-3 me-3">
                            <i class="fas fa-shopping-cart text-info fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-0 small text-uppercase fw-bold">Active Orders</h6>
                            <h3 class="fw-bold mb-0">{{ $stats['total_orders'] ?? 0 }}</h3>
                        </div>
                    </div>
                    <a href="{{ route('orders.index') }}" class="small text-info text-decoration-none fw-bold">View Detail <i class="fas fa-chevron-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 bg-success-light rounded-lg p-3 me-3">
                            <i class="fas fa-truck-fast text-success fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-0 small text-uppercase fw-bold">Dispatched</h6>
                            <h3 class="fw-bold mb-0">{{ $stats['total_dispatches'] ?? 0 }}</h3>
                        </div>
                    </div>
                    <a href="{{ route('dispatches.index') }}" class="small text-success text-decoration-none fw-bold">View Detail <i class="fas fa-chevron-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 bg-warning-light rounded-lg p-3 me-3">
                            <i class="fas fa-boxes-packing text-warning fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-0 small text-uppercase fw-bold">Pallet Stock</h6>
                            <h3 class="fw-bold mb-0">{{ $stats['total_stock'] ?? 0 }}</h3>
                        </div>
                    </div>
                    <a href="{{ route('stock-pallets.report') }}" class="small text-warning text-decoration-none fw-bold">View Detail <i class="fas fa-chevron-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-primary-light { background-color: rgba(239, 124, 27, 0.1); }
    .bg-info-light { background-color: rgba(13, 202, 240, 0.1); }
    .bg-success-light { background-color: rgba(25, 135, 84, 0.1); }
    .bg-warning-light { background-color: rgba(255, 193, 7, 0.1); }
</style>
@endsection
