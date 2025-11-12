<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Design extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'party_id', 'image'];

    public function party()
    {
        return $this->belongsTo(Party::class);
    }
}
