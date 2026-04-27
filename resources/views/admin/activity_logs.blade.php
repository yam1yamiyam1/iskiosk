@extends('layouts.tabler')
@section('content')
<style>
.no-flex {
    display: block !important;
    align-items: initial !important;
}

.filter-buttons {
    margin-bottom: 1rem;
}

.filter-buttons .btn {
    margin-right: .5rem;
    background-color: #720100;
    border-color: #720100;
    color: #fff;
    transition: all 0.2s ease-in-out;
}

.filter-buttons .btn:hover {
    background-color: #8b1a1a;
    border-color: #8b1a1a;
}

.filter-buttons .btn.active {
    background-color: #b21c1c !important;
    border-color: #b21c1c !important;
    color: #fff !important;
    box-shadow: 0 0 10px rgba(178, 28, 28, 0.4);
}
</style>

<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Activity Logs</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="filter-buttons">
            @php
                $query = request()->except('module');
            @endphp

            <a href="{{ route('activity-logs.index', array_merge($query, ['module' => null])) }}"
            class="btn btn-primary d-sm-inline-block {{ request('module') == null ? 'active' : '' }}">
                <i class="fa-solid fa-list"></i> <small>All</small>
            </a>

            <a href="{{ route('activity-logs.index', array_merge($query, ['module' => 'authentications'])) }}"
            class="btn btn-primary d-sm-inline-block {{ request('module') == 'authentications' ? 'active' : '' }}">
                <i class="fa-solid fa-right-to-bracket"></i> <small>Authentications</small>
            </a>

            <a href="{{ route('activity-logs.index', array_merge($query, ['module' => 'accounts'])) }}"
            class="btn btn-primary d-sm-inline-block {{ request('module') == 'accounts' ? 'active' : '' }}">
                <i class="fa-solid fa-user"></i> <small>Accounts</small>
            </a>

            <a href="{{ route('activity-logs.index', array_merge($query, ['module' => 'users'])) }}"
            class="btn btn-primary d-sm-inline-block {{ request('module') == 'users' ? 'active' : '' }}">
                <i class="fa-solid fa-users-gear"></i> <small>Users</small>
            </a>

            <a href="{{ route('activity-logs.index', array_merge($query, ['module' => 'documents'])) }}"
            class="btn btn-primary d-sm-inline-block {{ request('module') == 'documents' ? 'active' : '' }}">
                <i class="fa-solid fa-file"></i> <small>Documents</small>
            </a>

            <a href="{{ route('activity-logs.index', array_merge($query, ['module' => 'departments'])) }}"
            class="btn btn-primary d-sm-inline-block {{ request('module') == 'departments' ? 'active' : '' }}">
                <i class="fa-solid fa-building"></i> <small>Departments</small>
            </a>

            <a href="{{ route('activity-logs.index', array_merge($query, ['module' => 'document_types'])) }}"
            class="btn btn-primary d-sm-inline-block {{ request('module') == 'document_types' ? 'active' : '' }}">
                <i class="fa-solid fa-file-lines"></i> <small>Document Types</small>
            </a>

            <a href="{{ route('activity-logs.index', array_merge($query, ['module' => 'students'])) }}"
            class="btn btn-primary d-sm-inline-block {{ request('module') == 'students' ? 'active' : '' }}">
                <i class="fa-solid fa-user-graduate"></i> <small>Students</small>
            </a>
        </div>

        <form method="GET" action="{{ route('activity-logs.index') }}" class="mb-4">
            <input type="hidden" name="module" value="{{ request('module') }}">
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="date" id="startDate" name="startDate" class="form-control"
                        value="{{ $startDate }}" onchange="updateToDateMin(); this.form.submit()" />
                </div>
                <div class="col-md-4">
                    <input type="date" id="endDate" name="endDate" class="form-control"
                        value="{{ $endDate }}" onchange="updateFromDateMax(); this.form.submit()" />
                </div>
            </div>
        </form>

        <div class="row row-deck row-cards">
            <div class="col-12 table-responsive no-flex">
                <table id="activity-table" class="table table-bordered table-striped align-middle datatable" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Module</th>
                            <th>Description</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($logs as $log)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $log->user_full_name ?? 'Unknown' }}</td>
                                <td>{{ $log->action }}</td>
                                <td>{{ $log->module_name }}</td>
                                <td>{{ $log->description }}</td>
                                <td>{{ $log->created_at_formatted }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
@endpush

@push('page-scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$('#activity-table').DataTable({
    pageLength: 10,
    lengthMenu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"]],
    language: {
        search: "_INPUT_",
        searchPlaceholder: "Search activity logs...",
        infoEmpty: "No activity logs found.",
    },
    infoCallback: function(settings, start, end, max, total, pre) {
        if (settings._iDisplayLength == -1) {
            return `Showing all ${total} logs`;
        }
        return `Showing ${start} to ${end} of ${total} logs`;
    },
    autoWidth: false
});
function updateToDateMin() {
    const fromDate = document.getElementById('startDate').value;
    document.getElementById('endDate').min = fromDate;
}
function updateFromDateMax() {
    const toDate = document.getElementById('endDate').value;
    document.getElementById('startDate').max = toDate;
}
document.addEventListener('DOMContentLoaded', function () {
    updateToDateMin();
    updateFromDateMax();
});
</script>
@endpush