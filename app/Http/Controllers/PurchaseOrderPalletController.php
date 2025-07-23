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
use App\Models\PurchaseOrderPalletDesign; // Using your provided model name
use Illuminate\Validation\ValidationException;

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
    $commonRules = [
        'purchase_order_id' => 'required|exists:purchase_orders,id',
        'purchase_order_item_id' => 'required|exists:purchase_order_items,id',
        'design' => 'required|exists:designs,id',
        'size' => 'required|exists:sizes,id',
        'finish' => 'required|exists:finishes,id',
        'batch_id' => 'required|exists:purchase_order_batches,id',
        'packing_date' => 'required|date',
        'pallets' => 'required|array|min:1',
    ];

    $palletRules = [
        'pallets.*.pallet_size' => 'required|numeric|min:1',
        'pallets.*.pallet_no' => 'required|numeric|min:1',
        'pallets.*.total_qty' => 'required|integer|min:0',
        'pallets.*.remark' => 'nullable|string|max:500',
        'pallets.*.po' => 'required|string',
        'pallets.*.design_id' => 'required|exists:designs,id',
        'pallets.*.size_id' => 'required|exists:sizes,id',
        'pallets.*.finish_id' => 'required|exists:finishes,id',
        'pallets.*.batch_id' => 'required|exists:purchase_order_batches,id',
        'pallets.*.design_quantities' => 'required|string',
    ];

    $request->validate(array_merge($commonRules, $palletRules));

    $mainDesignName = Design::find($request->design)->name ?? null;
    $mainSizeName = Size::find($request->size)->size_name ?? null;
    $mainFinishName = Finish::find($request->finish)->finish_name ?? null;

    $purchaseOrder = PurchaseOrder::findOrFail($request->purchase_order_id);
    $partyId = $purchaseOrder->party_id;

    $purchaseOrderItem = PurchaseOrderItem::findOrFail($request->purchase_order_item_id);
    $productionQty = $purchaseOrderItem->production_qty;

    $totalSumOfPalletQuantities = 0;

    foreach ($request->pallets as $index => $palletData) {
        $decodedDesigns = json_decode($palletData['design_quantities'], true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw ValidationException::withMessages([
                "pallets.{$index}.design_quantities" => 'Invalid JSON format for design quantities.'
            ]);
        }

        $sumOfDesignsForThisPallet = 0;
        foreach ($decodedDesigns as $designId => $detail) {
            $sumOfDesignsForThisPallet += (int) $detail['quantity'];
        }

        if ((int)$sumOfDesignsForThisPallet !== (int)$palletData['total_qty']) {
            throw ValidationException::withMessages([
                "pallets.{$index}.design_quantities" => "Design total ({$sumOfDesignsForThisPallet}) does not match pallet total ({$palletData['total_qty']})."
            ]);
        }

        $totalSumOfPalletQuantities += (int)$palletData['total_qty'];

        $createdPallet = PurchaseOrderPallet::create([
            'purchase_order_id' => (int)$palletData['purchase_order_id'],
            'purchase_order_item_id' => (int)$palletData['purchase_order_item_id'],
            'party_id' => (int)$partyId,
            'po' => $palletData['po'],
            'design' => $mainDesignName,
            'size' => $mainSizeName,
            'finish' => $mainFinishName,
            'batch_id' => (int)$palletData['batch_id'],
            'pallet_size' => $palletData['pallet_size'],
            'pallet_no' => $palletData['pallet_no'],
            'total_qty' => (int)$palletData['total_qty'],
            'packing_date' => $request->packing_date,
            'remark' => $palletData['remark'] ?? null,
        ]);

        foreach ($decodedDesigns as $designId => $detail) {
            $qty = (int)$detail['quantity'];
            $sizeId = $detail['size_id'] ?? null;
            $finishId = $detail['finish_id'] ?? null;

            if (!$qty || !$sizeId || !$finishId) {
                throw ValidationException::withMessages([
                    "pallets.{$index}.design_quantities" => "Missing size/finish/qty for design ID {$designId}."
                ]);
            }

            PurchaseOrderPalletDesign::create([
                'purchase_order_pallet_id' => $createdPallet->id,
                'design_id' => (int)$designId,
                'quantity' => $qty,
                'size_id' => (int)$sizeId,
                'finish_id' => (int)$finishId,
            ]);
        }
    }

    if ($totalSumOfPalletQuantities > $productionQty) {
        throw ValidationException::withMessages([
            'pallets' => "Overall pallet quantity ({$totalSumOfPalletQuantities}) exceeds production quantity ({$productionQty})."
        ]);
    }

    return redirect()->route('purchase_order_pallets.index')->with('success', 'Pallets added successfully!');
}

}
