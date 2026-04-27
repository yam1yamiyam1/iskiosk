@extends('layouts.tabler')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Document Types
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#addDocumentTypeModal" style="background-color: #720100;">
                        <x-icon.plus />
                        Add New Document Type
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
                    <input type="text" class="form-control" id="advanced-search-input" placeholder="Search types..." />
                    <button class="btn btn-primary" style="background-color: #720100;" id="advanced-search-button" type="button">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </div>

            <div class="col-12 table-responsive">
                @if (count($types) === 0)
                    <div class="alert alert-warning" role="alert">
                        No document types available.
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
                            @foreach ($types as $index => $type)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $type->name }}</td>
                                    <td>
                                        <a href="#" class="text-primary me-2" data-bs-toggle="modal" data-bs-target="#editDocumentTypeModal{{ $type->id }}" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#deleteDocumentTypeModal{{ $type->id }}" title="Delete">
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

@include('includes.documenttype-add')
@foreach ($types as $type)
    @include('includes.documenttype-edit')
    @include('includes.documenttype-delete')
@endforeach
@endsection

@push('page-libraries')
<script src="{{ asset('dist/libs/apexcharts/dist/apexcharts.min.js') }}" defer></script>
@endpush

@pushonce('page-scripts')
<script>
    const advancedSearchInput = document.getElementById('advanced-search-input');
    const table = document.querySelector('.table tbody');

    const search = (value) => {
        const phrase = value.trim().toLowerCase();
        const rows = table.querySelectorAll('tr');

        rows.forEach(row => {
            const name = row.children[1]?.textContent.toLowerCase() || '';
            const match = name.includes(phrase);
            row.style.display = match ? '' : 'none';
        });
    };

    advancedSearchInput.addEventListener('input', (e) => {
        search(e.target.value);
    });
</script>
@endpushonce
