@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Manage Files</h2>

    <!-- Admin Actions -->
    <div class="mb-3">
        <a href="{{ route('admin.files.create') }}" class="btn btn-primary">Add New File</a>
    </div>

    <!-- Files Table -->
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>File Name</th> 
                <th>Availability</th> 
                <th>Actions</th> 
                <th>Uploaded By</th> 
            </tr>
        </thead>
        <tbody>
            @foreach ($files as $file)
                <tr>
                    <td>{{ $file->id }}</td>
                    <td>{{ $file->file_name }}</td>
                    <td>
                        @if ($file->is_available)
                            <span class="badge bg-success">Available</span>
                        @else
                            <span class="badge bg-secondary">Unavailable</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.files.edit', $file->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin.files.destroy', $file->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                    <td>{{ $file->user->name ?? 'Unknown' }}</td> <!-- Moved to Last Column -->
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
