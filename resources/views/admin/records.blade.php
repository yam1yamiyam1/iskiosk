@extends('layouts.tabler')

@section('content')

<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Records</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">

            <div class="col-12 table-responsive d-block">
                @if ($documents->isEmpty())
                    <div class="alert alert-warning">
                        No documents available.
                    </div>
                @else
                    <table id="documents-table" class="table table-striped datatable" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tracker ID</th>
                                <th>Document Type</th>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Program</th>
                                <th>Remarks</th>
                                <th>Status Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($documents as $index => $document)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><b>{{ $document->tracking_code }}</b></td>
                                    <td>{{ $document->document_type }}</td>
                                    <td>{{ $document->id_number }}</td>
                                    <td>
                                        {{ $document->surname }},
                                        {{ $document->given_name }}
                                        {{ $document->middle_name }}
                                    </td>
                                    <td>{{ $document->program }}</td>
                                    <td>{{ $document->remarks }}</td>
                                    @php
                                        switch($document->status) {
                                            case 'Submitted':
                                                $date = $document->created_at;
                                                $title = 'Date Submitted';
                                                break;
                                            case 'Collected and Processing':
                                                $date = $document->updated_at;
                                                $title = 'Date Collected';
                                                break;
                                            case 'Ready for Claiming':
                                                $date = $document->updated_at;
                                                $title = 'Last Updated';
                                                break;
                                            case 'Claimed':
                                                $date = $document->updated_at;
                                                $title = 'Date Claimed';
                                                break;
                                            default:
                                                $date = $document->updated_at;
                                                $title = 'Last Updated';
                                                break;
                                        }
                                    @endphp

                                    <td>
                                        <strong>{{ $title }}:</strong>
                                        <div style="font-size: 0.9em; margin-top: 2px;">
                                            {{ $date ? $date->copy()->addHours(8)->format('M. j, Y g:i A') : 'N/A' }}
                                        </div>
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

@endsection

@pushonce('page-scripts')
<script>
    const documentSearchInput = document.getElementById('document-search-input');
    const documentTableBody = document.querySelector('.table tbody');

    const searchDocuments = (value) => {
        const phrase = value.trim().toLowerCase();
        const rows = documentTableBody.querySelectorAll('tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(phrase) ? '' : 'none';
        });
    };

    documentSearchInput.addEventListener('input', (e) => {
        searchDocuments(e.target.value);
    });
</script>
@endpushonce

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
@endpush

@push('page-scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    const table = $('#documents-table').DataTable({
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"]],
        columnDefs: [
            { orderable: false, targets: [7] },
            { searchable: false, targets: 7 }
        ],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search documents...",
            infoEmpty: "No entries to show",
        },
        infoCallback: function(settings, start, end, max, total, pre) {
            if (settings._iDisplayLength == -1) {
                return `Showing all ${total} documents`;
            }
            return `Showing ${start} to ${end} of ${total} documents`;
        },
        autoWidth: false
    });

    $('#document-search-input').on('keyup', function () {
        table.search(this.value).draw();
    });

</script>
@endpush