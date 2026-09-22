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

        $designList = Design::whereIn('id', StockPallet::distinct()->pluck('design_id'))
                        ->orderBy('name')
                        ->pluck('name', 'id');

        $sizeList = Size::whereIn('id', StockPallet::distinct()->pluck('size_id'))
                        ->orderBy('size_name')
                        ->pluck('size_name', 'id');

        $finishList = Finish::whereIn('id', StockPallet::distinct()->pluck('finish_id'))
                        ->orderBy('finish_name')
                        ->pluck('finish_name', 'id');

        $palletSizeList = StockPallet::select('pallet_size')->distinct()->orderBy('pallet_size')->pluck('pallet_size', 'pallet_size');

        return view('stock_pallets.report', compact(
            'partyList', 'poList', 'designList', 'sizeList', 'finishList', 'palletSizeList'
        ));
    }

    // public function reportData(Request $request)
    // {
    //     $query = StockPallet::query();

    //     if ($request->filled('party_id')) {
    //         $query->where('party_id', $request->party_id);
    //     }
    //     if ($request->filled('po')) {
    //         $query->where('po', $request->po);
    //     }
    //     if ($request->filled('design')) {
    //         $query->where('design', $request->design);
    //     }
    //     if ($request->filled('size')) {
    //         $query->where('size', $request->size);
    //     }
    //     if ($request->filled('finish')) {
    //         $query->where('finish', $request->finish);
    //     }
    //     if ($request->filled('pallet_size')) {
    //         $query->where('pallet_size', $request->pallet_size);
    //     }

    //     return DataTables::of($query)
    //         ->addIndexColumn()
    //         ->make(true);
    // }

    public function reportData(Request $request)
    {
        $reportType = $request->input('report_type', 'all');

        if ($reportType === 'all') {
            $query = StockPallet::with(['designDetail', 'sizeDetail', 'finishDetail', 'partyDetail']);

            if ($request->filled('party_id')) {
                $query->where('party_id', $request->party_id);
            }
            if ($request->filled('po')) {
                $query->where('po', $request->po);
            }
            if ($request->filled('design')) {
                $query->where('design_id', $request->design);
            }
            if ($request->filled('size')) {
                $query->where('size_id', $request->size);
            }
            if ($request->filled('finish')) {
                $query->where('finish_id', $request->finish);
            }
            if ($request->filled('pallet_size')) {
                $query->where('pallet_size', $request->pallet_size);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('po_number', function ($row) {
                    return "<span class='po-number'>{$row->po}</span>";
                })
                ->editColumn('party_id', function ($row) {
                    return $row->partyDetail->party_name ?? '-';
                })
                ->editColumn('design_id', function ($row) {
                    return $row->designDetail->name ?? '-';
                })
                ->editColumn('size_id', function ($row) {
                    return $row->sizeDetail->size_name ?? '-';
                })
                ->editColumn('finish_id', function ($row) {
                    return $row->finishDetail->finish_name ?? '-';
                })
                ->editColumn('loos_box', function ($row) {
                    return $row->loos_box ?? 0;
                })
                ->rawColumns(['po_number'])
                ->make(true);
        }

        // Grouped queries
        $query = StockPallet::query();
        
        // Always apply base filters if they are present for the grouped view too
        if ($request->filled('party_id')) $query->where('party_id', $request->party_id);
        if ($request->filled('po')) $query->where('po', $request->po);
        if ($request->filled('design')) $query->where('design_id', $request->design);
        if ($request->filled('size')) $query->where('size_id', $request->size);
        if ($request->filled('finish')) $query->where('finish_id', $request->finish);
        if ($request->filled('pallet_size')) $query->where('pallet_size', $request->pallet_size);

        $groupBy = [];
        $selects = [\DB::raw('SUM(current_qty) as total_current_qty'), \DB::raw('SUM(loos_box) as total_loos_box')];

        if ($reportType === 'design') {
            $groupBy = ['design_id'];
            $selects[] = 'design_id';
            $query->with(['designDetail']);
        } elseif ($reportType === 'size') {
            $groupBy = ['size_id'];
            $selects[] = 'size_id';
            $query->with(['sizeDetail']);
        } elseif ($reportType === 'finish') {
            $groupBy = ['finish_id'];
            $selects[] = 'finish_id';
            $query->with(['finishDetail']);
        } elseif ($reportType === 'party') {
            $groupBy = ['party_id'];
            $selects[] = 'party_id';
            $query->with(['partyDetail']);
        } elseif ($reportType === 'po') {
            $groupBy = ['po'];
            $selects[] = 'po';
        } elseif ($reportType === 'design_size') {
            $groupBy = ['design_id', 'size_id'];
            $selects[] = 'design_id';
            $selects[] = 'size_id';
            $query->with(['designDetail', 'sizeDetail']);
        }

        $query->select($selects)->groupBy($groupBy);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('group_name', function ($row) use ($reportType) {
                if ($reportType === 'design') return $row->designDetail->name ?? '-';
                if ($reportType === 'size') return $row->sizeDetail->size_name ?? '-';
                if ($reportType === 'finish') return $row->finishDetail->finish_name ?? '-';
                if ($reportType === 'party') return $row->partyDetail->party_name ?? '-';
                if ($reportType === 'po') return $row->po ?? '-';
                if ($reportType === 'design_size') {
                    $d = $row->designDetail->name ?? '-';
                    $s = $row->sizeDetail->size_name ?? '-';
                    return "$d ($s)";
                }
                return '-';
            })
            ->addColumn('action', function ($row) use ($reportType) {
                $filterData = '';
                if ($reportType === 'design') $filterData = 'data-design="'.$row->design_id.'"';
                if ($reportType === 'size') $filterData = 'data-size="'.$row->size_id.'"';
                if ($reportType === 'finish') $filterData = 'data-finish="'.$row->finish_id.'"';
                if ($reportType === 'party') $filterData = 'data-party="'.$row->party_id.'"';
                if ($reportType === 'po') $filterData = 'data-po="'.$row->po.'"';
                if ($reportType === 'design_size') {
                    $filterData = 'data-design="'.$row->design_id.'" data-size="'.$row->size_id.'"';
                }
                
                return '<button class="btn btn-sm btn-outline-info view-details-btn rounded-pill px-3" '.$filterData.'>
                            <i class="bi bi-eye"></i> View
                        </button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
