<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Redirect user to appropriate dashboard based on their role
     */
    public function index()
    {
        $user = auth()->user();
        
        // Check if user has role
        if (!$user->role) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Akun Anda belum memiliki role. Silakan hubungi administrator.');
        }

        $roleName = $user->role->name;

        // Redirect based on role
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
                // If no role matched, logout and redirect to login
                auth()->logout();
                return redirect()->route('login')->with('error', 'Role tidak valid. Silakan hubungi administrator.');
        }
    }
}
