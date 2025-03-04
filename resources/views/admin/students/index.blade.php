@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Students</h2>

    <!-- Admin Actions -->
    <div class="mb-3">
        <a href="{{ route('admin.students.create') }}" class="btn btn-primary">Add New Student</a>
    </div>

    <!-- Students Table -->
    <table class="table table-striped table-hover">
        <thead class="thead-dark">
            <tr>
                <th class="sortable" data-column="student_number">
                    Student Number 
                    <span class="sort-icon"></span>
                </th>
                <th class="sortable" data-column="student_name">
                    Student Name
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
                    <td>{{ $student->student_id ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('admin.reports.student', $student->id) }}" class="student-link">
                            {{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name ?? '' }}
                        </a>
                    </td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->course }}</td>
                    <td>{{ $student->contact_number }}</td>
                    <td>
                        <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <button class="btn btn-danger btn-sm delete-student" 
                            data-id="{{ $student->id }}"  
                            data-student-name="{{ $student->last_name }}, {{ $student->first_name }}">
                            Delete
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $students->links() }}
    </div>
</div>

@push('styles')
<style>
/* Student Name Hover Effect */
.student-link {
    display: inline-block;
    padding: 6px 10px;
    border-radius: 4px;
    background-color: transparent;
    transition: background-color 0.3s, transform 0.2s, color 0.3s;
    font-weight: 600;
    text-decoration: none;
    color: #1e40af; /* Deep blue */
}

.student-link:hover {
    background-color: rgba(59, 130, 246, 0.15); /* Light blue background */
    color: #1e3a8a; /* Darker blue */
    transform: translateY(-2px);
    text-shadow: 0 1px 3px rgba(59, 130, 246, 0.4);
}

/* Sorting Icons */
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

/* Table Styling */
.table th, .table td {
    text-align: center;
    vertical-align: middle;
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
        
        if (confirm(`Are you sure you want to delete ${studentName}? This action cannot be undone.`)) {
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
