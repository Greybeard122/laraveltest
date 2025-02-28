@extends('layouts.default')

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
                <select id="file_id" class="form-control" name="file_id" required>
                    <option value="">-- Select a file --</option> 
                    @foreach($files as $file)
                        <option value="{{ $file->id }}" data-cor-cog="{{ in_array($file->file_name, ['COR', 'COG']) ? 'true' : 'false' }}">
                            {{ $file->file_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Manual School Year & Semester (Only for COR/COG) -->
            <div id="manual_sy_sem" style="display: none;">
                <label class="block text-gray-700 font-bold mb-1">Enter School Year (For COR/COG)</label>
                <input type="text" name="manual_school_year" class="w-full px-4 py-3 border border-gray-300 rounded-lg">

                <label class="block text-gray-700 font-bold mb-1 mt-2">Enter Semester (For COR/COG)</label>
                <input type="text" name="manual_semester" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
            </div>

            <!-- Regular School Year & Semester -->
            <div id="regular_sy_sem">
                <label class="block text-gray-700 font-bold mb-1">Select School Year</label>
                <select id="school_year" name="school_year_id" class="form-control">
                    <option value="">-- Select a School Year --</option>
                    @foreach($schoolYears as $year)
                        <option value="{{ $year->id }}">{{ $year->year }}</option>
                    @endforeach
                </select>

                <label class="block text-gray-700 font-bold mb-1 mt-2">Select Semester</label>
                <select id="semester" name="semester_id" class="form-control">
                    <option value="">-- Select a Semester --</option>
                </select>
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

            <!-- Copies -->
            <div>
                <label class="block text-gray-700 font-bold mb-1">Number of Copies</label>
                <input type="number" name="copies" min="1" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
            </div>

            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 rounded-lg shadow-md transition duration-200">
                Submit Request
            </button>
        </form>
    </div>
</div>

<!-- JavaScript to Toggle COR/COG and Semester Filtering -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fileDropdown = document.getElementById('file_id');
        const schoolYearDropdown = document.getElementById('school_year');
        const semesterDropdown = document.getElementById('semester');
        const manualSySem = document.getElementById('manual_sy_sem');
        const regularSySem = document.getElementById('regular_sy_sem');
        const semesters = @json($semesters);

        fileDropdown.addEventListener('change', function () {
            let selectedOption = this.options[this.selectedIndex];
            let isCorCog = selectedOption.getAttribute('data-cor-cog') === 'true';

            if (isCorCog) {
                manualSySem.style.display = 'block';
                regularSySem.style.display = 'none';
            } else {
                manualSySem.style.display = 'none';
                regularSySem.style.display = 'block';
            }
        });

        schoolYearDropdown.addEventListener('change', function () {
            const selectedYearId = this.value;
            semesterDropdown.innerHTML = '<option value="">-- Select a Semester --</option>'; // Reset

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
    });
</script>
@endsection
