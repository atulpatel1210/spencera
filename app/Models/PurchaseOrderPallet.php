<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrderPallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'po',
        'purchase_order_item_id',
        'design',
        'size',
        'finish',
        'batch_id',
        'pallet_size',
        'pallet_no',
        'total_qty',
        'packing_date',
        'remark',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function purchaseOrderItem()
    {
        return $this->belongsTo(PurchaseOrderItem::class);
    }

    public function purchaseOrderBatch()
    {
        return $this->belongsTo(PurchaseOrderBatch::class, 'batch_id');
    }

    public function sizeDetail(): BelongsTo
    {
        return $this->belongsTo(Size::class, 'size', 'id');
    }

    public function designDetail(): BelongsTo
    {
        return $this->belongsTo(Design::class, 'design', 'id');
    }

    public function finishDetail(): BelongsTo
    {
        return $this->belongsTo(Finish::class, 'finish', 'id');
    }

    // public function batchDetail(): HasMany
    // {
    //     return $this->hasMany(PurchaseOrderBatch::class, 'purchase_order_item_id', 'id');
    // }
}