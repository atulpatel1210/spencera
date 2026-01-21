<?php

namespace App\Http\Controllers;

use App\Exports\SamplePartyExport;
use App\Imports\PartiesImport;
use App\Models\Party;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Exception;
use Maatwebsite\Excel\Facades\Excel;

class PartyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('parties.index');
    }

    /**
     * Returns data for Yajra Datatables.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPartiesData()
    {
        try {
            $query = Party::select([
                'id',
                'party_name',
                'party_type',
                'contact_person',
                'email',
                'contact_no',
                'mobile_no',
                'address',
                'gst_no',
            ]);

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('actions', function(Party $party) {
                    $editUrl = route('parties.edit', $party->id);
                    $deleteUrl = route('parties.destroy', $party->id);
                    $csrf = csrf_field();
                    $method = method_field('DELETE');

                    return "
                        <a href='{$editUrl}' title='Edit' class='btn btn-sm btn-outline-primary rounded-circle'>
                            <i class='bi bi-pencil'></i>
                        </a>
                        <form action='{$deleteUrl}' method='POST' class='d-inline' onsubmit=\"return confirm('Are you sure?')\" style='display:inline-block;'>
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
        } catch (Exception $e) {
            \Log::error("Yajra DataTables error in PartyController: " . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'draw' => 0,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'An error occurred while fetching data. Please check logs for details.'
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('parties.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'party_name' => 'required|string|max:255|unique:parties,party_name',
            'party_type' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'contact_no' => 'nullable|string|max:20',
            'mobile_no' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'gst_no' => 'nullable|string|size:15',
        ]);

        Party::create($request->all());

        return redirect()->route('parties.index')->with('success', 'Party created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Party $party)
    {
        // Not typically used for simple CRUD, but can be implemented if needed
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Party $party)
    {
        return view('parties.edit', compact('party'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Party $party)
    {
        $request->validate([
            'party_name' => 'required|string|max:255|unique:parties,party_name,' . $party->id,
            'party_type' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'contact_no' => 'nullable|string|max:20',
            'mobile_no' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'gst_no' => 'nullable|string|size:15',
        ]);

        $party->update($request->all());

        return redirect()->route('parties.index')->with('success', 'Party updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Party $party)
    {
        $party->delete();

        return redirect()->route('parties.index')->with('success', 'Party deleted successfully.');
    }

    public function downloadSampleFile()
    {
        return Excel::download(new SamplePartyExport, 'sample_party_import.xlsx');
    }

    public function showImportForm()
    {
        return view('parties.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|mimes:xlsx,xls,csv'
        ]);

        $import = new PartiesImport();
        Excel::import($import, $request->file('import_file'));

        return back()->with([
            'successCount' => $import->successCount,
            'errors' => $import->customFailures,
        ]);
    }
}
