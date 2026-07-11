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
                    $html = '<div class="d-flex flex-column"><div class="py-2">&nbsp;</div>';
                    foreach($pallet->purchaseOrderPalletDesigns as $design) {
                        $size = $design->pallet_size ?? '0';
                        $html .= "<div class='small text-muted text-nowrap d-flex align-items-center border-bottom py-2 text-nowrap'>{$size}</div>";
                    }
                    $html .= '</div>';
                    return $html;
                }
                return $pallet->pallet_size;
            })
            ->editColumn('pallet_no', function(PurchaseOrderPallet $pallet) {
                if ($pallet->is_mix_pallet) {
                    $html = '<div class="d-flex flex-column"><div class="py-2">&nbsp;</div>';
                    foreach($pallet->purchaseOrderPalletDesigns as $design) {
                        $no = $design->pallet_no ?? '0';
                        $html .= "<div class='small text-muted text-nowrap d-flex align-items-center border-bottom py-2 text-nowrap'>{$no}</div>";
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
            ->rawColumns(['po_number', 'design_detail.name', 'size_detail.size_name', 'finish_detail.finish_name', 'batch_no', 'pallet_size', 'pallet_no', 'total_qty'])
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
                $packedQty = \App\Models\PurchaseOrderPalletDesign::where('batch_id', $batch->id)->sum('total_qty');
                $batch->packed_qty = $packedQty;
                $batch->remaining_qty = $batch->qty - $packedQty;
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
                $existingQty = \App\Models\PurchaseOrderPalletDesign::where('batch_id', $batchId)->sum('total_qty');
                
                if (($existingQty + $incomingQty) > $batch->qty) {
                     $item = $batch->purchaseOrderItem;
                     $designName = $item->designDetail->name ?? '';
                     $batchName = $batch->batch_no;
                     throw ValidationException::withMessages([
                        'pallets' => "Quantity limit exceeded for Design: {$designName}, Batch: {$batchName}. Limit: {$batch->qty}, Existing: $existingQty, Attempting to add: $incomingQty."
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
