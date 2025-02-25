<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolYear;
use Illuminate\Http\Request; 

class SchoolYearController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required|string|unique:school_years,year',
        ]);

        SchoolYear::create(['year' => $request->year]);

        return back()->with('success', 'School Year added successfully.');
    }
}
