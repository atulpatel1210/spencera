<?php

namespace App\Http\Controllers;

use App\Models\Design;
use App\Models\Finish;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderBatch;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseOrderPallet;
use App\Models\Size;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables; 

class PurchaseOrderPalletController extends Controller
{

    public function index()
    {
        return view('purchase_order_pallets.index');
    }

    /**
     * Returns data for Yajra Datatables.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPalletsData()
    {
        $query = PurchaseOrderPallet::with(['designDetail', 'sizeDetail', 'finishDetail']);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('design_detail.name', function(PurchaseOrderPallet $pallet) {
                return $pallet->designDetail->name ?? 'N/A';
            })
            ->addColumn('size_detail.size_name', function(PurchaseOrderPallet $pallet) {
                return $pallet->sizeDetail->size_name ?? 'N/A';
            })
            ->addColumn('finish_detail.finish_name', function(PurchaseOrderPallet $pallet) {
                return $pallet->finishDetail->finish_name ?? 'N/A';
            })
            // ->addColumn('actions', function(PurchaseOrderPallet $pallet) {
            //     $editUrl = route('purchase_order_pallets.edit', $pallet->id);
            //     $deleteUrl = route('purchase_order_pallets.destroy', $pallet->id);
            //     $csrf = csrf_field();
            //     $method = method_field('DELETE');

            //     return "
            //         <a href='{$editUrl}' class='btn btn-sm btn-warning'>Edit</a>
            //         <form action='{$deleteUrl}' method='POST' style='display:inline;'>
            //             {$csrf}
            //             {$method}
            //             <button type='submit' onclick=\"return confirm('Are you sure?')\" class='btn btn-sm btn-danger'>Del</button>
            //         </form>
            //     ";
            // })
            // ->rawColumns(['actions']) // Important: tells Datatables to render 'actions' as raw HTML
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $purchaseOrders = PurchaseOrder::all();
        $designs = Design::all();
        $sizes = Size::all();
        $finishes = Finish::all();

        return view('purchase_order_pallets.create', compact('purchaseOrders', 'designs', 'sizes', 'finishes'));
    }

    /**
     * Get order items by purchase order ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrder(Request $request)
    {
        $purchaseOrderId = $request->input('purchase_order_id');
        $orderItems = PurchaseOrderItem::with(['sizeDetail','designDetail','finishDetail','batchDetail'])->where('purchase_order_id', $purchaseOrderId)->get();
        return response()->json($orderItems);
    }

    public function getOrderItems(Request $request)
    {
        $purchaseOrderId = $request->input('purchase_order_id');
        $designId = $request->input('design_id');
        $sizeId = $request->input('size_id');
        $finishId = $request->input('finish_id');

        $query = PurchaseOrderItem::where('purchase_order_id', $purchaseOrderId)
                    ->with(['designDetail', 'sizeDetail', 'finishDetail']); // Eager load details

        if (!empty($designId)) {
            $query->where('design', $designId);
        }
        if (!empty($sizeId)) {
            $query->where('size', $sizeId);
        }
        if (!empty($finishId)) {
            $query->where('finish', $finishId);
        }

        $orderItems = $query->get();

        return response()->json($orderItems);
    }

    /**
     * Get batches by purchase order item ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBatches(Request $request)
    {
        $purchaseOrderItemId = $request->input('purchase_order_item_id');
        $batches = PurchaseOrderBatch::where('purchase_order_item_id', $purchaseOrderItemId)->get();
        return response()->json($batches);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'purchase_order_item_id' => 'required|exists:purchase_order_items,id',
            // 'po' => 'required|string',
            'design' => 'required|string',
            'size' => 'required|string',
            'finish' => 'required|string',
            'packing_date' => 'required|date',
            'pallets' => 'required|array',
            'batch_id' => 'required|exists:purchase_order_batches,id',
            'pallets.*.pallet_size' => 'required|string',
            'pallets.*.pallet_no' => 'required|string',
            'pallets.*.total_qty' => 'required|integer|min:1',
            'pallets.*.remark' => 'nullable|string',
        ]);

        $purchaseOrder = \App\Models\PurchaseOrder::find($request->purchase_order_id);
        if (!$purchaseOrder) {
            return redirect()->back()->withErrors(['purchase_order_id' => 'Invalid Purchase Order']);
        }
        $partyId = $purchaseOrder->party_id;

        foreach ($request->pallets as $palletData) {
            $createData = [
                'purchase_order_id' => $request->purchase_order_id,
                'purchase_order_item_id' => $request->purchase_order_item_id,
                'party_id' => $partyId,
                'po' => $palletData['po'],
                'design' => $request->design,
                'size' => $request->size,
                'finish' => $request->finish,
                'batch_id' => $request->batch_id,
                'pallet_size' => $palletData['pallet_size'],
                'pallet_no' => $palletData['pallet_no'],
                'total_qty' => $palletData['total_qty'],
                'packing_date' => $request->packing_date,
                'remark' => $palletData['remark'] ?? null,
            ];
            PurchaseOrderPallet::create($createData);
        }
        return redirect()->route('purchase_order_pallets.index')->with('success', 'Pallets added successfully!');
        // return redirect()->route('purchase_order_pallets.create')->with('success', 'Pallets added successfully!');
    }
}
