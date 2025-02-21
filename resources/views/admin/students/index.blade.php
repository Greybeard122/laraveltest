@extends('layouts.admin')
@section('content')
<div class="container">
    <h2>Students</h2>

    <!-- Admin Actions -->
    <div class="mb-3">
        <a href="{{ route('admin.students.create') }}" class="btn btn-primary">Add New Student</a>
    </div>

    <!-- Students Table -->
    <table class="table">
        <thead>
            <tr>
                <th class="sortable" data-column="student_id">
                    Student ID 
                    <span class="sort-icon"></span>
                </th>
                <th class="sortable" data-column="first_name">
                    First Name
                    <span class="sort-icon"></span>
                </th>
                <th class="sortable" data-column="last_name">
                    Last Name
                    <span class="sort-icon"></span>
                </th>
                <th class="sortable" data-column="email">
                    Email
                    <span class="sort-icon"></span>
                </th>
                <th class="sortable" data-column="course">
                    Course
                    <span class="sort-icon"></span>
                </th>
                <th class="sortable" data-column="contact_number">
                    Contact Number
                    <span class="sort-icon"></span>
                </th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $student)
                <tr>
                    <td>{{ $student->student_id }}</td>
                    <td>{{ $student->first_name }}</td>
                    <td>{{ $student->last_name }}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->course }}</td>
                    <td>{{ $student->contact_number }}</td>
                    <td>
                        <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <button class="btn btn-danger btn-sm delete-student" 
                            data-id="{{ $student->id }}"  
                            data-student-name="{{ $student->first_name }} {{ $student->last_name }}">
                            Delete
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <!-- Pagination -->
    {{ $students->links() }}
</div>

@push('styles')
<style>
.sortable {
    cursor: pointer;
    position: relative;
    padding-right: 20px !important;
}

.sort-icon::after {
    content: '↕️';
    position: absolute;
    right: 5px;
    opacity: 0.3;
}

.sorting-asc .sort-icon::after {
    content: '↑';
    opacity: 1;
}

.sorting-desc .sort-icon::after {
    content: '↓';
    opacity: 1;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Set up CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Handle delete button click
    $('.delete-student').click(function() {
    const studentId = $(this).data('id');
    const studentName = $(this).data('student-name');
    
    if (confirm(`Are you sure you want to delete ${studentName}?`)) {
        $.ajax({
            url: `/admin/students/${studentId}`,
            type: 'DELETE',
            success: function(result) {
                if (result.success) {
                    alert(result.message);
                    window.location.reload();
                } else {
                    alert('Error: ' + result.message);
                }
            },
            error: function(xhr, status, error) {
                console.log('Status:', status);
                console.log('Error:', error);
                console.log('Response:', xhr.responseText);
                
                let errorMessage = 'Error deleting student';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                alert(errorMessage);
            }
        });
    }
});

    // Handle column sorting
    $('.sortable').click(function() {
        const column = $(this).data('column');
        const currentUrl = new URL(window.location.href);
        
        // Get current sort direction or default to asc
        let direction = 'asc';
        if (currentUrl.searchParams.get('sort') === column && 
            currentUrl.searchParams.get('direction') === 'asc') {
            direction = 'desc';
        }

        // Update URL parameters
        currentUrl.searchParams.set('sort', column);
        currentUrl.searchParams.set('direction', direction);
        
        // Redirect to sorted URL
        window.location.href = currentUrl.toString();
    });

    // Highlight current sort column
    const currentSort = new URL(window.location.href).searchParams.get('sort');
    const currentDirection = new URL(window.location.href).searchParams.get('direction');
    if (currentSort) {
        $(`.sortable[data-column="${currentSort}"]`).addClass(`sorting-${currentDirection}`);
    }
});
</script>
@endpush
@endsection