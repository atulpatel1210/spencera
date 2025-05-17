<?php

namespace App\Http\Controllers;

use App\Models\Design;
use Illuminate\Http\Request;

class DesignController extends Controller
{
    public function index()
    {
        $designs = Design::latest()->paginate(10);
        return view('designs.index', compact('designs'));
    }

    public function create()
    {
        return view('designs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:designs,name'
        ]);

        Design::create($request->only('name'));
        return redirect()->route('designs.index')->with('success', 'Design added successfully.');
    }

    public function edit(Design $design)
    {
        return view('designs.edit', compact('design'));
    }

    public function update(Request $request, Design $design)
    {
        $request->validate([
            'name' => 'required|unique:designs,name,' . $design->id
        ]);

        $design->update($request->only('name'));
        return redirect()->route('designs.index')->with('success', 'Design updated successfully.');
    }

    public function destroy(Design $design)
    {
        $design->delete();
        return redirect()->route('designs.index')->with('success', 'Design deleted successfully.');
    }
}
