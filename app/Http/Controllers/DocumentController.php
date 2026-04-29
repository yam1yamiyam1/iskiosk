<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Department;
use App\Models\BatchDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\DocumentStatusUpdated;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DocumentsExport;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $statusOrder = [
            'Submitted',
            'Collected and Processing',
            'Ready for Claiming',
            'Claimed',
        ];

        $passes = \App\Models\Pass::first();

        $query = Document::whereNot('status', 'Claimed');

        if ($request->filled('start_date')) {
            $query->whereDate(DB::raw('DATE_ADD(created_at, INTERVAL 8 HOUR)'), '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate(DB::raw('DATE_ADD(created_at, INTERVAL 8 HOUR)'), '<=', $request->end_date);
        }

        $documents = $query
            ->orderByRaw("FIELD(status, ?, ?, ?, ?)", $statusOrder)
            ->latest()
            ->get();

        $users = \App\Models\User::all();
        $quickRemarks = \App\Models\QuickRemark::all();

        return view('admin.document', compact('documents', 'users', 'passes', 'quickRemarks'));
    }

    
    public function record()
    {
        $statusOrder = [
            'Submitted',
            'Collected and Processing',
            'Ready for Claiming',
            'Claimed',
        ];
        
        $passes = \App\Models\Pass::first();

        $documents = Document::where('status','Claimed')
        ->latest()->get();

        $users = \App\Models\User::all();

        return view('admin.records', compact('documents', 'users', 'passes'));
    }

    

    public function store(Request $request)
    {
    }

    public function verify(Request $request)
    {
        return Student::where('id_number', $request->id_number)->first();
    }

    public function update(Request $request, Document $document)
    {
    }

    public function destroy(Document $document)
    {
    }

    public function processAll(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $userId = $request->user_id;

        $documents = Document::where('status', 'Submitted')->get();
        $pendingBatch = BatchDocument::where('status', 'pending')->first();

        if ($documents->isEmpty() || !$pendingBatch) {
            return redirect()->back()->with('error', 'No submitted documents available.');
        }

        Document::where('status', 'Submitted')
            ->update([
                'status' => 'Collected and Processing',
            ]);

        foreach ($documents as $doc) {
            // Keep in-memory status in sync so the outgoing email body matches.
            $doc->status = 'Collected and Processing';

            if ($doc->email) {
                Mail::to($doc->email)->send(new DocumentStatusUpdated($doc));
            }

            ActivityLog::create([
                'user_id' => Auth::id(),
                'user_full_name' => Auth::user()->lname . ', ' . Auth::user()->fname,
                'module' => 'Document',
                'action' => 'Status Updated',
                'description' => "Document '{$doc->tracking_code}' status changed to Collected and Processing.",
                'ip_address' => $request->ip(),
                'device' => $request->userAgent(),
            ]);
        }

        $pendingBatch->update([
            'user_id' => $userId,
            'status' => 'finalized',
            'finalized_at' => Carbon::now('Asia/Manila'),
        ]);

        return redirect()->back()->with(
            'success',
            'All documents were manually processed and assigned successfully.'
        );
    }

    public function markAllReady(Request $request)
    {
        $request->validate([
            'remark' => 'nullable|string|max:500'
        ]);

        $documents = Document::where('status', 'Collected and Processing')->get();

        foreach ($documents as $document) {
            $document->update([
                'status' => 'Ready for Claiming',
                'remarks' => $request->remark
            ]);

            if ($document->email) {
                Mail::to($document->email)->send(new DocumentStatusUpdated($document));
            }

            ActivityLog::create([
                'user_id' => Auth::id(),
                'user_full_name' => Auth::user()->lname . ', ' . Auth::user()->fname,
                'module' => 'Document',
                'action' => 'Status Updated',
                'description' => "Document '{$document->tracking_code}' status changed to Ready for Claiming.",
                'ip_address' => $request->ip(),
                'device' => $request->userAgent(),
            ]);
        }

        return back()->with('success', 'All collected documents are now Ready for Claiming.');
    }

    public function markAllClaimed(Request $request)
    {
        $documents = Document::where('status', 'Ready for Claiming')->get();

        foreach ($documents as $document) {
            $document->update(['status' => 'Claimed']);

            if ($document->email) {
                Mail::to($document->email)->send(new DocumentStatusUpdated($document));
            }

            ActivityLog::create([
                'user_id' => Auth::id(),
                'user_full_name' => Auth::user()->lname . ', ' . Auth::user()->fname,
                'module' => 'Document',
                'action' => 'Status Updated',
                'description' => "Document '{$document->tracking_code}' status changed to Claimed.",
                'ip_address' => request()->ip(),
                'device' => request()->userAgent(),
            ]);
        }

        return redirect()->back()->with('success', 'All ready documents are now Claimed.');
    }

    public function markReady(Request $request, $id)
    {
        $request->validate([
            'remark' => 'nullable|string|max:500'
        ]);

        $document = Document::findOrFail($id);

        if ($document->status !== 'Collected and Processing') {
            return back()->with('error', 'Invalid status transition.');
        }

        $document->update([
            'status' => 'Ready for claiming',
            'remarks' => $request->remark,
        ]);

        if ($document->email) {
            Mail::to($document->email)->send(new DocumentStatusUpdated($document));
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_full_name' => Auth::user()->lname . ', ' . Auth::user()->fname,
            'module' => 'Document',
            'action' => 'Status Updated',
            'description' => "Document '{$document->tracking_code}' status changed to Ready for Claiming.",
            'ip_address' => $request->ip(),
            'device' => $request->userAgent(),
        ]);

        return back()->with('success', 'Document marked as Ready.');
    }

    public function markClaimed($id)
    {
        $document = Document::findOrFail($id);

        if ($document->status !== 'Ready for claiming') {
            return back()->with('error', 'Invalid status transition.');
        }

        $document->update([
            'status' => 'Claimed',
        ]);

        if ($document->email) {
            Mail::to($document->email)->send(new DocumentStatusUpdated($document));
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_full_name' => Auth::user()->lname . ', ' . Auth::user()->fname,
            'module' => 'Document',
            'action' => 'Status Updated',
            'description' => "Document '{$document->tracking_code}' status changed to Claimed.",
            'ip_address' => request()->ip(),
            'device' => request()->userAgent(),
        ]);

        return back()->with('success', 'Document marked as Claimed.');
    }

    public function scanTrack(Request $request)
    {
        $trackingCode = trim($request->tracking_code);

        $document = Document::where('tracking_code', $trackingCode)->first();

        if (!$document) {
            return response()->json([
                'status' => 'not_found'
            ]);
        }

        return response()->json([
            'status' => 'found',
            'document' => [
                'id' => $document->id,
                'fullname' => $document->surname . ', ' . $document->given_name . ' ' . $document->middle_name,
                'year_level' => $document->year_level,
                'program' => $document->program,
                'document_type' => $document->document_type,
                'email' => $document->email,
                'contact_number' => $document->contact_number,
                'current_status' => $document->status,
            ]
        ]);
    }

    public function confirmScan(Request $request)
    {
        $document = Document::find($request->id);

        if (!$document) {
            return response()->json(['status' => 'not_found']);
        }

        $action = $request->input('action', 'process');

        if ($document->status === 'Submitted') {
            if ($action === 'override') {
                $finalRemark = '[OVERRIDE] No kiosk waiting required.';
                if (!empty($document->remarks)) {
                    $finalRemark .= ' ' . $document->remarks;
                }
                
                $document->status = 'Claimed';
                $document->date_claimed = now();
                $document->remarks = $finalRemark;
                $document->save();
                
                if ($document->email) {
                    Mail::to($document->email)->send(new DocumentStatusUpdated($document));
                }

                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'user_full_name' => Auth::user()->lname . ', ' . Auth::user()->fname,
                    'module' => 'Document',
                    'action' => 'Override Submitted',
                    'description' => "Document '{$document->tracking_code}' overridden from Submitted to Claimed via scan.",
                    'ip_address' => request()->ip(),
                    'device' => request()->userAgent(),
                ]);

                return response()->json([
                    'status' => 'updated',
                    'new_status' => 'Claimed'
                ]);
            } else {
                $document->status = 'Collected and Processing';
                $document->save();
                if ($document->email) {
                    Mail::to($document->email)->send(new DocumentStatusUpdated($document));
                }

                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'user_full_name' => Auth::user()->lname . ', ' . Auth::user()->fname,
                    'module' => 'Document',
                    'action' => 'Status Updated',
                    'description' => "Document '{$document->tracking_code}' status changed to Collected and Processing via scan.",
                    'ip_address' => request()->ip(),
                    'device' => request()->userAgent(),
                ]);

                return response()->json([
                    'status' => 'updated',
                    'new_status' => 'Collected and Processing'
                ]);
            }
        } elseif ($document->status === 'Collected and Processing') {
            $document->status = 'Ready for claiming';
            $document->save();
            if ($document->email) {
                Mail::to($document->email)->send(new DocumentStatusUpdated($document));
            }

            ActivityLog::create([
                'user_id' => Auth::id(),
                'user_full_name' => Auth::user()->lname . ', ' . Auth::user()->fname,
                'module' => 'Document',
                'action' => 'Status Updated',
                'description' => "Document '{$document->tracking_code}' status changed to Ready for Claiming via scan.",
                'ip_address' => request()->ip(),
                'device' => request()->userAgent(),
            ]);
            
            return response()->json([
                'status' => 'updated',
                'new_status' => 'Ready for claiming'
            ]);
        } elseif ($document->status === 'Ready for claiming') {
            $document->status = 'Claimed';
            $document->save();
            if ($document->email) {
                Mail::to($document->email)->send(new DocumentStatusUpdated($document));
            }

            ActivityLog::create([
                'user_id' => Auth::id(),
                'user_full_name' => Auth::user()->lname . ', ' . Auth::user()->fname,
                'module' => 'Document',
                'action' => 'Status Updated',
                'description' => "Document '{$document->tracking_code}' status changed to Claimed via scan.",
                'ip_address' => request()->ip(),
                'device' => request()->userAgent(),
            ]);

            
            return response()->json([
                'status' => 'updated',
                'new_status' => 'Claimed'
            ]);

        } else {
            return response()->json([
                'status' => 'no_change',
                'current_status' => $document->status
            ]);
        }

        return response()->json([
            'status' => 'updated',
            'new_status' => $document->status
        ]);
    }

    public function testing() {
        return view('admin.testing');
    }

    public function export(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $fileName = 'documents_report_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(
            new DocumentsExport($startDate, $endDate),
            $fileName
        );
    }

    public function overrideSubmitted(Request $request, $id)
    {
        $request->validate([
            'remark' => 'nullable|string|max:500',
        ]);

        $document = Document::findOrFail($id);

        if ($document->status !== 'Submitted') {
            return back()->with('error', 'Only submitted documents can be overridden.');
        }

        $overrideRemark = trim((string) $request->input('remark'));
        $finalRemark = '[OVERRIDE] No kiosk waiting required.';
        if ($overrideRemark !== '') {
            $finalRemark .= ' ' . $overrideRemark;
        }

        $document->update([
            'status' => 'Claimed',
            'date_claimed' => now(),
            'remarks' => $finalRemark,
        ]);

        if ($document->email) {
            Mail::to($document->email)->send(new DocumentStatusUpdated($document));
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_full_name' => Auth::user()->lname . ', ' . Auth::user()->fname,
            'module' => 'Document',
            'action' => 'Override Submitted',
            'description' => "Document '{$document->tracking_code}' overridden from Submitted to Claimed.",
            'ip_address' => $request->ip(),
            'device' => $request->userAgent(),
        ]);

        return redirect()
            ->route('documents.index')
            ->with('success', "Document overridden successfully. You can print its guide.");
    }

    public function printOverrideGuide($id)
    {
        $document = Document::findOrFail($id);

        if (!str_contains((string) $document->remarks, '[OVERRIDE]')) {
            return redirect()->route('documents.index')->with('error', 'Guide is available for overridden documents only.');
        }

        return view('admin.override-guide', compact('document'));
    }
}