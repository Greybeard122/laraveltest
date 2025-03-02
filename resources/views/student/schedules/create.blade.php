@extends('layouts.default')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="w-full max-w-3xl bg-white bg-opacity-50 backdrop-blur-sm shadow-lg rounded-lg p-8 sm:px-10">
        <div class="text-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800 drop-shadow-lg">Schedule File Retrieval</h2>
        </div>

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="bg-red-100 text-red-600 px-4 py-2 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('student.schedules.store') }}" class="space-y-4">
            @csrf

            <!-- Select File -->
            <div class="form-group">
                <label class="form-label">Select File to Retrieve</label>
                <select class="form-control" name="file_id" id="file_id" required>
                    <option value="">-- Select a file --</option> 
                    @foreach($files as $file)
                        <option value="{{ $file->id }}">{{ $file->file_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Manual School Year & Semester (Only for COR/COG) -->
            <div id="manual_sy_sem" class="form-group" style="display: none;">
                <label class="form-label">Enter School Year</label>
                <input type="text" name="manual_school_year" class="form-control">
                
                <label class="form-label">Enter Semester</label>
                <input type="text" name="manual_semester" class="form-control">
            </div>

            <!-- Grid Layout for Preferred Date & Time -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Preferred Date</label>
                    <input type="date" id="preferred_date" name="preferred_date" class="form-control" required>
                </div>

                <div>
                    <label class="form-label">Preferred Time</label>
                    <select name="preferred_time" class="form-control" required>
                        <option value="AM">Morning (AM)</option>
                        <option value="PM">Afternoon (PM)</option>
                    </select>
                </div>
            </div>

            <!-- Reason -->
            <div class="form-group">
                <label class="form-label">Reason for Retrieval</label>
                <input type="text" name="reason" class="form-control" required>
            </div>

            <!-- Grid Layout for School Year & Semester -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Select School Year</label>
                    <select id="school_year" name="school_year_id" class="form-control" required>
                        <option value="">-- Select a School Year --</option>
                        @foreach($schoolYears as $year)
                            <option value="{{ $year->id }}">{{ $year->year }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Select Semester</label>
                    <select id="semester" name="semester_id" class="form-control" required>
                        <option value="">-- Select a Semester --</option>
                    </select>
                </div>
            </div>

            <!-- Copies -->
            <div class="form-group">
                <label class="form-label">Number of Copies</label>
                <input type="number" name="copies" min="1" class="form-control" required>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 rounded-lg shadow-md transition">
                Submit Request
            </button>
        </form>
    </div>
</div>

<!-- JavaScript to Handle COR/COG & Semester Filtering -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fileDropdown = document.getElementById('file_id');
        const manualFields = document.getElementById('manual_sy_sem');
        const schoolYearDropdown = document.getElementById('school_year');
        const semesterDropdown = document.getElementById('semester');
        const semesters = @json($semesters); 
        const preferredDateInput = document.getElementById('preferred_date');

        // Show manual school year & semester fields for COR/COG selection
        fileDropdown.addEventListener('change', function () {
            const selectedFileName = fileDropdown.options[fileDropdown.selectedIndex].text;
            manualFields.style.display = (selectedFileName === 'COR' || selectedFileName === 'COG') ? 'block' : 'none';
        });

        // Populate semesters based on selected school year
        schoolYearDropdown.addEventListener('change', function () {
            const selectedYearId = this.value;
            semesterDropdown.innerHTML = '<option value="">-- Select a Semester --</option>';
            
            if (selectedYearId) {
                semesters.filter(sem => sem.school_year_id == selectedYearId)
                        .forEach(sem => {
                            let option = document.createElement('option');
                            option.value = sem.id;
                            option.textContent = sem.name;
                            semesterDropdown.appendChild(option);
                        });
            }
        });

        // Prevent selecting weekends in the date picker
        preferredDateInput.addEventListener('input', function () {
            let date = new Date(this.value);
            if (date.getDay() === 6 || date.getDay() === 0) {  // 6 = Saturday, 0 = Sunday
                alert("Scheduling on weekends is not allowed. Please select a weekday.");
                this.value = "";
            }
        });
    });
</script>
@endsection
