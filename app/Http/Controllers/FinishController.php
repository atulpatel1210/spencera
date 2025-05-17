<?php

namespace App\Http\Controllers;

use App\Models\Finish;
use Illuminate\Http\Request;

class FinishController extends Controller
{
    public function index()
    {
        $finishes = Finish::latest()->paginate(10);
        return view('finishes.index', compact('finishes'));
    }

    public function create()
    {
        return view('finishes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'finish_name' => 'required|unique:finishes,finish_name'
        ]);

        Finish::create($request->only('finish_name'));
        return redirect()->route('finishes.index')->with('success', 'Finish added successfully.');
    }

    public function edit(Finish $finish)
    {
        return view('finishes.edit', compact('finish'));
    }

    public function update(Request $request, Finish $finish)
    {
        $request->validate([
            'finish_name' => 'required|unique:finishes,finish_name,' . $finish->id
        ]);

        $finish->update($request->only('finish_name'));
        return redirect()->route('finishes.index')->with('success', 'Finish updated successfully.');
    }

    public function destroy(Finish $finish)
    {
        $finish->delete();
        return redirect()->route('finishes.index')->with('success', 'Finish deleted successfully.');
    }
}
