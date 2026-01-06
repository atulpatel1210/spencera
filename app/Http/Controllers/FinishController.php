<?php

namespace App\Http\Controllers;

use App\Exports\SampleFinishExport;
use App\Imports\FinishesImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Finish;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FinishController extends Controller
{
    public function index()
    {
        return view('finishes.index');
    }

    public function getFinishesData()
    {
        $query = Finish::select(['id', 'finish_name']);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('actions', function(Finish $finish) {
                $editUrl = route('finishes.edit', $finish->id);
                $deleteUrl = route('finishes.destroy', $finish->id);
                $csrf = csrf_field();
                $method = method_field('DELETE');

                return "
                    <a href='{$editUrl}' title='Edit' class='btn btn-sm text-primary'>
                        <i class='fa fa-edit fa-fw fa-lg'></i>
                    </a>
                    <form action='{$deleteUrl}' method='POST' class='d-inline' onsubmit=\"return confirm('Are you sure you want to delete this finish?')\" style='display:inline-block;vertical-align:middle;margin-right:0;'>
                        {$csrf}
                        {$method}
                        <button type='submit' title='Delete' class='btn btn-sm text-danger'>
                            <i class='fa fa-trash fa-fw fa-lg'></i>
                        </button>
                    </form>
                ";
            })
            ->rawColumns(['actions'])
            ->toJson();
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

    public function downloadSampleFile()
    {
        return Excel::download(new SampleFinishExport, 'sample_finish_import.xlsx');
    }

    public function showImportForm()
    {
        return view('finishes.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|mimes:xlsx,xls,csv'
        ]);

        $import = new FinishesImport();
        Excel::import($import, $request->file('import_file'));

        return back()->with([
            'successCount' => $import->successCount,
            'errors' => $import->customFailures,
        ]);
    }
}
