
@extends('layouts.admin')

@section('content')
    <h1>Create New File</h1>

    <form action="{{ route('admin.files.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="file_name">File Name</label>
            <input type="text" name="file_name" id="file_name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="file">Choose File</label>
            <input type="file" name="file" id="file" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Save File</button>
    </form>
@endsection
