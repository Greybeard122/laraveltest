@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2>Edit File: {{ $file->file_name }}</h2>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form to Edit File -->
        <form action="{{ route('admin.files.update', $file->id) }}" method="POST">
            @csrf
            @method('PUT') <!-- Make sure to include this to send a PUT request -->
            
            <!-- File Name Input -->
            <div class="form-group">
                <label for="name">File Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $file->file_name) }}" placeholder="Edit the file name" required>
            </div>
        
            <!-- Is Available Checkbox -->
            <div class="form-group form-check">
                <input type="checkbox" name="is_available" id="is_available" class="form-check-input" {{ $file->is_available ? 'checked' : '' }}>
                <label class="form-check-label" for="is_available">Is Available?</label>
            </div>
        
            <!-- Submit Button -->
            <button type="submit" class="btn btn-warning">Update File</button>
        
            <!-- Back Button -->
            <a href="{{ route('admin.files.index') }}" class="btn btn-secondary">Back</a>
        </form>
        
    </div>
@endsection
