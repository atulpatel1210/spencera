<?php

namespace App\Http\Controllers;

use App\Models\Pallet;
use Illuminate\Http\Request;

class PalletController extends Controller
{
    public function index()
    {
        $pallets = Pallet::latest()->paginate(10);
        return view('pallet.index', compact('pallets'));
    }

    public function create()
    {
        return view('pallet.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pallet_name' => 'required|unique:pallets,pallet_name|max:255',
        ]);

        Pallet::create($request->all());

        return redirect()->route('pallet.index')->with('success', 'Pallet created successfully.');
    }

    public function edit(Pallet $pallet)
    {
        return view('pallet.edit', compact('pallet'));
    }

    public function update(Request $request, Pallet $pallet)
    {
        $request->validate([
            'pallet_name' => 'required|unique:pallets,pallet_name,' . $pallet->id,
        ]);

        $pallet->update($request->all());

        return redirect()->route('pallet.index')->with('success', 'Pallet updated successfully.');
    }

    public function destroy(Pallet $pallet)
    {
        $pallet->delete();

        return redirect()->route('pallet.index')->with('success', 'Pallet deleted successfully.');
    }
}
