@extends('layouts.student')

@section('content')
<div class="flex justify-center items-center h-full">
    <div class="w-full max-w-3xl bg-white bg-opacity-50 backdrop-blur-sm shadow-lg rounded-lg p-8 sm:px-10">
        <div class="text-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800 drop-shadow-lg">Schedule File Retrieval</h2>
        </div>

        <form method="POST" action="{{ route('student.schedules.store') }}" class="space-y-4">
            @csrf
            
            <!-- Select File -->
            <div>
                <label class="block text-gray-700 font-bold mb-1">Select File to Retrieve</label>
                <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 transition duration-200 @error('file_id') is-invalid @enderror" name="file_id" required>
                    @foreach($files as $file)
                        <option value="{{ $file->id }}">{{ $file->file_name }}</option>
                    @endforeach
                </select>
                @error('file_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Preferred Date -->
            <div>
                <label class="block text-gray-700 font-bold mb-1">Preferred Date</label>
                <input type="date" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 transition duration-200 @error('preferred_date') is-invalid @enderror" 
                       name="preferred_date" min="{{ date('Y-m-d') }}" required>
                @error('preferred_date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Preferred Time -->
            <div>
                <label class="block text-gray-700 font-bold mb-1">Preferred Time</label>
                <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 transition duration-200 @error('preferred_time') is-invalid @enderror" 
                        name="preferred_time" required>
                    <option value="AM">Morning (AM)</option>
                    <option value="PM">Afternoon (PM)</option>
                </select>
                @error('preferred_time') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Reason -->
            <div>
                <label class="block text-gray-700 font-bold mb-1">Reason for Retrieval</label>
                <input type="text" name="reason" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 transition duration-200" required>
            </div>

            <!-- School Year -->
            <div>
                <label class="block text-gray-700 font-bold mb-1">Select School Year</label>
                <select name="school_year_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 transition duration-200" required>
                    @foreach($schoolYears as $year)
                        <option value="{{ $year->id }}">{{ $year->year }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Semester -->
            <div>
                <label class="block text-gray-700 font-bold mb-1">Select Semester</label>
                <select name="semester_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 transition duration-200" required>
                    @foreach($semesters as $semester)
                        <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                    @endforeach
                </select>
            </div>               

            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 rounded-lg shadow-md transition duration-200">
                Submit Request
            </button>
        </form>
    </div>
</div>
@endsection
