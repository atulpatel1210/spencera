<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use App\Models\Design;
use App\Models\Finish;
use App\Models\Party;
use App\Models\PurchaseOrderBatch;
use App\Models\Size;
use App\Models\PurchaseOrderItem;
use Yajra\DataTables\Facades\DataTables;
use Exception;

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
            'party_id' => 'required',
            'order_date' => 'required',
            'order_items' => 'required|array',
            'order_items.*.design' => 'nullable|string',
            'order_items.*.size' => 'nullable|string',
            'order_items.*.finish' => 'nullable|string',
            'order_items.*.order_qty' => 'required|integer|min:0',
            'order_items.*.pending_qty' => 'nullable|integer|min:0',
            'order_items.*.planning_qty' => 'nullable|integer|min:0',
            'order_items.*.production_qty' => 'nullable|integer|min:0',
            'order_items.*.short_qty' => 'nullable|integer|min:0',
            'order_items.*.remark' => 'nullable|string',
            'order_items.*.status' => 'nullable|string',
        ]);

        $purchaseOrder = PurchaseOrder::create([
            'po' => $request->input('po'),
            'party_id' => $request->input('party_id'),
            'order_date' => $request->input('order_date'),
        ]);

        if ($request->has('order_items')) {
            foreach ($request->input('order_items') as $itemData) {
                $purchaseOrder->orderItems()->create(array_merge($itemData, [
                    'purchase_order_id' => $purchaseOrder->id,
                    'po' => $purchaseOrder->po,
                    'party_id' => $purchaseOrder->party_id,
                ]));
            }
        }

        return redirect()->route('orders.index')->with('success', 'Order created successfully!');
    }

    public function index()
    {
        return view('purchase_orders.index');
    }

    public function getOrdersData()
    {
        try {
            $query = PurchaseOrder::select(['id', 'po', 'party_id', 'order_date'])
                                ->with('party');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('party_name', function (PurchaseOrder $order) {
                    return $order->party->party_name ?? 'N/A';
                })
                ->addColumn('actions', function (PurchaseOrder $order) {
                    $viewUrl = route('orders.show', $order->id);
                    $editUrl = route('orders.edit', $order->id);
                    $deleteUrl = route('orders.destroy', $order->id);
                    $csrf = csrf_field();
                    $method = method_field('DELETE');

                    return "
                        <a href='{$viewUrl}' class='btn btn-info btn-sm'>View</a>
                        <a href='{$editUrl}' class='btn btn-primary btn-sm ms-2'>Edit</a>
                        <form action='{$deleteUrl}' method='POST' class='d-inline'>
                            {$csrf}
                            {$method}
                            <button type='submit' class='btn btn-danger btn-sm ms-2' onclick=\"return confirm('Are you sure?')\">Delete</button>
                        </form>
                    ";
                })
                ->rawColumns(['actions'])
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
        $order->load('orderItems');
        $designs = Design::all();
        $sizes = Size::all();
        $finishes = Finish::all();
        $parties = Party::all();
        return view('purchase_orders.edit', compact('order', 'designs', 'sizes', 'finishes', 'parties'));
    }

    public function update(Request $request, PurchaseOrder $order)
    {
        $request->validate([
            'po' => 'required|unique:purchase_orders,po,' . $order->id,
            'party_id' => 'required',
            'order_date' => 'required',
            'order_items' => 'required|array',
            'order_items.*.design' => 'nullable|string',
            'order_items.*.size' => 'nullable|string',
            'order_items.*.finish' => 'nullable|string',
            'order_items.*.order_qty' => 'required|integer|min:0',
            'order_items.*.pending_qty' => 'nullable|integer|min:0',
            'order_items.*.planning_qty' => 'nullable|integer|min:0',
            'order_items.*.production_qty' => 'nullable|integer|min:0',
            'order_items.*.short_qty' => 'nullable|integer|min:0',
            'order_items.*.remark' => 'nullable|string',
            'order_items.*.status' => 'nullable|string',
        ]);

        $order->update(['po' => $request->input('po')]);
        $order->orderItems()->delete();

        $newItems = [];
        if ($request->has('order_items')) {
            foreach ($request->input('order_items') as $itemData) {
                $newItems[] = array_merge($itemData, ['purchase_order_id' => $order->id, 'po' => $order->po, 'party_id' => $order->party_id]);
            }
            $order->orderItems()->createMany($newItems);
        }

        return redirect()->route('orders.index')->with('success', 'Order updated successfully!');
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
            // Corrected: Include foreign keys in the select statement for relationships to work
            $query = PurchaseOrderItem::select([
                'id', // Always good to select ID
                'purchase_order_id', // Foreign key for orderMaster
                'design', // Foreign key for designDetail
                'size',   // Foreign key for sizeDetail
                'finish', // Foreign key for finishDetail
                'po', // Directly select 'po' if it's a column in PurchaseOrderItem
                'order_qty',
                'pending_qty',
                'planning_qty',
                'production_qty',
                'short_qty',
                'remark'
            ])->with(['orderMaster', 'designDetail', 'sizeDetail', 'finishDetail']);

            return DataTables::of($query)
                ->addIndexColumn()
                // Corrected: Use 'orderMaster.po' to match the relationship name in the model
                ->addColumn('purchase_order.po', function (PurchaseOrderItem $item) {
                    return $item->orderMaster->po ?? 'N/A';
                })
                ->addColumn('design_detail.name', function (PurchaseOrderItem $item) {
                    // Removed dd() for production
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
        $type = $request->type;

        if (!in_array($type, ['planning', 'production'])) {
            return response()->json(['success' => false, 'message' => 'Invalid type']);
        }

        $qty = (int) $request->quantity;

        if ($qty > $item->order_qty) {
            return response()->json(['success' => false, 'message' => ucfirst($type).' quantity cannot exceed order quantity']);
        }
        if(strtolower($type) == 'production'){
            $item->planning_qty = $item->planning_qty - $qty;
            $item->short_qty = $item->short_qty - $qty;
            $item->production_qty = $qty + $item->production_qty;
        } else {
            $item->pending_qty = $item->pending_qty - $qty;
            $item->planning_qty = $qty + $item->planning_qty;
        }
        $item->remark = $request->remark;
        $item->save();
        
        $batchNo = $request->batch_no;
        if (strtolower($type) == 'production' && $batchNo) {
            $batch = PurchaseOrderBatch::where('purchase_order_id', $item->purchase_order_id)
                ->where('purchase_order_item_id', $item->id)
                ->where('batch_no', $batchNo)
                ->first();

            if ($batch) {
                $batch->qty += $qty;
                $batch->save();
            } else {
                PurchaseOrderBatch::create([
                    'purchase_order_id' => $item->purchase_order_id,
                    'purchase_order_item_id' => $item->id,
                    'batch_no' => $batchNo,
                    'qty' => $qty,
                    'party_id' => $item->party_id
                ]);
            }
        }
        return response()->json(['success' => true]);
    }

}