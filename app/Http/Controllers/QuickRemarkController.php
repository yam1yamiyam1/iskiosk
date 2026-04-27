<?php

namespace App\Http\Controllers;

use App\Models\QuickRemark;
use Illuminate\Http\Request;

class QuickRemarkController extends Controller
{
    public function index()
    {
        $quickRemarks = QuickRemark::latest()->get();
        return view('admin.quick-remarks', compact('quickRemarks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'remark' => 'required|string|max:500'
        ]);

        QuickRemark::create([
            'remark' => $request->remark
        ]);

        return back()->with('success', 'Quick remark added successfully.');
    }

    public function destroy(QuickRemark $quickRemark)
    {
        $quickRemark->delete();
        
        return back()->with('success', 'Quick remark deleted successfully.');
    }
}
