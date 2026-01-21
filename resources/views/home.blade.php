@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-5 align-items-center">
        <div class="col-md-6">
            <h1 class="display-5 fw-bold text-primary mb-0">Welcome Back!</h1>
            <p class="lead text-muted">Here's an overview of your application modules.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <span class="text-secondary small">{{ date('l, F j, Y') }}</span>
        </div>
    </div>

    <div class="row g-4">
        {{-- Master Data Section --}}
        <div class="col-12">
            <h6 class="text-uppercase text-secondary fw-bold small mb-3 letter-spacing-1 border-bottom pb-2">Master Data</h6>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('parties.index') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0 hover-elevate bg-white rounded-4">
                    <div class="card-body p-4 d-flex align-items-center">
                        <div class="icon-square bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3">
                            <i class="bi bi-people-fill fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Parties</h6>
                            <p class="text-muted small mb-0">Manage customer & supplier details</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('designs.index') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0 hover-elevate bg-white rounded-4">
                    <div class="card-body p-4 d-flex align-items-center">
                        <div class="icon-square bg-success bg-opacity-10 text-success rounded-circle p-3 me-3">
                            <i class="bi bi-palette-fill fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Designs</h6>
                            <p class="text-muted small mb-0">Product design catalog</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-3">
            <a href="{{ route('finishes.index') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0 hover-elevate bg-white rounded-4">
                    <div class="card-body p-4 d-flex align-items-center">
                        <div class="icon-square bg-info bg-opacity-10 text-info rounded-circle p-3 me-3">
                            <i class="bi bi-brush-fill fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Finishes</h6>
                            <p class="text-muted small mb-0">Surface finish options</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-3">
            <a href="{{ route('sizes.index') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0 hover-elevate bg-white rounded-4">
                    <div class="card-body p-4 d-flex align-items-center">
                        <div class="icon-square bg-warning bg-opacity-10 text-warning rounded-circle p-3 me-3">
                            <i class="bi bi-aspect-ratio-fill fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Sizes</h6>
                            <p class="text-muted small mb-0">Product dimensions</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Operations Section --}}
        <div class="col-12 mt-4">
             <h6 class="text-uppercase text-secondary fw-bold small mb-3 letter-spacing-1 border-bottom pb-2">Operations</h6>
        </div>

        <div class="col-md-6 col-lg-3">
            <a href="{{ route('orders.index') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0 hover-elevate bg-white rounded-4">
                    <div class="card-body p-4 d-flex align-items-center">
                        <div class="icon-square bg-danger bg-opacity-10 text-danger rounded-circle p-3 me-3">
                            <i class="bi bi-cart-fill fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Purchase Orders</h6>
                            <p class="text-muted small mb-0">Manage orders & planning</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-3">
            <a href="{{ route('purchase_order_item.list') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0 hover-elevate bg-white rounded-4">
                     <div class="card-body p-4 d-flex align-items-center">
                        <div class="icon-square bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3">
                            <i class="bi bi-list-check fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Prod. & Planning</h6>
                            <p class="text-muted small mb-0">Track item status & production</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-3">
             <a href="{{ route('purchase_order_pallets.index') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0 hover-elevate bg-white rounded-4">
                    <div class="card-body p-4 d-flex align-items-center">
                        <div class="icon-square bg-secondary bg-opacity-10 text-secondary rounded-circle p-3 me-3">
                            <i class="bi bi-box-seam-fill fs-4"></i>
                        </div>
                        <div>
                             <h6 class="fw-bold text-dark mb-1">Pallet Packing</h6>
                            <p class="text-muted small mb-0">Packing configuration</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-3">
            <a href="{{ route('dispatches.index') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0 hover-elevate bg-white rounded-4">
                    <div class="card-body p-4 d-flex align-items-center">
                        <div class="icon-square bg-dark bg-opacity-10 text-dark rounded-circle p-3 me-3">
                            <i class="bi bi-truck-front-fill fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Dispatch</h6>
                            <p class="text-muted small mb-0">Shipment management</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Reports Section --}}
        <div class="col-12 mt-4">
            <h6 class="text-uppercase text-secondary fw-bold small mb-3 letter-spacing-1 border-bottom pb-2">Reports</h6>
        </div>

        <div class="col-md-6 col-lg-3">
            <a href="{{ route('stock-pallets.report') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0 hover-elevate bg-white rounded-4">
                    <div class="card-body p-4 d-flex align-items-center">
                        <div class="icon-square bg-info bg-opacity-10 text-info rounded-circle p-3 me-3">
                            <i class="bi bi-bar-chart-line-fill fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Stock Pallet Report</h6>
                            <p class="text-muted small mb-0">Inventory overview</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<style>
    .hover-elevate { transition: transform 0.2s, box-shadow 0.2s; }
    .hover-elevate:hover { transform: translateY(-5px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
    .icon-square { width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; }
    .letter-spacing-1 { letter-spacing: 1px; }
</style>
@endsection
