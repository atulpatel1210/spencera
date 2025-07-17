<?php

namespace App\Http\Controllers;

use App\Exports\SampleDesignExport;
use App\Imports\DesignsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Design;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DesignController extends Controller
{
    public function index()
    {
        return view('designs.index');
    }

    public function getDesignsData()
    {
        $query = Design::select(['id', 'name']);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('actions', function(Design $design) {
                $editUrl = route('designs.edit', $design->id);
                $deleteUrl = route('designs.destroy', $design->id);
                $csrf = csrf_field();
                $method = method_field('DELETE');

                return "
                    <a href='{$editUrl}' class='btn btn-sm btn-warning'>Edit</a>
                    <form action='{$deleteUrl}' method='POST' style='display:inline;'>
                        {$csrf}
                        {$method}
                        <button type='submit' onclick=\"return confirm('Are you sure you want to delete this design?')\" class='btn btn-sm btn-danger'>Del</button>
                    </form>
                ";
            })
            ->rawColumns(['actions'])
            ->toJson();
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

    public function downloadSampleFile()
    {
        return Excel::download(new SampleDesignExport, 'sample_design_import.xlsx');
    }

    public function showImportForm()
    {
        return view('designs.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|mimes:xlsx,xls,csv'
        ]);

        $import = new DesignsImport();
        Excel::import($import, $request->file('import_file'));

        return back()->with([
            'successCount' => $import->successCount,
            'errors' => $import->customFailures,
        ]);
    }
}
