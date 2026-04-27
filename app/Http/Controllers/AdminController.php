<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use \Milon\Barcode\DNS1D;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        return view('admin.user', compact('users'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fname' => ['required', 'regex:/^[A-Za-z\s.-]{3,}$/'],
            'mi' => ['nullable', 'max:2', 'regex:/^[A-Za-z\s.-]{1,2}$/'],
            'lname' => ['required', 'regex:/^[A-Za-z\s.-]{3,}$/'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['min:8', 'confirmed', 'regex:/^\S*$/'],
            'role' => ['required', 'in:0,1'],
            'status' => ['required', 'in:0,1'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        

        try {
            $data = $request->except(['image', 'role', 'password_confirmation']);
            $data['password'] = Hash::make($request->password);

            do {
                $barcode = 'USR-' . strtoupper(Str::random(6)) . '-' . random_int(100, 999);
            } while (User::where('barcode', $barcode)->exists());

            $data['barcode'] = $barcode;

            $data['status'] = $request->input('status');

            $user = User::create($data);

            if ($request->hasFile('image')) {
                $file = $request->file('image');

                if ($file->isValid()) {
                    $filename = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('users', $filename, 'public');

                    $user->update(['image' => $filename]);
                } else {
                    return back()->withErrors(['image' => 'Invalid image file']);
                }
            }

            $role = Role::create([
                'role' => $request['role'] ?? 0,
                'name' => $user->fname,
            ]);
            
            
            $user->roles()->attach($role->id);

            $roleName = $request->role == 1 ? 'Admin' : 'Staff';

            $description = "Created new {$roleName} staff '{$user->fname} {$user->lname}' (email: {$user->email}).";

            ActivityLog::create([
                'user_id' => auth()->id(),
                'user_full_name' => auth()->user()->fname . ' ' . auth()->user()->lname . ' (' . auth()->user()->email . ')',
                'module' => 7,
                'action' => 'Created User',
                'ip_address' => $request->ip(),
                'device' => $request->userAgent(),
                'description' => $description,
            ]);

            return redirect()->back()->with('success', 'Staff has been created.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Something went wrong while creating the ustaffser.']);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'fname' => ['required', 'regex:/^[A-Za-z\s.-]{3,}$/'],
            'mi' => ['nullable', 'max:2', 'regex:/^[A-Za-z\s.-]{1,2}$/'],
            'lname' => ['required', 'regex:/^[A-Za-z\s.-]{3,}$/'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'min:8', 'confirmed', 'regex:/^\S*$/'],
            'role' => ['required', 'in:0,1'],
            'status' => ['required', 'in:0,1'],
        ]);

        $original = $user->only(['fname', 'mi', 'lname', 'email', 'status']);
        $changes = [];

        $user->fname = $request->input('fname');
        $user->mi = $request->input('mi');
        $user->lname = $request->input('lname');
        $user->email = $request->input('email');
        $user->status = $request->input('status');

        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
            $changes[] = 'password changed';
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if ($file->isValid()) {
                if ($user->image) {
                    \Storage::disk('public')->delete('users/' . $user->image);
                }
                $filename = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('users', $filename, 'public');
                $user->image = $filename;
                $changes[] = 'profile image updated';
            }
        }

        $roleChanged = false;
        if ($user->roles()->exists()) {
            $userRole = $user->roles()->first();
            if ($userRole->role != $request->input('role')) {
                $userRole->role = $request->input('role');
                $userRole->save();
                $roleChanged = true;
                $changes[] = 'role changed to ' . ($request->input('role') == 1 ? 'Admin' : 'Staff');
            }
        }

        foreach ($original as $field => $oldValue) {
            $newValue = $user->$field;
            if ($oldValue != $newValue) {
                $changes[] = "{$field} changed from '{$oldValue}' to '{$newValue}'";
            }
        }

        if (!empty($changes)) {
            $user->save();

            ActivityLog::create([
                'user_id' => auth()->id(),
                'user_full_name' => auth()->user()->fname . ' ' . auth()->user()->lname . ' (' . auth()->user()->email . ')',
                'module' => 7,
                'action' => 'Updated User',
                'ip_address' => $request->ip(),
                'device' => $request->userAgent(),
                'description' => implode('; ', $changes),
            ]);

            return redirect()->route('users.index')->with('success', 'User updated successfully.');
        }

        return redirect()->route('users.index')->with('info', 'No changes were made.');
    }

    public function disable(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        if ($user->status == 0) {
            $user->status = 1;
            flash()->success('Success', 'Account Enabled Successfully');
        }
        else {
            $user->status = 0;
            if ($user->suspended_at != null || $user->suspended_at != "") {
                $user->suspended_at = null;
                $user->suspension_end_at = null;
            }
            flash()->success('Success', 'Account Disabled Successfully');
        }
        $user->save();
    
        return back()->with('success');
    }
    
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (auth()->check() && auth()->id() == $user->id) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $role = $user->roles()->exists() && $user->roles()->first()->role == 1 ? 'Admin' : 'Staff';

        $tempUserId = 'DEL-' . strtoupper(uniqid());

        ActivityLog::where('user_id', $user->id)
        ->update(['temp_user_id' => $tempUserId]);

        if ($user->image && \Storage::disk('public')->exists('users/' . $user->image)) {
            \Storage::disk('public')->delete('users/' . $user->image);
        }

        $deletedUserName = "{$user->fname} {$user->lname} ({$user->email})";

        $user->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'user_full_name' => auth()->user()->fname . ' ' . auth()->user()->lname . ' (' . auth()->user()->email . ')',
            'module' => 7,
            'action' => 'Deleted User',
            'ip_address' => request()->ip(),
            'device' => request()->userAgent(),
            'description' => "Deleted {$role} account: {$deletedUserName}.",
        ]);

        return redirect()->back()->with('success', 'User has been deleted.');
    }

    public function regenerateBarcode(User $user)
    {
        try {
            do {
                $barcode = 'USR-' . strtoupper(Str::random(6)) . '-' . random_int(100, 999);
            } while (User::where('barcode', $barcode)->exists());

            $user->barcode = $barcode;
            $user->save();


            $barcodeGenerator = new DNS1D();

            $barcode = $barcodeGenerator->getBarcodePNG($user->barcode, 'C128');

            return response()->json([
                'success' => true,
                'barcode' => $barcode
            ]);


        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function downloadAllBarcodes()
    {
        $users = User::whereNotNull('barcode')->get();

        $pdf = Pdf::loadView('pdf.user-barcodes', compact('users'))
            ->setPaper('A4');

        return $pdf->download('all_user_barcodes.pdf');
    }
}
