@extends('layouts.app')

@section('content')
<div class="container-fluid px-md-4 py-4">
    {{-- High-End Header Section --}}
    <div class="row mb-5 align-items-center">
        <div class="col-md-7">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 animate__animated animate__fadeIn">
                    <div class="rounded-circle p-1 bg-gradient-orange shadow-lg" style="width: 85px; height: 85px;">
                        <div class="bg-dark rounded-circle w-100 h-100 d-flex align-items-center justify-content-center border border-3 border-white/20">
                            <span class="text-white fw-bold display-6">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                    </div>
                </div>
                <div class="ms-4">
                    <span class="badge bg-primary/10 text-primary px-3 py-1 rounded-pill small fw-bold mb-2">MANAGEMENT PORTAL</span>
                    <h1 class="display-5 fw-extrabold text-dark mb-1 tracking-tight">Hello, {{ Auth::user()->name }} <span class="wave">👋</span></h1>
                    <p class="text-muted d-flex align-items-center mb-0">
                        <i class="fas fa-calendar-check me-2 text-primary"></i> <span class="fw-semibold text-gray-700">{{ date('l, d F Y') }}</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-5 text-md-end mt-4 mt-md-0">
            <div class="p-2 bg-white rounded-4 shadow-sm border border-gray-100 d-inline-block">
                <div class="d-flex gap-2">
                    <a href="{{ route('orders.index') }}" class="btn btn-primary rounded-3 px-4 shadow-primary d-flex align-items-center transition-all hover-translate-y">
                        <i class="fas fa-cart-plus me-2"></i> New Order
                    </a>
                    <button class="btn btn-outline-light text-dark border-gray-100 bg-gray-50 rounded-3 px-3">
                        <i class="fas fa-sliders"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Stats Grid --}}
    <div class="row g-4 mb-5">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card dash-card h-100 p-4 border-0 shadow-sm transition-all hover-lift">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div class="icon-box bg-primary-light">
                        <i class="fas fa-users-viewfinder text-primary fs-3"></i>
                    </div>
                    <div class="text-end">
                        <span class="text-success small fw-bold"><i class="fas fa-arrow-up me-1"></i>Live</span>
                        <div class="text-muted smaller fw-bold mt-1 uppercase tracking-tighter">Database</div>
                    </div>
                </div>
                <h2 class="stat-value">{{ $stats['total_parties'] ?? 0 }}</h2>
                <p class="stat-label">Total Partners</p>
                <div class="progress mt-4 bg-gray-100" style="height: 5px;">
                    <div class="progress-bar bg-primary rounded-pill" style="width: 100%;"></div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card dash-card h-100 p-4 border-0 shadow-sm transition-all hover-lift">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div class="icon-box bg-info-light">
                        <i class="fas fa-receipt text-info fs-3"></i>
                    </div>
                    <div class="text-end">
                        <span class="text-info small fw-bold">Recent</span>
                        <div class="text-muted smaller fw-bold mt-1 uppercase tracking-tighter">{{ $stats['recent_orders'] ?? 0 }} IN 7D</div>
                    </div>
                </div>
                <h2 class="stat-value">{{ $stats['total_orders'] ?? 0 }}</h2>
                <p class="stat-label">Purchase Orders</p>
                <div class="progress mt-4 bg-gray-100" style="height: 5px;">
                    <div class="progress-bar bg-info rounded-pill" style="width: 100%;"></div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card dash-card h-100 p-4 border-0 shadow-sm transition-all hover-lift">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div class="icon-box bg-success-light">
                        <i class="fas fa-truck-ramp-box text-success fs-3"></i>
                    </div>
                    <div class="text-end">
                        <span class="text-success small fw-bold">Verified</span>
                        <div class="text-muted smaller fw-bold mt-1 uppercase tracking-tighter">Operations</div>
                    </div>
                </div>
                <h2 class="stat-value">{{ $stats['total_dispatches'] ?? 0 }}</h2>
                <p class="stat-label">Total Dispatches</p>
                <div class="progress mt-4 bg-gray-100" style="height: 5px;">
                    <div class="progress-bar bg-success rounded-pill" style="width: 100%;"></div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card dash-card h-100 p-4 border-0 shadow-sm transition-all hover-lift">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div class="icon-box bg-warning-light">
                        <i class="fas fa-boxes-stacked text-warning fs-3"></i>
                    </div>
                    <div class="text-end">
                        <span class="text-warning small fw-bold">Inventory</span>
                        <div class="text-muted smaller fw-bold mt-1 uppercase tracking-tighter">Real-time</div>
                    </div>
                </div>
                <h2 class="stat-value">{{ $stats['total_stock'] ?? 0 }}</h2>
                <p class="stat-label">Stock Pallets</p>
                <div class="progress mt-4 bg-gray-100" style="height: 5px;">
                    <div class="progress-bar bg-warning rounded-pill" style="width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Section: Recent Operations & Data Metrics --}}
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card dash-card border-0 p-4 h-100 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-extrabold mb-0 d-flex align-items-center text-dark">
                        <i class="fas fa-bolt-lightning me-3 text-primary"></i> Recent Dispatches
                    </h5>
                    <a href="{{ route('dispatches.index') }}" class="btn btn-light btn-sm rounded-pill px-3 fw-bold">View History</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="border-0 px-3 py-3 text-uppercase smaller fw-bold text-gray-400">Date</th>
                                <th class="border-0 px-3 py-3 text-uppercase smaller fw-bold text-gray-400">Ref No</th>
                                <th class="border-0 px-3 py-3 text-uppercase smaller fw-bold text-gray-400">Partner</th>
                                <th class="border-0 px-3 py-3 text-uppercase smaller fw-bold text-gray-400 text-end">Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stats['latest_dispatches'] as $dispatch)
                            <tr>
                                <td class="px-3 py-3"><span class="text-muted small fw-bold">{{ \Carbon\Carbon::parse($dispatch->dispatch_date)->format('d M, Y') }}</span></td>
                                <td class="px-3 py-3"><span class="fw-bold">{{ $dispatch->po }}</span></td>
                                <td class="px-3 py-3"><div class="fw-semibold">{{ $dispatch->party->party_name ?? 'N/A' }}</div></td>
                                <td class="px-3 py-3 text-end"><span class="badge bg-success-light text-success rounded-pill px-3 fw-bold">{{ $dispatch->dispatched_qty }} Units</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="fas fa-folder-open fs-2 mb-3 d-block opacity-20"></i>
                                    No recent dispatches found
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- REAL DATA Cloud Connectivity Section --}}
        <div class="col-lg-4">
            <div class="card welcome-banner p-4 border-0 h-100 shadow-lg">
                <div class="position-relative z-index-1 text-white">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h5 class="fw-extrabold mb-0">Business Intelligence</h5>
                        <div class="bg-success rounded-circle pulse-success" style="width: 10px; height: 10px;"></div>
                    </div>
                    
                    <p class="text-white/70 small mb-4">Live analytics pulled directly from your operational database.</p>
                    
                    <div class="space-y-4">
                        <div class="metric-item mb-4">
                            <div class="d-flex justify-content-between smaller fw-bold mb-2">
                                <span class="text-white opacity-80 uppercase tracking-wider">Total Inventory Items</span>
                                <span class="text-white fw-bold">{{ $stats['total_boxes_in_stock'] }} Boxes</span>
                            </div>
                            <div class="progress bg-white/10" style="height: 6px;">
                                <div class="progress-bar bg-white rounded-pill" style="width: 100%;"></div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="p-3 rounded-4 bg-white/5 border border-white/10">
                                <div class="row align-items-center text-center">
                                    <div class="col-6 border-end border-white/10">
                                        <div class="smaller text-white-50 fw-bold uppercase">Design Library</div>
                                        <div class="fs-4 fw-extrabold">{{ $stats['total_designs'] }}</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="smaller text-white-50 fw-bold uppercase">Recent Growth</div>
                                        <div class="fs-4 fw-extrabold text-success">+{{ $stats['recent_orders'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="metric-item mb-4">
                            <div class="d-flex justify-content-between smaller fw-bold mb-2">
                                <span class="text-white opacity-80 uppercase tracking-wider">Operational Load</span>
                                <span class="text-white/70">Stable</span>
                            </div>
                            <div class="progress bg-white/10" style="height: 6px;">
                                <div class="progress-bar bg-success rounded-pill" style="width: 35%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <i class="fas fa-rocket position-absolute bottom-0 end-0 text-white/5" style="font-size: 10rem; margin-right: -2rem; margin-bottom: -2rem;"></i>
            </div>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap');
    
    body { font-family: 'Plus Jakarta Sans', sans-serif !important; background-color: #f4f7fa; }
    .fw-extrabold { font-weight: 800; }
    .tracking-tight { letter-spacing: -0.04em; }
    .bg-white\/10 { background-color: rgba(255, 255, 255, 0.1); }
    .bg-white\/5 { background-color: rgba(255, 255, 255, 0.05); }
    .bg-gray-50 { background-color: #f9fafb; }
    .bg-primary-light { background-color: rgba(239, 124, 27, 0.08); }
    .bg-info-light { background-color: rgba(13, 202, 240, 0.08); }
    .bg-success-light { background-color: rgba(25, 135, 84, 0.08); }
    .bg-warning-light { background-color: rgba(255, 193, 7, 0.08); }
    .shadow-primary { box-shadow: 0 8px 20px rgba(239, 124, 27, 0.3); }
    
    /* Animations */
    .hover-lift:hover { transform: translateY(-5px); }
    .hover-translate-y:hover { transform: translateY(-2px); }
    .hover-scale:hover { transform: scale(1.02); }
    
    .pulse-success {
        box-shadow: 0 0 0 rgba(25, 135, 84, 0.4);
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(25, 135, 84, 0); }
        100% { box-shadow: 0 0 0 0 rgba(25, 135, 84, 0); }
    }
    
    .wave {
        display: inline-block;
        animation: wave-animation 2.5s infinite;
        transform-origin: 70% 70%;
    }
    
    @keyframes wave-animation {
        0% { transform: rotate(0deg) }
        10% { transform: rotate(14deg) }
        20% { transform: rotate(-8deg) }
        30% { transform: rotate(14deg) }
        40% { transform: rotate(-4deg) }
        50% { transform: rotate(10deg) }
        60% { transform: rotate(0deg) }
        100% { transform: rotate(0deg) }
    }
    
    .smaller { font-size: 0.65rem; }
    .stat-value { font-size: 2.25rem; font-weight: 800; letter-spacing: -0.02em; color: #0f172a; margin-bottom: 2px; }
    .stat-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; margin-bottom: 0; }
</style>
@endsection
