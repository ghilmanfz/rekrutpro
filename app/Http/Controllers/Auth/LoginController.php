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
    


    public function showLoginForm()
    {
        return view('auth.login');
    }

    


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

         
        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan.',
            ])->withInput();
        }

         
        if (!$user->is_active) {
            return back()->withErrors([
                'email' => 'Akun Anda tidak aktif. Hubungi administrator.',
            ])->withInput();
        }

         
        if ($user->isCandidate() && !$user->is_verified) {
            return back()->withErrors([
                'email' => 'Email belum diverifikasi. Silakan cek email Anda.',
            ])->withInput();
        }

         
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->filled('remember'))) {
            $request->session()->regenerate();

             
            $user->update(['last_login_at' => now()]);

             
            AuditLog::log('login', $user);

             
            return $this->redirectBasedOnRole($user);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput();
    }

    


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

         
        return redirect('/');
    }

    


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
