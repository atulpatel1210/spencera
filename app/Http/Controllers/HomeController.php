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
            'total_stock' => \App\Models\StockPallet::sum('pallet_no'),
        ];

        return view('dashboard', compact('stats'));
    }
}
