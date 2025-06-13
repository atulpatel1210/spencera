<?php

namespace App\Http\Controllers;

use App\Models\Dispatch;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseOrderBatch;
use App\Models\StockPallet;
use App\Models\Party;
use App\Models\Design;
use App\Models\Size;
use App\Models\Finish;
use App\Models\PurchaseOrderPallet;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Exception;

class DispatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dispatches.index');
    }

    /**
     * Returns data for Yajra Datatables for Dispatches.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDispatchesData()
    {
        try {
            $query = Dispatch::select([
                'id',
                'party_id',
                'purchase_order_id',
                'purchase_order_item_id',
                'pallet_id',
                'batch_id',
                'dispatched_qty',
                'dispatch_date',
                'vehicle_no',
                'container_no',
                'remark'
            ])->with(['party', 'purchaseOrder', 'purchaseOrderItem.designDetail', 'purchaseOrderItem.sizeDetail', 'purchaseOrderItem.finishDetail', 'stockPallet', 'batch']); // Eager load relationships

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('party_name', function (Dispatch $dispatch) {
                    return $dispatch->party->party_name ?? 'N/A';
                })
                ->addColumn('po_number', function (Dispatch $dispatch) {
                    return $dispatch->purchaseOrder->po ?? 'N/A';
                })
                ->addColumn('item_details', function (Dispatch $dispatch) {
                    $item = $dispatch->purchaseOrderItem;
                    if ($item) {
                        return ($item->designDetail->name ?? 'N/A') . ' / ' .
                               ($item->sizeDetail->size_name ?? 'N/A') . ' / ' .
                               ($item->finishDetail->finish_name ?? 'N/A');
                    }
                    return 'N/A';
                })
                ->addColumn('pallet_no', function (Dispatch $dispatch) {
                    return $dispatch->stockPallet->pallet_no ?? 'N/A';
                })
                ->addColumn('batch_no', function (Dispatch $dispatch) {
                    return $dispatch->batch->batch_no ?? 'N/A';
                })
                ->addColumn('actions', function (Dispatch $dispatch) {
                    // You can add edit/delete actions here if needed
                    return "
                        <a href='" . route('dispatches.show', $dispatch->id) . "' class='btn btn-sm btn-info'>View</a>
                        <a href='" . route('dispatches.edit', $dispatch->id) . "' class='btn btn-sm btn-warning'>Edit</a>
                        <form action='" . route('dispatches.destroy', $dispatch->id) . "' method='POST' style='display:inline-block;'>
                            " . csrf_field() . method_field('DELETE') . "
                            <button type='submit' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure?')\">Delete</button>
                        </form>
                    ";
                })
                ->rawColumns(['actions'])
                ->toJson();
        } catch (Exception $e) {
            \Log::error("Yajra DataTables error in getDispatchesData: " . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'draw' => 0,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'An error occurred while fetching dispatch data.'
            ], 500);
        }
    }

    public function getPurchasesForDispatch(Request $request) {
        $partyId = $request->input('party_id');
        $purchaseOrders = PurchaseOrder::where('party_id', $partyId)
            ->select('id','po')
            ->distinct()
            ->get();
        return response()->json([
            'orderItems' => $purchaseOrders,
        ]);
    }

    public function getDesignsForDispatch(Request $request) {
        $partyId = $request->input('party_id');
        $purchaseOrderId = $request->input('purchase_order_id');

        $purchaseOrders = PurchaseOrderItem::where('party_id', $partyId)
            ->where('purchase_order_id', $purchaseOrderId)
            ->join('designs', 'purchase_order_items.design', '=', 'designs.id')
            ->select('designs.id', 'designs.name')
            ->distinct()
            ->get();

        return response()->json([
            'orderItems' => $purchaseOrders,
        ]);
    }

    public function getSizesForDispatch(Request $request) {
        $partyId = $request->input('party_id');
        $purchaseOrderId = $request->input('purchase_order_id');
        $designId = $request->input('design_id');

        $purchaseOrders = PurchaseOrderItem::where('party_id', $partyId)
            ->where('purchase_order_id', $purchaseOrderId)
            ->where('design', $designId)
            ->join('sizes', 'purchase_order_items.size', '=', 'sizes.id')
            ->select('sizes.id', 'sizes.size_name')
            ->distinct()
            ->get();

        return response()->json([
            'orderItems' => $purchaseOrders,
        ]);
    }

    public function getFinishsForDispatch(Request $request) {
        $partyId = $request->input('party_id');
        $purchaseOrderId = $request->input('purchase_order_id');
        $designId = $request->input('design_id');
        $sizeId = $request->input('size_id');

        $purchaseOrders = PurchaseOrderItem::where('party_id', $partyId)
            ->where('purchase_order_id', $purchaseOrderId)
            ->where('design', $designId)
            ->where('size', $sizeId)
            ->join('finishes', 'purchase_order_items.finish', '=', 'finishes.id')
            ->select('finishes.id', 'finishes.finish_name')
            ->distinct()
            ->get();

        return response()->json([
            'orderItems' => $purchaseOrders,
        ]);
    }

    /**
     * Get order items by purchase order ID, and optionally by design, size, finish.
     * Also returns unique designs, sizes, finishes available for the given PO.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrderItemsForDispatch(Request $request)
    {
        $partyId = $request->input('party_id');
        $purchaseOrderId = $request->input('purchase_order_id');
        $designId = $request->input('design_id');
        $sizeId = $request->input('size_id');
        $finishId = $request->input('finish_id');

        $query = PurchaseOrderItem::where('purchase_order_id', $purchaseOrderId)
                                ->with(['designDetail', 'sizeDetail', 'finishDetail']);

        if ($partyId) {
            $query->where('party_id', $partyId);
        }
        if ($designId) {
            $query->where('design', $designId);
        }
        if ($sizeId) {
            $query->where('size', $sizeId);
        }
        if ($finishId) {
            $query->where('finish', $finishId);
        }

        $orderItems = $query->get();

        return response()->json([
            'orderItems' => $orderItems,
        ]);
    }

    /**
     * Get batches by purchase order item ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBatchesForDispatch(Request $request)
    {
        $purchaseOrderItemId = $request->input('purchase_order_item_id');
        $partyId = $request->input('party_id');
        $purchaseOrderId = $request->input('purchase_order_id');

        $query = PurchaseOrderBatch::where('purchase_order_item_id', $purchaseOrderItemId);
        if ($partyId) {
            $query->where('party_id', $partyId);
        }
        if ($purchaseOrderId) {
            $query->where('purchase_order_id', $purchaseOrderId);
        }

        $batches = $query->get();
        return response()->json($batches);
    }

    /**
     * Get available stock pallets by purchase order item ID and batch ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPalletsForDispatch(Request $request)
    {
        $partyId = $request->input('party_id');
        $purchaseOrderId = $request->input('purchase_order_id');
        $designId = $request->input('design_id');
        $sizeId = $request->input('size_id');
        $finishId = $request->input('finish_id');
        $purchaseOrderItemId = $request->input('purchase_order_item_id');
        $batchId = $request->input('batch_id');

        $query = PurchaseOrderPallet::where('purchase_order_id', $purchaseOrderId)
                                ->with(['designDetail', 'sizeDetail', 'finishDetail']);

        if ($partyId) {
            $query->where('party_id', $partyId);
        }
        if ($purchaseOrderItemId) {
            $query->where('purchase_order_item_id', $purchaseOrderItemId);
        }
        if ($batchId) {
            $query->where('batch_id', $batchId);
        }
        if ($designId) {
            $query->where('design', $designId);
        }
        if ($sizeId) {
            $query->where('size', $sizeId);
        }
        if ($finishId) {
            $query->where('finish', $finishId);
        }

        $pallets = $query->get();

        return response()->json($pallets);
    }

    /**
     * Show the form for creating a new dispatch.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parties = Party::all();
        return view('dispatches.create', compact('parties'));
    }

    /**
     * Store a newly created dispatch in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'party_id' => 'required|exists:parties,id',
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'design' => 'required|exists:designs,id',
            'size' => 'required|exists:sizes,id',
            'finish' => 'required|exists:finishes,id',
            'purchase_order_item_id' => 'required|exists:purchase_order_items,id',
            'pallet_id' => 'required|exists:purchase_order_pallets,id',
            'batch_id' => 'required|exists:purchase_order_batches,id',
            'pallet_no' => 'required|integer|min:1',
            'dispatched_qty' => 'required|integer|min:1',
            'dispatch_date' => 'required|date',
            'vehicle_no' => 'required|string|max:255',
            'container_no' => 'required|string|max:255',
            'remark' => 'nullable|string',
        ]);

        $pallet = PurchaseOrderPallet::where('id', $request->pallet_id)->first();

        $stock = StockPallet::where('party_id', $request->party_id)
            ->where('purchase_order_id', $request->purchase_order_id)
            ->where('purchase_order_item_id', $request->purchase_order_item_id)
            ->where('design', $request->design)
            ->where('size', $request->size)
            ->where('finish', $request->finish)
            ->where('batch_id', $request->batch_id)
            ->first();

        if ($stock) {
            if ($request->pallet_no > $stock->pallet_no) {
                return back()->withErrors([
                    'pallet_no' => "Pallent no cannot exceed available pallet no of ({$stock->pallet_no})."
                ])->withInput();
            }
            $stock->pallet_no = $stock->pallet_no - $request->pallet_no;
            $stock->current_qty = $stock->pallet_no * $stock->pallet_size;
            $stock->save();

        } else {
            $newStock = new StockPallet();
            $newStock->party_id = $request->party_id;
            $newStock->purchase_order_id = $request->purchase_order_id;
            $newStock->po = $request->po;
            $newStock->purchase_order_item_id = $request->purchase_order_item_id;
            $newStock->design = $request->design;
            $newStock->size = $request->size;
            $newStock->finish = $request->finish;
            $newStock->batch_id = $request->batch_id;
            $newStock->pallet_size = $pallet->pallet_size;
            $newStock->pallet_no = $request->pallet_no;
            $newStock->current_qty = $newStock->pallet_size * $newStock->pallet_no;
            $newStock->save();
            
            $stock = $newStock;
        }

        Dispatch::create([
            'party_id' => $request->party_id,
            'purchase_order_id' => $request->purchase_order_id,
            'po' => $request->po,
            'purchase_order_item_id' => $request->purchase_order_item_id,
            'pallet_id' => $request->pallet_id,
            'stock_id' => $stock->id,
            'batch_id' => $request->batch_id,
            'dispatched_qty' => $request->dispatched_qty,
            'dispatch_date' => $request->dispatch_date,
            'vehicle_no' => $request->vehicle_no,
            'container_no' => $request->container_no,
            'remark' => $request->remark,
        ]);

        return redirect()->route('dispatches.index')->with('success', 'Dispatch created successfully and pallet stock updated!');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Dispatch  $dispatch
     * @return \Illuminate\Http\Response
     */
    public function show(Dispatch $dispatch)
    {
        $dispatch->load(['party', 'purchaseOrder', 'purchaseOrderItem.designDetail', 'purchaseOrderItem.sizeDetail', 'purchaseOrderItem.finishDetail', 'stockPallet', 'batch']);
        return view('dispatches.show', compact('dispatch'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Dispatch  $dispatch
     * @return \Illuminate\Http\Response
     */
    public function edit(Dispatch $dispatch)
    {
        $parties = Party::all();
        $purchaseOrders = PurchaseOrder::all();
        $designs = Design::all();   // Pass all master designs
        $sizes = Size::all();       // Pass all master sizes
        $finishes = Finish::all();  // Pass all master finishes

        // Load related items for the specific dispatch
        $purchaseOrderItems = PurchaseOrderItem::where('purchase_order_id', $dispatch->purchase_order_id)
                                                ->with(['designDetail', 'sizeDetail', 'finishDetail'])
                                                ->get();
        $batches = PurchaseOrderBatch::where('purchase_order_item_id', $dispatch->purchase_order_item_id)->get();
        $stockPallets = StockPallet::where('purchase_order_item_id', $dispatch->purchase_order_item_id)
                                    ->where('batch_id', $dispatch->batch_id)
                                    ->get(); // Get all relevant pallets, even if current_qty is 0 for display

        return view('dispatches.edit', compact('dispatch', 'parties', 'purchaseOrders', 'purchaseOrderItems', 'batches', 'stockPallets', 'designs', 'sizes', 'finishes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Dispatch  $dispatch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dispatch $dispatch)
    {
        $request->validate([
            'party_id' => 'required|exists:parties,id',
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'purchase_order_item_id' => 'required|exists:purchase_order_items,id',
            'pallet_id' => 'required|exists:stock_pallets,id',
            'batch_id' => 'nullable|exists:purchase_order_batches,id',
            'dispatched_qty' => 'required|integer|min:1',
            'dispatch_date' => 'required|date',
            'vehicle_no' => 'nullable|string|max:255',
            'container_no' => 'nullable|string|max:255',
            'remark' => 'nullable|string',
        ]);

        // Get original dispatched quantity
        $originalQty = $dispatch->dispatched_qty;
        $newQty = $request->dispatched_qty;

        // If pallet changed or quantity changed, adjust stock
        if ($dispatch->pallet_id != $request->pallet_id || $originalQty != $newQty) {
            // Revert original quantity to the old pallet
            $oldPallet = StockPallet::find($dispatch->pallet_id);
            if ($oldPallet) {
                $oldPallet->current_qty += $originalQty;
                $oldPallet->save();
            }

            // Deduct new quantity from the new/same pallet
            $newPallet = StockPallet::find($request->pallet_id);
            if (!$newPallet) {
                return back()->withErrors(['pallet_id' => 'Selected new pallet not found.'])->withInput();
            }
            if ($newQty > $newPallet->current_qty + $originalQty && $newPallet->id == $oldPallet->id) {
                // Special case: if same pallet, and new quantity exceeds available + original
                return back()->withErrors(['dispatched_qty' => 'Dispatched quantity cannot exceed available pallet quantity.'])->withInput();
            } elseif ($newQty > $newPallet->current_qty && $newPallet->id != $oldPallet->id) {
                 // Different pallet, new quantity exceeds its current available
                 return back()->withErrors(['dispatched_qty' => 'Dispatched quantity cannot exceed available pallet quantity (' . $newPallet->current_qty . ').'])->withInput();
            }

            $newPallet->current_qty -= $newQty;
            $newPallet->save();
        }

        $dispatch->update([
            'party_id' => $request->party_id,
            'purchase_order_id' => $request->purchase_order_id,
            'purchase_order_item_id' => $request->purchase_order_item_id,
            'pallet_id' => $request->pallet_id,
            'batch_id' => $request->batch_id,
            'dispatched_qty' => $request->dispatched_qty,
            'dispatch_date' => $request->dispatch_date,
            'vehicle_no' => $request->vehicle_no,
            'container_no' => $request->container_no,
            'remark' => $request->remark,
        ]);

        return redirect()->route('dispatches.index')->with('success', 'ડિસ્પેચ સફળતાપૂર્વક અપડેટ થયું અને સ્ટોક અપડેટ થયો!');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Dispatch  $dispatch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dispatch $dispatch)
    {
        // Before deleting the dispatch, add the quantity back to the stock pallet
        $pallet = StockPallet::find($dispatch->pallet_id);
        if ($pallet) {
            $pallet->current_qty += $dispatch->dispatched_qty;
            $pallet->save();
        }

        $dispatch->delete();

        return redirect()->route('dispatches.index')->with('success', 'ડિસ્પેચ સફળતાપૂર્વક ડિલીટ થયું અને સ્ટોક અપડેટ થયો!');
    }
}