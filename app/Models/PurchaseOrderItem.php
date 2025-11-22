<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'po',
        'party_id',
        'design_id',
        'size_id',
        'finish_id',
        'pallet_id',
        'batch_no',
        //'pallet', // renamed to pallet_id
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

    public function sizeDetail(): BelongsTo
    {
        return $this->belongsTo(Size::class, 'size_id', 'id');
    }

    public function designDetail(): BelongsTo
    {
        return $this->belongsTo(Design::class, 'design_id', 'id');
    }

    public function finishDetail(): BelongsTo
    {
        return $this->belongsTo(Finish::class, 'finish_id', 'id');
    }

    public function batchDetail(): HasMany
    {
        return $this->hasMany(PurchaseOrderBatch::class, 'purchase_order_item_id', 'id');
    }

    public function pallets(): HasMany
    {
        return $this->hasMany(Pallet::class, 'purchase_order_item_id', 'id');
    }
}