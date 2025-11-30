<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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

        $request->session()->regenerate();

        // Check if user has completed registration
        $user = auth()->user();
        
        // ✅ PERBAIKAN: Jika registrasi belum complete, redirect ke step yang belum selesai
        if (!$user->registration_completed && $user->role->name === 'candidate') {
            // Redirect ke step yang sesuai berdasarkan registration_step
            $step = $user->registration_step ?? 1;
            
            return redirect()->route("register.step{$step}")
                ->with('info', 'Silakan lanjutkan proses registrasi Anda.');
        }

        // Check if account is suspended by admin
        if (!$user->is_active && $user->registration_completed) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login')
                ->withErrors(['email' => 'Akun Anda telah dinonaktifkan oleh administrator. Silakan hubungi tim support.']);
        }

        // Redirect based on user role
        return $this->redirectBasedOnRole($user);
    }

    /**
     * Redirect user to appropriate dashboard based on their role
     */
    protected function redirectBasedOnRole($user): RedirectResponse
    {
        // Get user's role name
        $roleName = $user->role->name ?? null;

        switch ($roleName) {
            case 'super_admin':
                return redirect()->route('superadmin.dashboard');
                
            case 'hr':
                return redirect()->route('hr.dashboard');
                
            case 'interviewer':
                return redirect()->route('interviewer.dashboard');
                
            case 'candidate':
                return redirect()->route('candidate.dashboard');
                
            default:
                // Fallback to general dashboard if role not recognized
                return redirect()->route('dashboard');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
