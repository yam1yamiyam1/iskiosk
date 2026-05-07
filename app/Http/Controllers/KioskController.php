<?php

namespace App\Http\Controllers;

use \Milon\Barcode\DNS1D;
use App\Models\Student;
use App\Models\User;
use App\Models\Document;
use App\Models\Department;
use App\Models\DocumentType;
use App\Models\BatchDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\DocumentStatusUpdated;
use Carbon\Carbon;

class KioskController extends Controller
{
    private function normalizeIdNumber(string $idNumber): string
    {
        $id = strtoupper(trim($idNumber));
        $id = preg_replace('/\s+/', '', $id);
        return $id;
    }

    public function index()
    {
        return view('kiosk.home');
    }

    public function carousel()
    {
        $carouselSlides = collect(glob(public_path('assets/img/kiosk-carousel/*.png')) ?: [])
            ->sort(SORT_NATURAL)
            ->values()
            ->map(fn ($path) => asset('assets/img/kiosk-carousel/'.basename($path)))
            ->all();

        return view('kiosk.carousel', compact('carouselSlides'));
    }

    public function create()
    {
        $departments   = Department::orderBy('name')->get();
        $documentTypes = DocumentType::orderBy('name')->get();

        return view('kiosk.submit', compact('departments', 'documentTypes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_number'       => 'required|string',
            'surname'         => 'required|string',
            'given_name'      => 'required|string',
            'middle_name'     => 'nullable|string',
            'year_level'      => 'required|string',
            'program'         => 'required|string',
            'email'           => 'required|email',
            'contact_number'  => 'required|string',
            'document_type'   => 'required|string',
            'confirm_update'  => 'nullable|boolean',
        ]);


        $data['id_number'] = $this->normalizeIdNumber($data['id_number']);

        DB::beginTransaction();

        try {
            $student = Student::where('id_number', $data['id_number'])->first();

            if (!$student) {
                $student = Student::create($data);
            } else {
                $changedFields = [];

                foreach ([
                    'surname',
                    'given_name',
                    'middle_name',
                    'year_level',
                    'program',
                    'email',
                    'contact_number',
                ] as $field) {
                    if (($student->$field ?? '') !== ($data[$field] ?? '')) {
                        $changedFields[$field] = [
                            'old' => $student->$field,
                            'new' => $data[$field],
                        ];
                    }
                }

                if (!empty($changedFields) && !$request->boolean('confirm_update')) {
                    DB::rollBack();

                    return response()->json([
                        'status'  => 'confirm_update',
                        'changes' => $changedFields,
                        'message' => 'Your information does not match our records.',
                    ], 409);
                }

                if (!empty($changedFields)) {
                    $student->update($data);
                }
            }

            $pendingBatch = BatchDocument::where('status', 'pending')->first();

            if (!$pendingBatch) {
                $pendingBatch = BatchDocument::create([
                    'status'   => 'pending',
                    'added_at'=> now(),
                ]);
            }

            $fullName = $student->surname . ', ' . $student->given_name;

            if (!empty($student->middle_name)) {
                $fullName .= ' ' . $student->middle_name;
            }

            $document = Document::create([
                'id_number'      => $student->id_number,
                'surname'        => $student->surname,
                'given_name'     => $student->given_name,
                'middle_name'    => $student->middle_name,
                'year_level'     => $student->year_level,
                'program'        => $student->program,
                'email'          => $student->email,
                'contact_number' => $student->contact_number,
                'document_type'  => $data['document_type'],
                'tracking_code' => 'TRK-' . strtoupper(Str::random(8)),
                'status'         => 'Pending Print',
                'batch_id'       => $pendingBatch->id,
            ]);

            DB::commit();

            $barcodeGenerator = new DNS1D();
            $barcodeImage = $barcodeGenerator->getBarcodePNG($document->tracking_code, 'C128');

            $barcodeBase64 = base64_encode($barcodeImage);

            return response()->json([
                'status' => 'success',
                'tracking_code' => $document->tracking_code,
                'barcode_base64' => $barcodeBase64,
                'full_name' => $fullName,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function verifyID(Request $request)
    {
        $request->validate([
            'id_number' => 'required',
        ]);


        $idNumber = $this->normalizeIdNumber($request->input('id_number'));
        $idNumberNoDash = str_replace('-', '', $idNumber);

        $user = \App\Models\User::where('barcode', $idNumber)->first();

        if ($user) {

            $documents = \App\Models\Document::where('status', 'Submitted')->get();
            $pendingBatch = \App\Models\BatchDocument::where('status', 'pending')->first();

            if ($documents->isEmpty() || !$pendingBatch) {
                return response()->json([
                    'type' => 'user',
                    'exists' => true,
                    'error' => 'No submitted documents available.'
                ]);
            }

            \App\Models\Document::where('status', 'Submitted')
                ->update(['status' => 'Collected and Processing']);

            $pendingBatch->update([
                'user_id' => $user->id,
                'status' => 'finalized',
                'finalized_at' => Carbon::now('Asia/Manila'),
            ]);

            return response()->json([
                'type' => 'user',
                'exists' => true,
                'message' => 'Documents updated successfully',
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ]);
        }

        $student = \App\Models\Student::where('id_number', $idNumber)
            ->orWhereRaw(
                "REPLACE(REPLACE(UPPER(id_number), ' ', ''), '-', '') = ?",
                [$idNumberNoDash]
            )
            ->first();

        return response()->json([
            'type' => 'student',
            'exists' => (bool) $student,
            'student' => $student ? [
                'surname' => $student->surname,
                'given_name' => $student->given_name,
                'middle_name' => $student->middle_name,
                'year_level' => $student->year_level,
                'program' => $student->program,
                'email' => $student->email,
                'contact_number' => $student->contact_number,
            ] : null,
        ]);
    }

    public function claim()
    {
        return view('kiosk.claim');
    }

    public function verifyClaim(Request $request)
    {
        $request->validate(['tracking_code' => 'required|string']);
        
        $document = \App\Models\Document::with(['document_typeb'])->where('tracking_code', $request->tracking_code)->first();

        if (!$document) {
            return response()->json([
                'status' => 'error',
                'message' => 'Document not found.',
            ], 404);
        }

        if ($document->status !== 'Ready for claiming') {
            return response()->json([
                'status' => 'error',
                'message' => 'Document is not ready for claiming (Current status: ' . $document->status . ').',
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'document' => [
                'id' => $document->id,
                'tracking_code' => $document->tracking_code,
                'full_name' => $document->surname . ', ' . $document->given_name . ($document->middle_name ? ' ' . $document->middle_name : ''),
                'document_type' => $document->document_typeb ? $document->document_typeb->name : 'Unknown',
            ]
        ]);
    }

    public function confirmClaim(Request $request)
    {
        $request->validate(['tracking_code' => 'required|string']);

        $document = \App\Models\Document::where('tracking_code', $request->tracking_code)->first();

        if (!$document || $document->status !== 'Ready for claiming') {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid document or status.',
            ], 400);
        }

        $document->update([
            'status' => 'Claimed',
            'date_claimed' => now(),
        ]);

        if ($document->email) {
            \Illuminate\Support\Facades\Mail::to($document->email)->send(new \App\Mail\DocumentStatusUpdated($document));
        }

        \App\Models\ActivityLog::create([
            'user_id' => null,
            'user_full_name' => 'Kiosk System',
            'module' => 'Document',
            'action' => 'Status Updated',
            'description' => "Document '{$document->tracking_code}' status changed to Claimed via Kiosk.",
            'ip_address' => $request->ip(),
            'device' => $request->userAgent(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Document successfully claimed.',
            'tracking_code' => $document->tracking_code,
        ]);
    }
    public function finalizeSubmission(Request $request)
    {
        $request->validate(['tracking_code' => 'required|string']);

        $document = Document::where('tracking_code', $request->tracking_code)->first();

        if ($document && $document->status === 'Pending Print') {
            $document->update(['status' => 'Submitted']);

            if ($document->email) {
                Mail::to($document->email)->send(new DocumentStatusUpdated($document));
            }

            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error', 'message' => 'Invalid document'], 400);
    }

    public function flagPrinterError(Request $request)
    {
        $request->validate(['tracking_code' => 'required|string']);

        $document = Document::where('tracking_code', $request->tracking_code)->first();

        if ($document && $document->status === 'Pending Print') {
            $document->update(['status' => 'Printer Error']);

            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error', 'message' => 'Invalid document'], 400);
    }
}
