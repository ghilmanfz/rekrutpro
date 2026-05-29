<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\SystemConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    


    public function showStep1()
    {
        return view('auth.register-step1');
    }

    


    public function processStep1(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20|regex:/^628[0-9]{7,12}$/',
            'password' => 'required|string|min:8|confirmed',
            'agree_terms' => 'required|accepted',
        ], [
            'phone.regex' => 'Format nomor WhatsApp tidak valid. Gunakan format 628xxx (contoh: 6281234567890).',
        ]);

         
        $candidateRole = Role::where('name', 'candidate')->first();
        
        if (!$candidateRole) {
            \Log::error('Candidate role not found in database');
            return back()->with('error', 'Role candidate tidak ditemukan. Hubungi administrator.');
        }

         
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role_id' => $candidateRole->id,
            'registration_step' => 1,
            'registration_completed' => false,
            'is_active' => true,  
            'is_verified' => false,
        ]);

         
        if (!$user->role_id) {
            \Log::error('Role ID was not assigned to user', ['user_id' => $user->id, 'role_id' => $candidateRole->id]);
            $user->update(['role_id' => $candidateRole->id]);
        }

         
        auth()->login($user);

         
        return redirect()->route('register.step2')
            ->with('success', 'Akun berhasil dibuat! Silakan lanjutkan ke tahap selanjutnya.');
    }

    


    public function showStep2()
    {
        $user = auth()->user();
        
         
        if ($user->registration_step < 1) {
            return redirect()->route('register.step1');
        }

        return view('auth.register-step2');
    }

    


    public function processStep2(Request $request)
    {
        $validated = $request->validate([
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120',  
        ]);

        $user = auth()->user();

         
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

    


    public function showStep3()
    {
        $user = auth()->user();
        
        if ($user->registration_step < 2) {
            return redirect()->route('register.step2');
        }

         
        $otpCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->update([
            'otp_code' => $otpCode,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

         
        $this->sendOtpWhatsApp($user->phone, $otpCode);

        return view('auth.register-step3');
    }

    


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

    


    public function showStep4()
    {
        $user = auth()->user();
        
        if ($user->registration_step < 3) {
            return redirect()->route('register.step3');
        }

        return view('auth.register-step4');
    }

    


    public function processStep4(Request $request)
    {
        $validated = $request->validate([
            'address' => 'required|string',
            'education' => 'required|in:SMA/SMK,D3,S1,S2,S3',
            'experience' => 'nullable|string',
            'skills' => 'nullable|string',
        ]);

        $user = auth()->user();
        
        $user->update([
            'address' => $validated['address'],
            'education' => $validated['education'],
            'experience' => $validated['experience'],
            'skills' => $validated['skills'],
            'registration_step' => 4,
        ]);

        return redirect()->route('register.step5');
    }

    


    public function showStep5()
    {
        $user = auth()->user();
        
        if ($user->registration_step < 4) {
            return redirect()->route('register.step4');
        }

         
        $user->update([
            'registration_step' => 5,
            'registration_completed' => true,
            'is_active' => true,
        ]);

        return view('auth.register-step5');
    }

    


    public function complete()
    {
        $user = auth()->user();
        
        if (!$user->registration_completed) {
            return redirect()->route('register.step1');
        }

        return redirect()->route('candidate.dashboard')->with('success', 'Selamat datang! Akun Anda berhasil dibuat.');
    }

    


    public function resendOTP()
    {
        $user = auth()->user();
        
        $otpCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->update([
            'otp_code' => $otpCode,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

         
        $this->sendOtpWhatsApp($user->phone, $otpCode);

        return back()->with('success', 'Kode OTP baru telah dikirim ke WhatsApp Anda.');
    }

    


    private function sendOtpWhatsApp(string $phone, string $otpCode): void
    {
        $apiKey = SystemConfig::get('whatsapp_api_key');

        if (!$apiKey) {
            \Log::warning('WhatsApp API key not configured. OTP (dev): ' . $otpCode);
            return;
        }

        $message = "Kode OTP RekrutPro Anda: *{$otpCode}*\n\nKode ini berlaku selama 10 menit. Jangan bagikan kode ini kepada siapapun.";

        try {
            Http::withHeaders(['Authorization' => $apiKey])
                ->post('https://api.fonnte.com/send', [
                    'target' => $phone,
                    'message' => $message,
                ]);
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP via WhatsApp: ' . $e->getMessage());
        }
    }
}
