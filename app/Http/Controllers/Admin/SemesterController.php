<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use App\Models\SchoolYear;
use Illuminate\Http\Request; 

class SemesterController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'school_year_id' => 'required|exists:school_years,id',
        ]);

        Semester::create([
            'name' => $request->name,
            'school_year_id' => $request->school_year_id,
        ]);

        return back()->with('success', 'Semester added successfully.');
    }
}
