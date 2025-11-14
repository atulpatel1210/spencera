<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItemTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_item_id',
        'quantity',
        'batch_no',
        'location',
        'date',
        'remark',
        'type',
        'created_by',
        'updated_by',
    ];

    public function item()
    {
        return $this->belongsTo(PurchaseOrderItem::class, 'purchase_order_item_id');
    }
}
