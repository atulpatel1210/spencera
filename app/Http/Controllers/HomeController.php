<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $stats = [
            'total_parties' => \App\Models\Party::count(),
            'total_orders' => \App\Models\PurchaseOrder::count(),
            'total_dispatches' => \App\Models\Dispatch::count(),
            'total_stock' => \App\Models\StockPallet::count(),
            'total_designs' => \App\Models\Design::count() ?? 0,
            'recent_orders' => \App\Models\PurchaseOrder::where('created_at', '>=', now()->subDays(7))->count() ?? 0,
            // Activity Overview Data
            'total_boxes_in_stock' => \App\Models\StockPallet::sum('pallet_no') ?? 0,
            'latest_dispatches' => \App\Models\Dispatch::with(['party', 'purchaseOrder'])->latest()->take(5)->get(),
        ];

        return view('dashboard', compact('stats'));
    }
}
