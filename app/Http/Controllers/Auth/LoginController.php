<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'signin-email' => 'required|string|email',
            'signin-password' => 'required|string',
        ]);
    
        if (auth()->attempt(['email' => $request->input('signin-email'), 'password' => $request->input('signin-password')])) {
            $user = auth()->user();
    
            if ($user->isActive()) {
                auth()->logout();
                return back()->withErrors([
                    'signin-email' => 'Your account is disabled. Please contact support.',
                ]);
            }
            
            $this->logActivity($user, 'login', $request);
            return $this->authenticated($request, $user);
        }
    
        return back()->withErrors([
            'signin-email' => 'The provided credentials do not match our records.',
        ]);
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->hasRole('1')) {
            return redirect()->route('dashboard');
        } elseif ($user->hasRole('0')) {
            return redirect()->route('dashboard');
        }

        return redirect($this->redirectTo);
    }

    public function logout(Request $request)
    {

        $user = auth()->user();

        if ($user) {
            $this->logActivity($user, 'logout', $request);
        }
        
        auth()->logout();
        return redirect('/login');
    }

    protected function logActivity($user, $action, $request)
    {
        ActivityLog::create([
            'user_id' => $user->id,
            'user_full_name' => $user->name ?? ($user->first_name . ' ' . $user->last_name ?? 'Unknown User'),
            'module' => 1,
            'action' => $action,
            'ip_address' => $request->ip(),
            'description' => "User {$action} successfully.",
        ]);
    }
}
