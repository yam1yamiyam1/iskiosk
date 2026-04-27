@extends('layouts.tabler')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Departments</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#addDepartmentModal" style="background-color: #720100;">
                        <x-icon.plus /> Add New Department
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-12">
                <div class="input-group mb-4">
                    <input type="text" class="form-control" id="department-search-input" placeholder="Search departments..." />
                    <button class="btn btn-primary" style="background-color: #720100;" id="department-search-button" type="button">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </div>

            <div class="col-12 table-responsive">
                @if (count($departments) === 0)
                    <div class="alert alert-warning" role="alert">
                        No departments available.
                    </div>
                @else
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th style="width: 80px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($departments as $index => $department)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $department->name }}</td>
                                    <td>
                                        <a href="#" class="text-primary me-2" data-bs-toggle="modal" data-bs-target="#editDepartmentModal{{ $department->id }}" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#deleteDepartmentModal{{ $department->id }}" title="Delete">
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

@include('includes.department-add')
@foreach ($departments as $department)
    @include('includes.department-edit')
    @include('includes.department-delete')
@endforeach
@endsection

@push('page-libraries')
<script src="{{ asset('dist/libs/apexcharts/dist/apexcharts.min.js') }}" defer></script>
@endpush

@pushonce('page-scripts')
<script>
    const departmentSearchInput = document.getElementById('department-search-input');
    const departmentTable = document.querySelector('.table tbody');

    const searchDepartments = (value) => {
        const phrase = value.trim().toLowerCase();
        const rows = departmentTable.querySelectorAll('tr');

        rows.forEach(row => {
            const name = row.children[1]?.textContent.toLowerCase() || '';
            const match = name.includes(phrase);
            row.style.display = match ? '' : 'none';
        });
    };

    departmentSearchInput.addEventListener('input', (e) => {
        searchDepartments(e.target.value);
    });
</script>
@endpushonce
