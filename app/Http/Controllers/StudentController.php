<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::latest()->get();
        return view('admin.student', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_number' => 'required|string|max:50|unique:students,id_number',
            'surname' => 'required|string|max:100',
            'given_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'year_level' => 'nullable|string|max:10',
            'program' => 'nullable|string|max:100',
            'email' => 'nullable|email|unique:students,email',
            'contact_number' => 'nullable|string|max:20',
        ]);

        $student = Student::create($request->all());

        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_full_name' => Auth::user()->lname . ', ' . Auth::user()->fname,
            'module' => 'Student',
            'action' => 'Created',
            'description' => "Added student: {$student->surname}, {$student->given_name}",
            'ip_address' => $request->ip(),
            'device' => $request->header('User-Agent'),
        ]);

        return back()->with('success', 'Student added successfully.');
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'id_number' => 'required|string|max:50|unique:students,id_number,' . $student->id,
            'surname' => 'required|string|max:100',
            'given_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'year_level' => 'nullable|string|max:10',
            'program' => 'nullable|string|max:100',
            'email' => 'nullable|email|unique:students,email,' . $student->id,
            'contact_number' => 'nullable|string|max:20',
        ]);

        $oldData = $student->getOriginal();

        $student->fill($request->all());

        if ($student->isDirty()) {
            $changes = [];
            foreach ($student->getDirty() as $field => $newValue) {
                $oldValue = $oldData[$field] ?? null;
                $changes[] = "{$field}: '{$oldValue}' → '{$newValue}'";
            }

            $student->save();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'user_full_name' => Auth::user()->lname . ', ' . Auth::user()->fname,
                'module' => 'Student',
                'action' => 'Updated',
                'description' => "Updated student: " . implode('; ', $changes),
                'ip_address' => $request->ip(),
                'device' => $request->header('User-Agent'),
            ]);

            return back()->with('success', 'Student updated successfully.');
        }

        return back()->with('info', 'No changes were made.');
    }

    public function destroy(Request $request, Student $student)
    {
        $name = $student->surname . ', ' . $student->given_name;
        $student->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_full_name' => Auth::user()->lname . ', ' . Auth::user()->fname,
            'module' => 'Student',
            'action' => 'Deleted',
            'description' => "Deleted student: {$name}",
            'ip_address' => $request->ip(),
            'device' => $request->header('User-Agent'),
        ]);

        return back()->with('success', 'Student deleted successfully.');
    }
}