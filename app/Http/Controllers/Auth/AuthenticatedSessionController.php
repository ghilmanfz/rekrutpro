<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    


    public function create(): View
    {
        return view('auth.login');
    }

    


    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

         
        $user = auth()->user();
        $roleName = $user->role?->name;

         
        if (!$user->is_active) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login')
                ->withErrors(['email' => 'Akun Anda telah dinonaktifkan oleh administrator. Silakan hubungi tim support.']);
        }
        
         
        if (!$user->registration_completed && $roleName === Role::CANDIDATE) {
            $step = min(max(($user->registration_step ?? 1) + 1, 2), 5);
            
            return redirect()->route("register.step{$step}")
                ->with('info', 'Silakan lanjutkan proses registrasi Anda.');
        }

         
        return $this->redirectBasedOnRole($user);
    }

    


    protected function redirectBasedOnRole($user): RedirectResponse
    {
         
        $roleName = $user->role?->name;

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
                 
                return redirect()->route('dashboard');
        }
    }

    


    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
