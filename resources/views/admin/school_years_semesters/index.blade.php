@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4">
    <h2 class="text-2xl font-semibold mb-6 text-center">Manage School Years & Semesters</h2>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid md:grid-cols-2 gap-6">
        <!-- Add School Year -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h4 class="text-xl font-semibold mb-4">Add School Year</h4>
            <form method="POST" action="{{ route('admin.school-years.store') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="year">
                        School Year
                    </label>
                    <input 
                        type="text" 
                        name="year" 
                        id="year"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                        placeholder="e.g., 2024-2025" 
                        required
                    >
                </div>
                <button 
                    type="submit" 
                    class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                >
                    Add School Year
                </button>
            </form>
        </div>

        <!-- Add Semester -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h4 class="text-xl font-semibold mb-4">Add Semester</h4>
            <form method="POST" action="{{ route('admin.semesters.store') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="semester-name">
                        Semester Name
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        id="semester-name"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                        placeholder="e.g., 1st Semester" 
                        required
                    >
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="school-year">
                        School Year
                    </label>
                    <select 
                        name="school_year_id" 
                        id="school-year"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                        required
                    >
                        <option value="">Select School Year</option>
                        @foreach($schoolYears as $year)
                            <option value="{{ $year->id }}">{{ $year->year }}</option>
                        @endforeach
                    </select>
                </div>
                <button 
                    type="submit" 
                    class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                >
                    Add Semester
                </button>
            </form>
        </div>
    </div>

    <!-- List of School Years & Semesters -->
    <div class="bg-white shadow-md rounded-lg p-6 mt-6">
        <h4 class="text-xl font-semibold mb-4">Existing School Years & Semesters</h4>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-3 text-left">School Year</th>
                        <th class="border p-3 text-left">Semesters</th>
                        <th class="border p-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schoolYears as $year)
                        <tr class="hover:bg-gray-100">
                            <td class="border p-3">{{ $year->year }}</td>
                            <td class="border p-3">
                                @if($year->semesters->count() > 0)
                                    <ul class="space-y-2">
                                        @foreach($year->semesters as $semester)
                                            <li class="flex items-center justify-between">
                                                <span>{{ $semester->name }}</span>
                                                <form 
                                                    action="{{ route('admin.semesters.destroy', $semester->id) }}" 
                                                    method="POST" 
                                                    class="inline-block"
                                                >
                                                    @csrf
                                                    @method('DELETE')
                                                    <button 
                                                        type="submit" 
                                                        class="bg-red-500 hover:bg-red-700 text-white text-xs py-1 px-2 rounded"
                                                        onclick="return confirm('Are you sure you want to delete this semester?');"
                                                    >
                                                        Delete
                                                    </button>
                                                </form>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-gray-500">No Semesters Added</span>
                                @endif
                            </td>
                            <td class="border p-3">
                                <form 
                                    action="{{ route('admin.school-years.destroy', $year->id) }}" 
                                    method="POST" 
                                    class="inline-block"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button 
                                        type="submit" 
                                        class="bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded"
                                        onclick="return confirm('Are you sure you want to delete this school year?');"
                                    >
                                        Delete School Year
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection