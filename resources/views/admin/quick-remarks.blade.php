@extends('layouts.tabler')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Quick Remarks</h2>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row row-deck row-cards">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Add New Quick Remark</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('quick-remarks.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Remark Text</label>
                                <textarea name="remark" class="form-control" rows="4" required maxlength="500" placeholder="Enter remark..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Add Remark</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Existing Quick Remarks</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th>Remark</th>
                                    <th>Added</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($quickRemarks as $qr)
                                    <tr>
                                        <td style="white-space: normal;">{{ $qr->remark }}</td>
                                        <td>{{ $qr->created_at->format('M j, Y') }}</td>
                                        <td class="text-end">
                                            <form action="{{ route('quick-remarks.destroy', $qr->id) }}" method="POST" onsubmit="return confirm('Delete this remark?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No quick remarks found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
