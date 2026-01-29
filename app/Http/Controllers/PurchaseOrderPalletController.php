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
            ->addColumn('po_number', function(PurchaseOrderPallet $pallet) {
                return "<span class='po-number'>{$pallet->po}</span>";
            })
            ->addColumn('design_detail.name', function(PurchaseOrderPallet $pallet) {
                return $pallet->designDetail->name ?? 'N/A';
            })
            ->addColumn('size_detail.size_name', function(PurchaseOrderPallet $pallet) {
                return $pallet->sizeDetail->size_name ?? 'N/A';
            })
            ->addColumn('finish_detail.finish_name', function(PurchaseOrderPallet $pallet) {
                return $pallet->finishDetail->finish_name ?? 'N/A';
            })
            ->rawColumns(['po_number'])
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
            $query->where('design_id', $designId);
        }
        if (!empty($sizeId)) {
            $query->where('size_id', $sizeId);
        }
        if (!empty($finishId)) {
            $query->where('finish_id', $finishId);
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
            'packing_date' => 'required|date',
            'pallets' => 'required|array|min:1',
        ];

        $palletRules = [
            'pallets.*.purchase_order_item_id' => 'required|exists:purchase_order_items,id',
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

        // Start Transaction for data integrity
        \DB::beginTransaction();

        try {
            $purchaseOrder = PurchaseOrder::findOrFail($request->purchase_order_id);
            $partyId = $purchaseOrder->party_id;

            // We need to track total quantities per Order Item to ensure we don't over-produce (optional validation)

            // Group incoming pallet quantities by PurchaseOrderItem to validate total production limits
            $incomingQuantities = [];
            foreach ($request->pallets as $p) {
                $pItemId = $p['purchase_order_item_id'];
                if (!isset($incomingQuantities[$pItemId])) {
                    $incomingQuantities[$pItemId] = 0;
                }
                $incomingQuantities[$pItemId] += (int)$p['total_qty'];
            }

            // Check against DB limits
            foreach ($incomingQuantities as $itemId => $incomingQty) {
                $poItem = PurchaseOrderItem::lockForUpdate()->find($itemId);
                
                // Sum existing PurchaseOrderPallet quantities
                $existingQty = PurchaseOrderPallet::where('purchase_order_item_id', $itemId)->sum('total_qty');
                
                if (($existingQty + $incomingQty) > $poItem->production_qty) {
                     throw ValidationException::withMessages([
                        'pallets' => "Production limit exceeded for Item (ID: $itemId). Limit: {$poItem->production_qty}, Existing: $existingQty, Attempting to add: $incomingQty."
                    ]);
                }
            }

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

                // Verifying Integrity: PO Item must belong to the selected PO
                $poItem = PurchaseOrderItem::find($palletData['purchase_order_item_id']);
                if ($poItem) {
                    if ($poItem->purchase_order_id != $request->purchase_order_id) {
                         throw ValidationException::withMessages([
                            "pallets.{$index}.purchase_order_item_id" => "Mismatch: Order Item does not belong to the selected Purchase Order."
                        ]);
                    }
                    if ($poItem->design_id != $palletData['design_id'] || $poItem->size_id != $palletData['size_id'] || $poItem->finish_id != $palletData['finish_id']) {
                        throw ValidationException::withMessages([
                            "pallets.{$index}.design_id" => "Mismatch: Pallet specifications (Design/Size/Finish) do not match the Order Item."
                        ]);
                    }
                } else {
                     throw ValidationException::withMessages([
                        "pallets.{$index}.purchase_order_item_id" => "Invalid Purchase Order Item ID."
                    ]);
                }

                // Verify Batch Integrity
                $batch = PurchaseOrderBatch::find($palletData['batch_id']);
                if ($batch && $batch->purchase_order_item_id != $palletData['purchase_order_item_id']) {
                     throw ValidationException::withMessages([
                        "pallets.{$index}.batch_id" => "Mismatch: Batch does not belong to the selected Order Item."
                    ]);
                }

                // Create the Pallet
                $createdPallet = PurchaseOrderPallet::create([
                    'purchase_order_id' => (int)$request->purchase_order_id,
                    'purchase_order_item_id' => (int)$palletData['purchase_order_item_id'],
                    'party_id' => (int)$partyId,
                    'po' => $palletData['po'], // Ensure this matches PO logic or fetch from relation
                    'design_id' => (int)$palletData['design_id'],
                    'size_id' => (int)$palletData['size_id'],
                    'finish_id' => (int)$palletData['finish_id'],
                    'batch_id' => (int)$palletData['batch_id'],
                    'pallet_size' => $palletData['pallet_size'],
                    'pallet_no' => $palletData['pallet_no'],
                    'total_qty' => (int)$palletData['total_qty'],
                    'packing_date' => $request->packing_date,
                    'remark' => $palletData['remark'] ?? null,
                ]);

                // Create Detail Records
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

            \DB::commit();
            return redirect()->route('purchase_order_pallets.index')->with('success', 'Pallets added successfully!');

        } catch (\Exception $e) {
            \DB::rollBack();
            // Re-throw validation exceptions to be handled by Laravel
            if ($e instanceof ValidationException) {
                throw $e;
            }
            // Log other errors if necessary
            return back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()])->withInput();
        }
    }
}
