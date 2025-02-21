@extends('layouts.admin')



@section('content')
    <div class="container">
        <h2>Add New File</h2>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form to Create File -->
        <form action="{{ route('admin.files.store') }}" method="POST">
            @csrf
            
            <!-- File Name Input -->
            <div class="form-group">
                <label for="name">File Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="Enter the file name" required>
            </div>

            <!-- Is Available Checkbox -->
            <div class="form-group form-check">
                <input type="checkbox" name="is_available" id="is_available" class="form-check-input">
                <label class="form-check-label" for="is_available">Is Available?</label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Create File</button>

            <!-- Back Button -->
            <a href="{{ route('admin.files.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
@endsection
