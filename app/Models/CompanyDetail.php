<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyDetail extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name', 'address_line1', 'address_line2', 'city', 'state', 'zip',
        'contact_person', 'phone', 'pan_number', 'gst_no'
    ];
}