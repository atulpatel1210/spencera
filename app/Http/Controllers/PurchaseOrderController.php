<?php

namespace App\Http\Controllers;

use App\Models\CompanyDetail;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use App\Models\Design;
use App\Models\Finish;
use App\Models\Pallet;
use App\Models\Party;
use App\Models\PurchaseOrderBatch;
use App\Models\Size;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseOrderItemTransaction;
use Yajra\DataTables\Facades\DataTables;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PurchaseOrderController extends Controller
{
    public function create()
    {
        $designs = Design::all();
        $sizes = Size::all();
        $finishes = Finish::all();
        $parties = Party::all();

        return view('purchase_orders.create', compact('designs', 'sizes', 'finishes', 'parties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'po' => 'required|unique:purchase_orders,po',
            'party_id' => 'required|exists:parties,id',
            'brand_name' => 'nullable|string|max:255',
            'order_date' => 'required|date',
            'box_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'order_items' => 'required',
        ]);

        DB::beginTransaction();

        try {

            $data = $request->only('po', 'party_id', 'brand_name', 'order_date');

            if ($request->hasFile('box_image')) {
                $data['box_image'] = $request->file('box_image')->store('box_images', 'public');
                $data['box_image'] = basename($data['box_image']);
            }

            $order = PurchaseOrder::create($data);

            $items = json_decode($request->order_items, true);

            foreach ($items as $item) {
                // $order->orderItems()->create([
                $orderItem = $order->orderItems()->create([
                    'po' => $order->po,
                    'party_id' => $order->party_id,
                    'design_id' => $item['design_id'],
                    'size_id' => $item['size_id'],
                    'finish_id' => $item['finish_id'],
                    'order_qty' => $item['order_qty'],
                    'remark' => $item['remark'] ?? null,
                    'pending_qty' => $item['order_qty'],
                    'planning_qty' => 0,
                    'production_qty' => 0,
                    'short_qty' => 0,
                ]);
                // if (!empty($item['has_pallet']) && $item['has_pallet'] == 1 && !empty($item['pallets'])) {

                    foreach ($item['pallets'] as $pallet) {

                        Pallet::create([
                            'purchase_order_item_id' => $orderItem->id,
                            'box_pallet'  => $pallet['box_pallet'],
                            'total_pallet' => $pallet['total_pallet'],
                        ]);
                    }
                // }
            }

            DB::commit();

            return redirect()->route('orders.index')->with('success', 'Order saved successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Something went wrong! ' . $e->getMessage())
                ->withInput();
        }
    }


    public function index()
    {
        return view('purchase_orders.index');
    }

    public function getOrdersData()
    {
        try {
            $query = PurchaseOrder::select(['id', 'po', 'party_id', 'order_date', 'brand_name', 'box_image'])
                                ->with('party');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('party_name', function (PurchaseOrder $order) {
                    return $order->party->party_name ?? 'N/A';
                })
                ->addColumn('brand_name', function (PurchaseOrder $order) {
                    return $order->brand_name ?? 'N/A';
                })
                ->addColumn('box_image', function (PurchaseOrder $order) {
                    if ($order->box_image) {
                        $url = asset('storage/box_images/' . $order->box_image);
                        return "<img src='{$url}' alt='Box' style='height:50px; object-fit:contain;'/>";
                    }
                    return 'N/A';
                })
                ->addColumn('actions', function (PurchaseOrder $order) {
                    $viewUrl = route('orders.show', $order->id);
                    $editUrl = route('orders.edit', $order->id);
                    $printUrl = route('orders.print', $order->id);
                    $deleteUrl = route('orders.destroy', $order->id);
                    $csrf = csrf_field();
                    $method = method_field('DELETE');
                    return "
                        <a href='{$viewUrl}' title='View' class='btn btn-sm text-secondary'>
                            <i class='fa fa-eye fa-fw fa-lg'></i>
                        </a>
                        <a href='{$editUrl}' title='Edit' class='btn btn-sm text-primary'>
                            <i class='fa fa-edit fa-fw fa-lg'></i>
                        </a>
                        <a href='{$printUrl}' title='Print PO' target='_blank' class='btn btn-sm text-info'>
                            <i class='fa fa-print fa-fw fa-lg'></i>
                        </a>
                        <form action='{$deleteUrl}' method='POST' class='d-inline' onsubmit=\"return confirm('Are you sure?')\" style='display:inline-block;vertical-align:middle;margin-right:0;'>
                            {$csrf}
                            {$method}
                            <button type='submit' title='Delete' class='btn btn-sm text-danger'>
                                <i class='fa fa-trash fa-fw fa-lg'></i>
                            </button>
                        </form>
                    ";
                })
                ->rawColumns(['actions', 'box_image'])
                ->toJson();
        } catch (Exception $e) {
            \Log::error("Yajra DataTables error in getOrdersData: " . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'draw' => 0,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'An error occurred while fetching order data. Please check logs for details.'
            ], 500);
        }
    }

    public function show(PurchaseOrder $order)
    {
        $order->load('orderItems.sizeDetail','orderItems.designDetail','orderItems.finishDetail');
        // dd($order);
        return view('purchase_orders.show', compact('order'));
    }

    public function edit(PurchaseOrder $order)
    {
        $order->load(['orderItems.designDetail', 'orderItems.sizeDetail', 'orderItems.finishDetail']);

        $currentItems = $order->orderItems->map(function ($item) {
            return [
                'design_id' => $item->design_id,
                'design_text' => $item->designDetail->name ?? 'N/A',
                'size_id' => $item->size_id,
                'size_text' => $item->sizeDetail->size_name ?? 'N/A',
                'finish_id' => $item->finish_id,
                'finish_text' => $item->finishDetail->finish_name ?? 'N/A',
                'order_qty' => $item->order_qty,
                'remark' => $item->remark,
                'pallets' => json_decode($item->pallets ?? '[]', true),
            ];
        });

        $orderItemsJson = json_encode($currentItems);

        $designs = Design::all();
        $sizes = Size::all();
        $finishes = Finish::all();
        $parties = Party::all();

        return view('purchase_orders.edit', compact('order', 'designs', 'sizes', 'finishes', 'parties', 'orderItemsJson'));
    }

    public function update(Request $request, PurchaseOrder $order)
    {
        $request->validate([
            'po' => 'required|unique:purchase_orders,po,' . $order->id,
            'party_id' => 'required|exists:parties,id',
            'brand_name' => 'nullable|string|max:255',
            'order_date' => 'required|date',
            'box_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
            'order_items' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $updateData = $request->only('po', 'party_id', 'brand_name', 'order_date');
            if ($request->hasFile('box_image')) {
                if ($order->box_image) {
                    Storage::disk('public')->delete('box_images/' . $order->box_image); 
                }
                $filePath = $request->file('box_image')->store('box_images', 'public');
                $updateData['box_image'] = basename($filePath);
            }

            $order->update($updateData);
            $order->orderItems()->delete(); 
            $orderItemsData = json_decode($request->input('order_items'), true);
            
            if ($orderItemsData) {
                foreach ($orderItemsData as $item) {
                    $orderItem = $order->orderItems()->create([
                        'po' => $order->po,
                        'party_id' => $order->party_id,
                        'design_id' => $item['design_id'],
                        'size_id' => $item['size_id'],
                        'finish_id' => $item['finish_id'],
                        'order_qty' => $item['order_qty'],
                        'remark' => $item['remark'] ?? null,
                        'pending_qty' => $item['order_qty'],
                        'planning_qty' => 0,
                        'production_qty' => 0,
                        'short_qty' => 0,
                        // 'status' => 'Pending', 
                    ]);
                    
                    if (!empty($item['pallets'])) {
                        foreach ($item['pallets'] as $pallet) {
                            Pallet::create([
                                'purchase_order_item_id' => $orderItem->id,
                                'box_pallet' => $pallet['box_pallet'],
                                'total_pallet' => $pallet['total_pallet'],
                            ]);
                        }
                    }
                }
            }
            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Something went wrong while updating order! ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(PurchaseOrder $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully!');
    }

    public function getAllItem()
    {
        return view('purchase_orders.order_item_list');
    }

    /**
     * Returns data for Yajra Datatables for Purchase Order Items.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrderItemData()
    {
        try {
            $query = PurchaseOrderItem::select([
                'id',
                'purchase_order_id',
                'design_id',
                'size_id',
                'finish_id',
                'po',
                'order_qty',
                'pending_qty',
                'planning_qty',
                'production_qty',
                'short_qty',
                'remark'
            ])->with(['orderMaster', 'designDetail', 'sizeDetail', 'finishDetail']);

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('purchase_order.po', function (PurchaseOrderItem $item) {
                    return $item->orderMaster->po ?? 'N/A';
                })
                ->addColumn('design_detail.name', function (PurchaseOrderItem $item) {
                    return $item->designDetail->name ?? 'N/A';
                })
                ->addColumn('size_detail.size_name', function (PurchaseOrderItem $item) {
                    return $item->sizeDetail->size_name ?? 'N/A';
                })
                ->addColumn('finish_detail.finish_name', function (PurchaseOrderItem $item) {
                    return $item->finishDetail->finish_name ?? 'N/A';
                })
                ->addColumn('actions', function (PurchaseOrderItem $item) {
                    $planningBtn = $item->pending_qty > 0
                        ? "<button class='btn btn-sm btn-outline-info openModal' data-id='{$item->id}' data-type='planning'>Planning</button>"
                        : "<button class='btn btn-sm btn-outline-info disabled' disabled>Planning</button>";

                    $productionBtn = $item->planning_qty > 0
                        ? "<button class='btn btn-sm btn-outline-primary openModal' data-id='{$item->id}' data-type='production'>Production</button>"
                        : "<button class='btn btn-sm btn-outline-primary disabled' disabled>Production</button>";

                    return "<div class='btn-group'>{$planningBtn}{$productionBtn}</div>";
                })
                ->rawColumns(['actions'])
                ->toJson();
        } catch (Exception $e) {
            \Log::error("Yajra DataTables error in getOrderItemData: " . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'draw' => 0,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'An error occurred while fetching order item data. Please check logs for details.'
            ], 500);
        }
    }



    public function getItem($id)
    {
        $item = PurchaseOrderItem::findOrFail($id);

        return response()->json([
            'item' => $item,
            'order_qty' => $item->order_qty,
            'planning_qty' => $item->planning_qty,
            'production_qty' => $item->production_qty,
            'remark' => $item->remark,
        ]);
    }

    public function updateOrderItem(Request $request, $id)
    {
        $item = PurchaseOrderItem::findOrFail($id);
        $type = strtolower($request->type);

        if (!in_array($type, ['planning', 'production'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid quantity type.'
            ], 422);
        }

        $rules = [
            'quantity' => 'required|integer|min:1',
            'remark'   => 'nullable|string|max:255',
            'date'     => 'required|date',
        ];

        if ($type === 'production') {
            $rules['batch_no'] = 'required|string|max:100';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $qty = (int) $request->quantity;

        if (strtolower($type) == 'planning' && $qty > $item->pending_qty) {
            return response()->json([
                'success' => false,
                'errors' => ['quantity' => ['Planning quantity cannot exceed pending quantity']]
            ], 422);
        }
        if($type == 'production'){
            $item->planning_qty = $item->planning_qty - $qty;
            $item->short_qty = $item->short_qty - $qty;
            $item->production_qty = $qty + $item->production_qty;
        } else {
            $item->pending_qty = $item->pending_qty - $qty;
            $item->planning_qty = $qty + $item->planning_qty;
        }
        $item->remark = $request->remark;
        $item->save();
        
        if ($type == 'production') {
            $batchNo = $request->batch_no;
            $batch = PurchaseOrderBatch::where('purchase_order_id', $item->purchase_order_id)
                ->where('purchase_order_item_id', $item->id)
                ->where('batch_no', $batchNo)
                ->first();

            if ($batch) {
                $batch->qty += $qty;
                if ($request->filled('location')) {
                    $batch->location = $request->location;
                }
                $batch->save();
            } else {
                PurchaseOrderBatch::create([
                    'purchase_order_id' => $item->purchase_order_id,
                    'purchase_order_item_id' => $item->id,
                    'batch_no' => $batchNo,
                    'qty' => $qty,
                    'party_id' => $item->party_id,
                    'location' => $request->location ?? null,
                ]);
            }
        }
        $transactionData = [
            'purchase_order_item_id' => $item->id,
            'quantity' => $qty,
            'batch_no' => $request->batch_no ?? null,
            'date' => $request->date,
            'remark' => $request->remark,
            'type' => $type,
            'created_by' => auth()->id() ?? null,
        ];
        if ($request->filled('location')) {
            $transactionData['location'] = $request->location;
        }
        PurchaseOrderItemTransaction::create($transactionData);
        return response()->json(['success' => true]);
    }

    /**
     * Display the print view for a specific Purchase Order.
     */
    public function printView(PurchaseOrder $order)
    {
        $order->load([
            'party', 
            'orderItems.designDetail', 
            'orderItems.sizeDetail', 
            'orderItems.finishDetail'
        ]);
        $companyDetail = CompanyDetail::first(); 
        return view('purchase_orders.print_view', compact('order', 'companyDetail'));
    }

}