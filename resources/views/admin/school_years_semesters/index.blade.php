@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 w-full">
    <h2 class="text-2xl font-semibold mb-4">Manage School Years & Semesters</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Add School Year -->
        <div class="bg-white p-6 shadow-lg rounded-lg">
            <h4 class="text-lg font-semibold mb-4">Add School Year</h4>
            <form method="POST" action="{{ route('admin.school-years.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">School Year</label>
                    <input type="text" name="year" class="form-control" placeholder="e.g., 2024-2025" required>
                </div>
                <button type="submit" class="btn btn-primary w-full">Add School Year</button>
            </form>
        </div>

        <!-- Add Semester -->
        <div class="bg-white p-6 shadow-lg rounded-lg">
            <h4 class="text-lg font-semibold mb-4">Add Semester</h4>
            <form method="POST" action="{{ route('admin.semesters.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Semester Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g., 1st Semester" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">School Year</label>
                    <select name="school_year_id" class="form-control" required>
                        @foreach($schoolYears as $year)
                            <option value="{{ $year->id }}">{{ $year->year }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-full">Add Semester</button>
            </form>
        </div>
    </div>

    <!-- List of School Years & Semesters -->
    <div class="bg-white p-6 mt-6 shadow-lg rounded-lg">
        <h4 class="text-lg font-semibold mb-4">Existing School Years & Semesters</h4>
        <div class="overflow-x-auto">
            <table class="table-auto w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="border px-4 py-2">School Year</th>
                        <th class="border px-4 py-2">Semesters</th>
                        <th class="border px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schoolYears as $year)
                        <tr class="border">
                            <td class="border px-4 py-2 font-medium">{{ $year->year }}</td>
                            <td class="border px-4 py-2">
                                @if($year->semesters->count() > 0)
                                    <ul class="space-y-2">
                                        @foreach($year->semesters as $semester)
                                            <li class="flex justify-between items-center bg-gray-50 p-2 rounded-md">
                                                <span>{{ $semester->name }}</span>
                                                <form action="{{ route('admin.semesters.destroy', $semester->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger text-sm py-1 px-2">Delete</button>
                                                </form>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-gray-500">No Semesters Added</span>
                                @endif
                            </td>
                            <td class="border px-4 py-2">
                                <form action="{{ route('admin.school-years.destroy', $year->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger text-sm py-1 px-2 w-full">Delete Year</button>
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
