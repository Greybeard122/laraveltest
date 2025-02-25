<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolYear;
use App\Models\Semester;
use Illuminate\Http\Request;

class SchoolYearSemesterController extends Controller
{
    // Display the School Year & Semester Management Page
    public function index()
    {
        $schoolYears = SchoolYear::with('semesters')->orderBy('year', 'desc')->get();
        return view('admin.school_years_semesters.index', compact('schoolYears'));
    }

    // Store a New School Year
    public function storeSchoolYear(Request $request)
    {
        $request->validate([
            'year' => 'required|string|unique:school_years,year',
        ]);

        SchoolYear::create(['year' => $request->year]);

        return back()->with('success', 'School Year added successfully.');
    }

    // Store a New Semester
    public function storeSemester(Request $request)
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
