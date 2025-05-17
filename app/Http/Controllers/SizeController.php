<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function index()
    {
        $sizes = Size::latest()->paginate(10);
        return view('size.index', compact('sizes'));
    }

    public function create()
    {
        return view('size.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'size_name' => 'required|unique:sizes,size_name',
        ]);

        Size::create([
            'size_name' => $request->size_name,
        ]);

        return redirect()->route('sizes.index')->with('success', 'Size created successfully.');
    }

    public function edit(Size $size)
    {
        return view('size.edit', compact('size'));
    }

    public function update(Request $request, Size $size)
    {
        $request->validate([
            'size_name' => 'required|unique:sizes,size_name,' . $size->id,
        ]);

        $size->update([
            'size_name' => $request->size_name,
        ]);

        return redirect()->route('sizes.index')->with('success', 'Size updated successfully.');
    }

    public function destroy(Size $size)
    {
        $size->delete();
        return redirect()->route('sizes.index')->with('success', 'Size deleted successfully.');
    }
}