<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Employee;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->ensureAuthenticated();
    }

    private function ensureAuthenticated()
    {
        if (!Auth::check()) {
            header('Location: ' . route('login'));
            exit;
        }
    }

    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $employees = Employee::with('company:id,name')
                    ->select(['id', 'first_name', 'last_name', 'email', 'phone', 'company_id', 'created_at']);

                return DataTables::of($employees)
                    ->addIndexColumn()
                    ->editColumn('first_name', fn($row) => e($row->first_name))
                    ->editColumn('last_name', fn($row) => e($row->last_name))
                    ->editColumn('email', fn($row) => e($row->email))
                    ->editColumn('phone', fn($row) => e($row->phone))
                    ->addColumn('company', fn($row) => optional($row->company)->name ?? '<span class="text-muted">N/A</span>')
                    ->addColumn('created_at', fn($row) => $row->created_at ? $row->created_at->format('d-m-Y H:i A') : '-')
                    ->addColumn('actions', function ($row) {
                        $editUrl = route('employees.edit', $row->id);
                        return '
                            <div class="btn-group" role="group">
                                <button class="btn btn-info btn-sm btn-view" data-id="' . $row->id . '">View</button>
                                <a href="' . $editUrl . '" class="btn btn-warning btn-sm">Edit</a>
                                <button class="btn btn-danger btn-sm btn-delete" data-id="' . $row->id . '">Delete</button>
                            </div>
                        ';
                    })
                    ->rawColumns(['company', 'actions'])
                    ->make(true);
            }

            return view('employee.index');
        } catch (Exception $e) {
            Log::error('Employee list load failed: ' . $e->getMessage());
            return response()->json([
                'error' => 'Something went wrong while loading employee data. Please refresh the page.'
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = Company::pluck('name', 'id');
        return view('employee.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:15',
            'company_id' => 'required|exists:companies,id',
        ]);
        try {
            Employee::create($validated);

            return redirect()
                ->route('employees.index')
                ->with('success', 'Employee created successfully!');
        } catch (Exception $e) {
            Log::error('Employee creation failed: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Something went wrong while creating the employee. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        $employee->load('company:id,name');
        $employee->created_at_formatted = $employee->created_at ? $employee->created_at->format('d-m-Y H:i A') : '-';
        return response()->json($employee);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $companies = Company::pluck('name', 'id');
        return view('employee.edit', compact('employee', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:15',
                'company_id' => 'required|exists:companies,id',
            ]);

            $employee->update($validated);

            return redirect()
                ->route('employees.index')
                ->with('success', 'Employee updated successfully!');
        } catch (Exception $e) {
            Log::error('Employee update failed: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Something went wrong while updating the employee. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        try {
            $employee->delete();
            return response()->json(['success' => true, 'message' => 'Employee deleted successfully!']);
        } catch (Throwable $e) {
            Log::error('Employee deletion failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to delete employee.']);
        }
    }
}
