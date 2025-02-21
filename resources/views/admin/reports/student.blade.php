@extends('layouts.default')
<link href="{{ asset('css/students2.css') }}" rel="stylesheet">

@section('content')
<div class="container mx-auto px-4">
    <div class="text-center mb-6">
        <h2 class="text-3xl font-bold text-black drop-shadow-md">
            Report for {{ $student->last_name }}, {{ $student->first_name }}
        </h2>
    </div>

    <!-- Student Information -->
    <div class="card bg-white bg-opacity-50 backdrop-blur-sm shadow-lg rounded-lg p-6 mb-6">
        <p class="text-lg font-semibold text-gray-800">Student Name: 
            <span class="font">{{ $student->last_name }}, {{ $student->first_name }}</span>
        </p>
        <p class="text-lg font-semibold text-gray-800">Student ID: <span class="font-bold">{{ $student->id }}</span></p>
    </div>

    <!-- Report Table -->
    <div class="card bg-white bg-opacity-50 backdrop-blur-sm shadow-lg rounded-lg p-6">
        <div class="table-responsive">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th><a href="#" onclick="sortTable(0)">File Name ⬍</a></th>
                        <th><a href="#" onclick="sortTable(1)">Request Count ⬍</a></th>
                        <th><a href="#" onclick="sortTable(2)">Status ⬍</a></th>
                        <th><a href="#" onclick="sortTable(3)">Date Requested ⬍</a></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($studentSchedules as $schedule)
                        <tr>
                            <td>{{ $schedule->file_name }}</td>
                            <td class="text-center font-bold">{{ $schedule->request_count ?? 1 }}</td>
                            <td class="capitalize">{{ ucfirst($schedule->status) }}</td>
                            <td>{{ \Carbon\Carbon::parse($schedule->created_at)->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $studentSchedules->links() }}
    </div>
</div>

<!-- Sort Table JavaScript -->
<script>
    function sortTable(columnIndex) {
        let table = document.querySelector("table");
        let rows = Array.from(table.rows).slice(1);
        let sortedRows = rows.sort((rowA, rowB) => {
            let cellA = rowA.cells[columnIndex].textContent.trim();
            let cellB = rowB.cells[columnIndex].textContent.trim();
            return cellA.localeCompare(cellB, undefined, { numeric: true });
        });

        sortedRows.forEach(row => table.appendChild(row));
    }
</script>

@endsection
