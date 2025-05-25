<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Party extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'party_name',
        'party_type',
        'contact_person',
        'email',
        'contact_no',
        'mobile_no', 
        'address',   
        'gst_no',    
    ];

    public function party(): BelongsTo
    {
        return $this->belongsTo(Party::class, 'party_id', 'id');
    }
}
