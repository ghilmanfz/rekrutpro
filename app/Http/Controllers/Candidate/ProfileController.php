<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function __construct(private FileUploadService $fileUploadService)
    {
    }
    


    public function edit()
    {
        $user = auth()->user();
        
         
        $profileFields = [
            'name', 'email', 'phone', 'date_of_birth', 'address',
            'education', 'study_program'
        ];
        
        $completedFields = 0;
        foreach ($profileFields as $field) {
            if (!empty($user->$field)) {
                $completedFields++;
            }
        }
        $profileCompletion = round(($completedFields / count($profileFields)) * 100);

         
        $totalApplications = \App\Models\Application::where('candidate_id', auth()->id())->count();
        $acceptedApplications = \App\Models\Application::where('candidate_id', auth()->id())
            ->whereIn('status', ['offered', 'hired'])
            ->count();

        return view('candidate.profile', compact('user', 'profileCompletion', 'totalApplications', 'acceptedApplications'));
    }

    


    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => ['required', 'string', 'max:15', 'regex:/^(?:628[0-9]{7,12}|08[0-9]{8,11})$/'],
            'date_of_birth' => 'required|date|before_or_equal:today',
            'address' => 'required|string|max:1000',
            'education' => 'required|in:SMA/SMK,D3,S1,S2,S3',
            'study_program' => 'required|string|max:255',
            'experience' => 'nullable|string|max:5000',
            'skills' => 'nullable|string|max:2000',
            'linkedin_url' => 'nullable|url|max:255',
            'github_url' => 'nullable|url|max:255',
            'portfolio_url' => 'nullable|url|max:255',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'return_job_id' => [
                'nullable',
                'integer',
                Rule::exists('job_postings', 'id')->where(fn ($query) => $query->where('status', 'active')),
            ],
        ], [
            'phone.regex' => 'Nomor WhatsApp harus berupa angka dengan format 628xxx atau 08xxx.',
            'study_program.required' => 'Program studi atau jurusan wajib diisi.',
        ]);

        if ($request->hasFile('cv')) {
            $validated['cv_path'] = $this->fileUploadService->uploadCV(
                $request->file('cv'),
                $validated['name']
            );
        }

        $applyJobId = $validated['return_job_id'] ?? null;
        unset($validated['cv'], $validated['return_job_id']);

        $user->update($validated);

        if ($applyJobId && JobPosting::query()->whereKey($applyJobId)->where('status', 'active')->exists()) {
            return redirect()
                ->route('candidate.applications.create', $applyJobId)
                ->with('success', 'Profil berhasil diperbarui. Silakan lanjutkan lamaran Anda.');
        }

        return redirect()
            ->route('candidate.profile')
            ->with('success', 'Profil berhasil diperbarui!');
    }
}
