@extends('layouts.admin')
@section('content')

<div class="container mx-auto px-4 w-full">
    <!-- Title and Archived Link -->
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold">Schedule Management</h2>
        <a href="{{ route('admin.reports.index') }}" class="text-blue-600 hover:underline">
            <i class="fas fa-archive"></i> View Report Page
        </a>
    </div>

    <!-- Filter Form -->
    <div class="bg-white bg-opacity-30 backdrop-blur-sm shadow-lg rounded-lg p-4 mb-4">
        <form class="grid grid-cols-1 md:grid-cols-3 gap-4" method="GET" action="{{ route('admin.schedules.index') }}">
            <div>
                <label class="block font-medium">File Type</label>
                <select class="form-control w-full p-2 border rounded" name="file_id">
                    <option value="">All Files</option>
                    @foreach($files as $file)
                        <option value="{{ $file->id }}" {{ request('file_id') == $file->id ? 'selected' : '' }}>
                            {{ $file->file_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-medium">Status</label>
                <select class="form-control w-full p-2 border rounded" name="status">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('admin.schedules.index') }}" class="ml-2 text-gray-600 hover:underline">
                    <i class="fas fa-undo"></i> Clear
                </a>
            </div>
        </form>
    </div>

    <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-3 mb-4">
        <i class="fas fa-info-circle"></i> All schedule requests are kept for 7 days. Older requests are saved on the Report Page.
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 mb-4">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Schedules Table -->
    <div class="bg-white bg-opacity-30 backdrop-blur-sm shadow-lg rounded-lg overflow-hidden">
        <div class="bg-gray-800 text-white px-4 py-3">
            <h5 class="text-lg">Schedule Requests</h5>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-2"><a href="#" onclick="sortTable(0)" class="hover:underline">Student ⬍</a></th>
                        <th class="p-2"><a href="#" onclick="sortTable(1)" class="hover:underline">File ⬍</a></th>
                        <th class="p-2"><a href="#" onclick="sortTable(2)" class="hover:underline">Date ⬍</a></th>
                        <th class="p-2"><a href="#" onclick="sortTable(3)" class="hover:underline">Time ⬍</a></th>
                        <th class="p-2"><a href="#" onclick="sortTable(4)" class="hover:underline">Status ⬍</a></th>
                        <th class="p-2">Actions</th>
                    </tr>
                </thead>
                <tbody id="schedulesTable">
                    @foreach($schedules as $schedule)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="p-2">
                                @if($schedule->student)
                                    <a href="{{ route('admin.reports.student', $schedule->student->id) }}" class="text-blue-600 hover:underline">
                                        {{ $schedule->student->first_name . ' ' . $schedule->student->last_name }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="p-2">{{ optional($schedule->file)->file_name ?? 'N/A' }}</td>
                            <td class="p-2" data-date="{{ $schedule->preferred_date }}">
                                {{ \Carbon\Carbon::parse($schedule->preferred_date)->format('M d, Y') }}
                            </td>
                            <td class="p-2">{{ $schedule->preferred_time }}</td>
                            <td class="p-2 capitalize">
                                <span class="status-badge status-{{ $schedule->status }}">{{ $schedule->status }}</span>
                            </td>
                            <td class="p-2 flex space-x-2">
                                <form action="{{ route('schedules.approve', $schedule->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">Approve</button>
                                </form>
                                <form action="{{ route('schedules.reject', $schedule->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Reject</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($schedules->hasPages())
            <div class="p-4 flex justify-center">
                {{ $schedules->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    </div>
</div>

<script>
function sortTable(columnIndex) {
    const table = document.getElementById('schedulesTable');
    const rows = Array.from(table.getElementsByTagName('tr'));
    const direction = rows[0].dataset.sorted == "asc" ? -1 : 1;
    
    rows.sort((a, b) => {
        let aValue = a.cells[columnIndex].textContent.trim().toLowerCase();
        let bValue = b.cells[columnIndex].textContent.trim().toLowerCase();
        return aValue.localeCompare(bValue) * direction;
    });

    rows.forEach(row => table.appendChild(row));
    rows[0].dataset.sorted = direction === 1 ? "asc" : "desc";
}
</script>

<style>
/* Status Badge Styling */
.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 50rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Hover Effects */
.table-row {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.table-row:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

/* Tailwind Pagination Fix */
nav[aria-label="Pagination Navigation"] {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin-top: 1rem;
}

nav[aria-label="Pagination Navigation"] a {
    padding: 8px 12px;
    font-size: 14px;
    font-weight: 500;
    border-radius: 6px;
    transition: all 0.2s ease-in-out;
    background-color: white;
    text-decoration: none;
}

nav[aria-label="Pagination Navigation"] span[aria-current="page"] {
    background-color: #4f46e5;
    color: white;
    font-weight: bold;
}
</style>

@endsection
