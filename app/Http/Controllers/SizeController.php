<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SizesImport;
use App\Exports\SampleSizeExport;

class SizeController extends Controller
{
    public function index()
    {
        return view('size.index');
    }

    public function getSizesData()
    {
        $query = Size::select(['id', 'size_name']);

        return DataTables::of($query)
            ->addIndexColumn() 
            ->addColumn('actions', function(Size $size) {
                $editUrl = route('sizes.edit', $size->id);
                $deleteUrl = route('sizes.destroy', $size->id);
                $csrf = csrf_field();
                $method = method_field('DELETE');

                return "
                    <a href='{$editUrl}' title='Edit' class='btn btn-sm btn-outline-primary rounded-circle'>
                        <i class='bi bi-pencil'></i>
                    </a>
                    <form action='{$deleteUrl}' method='POST' class='d-inline' onsubmit=\"return confirm('Are you sure you want to delete this size?')\" style='display:inline-block;'>
                        {$csrf}
                        {$method}
                        <button type='submit' title='Delete' class='btn btn-sm btn-outline-danger rounded-circle'>
                            <i class='bi bi-trash'></i>
                        </button>
                    </form>
                ";
            })
            ->rawColumns(['actions'])
            ->toJson();
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

    public function downloadSampleFile()
    {
        return Excel::download(new SampleSizeExport, 'sample_size_import.xlsx');
    }

    public function showImportForm()
    {
        return view('size.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|mimes:xlsx,xls,csv'
        ]);

        $import = new SizesImport();
        Excel::import($import, $request->file('import_file'));

        return back()->with([
            'successCount' => $import->successCount,
            'errors' => $import->customFailures,
        ]);
    }
}