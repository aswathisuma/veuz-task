<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $companies = Company::select(['id', 'name', 'email', 'website', 'logo', 'created_at']);

                return DataTables::of($companies)
                    ->addIndexColumn()
                    ->editColumn('name', fn($row) => e($row->name))
                    ->editColumn('email', fn($row) => e($row->email))
                    ->editColumn('website', fn($row) => e($row->website))
                    ->addColumn('logo', function ($company) {
                        return $company->logo
                            ? '<img src="' . asset('storage/' . $company->logo) . '" width="50" height="50" class="rounded">'
                            : '<span class="text-muted">No Logo</span>';
                    })
                    ->addColumn('created_at', function ($company) {
                        return $company->created_at ?: $company->created_at->format('d-m-Y H:i A');
                    })
                    ->addColumn('actions', function ($company) {
                        $editUrl = route('companies.edit', $company->id);
                        return '
                            <div class="btn-group" role="group">
                                <button class="btn btn-info btn-sm btn-view" data-id="' . $company->id . '">View</button>
                                <a href="' . $editUrl . '" class="btn btn-warning btn-sm">Edit</a>
                                <button class="btn btn-danger btn-sm btn-delete" data-id="' . $company->id . '">Delete</button>
                            </div>
                        ';
                    })
                    ->rawColumns(['logo', 'actions'])
                    ->make(true);
            }
            return view('company.index');
        } catch (Exception $e) {
            Log::error('Company list load failed: ' . $e->getMessage());
            return response()->json([
                'error' => 'Something went wrong while loading data. Please refresh the page.'
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('company.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'website' => 'required|url|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048|dimensions:min_width=100,min_height=100',
        ]);

        try {
            if ($request->hasFile('logo')) {
                $validated['logo'] = $request->file('logo')->store('logos', 'public');
            }

            Company::create($validated);
            return redirect()
                ->route('companies.index')
                ->with('success', 'Company created successfully!');
        } catch (Exception $e) {
            Log::error('Company creation failed: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Something went wrong while creating the company. Please check your inputs and try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        $company->created_at_formatted = $company->created_at ? $company->created_at->format('d-m-Y H:i A') : '-';
        return response()->json($company);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        return view('company.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'website' => 'required|url|max:255',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048|dimensions:min_width=100,min_height=100',
            ]);

            if ($request->hasFile('logo')) {
                if ($company->logo && Storage::disk('public')->exists($company->logo)) {
                    Storage::disk('public')->delete($company->logo);
                }
                $validated['logo'] = $request->file('logo')->store('logos', 'public');
            }

            $company->update($validated);

            return redirect()
                ->route('companies.index')
                ->with('success', 'Company updated successfully!');
        } catch (Exception $e) {
            Log::error('Company update failed: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Something went wrong while updating the company. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        try {
            $company->delete();
            return response()->json(['success' => true, 'message' => 'Company deleted successfully!']);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete company.']);
        }
    }
}
