<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}