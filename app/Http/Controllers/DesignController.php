<?php

namespace App\Http\Controllers;

use App\Exports\SampleDesignExport;
use App\Imports\DesignsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Design;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Party;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DesignController extends Controller
{
    public function index()
    {
        return view('designs.index');
    }

    public function getDesignsData()
    {
        $query = Design::with('party')->select(['id', 'name', 'party_id', 'image']);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('party_name', function(Design $design) {
                return $design->party->party_name ?? '-';
            })
            ->addColumn('image', function(Design $design) {
                if ($design->image && Storage::disk('public')->exists('designs/'.$design->image)) {
                    return '<img src="'.asset('storage/designs/'.$design->image).'" width="60" height="60" class="rounded">';
                }
                return '-';
            })
            ->addColumn('actions', function(Design $design) {
                $editUrl = route('designs.edit', $design->id);
                $deleteUrl = route('designs.destroy', $design->id);
                $csrf = csrf_field();
                $method = method_field('DELETE');

                return "
                    <a href='{$editUrl}' title='Edit' class='btn btn-sm btn-outline-primary rounded-circle'>
                        <i class='bi bi-pencil'></i>
                    </a>
                    <form action='{$deleteUrl}' method='POST' class='d-inline' onsubmit=\"return confirm('Are you sure you want to delete this design?')\" style='display:inline-block;'>
                        {$csrf}
                        {$method}
                        <button type='submit' title='Delete' class='btn btn-sm btn-outline-danger rounded-circle'>
                            <i class='bi bi-trash'></i>
                        </button>
                    </form>
                ";
            })
            ->rawColumns(['image', 'actions'])
            ->toJson();
    }

    public function create()
    {
        $parties = Party::select('id', 'party_name')->orderBy('party_name')->get();
        return view('designs.create', compact('parties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'party_id' => 'required|exists:parties,id',
            'name' => [
                'required',
                Rule::unique('designs')->where(fn($q) => $q->where('party_id', $request->party_id)),
            ],
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only('name', 'party_id');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('designs', 'public');
            $data['image'] = basename($data['image']);
        }

        Design::create($data);
        return redirect()->route('designs.index')->with('success', 'Design added successfully.');
    }

    public function edit(Design $design)
    {
        $parties = Party::select('id', 'party_name')->orderBy('party_name')->get();
        return view('designs.edit', compact('design', 'parties'));
    }

    public function update(Request $request, Design $design)
    {
        $request->validate([
            'party_id' => 'required|exists:parties,id',
            'name' => [
                'required',
                Rule::unique('designs')->where(fn($q) => $q->where('party_id', $request->party_id))
                    ->ignore($design->id),
            ],
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only('name', 'party_id');

        if ($request->hasFile('image')) {
            if ($design->image && Storage::disk('public')->exists('designs/' . $design->image)) {
                Storage::disk('public')->delete('designs/' . $design->image);
            }

            $path = $request->file('image')->store('designs', 'public');
            $data['image'] = basename($path);
        } else {
            $data['image'] = $design->image;
        }

        $design->update($data);
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

    public function getDesignByParty(Request $request)
    {
        $designs = Design::where('party_id', $request->party_id)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json($designs);
    }
}
