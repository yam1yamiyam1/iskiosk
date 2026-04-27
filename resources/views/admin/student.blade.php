@extends('layouts.tabler')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Students</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <button type="button" class="btn btn-primary d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#addStudentModal"  style="background-color: #720100;">
                    <i class="fas fa-user-plus"></i> Add New Student
                </button>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-12 table-responsive">
                @if ($students->isEmpty())
                    <div class="alert alert-warning">No students available.</div>
                @else
                    <table id="students-table" class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ID Number</th>
                                <th>Full Name</th>
                                <th>Year Level</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th style="width: 120px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $index => $student)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $student->id_number }}</td>
                                <td>{{ $student->surname }}, {{ $student->given_name }} {{ $student->middle_name }}</td>
                                <td>{{ $student->year_level }}</td>
                                <td>{{ $student->email ?? '-' }}</td>
                                <td>{{ $student->contact_number ?? '-' }}</td>
                                <td>
                                    <a href="#" class="text-primary me-2" data-bs-toggle="modal" data-bs-target="#editStudentModal{{ $student->id }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#deleteStudentModal{{ $student->id }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>

@include('includes.student-add')
@foreach ($students as $student)
    @include('includes.student-edit')
    @include('includes.student-delete')
@endforeach
@endsection

@push('page-scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#students-table').DataTable({
            pageLength: 10,
            lengthMenu: [[5,10,25,50,-1],[5,10,25,50,'All']],
            columnDefs: [{ orderable: false, targets: 6 }],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search students..."
            },
            autoWidth: false
        });
    });
</script>
@endpush