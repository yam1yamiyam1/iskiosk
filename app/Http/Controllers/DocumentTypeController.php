<?php

namespace App\Http\Controllers;

use App\Models\DocumentType;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentTypeController extends Controller
{
    public function index()
    {
        $types = DocumentType::latest()->get();
        return view('admin.type', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:120|unique:document_types',
        ]);

        $type = DocumentType::create($request->only('name'));

        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_full_name' => Auth::user()->lname . ', ' . Auth::user()->fname,
            'module' => 'Document Type',
            'action' => 'Created',
            'description' => "Created document type: {$type->name}",
            'ip_address' => $request->ip(),
            'device' => $request->header('User-Agent'),
        ]);

        return back()->with('success', 'Document type created');
    }

    public function update(Request $request, DocumentType $type)
    {
        $request->validate([
            'name' => 'required|string|max:120|unique:document_types,name,' . $type->id,
        ]);

        $oldName = $type->name;
        $type->fill($request->only('name'));

        if ($type->isDirty()) {
            $type->save();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'user_full_name' => Auth::user()->lname . ', ' . Auth::user()->fname,
                'module' => 'Document Type',
                'action' => 'Updated',
                'description' => "Updated document type: '{$oldName}' to '{$type->name}'",
                'ip_address' => $request->ip(),
                'device' => $request->header('User-Agent'),
            ]);

            return back()->with('success', 'Document type updated successfully.');
        }
        
        return back()->with('info', 'No changes were made.');
    }

    public function destroy(Request $request, DocumentType $type)
    {
        $name = $type->name;
        $type->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_full_name' => Auth::user()->lname . ', ' . Auth::user()->fname,
            'module' => 'Document Type',
            'action' => 'Deleted',
            'description' => "Deleted document type: {$name}",
            'ip_address' => $request->ip(),
            'device' => $request->header('User-Agent'),
        ]);

        return back()->with('success', 'Document type deleted');
    }
}