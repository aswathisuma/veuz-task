<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $employessCount = Employee::count();
        $companiesCount = Company::count();
        return view('dashboard', compact('employessCount', 'companiesCount'));
    }

    
}
