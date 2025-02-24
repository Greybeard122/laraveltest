@extends('layouts.default')

@section('content')
<div class="container">
    <h2>Forgot Password</h2>
    @if (session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif
    <form method="POST" action="{{ route('student.password.email') }}">
        @csrf
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Send Reset Link</button>
    </form>
</div>
@endsection
