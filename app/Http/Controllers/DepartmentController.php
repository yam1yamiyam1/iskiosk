<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::latest()->get();
        return view('admin.department', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150|unique:departments',
        ]);

        $department = Department::create($request->only('name'));

        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_full_name' => Auth::user()->lname . ', ' . Auth::user()->fname,
            'module' => 'Department',
            'action' => 'Created',
            'description' => "Created department: {$department->name}",
            'ip_address' => $request->ip(),
            'device' => $request->header('User-Agent'),
        ]);

        return back()->with('success', 'Department created');
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|string|max:150|unique:departments,name,' . $department->id,
        ]);

        $oldName = $department->name;
        $department->fill($request->only('name'));

        if ($department->isDirty()) {
            $department->save();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'user_full_name' => Auth::user()->lname . ', ' . Auth::user()->fname,
                'module' => 'Department',
                'action' => 'Updated',
                'description' => "Updated department: '{$oldName}' to '{$department->name}'",
                'ip_address' => $request->ip(),
                'device' => $request->header('User-Agent'),
            ]);

            return back()->with('success', 'Department updated successfully.');
        }

        return back()->with('info', 'No changes were made.');
    }

    public function destroy(Request $request, Department $department)
    {
        $name = $department->name;
        $department->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_full_name' => Auth::user()->lname . ', ' . Auth::user()->fname,
            'module' => 'Department',
            'action' => 'Deleted',
            'description' => "Deleted department: {$name}",
            'ip_address' => $request->ip(),
            'device' => $request->header('User-Agent'),
        ]);

        return back()->with('success', 'Department deleted');
    }
}