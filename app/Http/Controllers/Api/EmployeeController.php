<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Throwable;

class EmployeeController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        try {
            $employees = Employee::with('company:id,name,email,website,logo')
                ->select('id', 'first_name', 'last_name', 'company_id', 'email', 'phone')
                ->get();

            if ($employees->isEmpty()) {
                return $this->successResponse([], 'No employee data found.');
            }

            return $this->successResponse($employees, 'Employee list fetched successfully.');
        } catch (Throwable $e) {
            return $this->errorResponse('Something went wrong. Please try again later.', 500, [
                'exception' => $e->getMessage(),
            ]);
        }
    }
}
