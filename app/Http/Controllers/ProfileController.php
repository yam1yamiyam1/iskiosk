<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */

    public function update(Request $request)
    {
        $request->validate([
            'fname' => ['required', 'regex:/^[A-Za-z\s.-]{3,}$/'],
            'mi' => ['nullable', 'max:2', 'regex:/^[A-Za-z\s.-]{1,2}$/'],
            'lname' => ['required', 'regex:/^[A-Za-z\s.-]{3,}$/'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'email' => ['required', 'email', Rule::unique('users')->ignore(auth()->id())],
            'password' => ['nullable', 'min:8', 'confirmed', 'regex:/^\S*$/'],
        ]);

        $user = auth()->user();

        $original = $user->only(['fname', 'mi', 'lname', 'email']);
        $updated = [
            'fname' => $request->input('fname'),
            'mi' => $request->input('mi'),
            'lname' => $request->input('lname'),
            'email' => $request->input('email'),
        ];

        $imageChanged = false;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if ($file->isValid()) {
                if ($user->image) {
                    \Storage::disk('public')->delete('users/' . $user->image);
                }

                $filename = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('users', $filename, 'public');
                $user->image = $filename;
                $imageChanged = true;
            } else {
                return back()->withErrors(['image' => 'Invalid image file']);
            }
        }


        $passwordChanged = $request->filled('password');
        

        if ($original == $updated && !$passwordChanged && !$imageChanged) {
            return redirect()->route('profile.edit')->with('info', 'No changes detected.');
        }

        $user->fill($updated);

        if ($passwordChanged) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        $changes = [];

        if ($original['fname'] !== $updated['fname'] || $original['lname'] !== $updated['lname'] || $original['mi'] !== $updated['mi']) {
            $changes[] = "updated their name";
        }

        if ($original['email'] !== $updated['email']) {
            $changes[] = "changed their email from {$original['email']} to {$updated['email']}";
        }

        if ($passwordChanged) {
            $changes[] = "changed their password";
        }

        if ($imageChanged) {
            $changes[] = "updated their profile image";
        }

        $description = ucfirst(implode(', ', $changes)) . '.';

        ActivityLog::create([
            'user_id' => $user->id,
            'user_full_name' => "{$user->fname} {$user->lname} ({$user->email})",
            'module' => 3,
            'action' => 'Updated Profile',
            'ip_address' => $request->ip(),
            'device' => $request->userAgent(),
            'description' => $description,
        ]);

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
