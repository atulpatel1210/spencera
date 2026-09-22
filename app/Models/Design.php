<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Design extends Model
{
    use \App\Traits\BelongsToCompany;

    use HasFactory;

    protected $fillable = ['name', 'party_id', 'image'];

    public function parties()
    {
        return $this->belongsToMany(Party::class, 'design_party');
    }
}
