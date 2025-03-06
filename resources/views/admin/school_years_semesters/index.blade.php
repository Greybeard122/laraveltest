@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 w-full">
    <h2 class="text-2xl font-semibold mb-4">Manage School Years & Semesters</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <!-- Add School Year -->
        <div class="col-md-6">
            <div class="card p-4 shadow-lg">
                <h4>Add School Year</h4>
                <form method="POST" action="{{ route('admin.school-years.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">School Year</label>
                        <input type="text" name="year" class="form-control" placeholder="e.g., 2024-2025" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add School Year</button>
                </form>
            </div>
        </div>

        <!-- Add Semester -->
        <div class="col-md-6">
            <div class="card p-4 shadow-lg">
                <h4>Add Semester</h4>
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
                    <button type="submit" class="btn btn-primary">Add Semester</button>
                </form>
            </div>
        </div>
    </div>

    <!-- List of School Years & Semesters -->
    <div class="card p-4 mt-5 shadow-lg">
        <h4>Existing School Years & Semesters</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>School Year</th>
                    <th>Semesters</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schoolYears as $year)
                    <tr>
                        <td>{{ $year->year }}</td>
                        <td>
                            @if($year->semesters->count() > 0)
                                <ul class="list-unstyled">
                                    @foreach($year->semesters as $semester)
                                    <li class="d-flex align-items-center pb-2">
                                        <span class="flex-grow-1">{{ $semester->name }}</span>
                                        <form action="{{ route('admin.semesters.destroy', $semester->id) }}" method="POST" class="m-0 ml-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger p-1">Delete</button>
                                        </form>
                                    </li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-muted">No Semesters Added</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.school-years.destroy', $year->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete School Year</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection