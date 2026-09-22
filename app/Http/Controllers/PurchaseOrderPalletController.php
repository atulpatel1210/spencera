<?php

namespace App\Http\Controllers;

use App\Models\Design;
use App\Models\Finish;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderBatch;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseOrderPallet;
use App\Models\Size;
use App\Models\StockPallet;
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
        $query = PurchaseOrderPallet::with(['designDetail', 'sizeDetail', 'finishDetail', 'purchaseOrderBatch', 'purchaseOrderPalletDesigns.design', 'purchaseOrderPalletDesigns.size', 'purchaseOrderPalletDesigns.finish', 'purchaseOrderPalletDesigns.batch', 'purchaseOrderPalletDesigns']);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('po_number', function(PurchaseOrderPallet $pallet) {
                return "<span class='po-number'>{$pallet->po}</span>";
            })
            ->addColumn('design_detail.name', function(PurchaseOrderPallet $pallet) {
                if ($pallet->is_mix_pallet) {
                    $html = '<div class="d-flex flex-column"><div class="fw-bold text-warning d-flex align-items-center py-2"><i class="bi bi-boxes me-1"></i> MIX</div>';
                    foreach($pallet->purchaseOrderPalletDesigns as $design) {
                        $name = $design->design->name ?? 'N/A';
                        $html .= "<div class='small text-muted text-nowrap d-flex align-items-center border-bottom py-2 text-nowrap'>- {$name} ({$design->quantity})</div>";
                    }
                    $html .= '</div>';
                    return $html;
                }
                return $pallet->designDetail->name ?? 'N/A';
            })
            ->addColumn('size_detail.size_name', function(PurchaseOrderPallet $pallet) {
                if ($pallet->is_mix_pallet) {
                    $html = '<div class="d-flex flex-column"><div class="py-2">&nbsp;</div>';
                    foreach($pallet->purchaseOrderPalletDesigns as $design) {
                        $size = $design->size->size_name ?? 'N/A';
                        $html .= "<div class='small text-muted text-nowrap d-flex align-items-center border-bottom py-2 text-nowrap'>{$size}</div>";
                    }
                    $html .= '</div>';
                    return $html;
                }
                return $pallet->sizeDetail->size_name ?? 'N/A';
            })
            ->addColumn('finish_detail.finish_name', function(PurchaseOrderPallet $pallet) {
                if ($pallet->is_mix_pallet) {
                    $html = '<div class="d-flex flex-column"><div class="py-2">&nbsp;</div>';
                    foreach($pallet->purchaseOrderPalletDesigns as $design) {
                        $finish = $design->finish->finish_name ?? 'N/A';
                        $html .= "<div class='small text-muted text-nowrap d-flex align-items-center border-bottom py-2 text-nowrap'>{$finish}</div>";
                    }
                    $html .= '</div>';
                    return $html;
                }
                return $pallet->finishDetail->finish_name ?? 'N/A';
            })
            ->addColumn('batch_no', function(PurchaseOrderPallet $pallet) {
                if ($pallet->is_mix_pallet) {
                    $html = '<div class="d-flex flex-column"><div class="py-2">&nbsp;</div>';
                    foreach($pallet->purchaseOrderPalletDesigns as $design) {
                        $batch = $design->batch->batch_no ?? 'N/A';
                        $html .= "<div class='small text-muted text-nowrap d-flex align-items-center border-bottom py-2 text-nowrap'><span class='badge bg-light text-dark border'>{$batch}</span></div>";
                    }
                    $html .= '</div>';
                    return $html;
                }
                return '<span class="badge bg-secondary-subtle text-secondary border border-secondary">' . ($pallet->purchaseOrderBatch->batch_no ?? 'N/A') . '</span>';
            })
            ->editColumn('pallet_size', function(PurchaseOrderPallet $pallet) {
                if ($pallet->is_mix_pallet) {
                    $html = '<div class="d-flex flex-column"><div class="fw-bold d-flex align-items-center py-2 text-center justify-content-center">' . $pallet->pallet_size . '</div>';
                    foreach($pallet->purchaseOrderPalletDesigns as $design) {
                        $html .= "<div class='small text-muted text-nowrap d-flex align-items-center justify-content-center border-bottom py-2 text-nowrap'>-</div>";
                    }
                    $html .= '</div>';
                    return $html;
                }
                return $pallet->pallet_size;
            })
            ->editColumn('pallet_no', function(PurchaseOrderPallet $pallet) {
                if ($pallet->is_mix_pallet) {
                    $html = '<div class="d-flex flex-column"><div class="fw-bold d-flex align-items-center py-2 text-center justify-content-center">' . $pallet->pallet_no . '</div>';
                    foreach($pallet->purchaseOrderPalletDesigns as $design) {
                        $html .= "<div class='small text-muted text-nowrap d-flex align-items-center justify-content-center border-bottom py-2 text-nowrap'>-</div>";
                    }
                    $html .= '</div>';
                    return $html;
                }
                return $pallet->pallet_no;
            })
            ->editColumn('total_qty', function(PurchaseOrderPallet $pallet) {
                if ($pallet->is_mix_pallet) {
                    $html = '<div class="d-flex flex-column"><div class="fw-bold text-success d-flex align-items-center py-2">Total: ' . $pallet->total_qty . '</div>';
                    foreach($pallet->purchaseOrderPalletDesigns as $design) {
                        $qty = $design->total_qty ?? $design->quantity ?? '0';
                        $html .= "<div class='small fw-bold text-success text-nowrap d-flex align-items-center border-bottom py-2 text-nowrap'>{$qty}</div>";
                    }
                    $html .= '</div>';
                    return $html;
                }
                return $pallet->total_qty;
            })
            ->addColumn('actions', function(PurchaseOrderPallet $pallet) {
                $editUrl = route('purchase_order_pallets.edit', $pallet->id);
                $deleteUrl = route('purchase_order_pallets.destroy', $pallet->id);
                $csrf = csrf_field();
                $method = method_field('DELETE');

                return "
                    <div class='d-flex justify-content-end gap-2 pe-3'>
                        <a href='{$editUrl}' title='Edit' class='btn btn-sm btn-outline-primary rounded-circle action-btn'>
                            <i class='fas fa-edit'></i>
                        </a>
                        <form action='{$deleteUrl}' method='POST' class='d-inline' onsubmit=\"return confirm('Are you sure you want to delete this pallet? Stock will be adjusted accordingly.')\">
                            {$csrf}
                            {$method}
                            <button type='submit' title='Delete' class='btn btn-sm btn-outline-danger rounded-circle action-btn'>
                                <i class='fas fa-trash-alt'></i>
                            </button>
                        </form>
                    </div>
                ";
            })
            ->rawColumns(['po_number', 'design_detail.name', 'size_detail.size_name', 'finish_detail.finish_name', 'batch_no', 'pallet_size', 'pallet_no', 'total_qty', 'actions'])
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $purchaseOrders = PurchaseOrder::whereHas('orderItems.batchDetail')->get();
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
        
        foreach ($orderItems as $item) {
            foreach ($item->batchDetail as $batch) {
                $packedQty = \App\Models\PurchaseOrderPalletDesign::where('batch_id', $batch->id)->sum('quantity');
                $batch->packed_qty = $packedQty;
            }
        }
        
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
            'pallets.*.pallet_size' => 'required|numeric|min:0',
            'pallets.*.pallet_no' => 'required|numeric|min:0',
            'pallets.*.total_qty' => 'required|integer|min:0',
            'pallets.*.remark' => 'nullable|string|max:500',
            'pallets.*.po' => 'required|string',
            'pallets.*.design_id' => 'required|exists:designs,id',
            'pallets.*.size_id' => 'required|exists:sizes,id',
            'pallets.*.finish_id' => 'required|exists:finishes,id',
            'pallets.*.batch_id' => 'required|exists:purchase_order_batches,id',
            'pallets.*.is_mix_pallet' => 'nullable|boolean',
            'pallets.*.design_quantities' => 'required|string',
        ];

        $request->validate(array_merge($commonRules, $palletRules));

        // Start Transaction for data integrity
        \DB::beginTransaction();

        try {
            $purchaseOrder = PurchaseOrder::findOrFail($request->purchase_order_id);
            $partyId = $purchaseOrder->party_id;

            // We need to track total quantities per Order Item to ensure we don't over-produce (optional validation)

            // Group incoming pallet quantities by Batch ID to validate total batch limits
            $incomingBatchQuantities = [];
            
            foreach ($request->pallets as $index => $palletData) {
                $decodedDesigns = json_decode($palletData['design_quantities'], true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw ValidationException::withMessages([
                        "pallets.{$index}.design_quantities" => 'Invalid JSON format for design quantities.'
                    ]);
                }
                
                foreach ($decodedDesigns as $detail) {
                    $batchId = $detail['batch_id'] ?? null;
                    $qty = (int) $detail['quantity'];
                    
                    if ($batchId) {
                        if (!isset($incomingBatchQuantities[$batchId])) {
                            $incomingBatchQuantities[$batchId] = 0;
                        }
                        $incomingBatchQuantities[$batchId] += $qty;
                    }
                }
            }

            // Check against DB limits for Batches
            foreach ($incomingBatchQuantities as $batchId => $incomingQty) {
                $batch = PurchaseOrderBatch::lockForUpdate()->find($batchId);
                if (!$batch) continue;
                
                // Sum existing PurchaseOrderPalletDesign quantities for this batch
                $existingQty = \App\Models\PurchaseOrderPalletDesign::where('batch_id', $batchId)->sum('quantity');
                
                if (($existingQty + $incomingQty) > $batch->qty) {
                     $item = $batch->purchaseOrderItem;
                     $designName = $item->designDetail->name ?? '';
                     $batchName = $batch->batch_no;
                     throw ValidationException::withMessages([
                        'pallets' => "Quantity limit exceeded for Design: {$designName}, Batch: {$batchName}. Limit: {$batch->qty}, Existing: $existingQty, Attempting to add: $incomingQty."
                    ]);
                }
                
                $batch->remaining_qty -= $incomingQty;
                $batch->save();
            }

            foreach ($request->pallets as $index => $palletData) {
                $decodedDesigns = json_decode($palletData['design_quantities'], true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw ValidationException::withMessages([
                        "pallets.{$index}.design_quantities" => 'Invalid JSON format for design quantities.'
                    ]);
                }

                $sumOfDesignsForThisPallet = 0;
                foreach ($decodedDesigns as $detail) {
                    $sumOfDesignsForThisPallet += (int) $detail['quantity'];
                }

                if ((int)$sumOfDesignsForThisPallet !== (int)$palletData['total_qty']) {
                    throw ValidationException::withMessages([
                        "pallets.{$index}.design_quantities" => "Mix items total ({$sumOfDesignsForThisPallet}) does not match pallet total ({$palletData['total_qty']})."
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
                    'is_mix_pallet' => isset($palletData['is_mix_pallet']) ? (int)$palletData['is_mix_pallet'] : 0,
                    'pallet_size' => $palletData['pallet_size'],
                    'pallet_no' => $palletData['pallet_no'],
                    'total_qty' => (int)$palletData['total_qty'],
                    'packing_date' => $request->packing_date,
                    'remark' => $palletData['remark'] ?? null,
                ]);

                // Create Detail Records
                foreach ($decodedDesigns as $detail) {
                    $qty = (int)$detail['quantity'];
                    $designId = $detail['design_id'] ?? null;
                    $sizeId = $detail['size_id'] ?? null;
                    $finishId = $detail['finish_id'] ?? null;
                    $batchId = $detail['batch_id'] ?? null;
                    $poItemId = $detail['purchase_order_item_id'] ?? null;

                    if (!$qty || !$designId || !$sizeId || !$finishId) {
                        throw ValidationException::withMessages([
                            "pallets.{$index}.design_quantities" => "Missing design/size/finish/qty in mix items."
                        ]);
                    }

                    PurchaseOrderPalletDesign::create([
                        'purchase_order_pallet_id' => $createdPallet->id,
                        'purchase_order_item_id' => $poItemId,
                        'design_id' => (int)$designId,
                        'quantity' => $qty,
                        'size_id' => (int)$sizeId,
                        'finish_id' => (int)$finishId,
                        'batch_id' => $batchId ? (int)$batchId : null,
                    ]);
                }

                // Update Stock
                if (isset($palletData['is_mix_pallet']) && $palletData['is_mix_pallet'] == 1) {
                    // For mix pallet, we can add each design quantity to stock
                    foreach ($decodedDesigns as $detail) {
                        // Deduct from loose box stock first
                        $looseStock = StockPallet::where([
                            'party_id' => $partyId,
                            'purchase_order_id' => $request->purchase_order_id,
                            'purchase_order_item_id' => $detail['purchase_order_item_id'] ?? $palletData['purchase_order_item_id'],
                            'design_id' => $detail['design_id'],
                            'size_id' => $detail['size_id'],
                            'finish_id' => $detail['finish_id'],
                            'batch_id' => $detail['batch_id'] ?? null,
                            'pallet_size' => '0',
                        ])->first();
                        
                        if ($looseStock && $looseStock->loos_box >= $detail['quantity']) {
                            $looseStock->loos_box -= $detail['quantity'];
                            $looseStock->current_qty -= $detail['quantity'];
                            $looseStock->save();
                        }

                        $stock = StockPallet::firstOrNew([
                            'party_id' => $partyId,
                            'purchase_order_id' => $request->purchase_order_id,
                            'purchase_order_item_id' => $detail['purchase_order_item_id'] ?? $palletData['purchase_order_item_id'],
                            'design_id' => $detail['design_id'],
                            'size_id' => $detail['size_id'],
                            'finish_id' => $detail['finish_id'],
                            'batch_id' => $detail['batch_id'] ?? null,
                            'pallet_size' => $palletData['pallet_size'],
                        ]);
                        $stock->po = $palletData['po'];
                        // Since it's a mix pallet, pallet count per item is fractional, we might just store 0 or full for display, 
                        // but Dispatch expects pallet_no. If we set pallet_no = 0, dispatch won't work by pallet for mixed items.
                        // For now we add pallet_no for the main item, or 0.
                        $stock->pallet_no = ($stock->pallet_no ?? 0);
                        $stock->current_qty = ($stock->current_qty ?? 0) + $detail['quantity'];
                        $stock->save();
                    }
                    
                    // // Also create a stock entry for the mix pallet itself to allow dispatching the physical pallet
                    // $mixStock = StockPallet::firstOrNew([
                    //     'party_id' => $partyId,
                    //     'purchase_order_id' => $request->purchase_order_id,
                    //     'purchase_order_item_id' => $palletData['purchase_order_item_id'],
                    //     'design_id' => $palletData['design_id'],
                    //     'size_id' => $palletData['size_id'],
                    //     'finish_id' => $palletData['finish_id'],
                    //     'batch_id' => $palletData['batch_id'],
                    //     'pallet_size' => $palletData['pallet_size'],
                    // ]);
                    // $mixStock->po = $palletData['po'];
                    // $mixStock->pallet_no = ($mixStock->pallet_no ?? 0) + $palletData['pallet_no'];
                    // // The qty is tracked in individual items above, but the physical pallet count is tracked here.
                    // $mixStock->current_qty = ($mixStock->current_qty ?? 0) + $palletData['total_qty'];
                    // $mixStock->save();
                } else {
                    // Deduct from loose box stock first
                    $looseStock = StockPallet::where([
                        'party_id' => $partyId,
                        'purchase_order_id' => $request->purchase_order_id,
                        'purchase_order_item_id' => $palletData['purchase_order_item_id'],
                        'design_id' => $palletData['design_id'],
                        'size_id' => $palletData['size_id'],
                        'finish_id' => $palletData['finish_id'],
                        'batch_id' => $palletData['batch_id'],
                        'pallet_size' => '0',
                    ])->first();
                    
                    if ($looseStock && $looseStock->loos_box >= $palletData['total_qty']) {
                        $looseStock->loos_box -= $palletData['total_qty'];
                        $looseStock->current_qty -= $palletData['total_qty'];
                        $looseStock->save();
                    }

                    $stock = StockPallet::firstOrNew([
                        'party_id' => $partyId,
                        'purchase_order_id' => $request->purchase_order_id,
                        'purchase_order_item_id' => $palletData['purchase_order_item_id'],
                        'design_id' => $palletData['design_id'],
                        'size_id' => $palletData['size_id'],
                        'finish_id' => $palletData['finish_id'],
                        'batch_id' => $palletData['batch_id'],
                        'pallet_size' => $palletData['pallet_size'],
                    ]);
                    $stock->po = $palletData['po'];
                    $stock->pallet_no = ($stock->pallet_no ?? 0) + $palletData['pallet_no'];
                    $stock->current_qty = ($stock->current_qty ?? 0) + $palletData['total_qty'];
                    $stock->save();
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

    public function edit(PurchaseOrderPallet $pallet)
    {
        $pallet->load(['designDetail', 'sizeDetail', 'finishDetail', 'purchaseOrderBatch', 'purchaseOrderPalletDesigns.design', 'purchaseOrderPalletDesigns.size', 'purchaseOrderPalletDesigns.finish', 'purchaseOrderPalletDesigns.batch', 'purchaseOrder.orderItems.batchDetail']);
        
        $purchaseOrders = PurchaseOrder::whereHas('orderItems.batchDetail')->get();
        $designs = Design::all();
        $sizes = Size::all();
        $finishes = Finish::all();

        return view('purchase_order_pallets.edit', compact('pallet', 'purchaseOrders', 'designs', 'sizes', 'finishes'));
    }

    public function update(Request $request, PurchaseOrderPallet $pallet)
    {
        if ($pallet->is_mix_pallet) {
            $request->validate([
                'pallet_size' => 'required|numeric|min:0',
                'pallet_no' => 'required|numeric|min:0',
                'total_qty' => 'required|integer|min:0',
                'remark' => 'nullable|string|max:500',
                'design_quantities' => 'required|string',
            ]);

            \DB::beginTransaction();
            try {
                // 1. Reverse existing stock and batch reductions
                $this->reversePalletStockAndBatch($pallet);
                
                // 2. Decode new design quantities
                $decodedDesigns = json_decode($request->design_quantities, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw ValidationException::withMessages(["design_quantities" => 'Invalid JSON format.']);
                }

                $sumOfDesigns = 0;
                $incomingBatchQuantities = [];
                foreach ($decodedDesigns as $detail) {
                    $qty = (int) $detail['quantity'];
                    $sumOfDesigns += $qty;
                    $batchId = $detail['batch_id'] ?? null;
                    if ($batchId) {
                        $incomingBatchQuantities[$batchId] = ($incomingBatchQuantities[$batchId] ?? 0) + $qty;
                    }
                }
                
                if ((int)$sumOfDesigns !== (int)$request->total_qty) {
                    throw ValidationException::withMessages(["design_quantities" => "Mix items total ({$sumOfDesigns}) does not match pallet total ({$request->total_qty})."]);
                }
                
                // 3. Apply new batch limits
                foreach ($incomingBatchQuantities as $batchId => $incomingQty) {
                    $batch = PurchaseOrderBatch::lockForUpdate()->find($batchId);
                    if (!$batch) continue;
                    
                    if ($incomingQty > $batch->remaining_qty) {
                        throw ValidationException::withMessages(['design_quantities' => "Quantity limit exceeded for Batch {$batch->batch_no}. Limit: {$batch->remaining_qty}."]);
                    }
                    $batch->remaining_qty -= $incomingQty;
                    $batch->save();
                }
                
                // 4. Update the Pallet itself
                $pallet->update([
                    'pallet_size' => $request->pallet_size,
                    'pallet_no' => $request->pallet_no,
                    'total_qty' => $request->total_qty,
                    'remark' => $request->remark,
                ]);
                
                // 5. Recreate Detail Records and Apply Stock
                $pallet->purchaseOrderPalletDesigns()->delete();
                
                $partyId = $pallet->party_id;
                $poId = $pallet->purchase_order_id;
                
                foreach ($decodedDesigns as $detail) {
                    $qty = (int)$detail['quantity'];
                    PurchaseOrderPalletDesign::create([
                        'purchase_order_pallet_id' => $pallet->id,
                        'purchase_order_item_id' => $detail['purchase_order_item_id'],
                        'design_id' => (int)$detail['design_id'],
                        'quantity' => $qty,
                        'size_id' => (int)$detail['size_id'],
                        'finish_id' => (int)$detail['finish_id'],
                        'batch_id' => $detail['batch_id'] ? (int)$detail['batch_id'] : null,
                    ]);
                    
                    // Deduct from loose box stock first
                    $looseStock = StockPallet::where([
                        'party_id' => $partyId,
                        'purchase_order_id' => $poId,
                        'purchase_order_item_id' => $detail['purchase_order_item_id'],
                        'design_id' => $detail['design_id'],
                        'size_id' => $detail['size_id'],
                        'finish_id' => $detail['finish_id'],
                        'batch_id' => $detail['batch_id'] ?? null,
                        'pallet_size' => '0',
                    ])->first();
                    
                    if ($looseStock && $looseStock->loos_box >= $qty) {
                        $looseStock->loos_box -= $qty;
                        $looseStock->current_qty -= $qty;
                        $looseStock->save();
                    }

                    $stock = StockPallet::firstOrNew([
                        'party_id' => $partyId,
                        'purchase_order_id' => $poId,
                        'purchase_order_item_id' => $detail['purchase_order_item_id'],
                        'design_id' => $detail['design_id'],
                        'size_id' => $detail['size_id'],
                        'finish_id' => $detail['finish_id'],
                        'batch_id' => $detail['batch_id'] ?? null,
                        'pallet_size' => $request->pallet_size,
                    ]);
                    $stock->po = $pallet->po;
                    $stock->pallet_no = ($stock->pallet_no ?? 0);
                    $stock->current_qty = ($stock->current_qty ?? 0) + $qty;
                    $stock->save();
                }
                
                \DB::commit();
                return redirect()->route('purchase_order_pallets.index')->with('success', 'Mix Pallet updated successfully!');
            } catch (\Exception $e) {
                \DB::rollBack();
                if ($e instanceof ValidationException) { throw $e; }
                return back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()])->withInput();
            }
        } else {
            $request->validate([
                'pallet_size' => 'required|numeric|min:0',
                'pallet_no' => 'required|numeric|min:0',
                'remark' => 'nullable|string|max:500',
            ]);

            $pallet->update([
                'pallet_size' => $request->pallet_size,
                'pallet_no' => $request->pallet_no,
                'remark' => $request->remark,
            ]);

            return redirect()->route('purchase_order_pallets.index')->with('success', 'Pallet updated successfully!');
        }
    }

    private function reversePalletStockAndBatch(PurchaseOrderPallet $pallet)
    {
        if ($pallet->is_mix_pallet) {
            foreach ($pallet->purchaseOrderPalletDesigns as $design) {
                // Restore Batch
                if ($design->batch_id) {
                    $batch = PurchaseOrderBatch::find($design->batch_id);
                    if ($batch) {
                        $batch->remaining_qty += $design->quantity;
                        $batch->save();
                    }
                }
                
                // Restore loose
                $looseStock = StockPallet::where([
                    'party_id' => $pallet->party_id,
                    'purchase_order_id' => $pallet->purchase_order_id,
                    'purchase_order_item_id' => $design->purchase_order_item_id ?? $pallet->purchase_order_item_id,
                    'design_id' => $design->design_id,
                    'size_id' => $design->size_id,
                    'finish_id' => $design->finish_id,
                    'batch_id' => $design->batch_id,
                    'pallet_size' => '0',
                ])->first();
                if ($looseStock) {
                    $looseStock->loos_box += $design->quantity;
                    $looseStock->current_qty += $design->quantity;
                    $looseStock->save();
                }

                // Deduct packed
                $stock = StockPallet::where([
                    'party_id' => $pallet->party_id,
                    'purchase_order_id' => $pallet->purchase_order_id,
                    'purchase_order_item_id' => $design->purchase_order_item_id ?? $pallet->purchase_order_item_id,
                    'design_id' => $design->design_id,
                    'size_id' => $design->size_id,
                    'finish_id' => $design->finish_id,
                    'batch_id' => $design->batch_id,
                    'pallet_size' => $pallet->pallet_size,
                ])->first();
                if ($stock) {
                    $stock->current_qty -= $design->quantity;
                    $stock->save();
                }
            }
        }
    }

    public function destroy(PurchaseOrderPallet $pallet)
    {
        \DB::beginTransaction();
        try {
            // Restore loose stock and deduct packed stock
            if ($pallet->is_mix_pallet) {
                foreach ($pallet->purchaseOrderPalletDesigns as $design) {
                    // Restore loose
                    $looseStock = StockPallet::where([
                        'party_id' => $pallet->party_id,
                        'purchase_order_id' => $pallet->purchase_order_id,
                        'purchase_order_item_id' => $design->purchase_order_item_id ?? $pallet->purchase_order_item_id,
                        'design_id' => $design->design_id,
                        'size_id' => $design->size_id,
                        'finish_id' => $design->finish_id,
                        'batch_id' => $design->batch_id,
                        'pallet_size' => '0',
                    ])->first();
                    if ($looseStock) {
                        $looseStock->loos_box += $design->quantity;
                        $looseStock->current_qty += $design->quantity;
                        $looseStock->save();
                    }

                    // Deduct packed
                    $stock = StockPallet::where([
                        'party_id' => $pallet->party_id,
                        'purchase_order_id' => $pallet->purchase_order_id,
                        'purchase_order_item_id' => $design->purchase_order_item_id ?? $pallet->purchase_order_item_id,
                        'design_id' => $design->design_id,
                        'size_id' => $design->size_id,
                        'finish_id' => $design->finish_id,
                        'batch_id' => $design->batch_id,
                        'pallet_size' => $pallet->pallet_size,
                    ])->first();
                    if ($stock) {
                        $stock->current_qty -= $design->quantity;
                        $stock->save();
                    }
                }
            } else {
                // Restore loose
                $looseStock = StockPallet::where([
                    'party_id' => $pallet->party_id,
                    'purchase_order_id' => $pallet->purchase_order_id,
                    'purchase_order_item_id' => $pallet->purchase_order_item_id,
                    'design_id' => $pallet->design_id,
                    'size_id' => $pallet->size_id,
                    'finish_id' => $pallet->finish_id,
                    'batch_id' => $pallet->batch_id,
                    'pallet_size' => '0',
                ])->first();
                if ($looseStock) {
                    $looseStock->loos_box += $pallet->total_qty;
                    $looseStock->current_qty += $pallet->total_qty;
                    $looseStock->save();
                }

                // Deduct packed
                $stock = StockPallet::where([
                    'party_id' => $pallet->party_id,
                    'purchase_order_id' => $pallet->purchase_order_id,
                    'purchase_order_item_id' => $pallet->purchase_order_item_id,
                    'design_id' => $pallet->design_id,
                    'size_id' => $pallet->size_id,
                    'finish_id' => $pallet->finish_id,
                    'batch_id' => $pallet->batch_id,
                    'pallet_size' => $pallet->pallet_size,
                ])->first();
                if ($stock) {
                    $stock->current_qty -= $pallet->total_qty;
                    $stock->pallet_no -= $pallet->pallet_no;
                    $stock->save();
                }
            }

            $pallet->delete();
            \DB::commit();
            return redirect()->route('purchase_order_pallets.index')->with('success', 'Pallet deleted successfully!');
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->withErrors(['error' => 'Error deleting pallet: ' . $e->getMessage()]);
        }
    }
}
