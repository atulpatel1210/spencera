<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_item_id',
        'box_pallet',
        'total_pallet',
        'total_boxe_pallets',
    ];

    /**
     * Relation: Pallet belongs to Purchase Order Item
     */
    public function orderItem()
    {
        return $this->belongsTo(PurchaseOrderItem::class, 'purchase_order_item_id');
    }
}