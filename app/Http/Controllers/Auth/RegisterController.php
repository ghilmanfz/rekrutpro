<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /**
     * Show registration form - Step 1
     */
    public function showStep1()
    {
        return view('auth.register-step1');
    }

    /**
     * Process Step 1 - Basic Account Info
     */
    public function processStep1(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'agree_terms' => 'required|accepted',
        ]);

        // Get candidate role
        $candidateRole = Role::where('name', 'candidate')->first();
        
        if (!$candidateRole) {
            \Log::error('Candidate role not found in database');
            return back()->with('error', 'Role candidate tidak ditemukan. Hubungi administrator.');
        }

        // Create user with step 1 completed
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $candidateRole->id,
            'registration_step' => 1,
            'registration_completed' => false,
            'is_active' => true, // ✅ PERBAIKAN: Set true agar bisa login lagi untuk lanjut registrasi
            'is_verified' => false,
        ]);

        // Verify role was assigned
        if (!$user->role_id) {
            \Log::error('Role ID was not assigned to user', ['user_id' => $user->id, 'role_id' => $candidateRole->id]);
            $user->update(['role_id' => $candidateRole->id]);
        }

        // Login user untuk melanjutkan ke step berikutnya
        auth()->login($user);

        // Redirect to step 2
        return redirect()->route('register.step2')
            ->with('success', 'Akun berhasil dibuat! Silakan lanjutkan ke tahap selanjutnya.');
    }

    /**
     * Show Step 2 - Upload CV
     */
    public function showStep2()
    {
        $user = auth()->user();
        
        // Check if user is at correct step
        if ($user->registration_step < 1) {
            return redirect()->route('register.step1');
        }

        return view('auth.register-step2');
    }

    /**
     * Process Step 2 - Upload CV
     */
    public function processStep2(Request $request)
    {
        $validated = $request->validate([
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB
        ]);

        $user = auth()->user();

        // Upload CV
        if ($request->hasFile('cv')) {
            $cvFile = $request->file('cv');
            $cvName = 'cv_' . $user->id . '_' . time() . '.' . $cvFile->getClientOriginalExtension();
            $cvPath = $cvFile->storeAs('cvs', $cvName, 'public');
            
            $user->update([
                'cv_path' => $cvPath,
                'registration_step' => 2,
            ]);
        }

        return redirect()->route('register.step3');
    }

    /**
     * Show Step 3 - Verify OTP
     */
    public function showStep3()
    {
        $user = auth()->user();
        
        if ($user->registration_step < 2) {
            return redirect()->route('register.step2');
        }

        // Generate OTP
        $otpCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->update([
            'otp_code' => $otpCode,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        // TODO: Send OTP via email
        // For development, show OTP in session
        session()->flash('otp_code', $otpCode);

        return view('auth.register-step3');
    }

    /**
     * Process Step 3 - Verify OTP
     */
    public function processStep3(Request $request)
    {
        $validated = $request->validate([
            'otp' => 'required|numeric|digits:6',
        ]);

        $user = auth()->user();

        if ($user->otp_code !== $validated['otp']) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid.']);
        }

        if ($user->otp_expires_at < now()) {
            return back()->withErrors(['otp' => 'Kode OTP sudah kadaluarsa.']);
        }

        $user->update([
            'is_verified' => true,
            'registration_step' => 3,
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        return redirect()->route('register.step4');
    }

    /**
     * Show Step 4 - Profile Details
     */
    public function showStep4()
    {
        $user = auth()->user();
        
        if ($user->registration_step < 3) {
            return redirect()->route('register.step3');
        }

        return view('auth.register-step4');
    }

    /**
     * Process Step 4 - Profile Details
     */
    public function processStep4(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'education' => 'required|in:SMA/SMK,D3,S1,S2,S3',
            'experience' => 'nullable|string',
            'skills' => 'nullable|string',
        ]);

        $user = auth()->user();
        
        $user->update([
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'education' => $validated['education'],
            'experience' => $validated['experience'],
            'skills' => $validated['skills'],
            'registration_step' => 4,
        ]);

        return redirect()->route('register.step5');
    }

    /**
     * Show Step 5 - Complete
     */
    public function showStep5()
    {
        $user = auth()->user();
        
        if ($user->registration_step < 4) {
            return redirect()->route('register.step4');
        }

        // Mark registration as completed
        $user->update([
            'registration_step' => 5,
            'registration_completed' => true,
            'is_active' => true,
        ]);

        return view('auth.register-step5');
    }

    /**
     * Complete registration and redirect to dashboard
     */
    public function complete()
    {
        $user = auth()->user();
        
        if (!$user->registration_completed) {
            return redirect()->route('register.step1');
        }

        return redirect()->route('candidate.dashboard')->with('success', 'Selamat datang! Akun Anda berhasil dibuat.');
    }

    /**
     * Resend OTP
     */
    public function resendOTP()
    {
        $user = auth()->user();
        
        $otpCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->update([
            'otp_code' => $otpCode,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        // TODO: Send OTP via email
        session()->flash('otp_code', $otpCode);

        return back()->with('success', 'Kode OTP baru telah dikirim ke email Anda.');
    }
}
