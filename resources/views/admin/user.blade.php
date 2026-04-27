@extends('layouts.tabler')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Users
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#addUserModal" style="background-color: #720100;">
                            <x-icon.plus />
                            Add New User
                        </button>

                        <a href="{{ route('users.barcodes.pdf') }}" 
                            class="btn btn-success">
                            <i class="fas fa-file-pdf"></i> Download All Barcodes
                        </a>
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
                        <input type="text" class="form-control" id="advanced-search-input" placeholder="Search..." />
                        <button data-mdb-button-init data-mdb-ripple-init class="btn btn-primary" style="background-color: #720100;" id="advanced-search-button" type="button">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                </div>

                <div class="col-12 table-responsive">
                    @if (count($users) === 0)
                        <div class="alert alert-warning" role="alert">
                            No users available.
                        </div>
                    @else
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th style="text-align: center;">Role</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $index => $user)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @if ($user->image)
                                                <img src="{{ asset('storage/users/' . $user->image) }}" alt="Image" width="60" height="60" style="object-fit: cover; border-radius: 4px;">
                                            @else
                                                <span class="text-muted">No Image</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->fname }} {{ $user->lname }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td class="text-center align-middle">
                                            @foreach ($user->roles as $role)
                                                @php
                                                    $isAdmin = $role->role == 1;
                                                    $roleName = $isAdmin ? 'Admin' : 'Staff';
                                                    $badgeColor = $isAdmin ? 'linear-gradient(135deg, #004aad, #007bff)' : 'linear-gradient(135deg, #495057, #6c757d)';
                                                    $icon = $isAdmin ? 'fa-user-shield' : 'fa-user';
                                                @endphp

                                                <div class="d-inline-flex justify-content-center align-items-center gap-2 text-white fw-semibold shadow-sm role-badge"
                                                    style="
                                                        width: 110px;
                                                        height: 34px;
                                                        font-size: 0.9rem;
                                                        border-radius: 50px;
                                                        background: {{ $badgeColor }};
                                                        transition: all 0.25s ease-in-out;
                                                    ">
                                                    <i class="fa-solid {{ $icon }}" style="font-size: 0.85rem;"></i>
                                                    {{ $roleName }}
                                                </div>
                                            @endforeach
                                        </td>
                                        <style>
                                            .role-badge:hover {
                                                transform: translateY(-2px) scale(1.03);
                                                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
                                            }
                                        </style>
                                        <td>{{ $user->isActive() ? 'Active' : 'Disabled' }}</td>
                                        <td>
                                            <a href="#" class="text-primary me-2"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editUserModal{{ $user->id }}"
                                            title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <a href="javascript:void(0)"
                                            class="text-success me-2"
                                            title="Download Barcode"
                                            onclick="downloadBarcode({{ $user->id }}, '{{ $user->fname }}', '{{ $user->lname }}')">
                                                <i class="fas fa-barcode"></i>
                                            </a>

                                            <a href="#" class="text-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteUserModal{{ $user->id }}"
                                            title="Delete">
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

    @include('includes.useradd')
    @foreach ($users as $user)
        @include('includes.useredit')
        @include('includes.userdelete')
    @endforeach
@endsection

@push('page-libraries')
    <script src="{{ asset('dist/libs/apexcharts/dist/apexcharts.min.js') }}" defer></script>
    <script src="{{ asset('dist/libs/jsvectormap/dist/js/jsvectormap.min.js') }}" defer></script>
    <script src="{{ asset('dist/libs/jsvectormap/dist/maps/world.js') }}" defer></script>
    <script src="{{ asset('dist/libs/jsvectormap/dist/maps/world-merc.js') }}" defer></script>
@endpush

@pushonce('page-scripts')
<script>
    const advancedSearchInput = document.getElementById('advanced-search-input');
    const table = document.querySelector('.table tbody');

    const search = (value) => {
        const [phrasePart, columnsPart] = value.split(' in:').map(str => str.trim().toLowerCase());
        const phrase = phrasePart;
        const columns = columnsPart ? columnsPart.split(',').map(str => str.trim()) : [];

        const rows = table.querySelectorAll('tr');

        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            let match = false;

            cells.forEach((cell, index) => {
                const columnName = ['name', 'email', 'role', 'status'][index];

                if (columns.length === 0 || columns.includes(columnName.toLowerCase())) {
                    if (cell.textContent.toLowerCase().includes(phrase)) {
                        match = true;
                    }
                }
            });

            row.style.display = match ? '' : 'none';
        });
    };

    advancedSearchInput.addEventListener('input', (e) => {
        search(e.target.value);
    });
</script>
@endpushonce
