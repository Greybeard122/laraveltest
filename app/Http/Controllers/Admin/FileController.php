<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Http\Request;

class FileController extends Controller
{
    /**
     * Display the list of files for admin.
     */
    public function index()
    {
        $files = File::all(); // Retrieve all files from the database
        return view('admin.files.index', compact('files'));
    }

    /**
     * Update the availability of a file.
     */
    public function updateAvailability(File $file)
    {
        // Toggle the availability status
        $file->update(['is_available' => !$file->is_available]);
        return back()->with('success', 'File availability updated.');
    }

    public function show($id)
    {
    $file = File::findOrFail($id);
    return view('admin.files.show', compact('file')); 
    }
    public function create()
{
    return view('admin.files.create'); // Create a new file
}

public function edit($id)
{
    $file = File::findOrFail($id);
    return view('admin.files.edit', compact('file')); // Edit the existing file
}


public function update(Request $request, $id)
{
    // Validate the input
    $request->validate([
        'name' => 'required|string|max:255',
    ]);

    // Find the file to update
    $file = File::findOrFail($id);

    // Update the file's data
    $file->update([
        'file_name' => $request->name, // Corrected to 'file_name'
        'is_available' => $request->has('is_available'), // true if checked, false if unchecked
    ]);

    // Redirect back with a success message
    return redirect()->route('admin.files.index')->with('success', 'File updated successfully.');
}


    public function store(Request $request)
{
    // Validate the request
    $request->validate([
        'name' => 'required|string|max:255',
    ]);

    // Create a new file
    $file = File::create([
        
        'file_name' => $request->name,

        'is_available' => $request->has('is_available'), // true if the checkbox is checked, false otherwise
    ]);

    // Return a success message and redirect to the files index
    return redirect()->route('admin.files.index')->with('success', 'File created successfully.');
}


    public function destroy($id)
    {
        $file = File::findOrFail($id);
        $file->delete();

        return redirect()->route('admin.files.index')->with('success', 'File deleted successfully');
    }

}
