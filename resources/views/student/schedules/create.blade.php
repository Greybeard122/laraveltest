@extends('layouts.student')
<link href="{{ asset('css/students2.css') }}" rel="stylesheet">
@section('content')
<div class="container">
    <div class="card schedule-card">
        <div class="card-header">Schedule File Retrieval</div>
        <div class="card-body">
            <form method="POST" action="{{ route('student.schedules.store') }}" class="schedule-form">
                @csrf
                
                <!-- Select File -->
                <div class="mb-3">
                    <label class="form-label">Select File to Retrieve</label>
                    <select class="form-control @error('file_id') is-invalid @enderror" name="file_id" required>
                        @foreach($files as $file)
                            <option value="{{ $file->id }}">{{ $file->file_name }}</option>
                        @endforeach
                    </select>
                    @error('file_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Preferred Date -->
                <div class="mb-3">
                    <label class="form-label">Preferred Date</label>
                    <input type="date" class="form-control @error('preferred_date') is-invalid @enderror" 
                           name="preferred_date" min="{{ date('Y-m-d') }}" required>
                    @error('preferred_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Preferred Time -->
                <div class="mb-3">
                    <label class="form-label">Preferred Time</label>
                    <select class="form-control @error('preferred_time') is-invalid @enderror" 
                            name="preferred_time" required>
                        <option value="AM">Morning (AM)</option>
                        <option value="PM">Afternoon (PM)</option>
                    </select>
                    @error('preferred_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Reason -->
                <div class="mb-3">
                    <label class="form-label">Reason for Retrieval</label>
                    <input type="text" name="reason" class="form-control" required>
                </div>
                <!-- School Year -->
                <div class="mb-3">
                    <label class="form-label">Select School Year</label>
                    <select name="school_year_id" class="form-control" required>
                        @foreach($schoolYears as $year)
                            <option value="{{ $year->id }}">{{ $year->year }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Semester -->
                <div class="mb-3">
                    <label class="form-label">Select Semester</label>
                    <select name="semester_id" class="form-control" required>
                        @foreach($semesters as $semester)
                            <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                        @endforeach
                    </select>
                </div>               

                <button type="submit" class="btn btn-submit">Submit Request</button>
            </form>
        </div>
    </div>
</div>
@endsection
