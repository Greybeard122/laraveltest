<!-- resources/views/auth/login.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <ul class="nav nav-tabs" id="loginTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="student-tab" data-toggle="tab" href="#student" role="tab">Student Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="admin-tab" data-toggle="tab" href="#admin" role="tab">Admin Login</a>
                        </li>
                    </ul>

                    <div class="tab-content mt-3" id="loginTabContent">
                        <!-- Student Login Form -->
                        <div class="tab-pane fade show active" id="student" role="tabpanel">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="student_id">Student ID</label>
                                    <input id="student_id" type="text" class="form-control @error('student_id') is-invalid @enderror" 
                                           name="student_id" value="{{ old('student_id') }}" required autofocus>
                                    @error('student_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                           name="password" required>
                                </div>

                                <button type="submit" class="btn btn-primary">Login as Student</button>
                            </form>
                        </div>

                        <!-- Admin Login Form -->
                        <div class="tab-pane fade" id="admin" role="tabpanel">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <input type="hidden" name="is_admin" value="1">
                                
                                <div class="form-group">
                                    <label for="admin_id">Admin ID</label>
                                    <input id="admin_id" type="text" class="form-control @error('admin_id') is-invalid @enderror" 
                                           name="admin_id" value="{{ old('admin_id') }}" required autofocus>
                                    @error('admin_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="admin_password">Password</label>
                                    <input id="admin_password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                           name="password" required>
                                </div>

                                <button type="submit" class="btn btn-primary">Login as Admin</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection