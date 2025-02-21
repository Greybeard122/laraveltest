@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Settings</h2>
    <form>
        <div class="mb-3">
            <label for="app_name" class="form-label">Application Name</label>
            <input type="text" class="form-control" id="app_name" placeholder="Enter App Name">
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
