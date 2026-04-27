@extends('layouts.tabler')

@section('content')

<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Documents</h2>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <form method="GET" action="{{ route('documents.index') }}">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>Start Date</label>
                        <input type="date" name="start_date" class="form-control"
                            value="{{ request('start_date') }}">
                    </div>

                    <div class="col-md-3">
                        <label>End Date</label>
                        <input type="date" name="end_date" class="form-control"
                            value="{{ request('end_date') }}">
                    </div>

                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">Filter</button>

                        <a href="{{ route('documents.index') }}" class="btn btn-secondary">Reset</a>

                        <a href="{{ route('documents.export', [
                            'start_date' => request('start_date'),
                            'end_date' => request('end_date')
                        ]) }}" 
                        class="btn btn-success">
                            <i class="fas fa-download"></i> Download Report
                        </a>
                    </div>
                </div>
            </form>

            <div class="col-12 mb-3 d-flex gap-2 flex-wrap">
                <button class="btn {{ $documents->where('status', 'Submitted')->isEmpty() || $passes ? 'btn-secondary' : 'btn-primary' }}"
                        data-bs-toggle="modal"
                        data-bs-target="#processAllModal"
                        {{ $documents->where('status', 'Submitted')->isEmpty() || $passes ? 'disabled' : '' }}>
                    <i class="fas fa-play px-1"></i> Manual
                </button>

                <button class="btn {{ $documents->where('status', 'Collected and Processing')->isEmpty() ? 'btn-secondary' : 'btn-warning' }}"
                        data-bs-toggle="modal"
                        data-bs-target="#markAllReadyModal"
                        {{ $documents->where('status', 'Collected and Processing')->isEmpty() ? 'disabled' : '' }}>
                    <i class="fas fa-box px-1"></i> Mark All as Ready
                </button>

                <button class="btn {{ $documents->where('status', 'Ready for claiming')->isEmpty() ? 'btn-secondary' : 'btn-success' }}"
                        data-bs-toggle="modal"
                        data-bs-target="#markAllClaimedModal"
                        {{ $documents->where('status', 'Ready for claiming')->isEmpty() ? 'disabled' : '' }}>
                    <i class="fas fa-check px-1"></i> Mark All as Claimed
                </button>
            </div>

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
                                <th>Status</th>
                                <th>Remarks</th>
                                <th>Status Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($documents as $index => $document)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><b>{{ $document->tracking_code }}</b></td>
                                    <td>{{ $document->document_typeb->name }}</td>
                                    <td>{{ $document->id_number }}</td>
                                    <td>
                                        {{ $document->surname }},
                                        {{ $document->given_name }}
                                        {{ $document->middle_name }}
                                    </td>
                                    <td>{{ $document->programb->name }}</td>
                                    <td>
                                        <span class="badge bg-light">
                                            @if($document->status === 'Submitted' && $passes)
                                                Waiting to be retrieved by {{ $passes->staff->name }}
                                            @else
                                                {{ ucfirst($document->status) }}
                                            @endif
                                        </span>
                                    </td>
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
                                    <td>
                                        @if ($document->status === 'Collected and Processing')
                                            <a href="#" class="link-warning me-2 text-decoration-none"
                                               data-bs-toggle="modal"
                                               data-bs-target="#markReadyModal{{ $document->id }}"
                                               title="Mark as Ready">
                                                <i class="fas fa-box px-1"></i> Mark as Ready
                                            </a>

                                        @elseif ($document->status === 'Ready for claiming')
                                            <a href="#" class="link-success me-2 text-decoration-none"
                                               data-bs-toggle="modal"
                                               data-bs-target="#markClaimedModal{{ $document->id }}"
                                               title="Mark as Claimed">
                                                <i class="fas fa-check px-1"></i> Mark as Claimed
                                            </a>
                                            @if(\Illuminate\Support\Str::contains((string) $document->remarks, '[OVERRIDE]'))
                                                <a href="{{ route('documents.overrideGuide', $document->id) }}"
                                                   target="_blank"
                                                   class="link-primary text-decoration-none"
                                                   title="Print Override Guide">
                                                    <i class="fas fa-print px-1"></i> Print Guide
                                                </a>
                                            @endif

                                        @elseif ($document->status === 'Submitted')
                                            <a href="#" class="link-danger me-2 text-decoration-none"
                                               data-bs-toggle="modal"
                                               data-bs-target="#overrideModal{{ $document->id }}"
                                               title="Override Submitted">
                                                <i class="fas fa-forward px-1"></i> Override
                                            </a>

                                        @elseif ($document->status === 'Claimed' && \Illuminate\Support\Str::contains((string) $document->remarks, '[OVERRIDE]'))
                                            <a href="{{ route('documents.overrideGuide', $document->id) }}"
                                               target="_blank"
                                               class="link-primary text-decoration-none"
                                               title="Print Override Guide">
                                                <i class="fas fa-print px-1"></i> Print Guide
                                            </a>

                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
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

<div class="modal fade" id="processAllModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manual Document Collection</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('documents.processAll') }}" method="POST">
                @csrf

                <div class="modal-body">
                    <p>
                        Use this option only during power outages or system failure.
                        This will mark all documents in the kiosk as collected.
                    </p>

                    <div class="mb-3">
                        <label class="form-label">Select User (Collector)</label>
                        <select name="user_id" class="form-select" required>
                            <option value="" selected disabled>Select User</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="alert alert-warning">
                        ⚠️ This action will update all pending documents as collected.
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        Confirm Manual Process
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="markAllReadyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mark All Collected Documents as Ready</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('documents.markAllReady') }}" method="POST">
                @csrf

                <div class="modal-body">
                    <p>
                        Are you sure you want to mark 
                        <strong>all collected documents</strong> as Ready for Claiming?
                    </p>

                    <div class="mb-3">
                        <label class="form-label">Remark (Optional)</label>
                        
                        <div class="mb-2">
                            @foreach($quickRemarks as $qr)
                                <span class="badge bg-primary text-white cursor-pointer quick-remark-chip" style="cursor: pointer; color: white !important; margin-right: 5px;" data-remark="{{ $qr->remark }}">{{ \Illuminate\Support\Str::limit($qr->remark, 30) }}</span>
                            @endforeach
                        </div>
                        <textarea name="remark" class="form-control remark-textarea" rows="3" maxlength="500"
                                placeholder="Enter remark for all documents (optional).."></textarea>
                    </div>

                    <div class="alert alert-warning">
                        ⚠️ This will apply the same remark to all affected documents.
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-warning">
                        Mark as Ready
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="markAllClaimedModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mark All Ready Documents as Claimed</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('documents.markAllClaimed') }}" method="POST">
                @csrf
                <div class="modal-body">
                    Are you sure you want to mark <strong>all ready for claiming documents</strong> as Claimed?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Mark as Claimed</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="scanResultModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">
            <div class="modal-header">
                <h5 class="modal-title" id="scanModalTitle">Scan Result</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p id="scanModalMessage"></p>
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="scanConfirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Scan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p><strong>Full Name:</strong> <span id="c_fullname"></span></p>
                <p><strong>Year Level:</strong> <span id="c_year"></span></p>
                <p><strong>Program:</strong> <span id="c_program"></span></p>
                <p><strong>Document Type:</strong> <span id="c_doc"></span></p>
                <p><strong>Email:</strong> <span id="c_email"></span></p>
                <p><strong>Contact:</strong> <span id="c_contact"></span></p>
                <p><strong>Status:</strong> <span id="c_status"></span></p>
                <p><strong>Update To:</strong> <span id="c_update"></span></p>

                <div class="alert alert-warning mt-3">
                    Are you sure you want to proceed?
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" id="confirmScanBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>

@foreach ($documents as $document)
    <div class="modal fade" id="markReadyModal{{ $document->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mark Document as Ready</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ route('documents.markReady', $document->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="modal-body">
                        <p>
                            Mark this document as <strong>Ready for Claiming</strong>?
                        </p>

                        <div class="mb-3">
                            <label class="form-label">Remark (Optional)</label>
                            
                            <div class="mb-2">
                                @foreach($quickRemarks as $qr)
                                    <span class="badge bg-primary text-white cursor-pointer quick-remark-chip" style="cursor: pointer; color: white !important; margin-right: 5px;" data-remark="{{ $qr->remark }}">{{ \Illuminate\Support\Str::limit($qr->remark, 30) }}</span>
                                @endforeach
                            </div>
                            <textarea name="remark" class="form-control remark-textarea" rows="3" maxlength="500"
                                    placeholder="Enter any note or remark (optional)..."></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-warning">
                            Confirm
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="markClaimedModal{{ $document->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mark Document as Claimed</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ route('documents.markClaimed', $document->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="modal-body">
                        Are you sure you want to mark this document as <strong>Claimed</strong>?
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="overrideModal{{ $document->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Override Submitted Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ route('documents.override', $document->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="modal-body">
                        <p>
                            This will bypass kiosk waiting and mark this document as
                            <strong>Claimed</strong> immediately.
                        </p>

                        <div class="mb-3">
                            <label class="form-label">Override Note (Optional)</label>
                            <textarea name="remark" class="form-control" rows="3" maxlength="500"
                                      placeholder="Reason for manual override..."></textarea>
                        </div>

                        <div class="alert alert-warning">
                            ⚠️ Use only for document types that do not require kiosk drop-off.
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Confirm Override</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
@endforeach

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
            { orderable: false, targets: [9] },
            { searchable: false, targets: 9 }
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

    const ws = new WebSocket('ws://localhost:8081');

    ws.addEventListener('message', async e => {
        let msg = e.data.trim();

        console.log("📩 Scan received:", msg);

        await handleTrackingScan(msg);
    });

    let scannedDocument = null;

    async function handleTrackingScan(code) {
        try {
            const res = await fetch('{{ route('documents.scanTrack') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ tracking_code: code })
            });

            const data = await res.json();

            if (data.status === 'not_found') {
                showScanModal('Not Found', '❌ Tracking code not found.', 'danger');
                return;
            }

            if (data.status === 'found') {
                const currentStatus = data.document.current_status;

                if (currentStatus === 'Claimed') {
                    showScanModal(
                        'Already Claimed',
                        '✅ This document has already been claimed.',
                        'success'
                    );
                    return;
                }
                
                scannedDocument = data.document;

                document.getElementById('c_fullname').innerText = data.document.fullname;
                document.getElementById('c_year').innerText = data.document.year_level;
                document.getElementById('c_program').innerText = data.document.program;
                document.getElementById('c_doc').innerText = data.document.document_type;
                document.getElementById('c_email').innerText = data.document.email;
                document.getElementById('c_contact').innerText = data.document.contact_number;
                document.getElementById('c_status').innerText = data.document.current_status;

                let nextStatus = '';

                if (data.document.current_status === 'Collected and Processing') {
                    nextStatus = 'Ready for Claiming';
                } else if (data.document.current_status === 'Ready for claiming') {
                    nextStatus = 'Claimed';
                } else {
                    nextStatus = 'No action available';
                }

                document.getElementById('c_update').innerText = nextStatus;

                new bootstrap.Modal(document.getElementById('scanConfirmModal')).show();
            }

        } catch (err) {
            console.error(err);
            showScanModal('Error', 'Something went wrong.', 'danger');
        }
    }

    document.getElementById('confirmScanBtn').addEventListener('click', async () => {
        if (!scannedDocument) return;

        const res = await fetch('{{ route('documents.confirmScan') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                id: scannedDocument.id
            })
        });

        const data = await res.json();

        if (data.status === 'updated') {
            showScanModal(
                'Success',
                `✅ Status updated to:<br><b>${data.new_status}</b>`,
                'success'
            );

            bootstrap.Modal.getInstance(document.getElementById('scanConfirmModal')).hide();

            setTimeout(() => location.reload(), 1000);
        }
    });

    function showScanModal(title, message, type = 'primary') {
        const modalEl = document.getElementById('scanResultModal');

        document.getElementById('scanModalTitle').innerText = title;
        document.getElementById('scanModalMessage').innerHTML = message;

        const header = modalEl.querySelector('.modal-header');
        header.className = `modal-header bg-${type} text-white`;

        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }

    document.querySelectorAll('.quick-remark-chip').forEach(chip => {
        chip.addEventListener('click', function() {
            const remarkText = this.getAttribute('data-remark');
            const modal = this.closest('.modal');
            if(modal) {
                const textarea = modal.querySelector('.remark-textarea');
                if(textarea) {
                    textarea.value = remarkText;
                }
            }
        });
    });
</script>
@endpush