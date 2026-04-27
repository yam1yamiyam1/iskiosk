<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;
use App\Models\ActivityLog;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();

        if ($user && !$user->isActive()) {
            Auth::logout();
            $this->logActivity($user, 'Login Denied', $request, 'Account is disabled');
            return back()->withErrors([
                'email' => 'Your account is disabled. Please contact support.',
            ]);
        }
        
        // if ($user && !$user->isActive()) {
        //     Auth::logout();
        //     return back()->withErrors([
        //         'email' => 'The website is currently under maintenance. Please try again later.',
        //     ]);
        // }


        $request->session()->regenerate();

        $this->logActivity($user, 'Login', $request, 'User logged in successfully');

        if ($user->hasRole('1')) {
            return redirect()->route('dashboard');
        } elseif ($user->hasRole('0')) {
            return redirect('/dashboard');
        }

        Auth::logout();
        $this->logActivity($user, 'Unauthorized Login', $request, 'User role not authorized');
        return redirect()->route('login')->withErrors([
            'email' => 'Unauthorized access.',
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user) {
            $this->logActivity($user, 'Logout', $request, 'User logged out successfully');
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    protected function logActivity($user, $action, $request, $message)
    {
        ActivityLog::create([
            'user_id' => $user->id ?? null,
            'user_full_name' => "{$user->fname} {$user->lname} ({$user->email})",
            'module' => 1,
            'action' => $action,
            'ip_address' => $request->ip(),
            'device' => $request->userAgent(),
            'description' => $message,
        ]);
    }
}
