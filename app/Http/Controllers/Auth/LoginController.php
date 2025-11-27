<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

        // Check if user exists
        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan.',
            ])->withInput();
        }

        // Check if user is active
        if (!$user->is_active) {
            return back()->withErrors([
                'email' => 'Akun Anda tidak aktif. Hubungi administrator.',
            ])->withInput();
        }

        // Check if email is verified (for candidates)
        if ($user->isCandidate() && !$user->is_verified) {
            return back()->withErrors([
                'email' => 'Email belum diverifikasi. Silakan cek email Anda.',
            ])->withInput();
        }

        // Attempt login
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->filled('remember'))) {
            $request->session()->regenerate();

            // Update last login
            $user->update(['last_login_at' => now()]);

            // Log activity
            AuditLog::log('login', $user);

            // Redirect based on role
            return $this->redirectBasedOnRole($user);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput();
    }

    /**
     * Redirect user based on their role
     */
    protected function redirectBasedOnRole($user)
    {
        if ($user->isSuperAdmin()) {
            return redirect()->route('super-admin.dashboard')
                ->with('success', 'Selamat datang, Super Admin!');
        }

        if ($user->isHR()) {
            return redirect()->route('hr.dashboard')
                ->with('success', 'Selamat datang, ' . $user->name . '!');
        }

        if ($user->isInterviewer()) {
            return redirect()->route('interviewer.dashboard')
                ->with('success', 'Selamat datang, ' . $user->name . '!');
        }

        if ($user->isCandidate()) {
            return redirect()->route('candidate.dashboard')
                ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
        }

        // Default redirect
        return redirect('/');
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        AuditLog::log('logout', auth()->user());

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda berhasil logout.');
    }
}
