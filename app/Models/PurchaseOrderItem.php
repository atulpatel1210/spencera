<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'po',
        'design',
        'size',
        'finish',
        'batch_no',
        'pallet',
        'order_qty',
        'pending_qty',
        'planning_qty',
        'production_qty',
        'short_qty',
        'remark',
        'status',
    ];

    public function orderMaster(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'po', 'po');
    }
}