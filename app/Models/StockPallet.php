<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockPallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'purchase_order_item_id',
        'batch_id',
        'design_id',
        'size_id',
        'finish_id',
        'pallet_size',
        'pallet_no',
        'current_qty', // Changed from total_qty
        'remark',
    ];

    /**
     * Get the purchase order that owns the stock pallet.
     */
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    /**
     * Get the purchase order item that owns the stock pallet.
     */
    public function purchaseOrderItem()
    {
        return $this->belongsTo(PurchaseOrderItem::class);
    }

    /**
     * Get the batch that owns the stock pallet.
     */
    public function batch()
    {
        return $this->belongsTo(PurchaseOrderBatch::class, 'batch_id');
    }

    /**
     * Get the design detail for the stock pallet.
     */
    public function designDetail()
    {
        return $this->belongsTo(Design::class, 'design_id');
    }

    /**
     * Get the size detail for the stock pallet.
     */
    public function sizeDetail()
    {
        return $this->belongsTo(Size::class, 'size_id');
    }

    /**
     * Get the finish detail for the stock pallet.
     */
    public function finishDetail()
    {
        return $this->belongsTo(Finish::class, 'finish_id');
    }
}
