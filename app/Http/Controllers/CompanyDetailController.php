<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyDetail;

class CompanyDetailController extends Controller
{
    // READ (Show): અહીં આપણે Edit ફોર્મ જ સીધું બતાવીશું
    public function edit()
    {
        // જો રેકોર્ડ અસ્તિત્વમાં ન હોય તો નવો ખાલી રેકોર્ડ બનાવો (Single Row Setup)
        $company = CompanyDetail::firstOrCreate([]); 
        return view('company_details.edit', compact('company'));
    }

    // UPDATE: વિગતોને અપડેટ કરો
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip' => 'nullable|string|max:20',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'pan_number' => 'nullable|string|max:20',
            'gst_no' => 'nullable|string|max:15',
        ]);

        $company = CompanyDetail::firstOrCreate([]);
        $company->update($request->all());

        return redirect()->route('company.edit')->with('success', 'Company details updated successfully.');
    }
}