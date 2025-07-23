<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderPalletDesign extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'purchase_order_pallet_designs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'purchase_order_pallet_id',
        'design_id',
        'size_id',
        'finish_id',
        'quantity',
    ];

    /**
     * Get the purchase order pallet that owns the detailed design.
     */
    public function purchaseOrderPallet()
    {
        return $this->belongsTo(PurchaseOrderPallet::class, 'purchase_order_pallet_id');
    }

    /**
     * Get the design associated with the detailed pallet design.
     */
    public function design()
    {
        return $this->belongsTo(Design::class, 'design_id');
    }

    /**
     * Get the size associated with the detailed pallet design.
     */
    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id');
    }

    /**
     * Get the finish associated with the detailed pallet design.
     */
    public function finish()
    {
        return $this->belongsTo(Finish::class, 'finish_id');
    }
}