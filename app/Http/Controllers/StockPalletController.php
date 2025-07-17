<?php
namespace App\Http\Controllers;

use App\Models\Design;
use App\Models\Finish;
use App\Models\Party;
use App\Models\PurchaseOrder;
use App\Models\Size;
use Illuminate\Http\Request;
use App\Models\StockPallet;
use Yajra\DataTables\DataTables;

class StockPalletController extends Controller
{
    public function showStockPalletReport()
    {
        $partyList = Party::whereIn('id', StockPallet::distinct()->pluck('party_id'))
                    ->orderBy('party_name')
                    ->pluck('party_name', 'id');

        $poList = PurchaseOrder::whereIn('id', StockPallet::distinct()->pluck('purchase_order_id'))
                    ->orderBy('po')
                    ->pluck('po', 'po');

        $designList = Design::whereIn('id', StockPallet::distinct()->pluck('design'))
                        ->orderBy('name')
                        ->pluck('name', 'id');

        $sizeList = Size::whereIn('id', StockPallet::distinct()->pluck('size'))
                        ->orderBy('size_name')
                        ->pluck('size_name', 'id');

        $finishList = Finish::whereIn('id', StockPallet::distinct()->pluck('finish'))
                        ->orderBy('finish_name')
                        ->pluck('finish_name', 'id');

        $palletSizeList = StockPallet::select('pallet_size')->distinct()->orderBy('pallet_size')->pluck('pallet_size', 'pallet_size');

        return view('stock_pallets.report', compact(
            'partyList', 'poList', 'designList', 'sizeList', 'finishList', 'palletSizeList'
        ));
    }

    public function reportData(Request $request)
    {
        $query = StockPallet::query();

        if ($request->filled('party_id')) {
            $query->where('party_id', $request->party_id);
        }
        if ($request->filled('po')) {
            $query->where('po', $request->po);
        }
        if ($request->filled('design')) {
            $query->where('design', $request->design);
        }
        if ($request->filled('size')) {
            $query->where('size', $request->size);
        }
        if ($request->filled('finish')) {
            $query->where('finish', $request->finish);
        }
        if ($request->filled('pallet_size')) {
            $query->where('pallet_size', $request->pallet_size);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->make(true);
    }
}
