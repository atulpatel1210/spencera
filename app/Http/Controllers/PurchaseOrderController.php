<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use App\Models\Design;
use App\Models\Finish;
use App\Models\PurchaseOrderBatch;
use App\Models\Size;
use App\Models\PurchaseOrderItem;

class PurchaseOrderController extends Controller
{
    public function create()
    {
        $designs = Design::all();
        $sizes = Size::all();
        $finishes = Finish::all();
        // $batchNos = 
        // $pallets = 

        return view('purchase_orders.create', compact('designs', 'sizes', 'finishes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'po' => 'required|unique:purchase_orders,po',
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
        ]);

        if ($request->has('order_items')) {
            foreach ($request->input('order_items') as $itemData) {
                $purchaseOrder->orderItems()->create(array_merge($itemData, [
                    'purchase_order_id' => $purchaseOrder->id,
                    'po' => $purchaseOrder->po, // Include 'po' here
                ]));
            }
        }

        return redirect()->route('orders.index')->with('success', 'Order created successfully!');
    }

    public function index()
    {
        $orders = PurchaseOrder::with('orderItems')->latest()->paginate(10);
        return view('purchase_orders.index', compact('orders'));
    }

    public function show(PurchaseOrder $order)
    {
        $order->load('orderItems');
        return view('purchase_orders.show', compact('order'));
    }

    public function edit(PurchaseOrder $order)
    {
        $order->load('orderItems');
        $designs = Design::all();
        $sizes = Size::all();
        $finishes = Finish::all();
        return view('purchase_orders.edit', compact('order', 'designs', 'sizes', 'finishes'));
    }

    public function update(Request $request, PurchaseOrder $order)
    {
        $request->validate([
            'po' => 'required|unique:purchase_orders,po,' . $order->id,
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
                $newItems[] = array_merge($itemData, ['purchase_order_id' => $order->id, 'po' => $order->po]); // Include 'po' here
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

    public function getItemData($id)
    {
        $item = PurchaseOrderItem::findOrFail($id);

        return response()->json([
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
                ]);
            }
        }
        return response()->json(['success' => true]);
    }

}