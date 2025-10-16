<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockPallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'party_id',
        'purchase_order_id',
        'purchase_order_item_id',
        'po',
        'batch_id',
        'design',
        'size',
        'finish',
        'pallet_size',
        'pallet_no',
        'current_qty',
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
        return $this->belongsTo(Design::class, 'design');
    }

    /**
     * Get the size detail for the stock pallet.
     */
    public function sizeDetail()
    {
        return $this->belongsTo(Size::class, 'size');
    }

    /**
     * Get the finish detail for the stock pallet.
     */
    public function finishDetail()
    {
        return $this->belongsTo(Finish::class, 'finish');
    }

    /**
     * Get the finish detail for the stock pallet.
     */
    public function partyDetail()
    {
        return $this->belongsTo(Party::class, 'party_id');
    }
}
