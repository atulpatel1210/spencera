<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'party_id',
        'purchase_order_id',
        'purchase_order_item_id',
        'po',
        'pallet_id',
        'stock_id',
        'batch_id',
        'dispatched_qty',
        'dispatch_date',
        'vehicle_no',
        'container_no',
        'remark',
    ];

    /**
     * Get the party associated with the dispatch.
     */
    public function party()
    {
        return $this->belongsTo(Party::class);
    }

    /**
     * Get the purchase order that owns the dispatch.
     */
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    /**
     * Get the purchase order item that owns the dispatch.
     */
    public function purchaseOrderItem()
    {
        return $this->belongsTo(PurchaseOrderItem::class);
    }

    /**
     * Get the pallet from which the items were dispatched.
     */
    public function stockPallet()
    {
        return $this->belongsTo(StockPallet::class, 'pallet_id');
    }

    /**
     * Get the batch associated with the dispatch.
     */
    public function batch()
    {
        return $this->belongsTo(PurchaseOrderBatch::class, 'batch_id');
    }
}
