<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form
     */
    public function edit()
    {
        $user = auth()->user();
        
        // Calculate profile completion
        $profileFields = [
            'name', 'email', 'phone', 'address', 
            'education', 'experience', 'skills'
        ];
        
        $completedFields = 0;
        foreach ($profileFields as $field) {
            if (!empty($user->$field)) {
                $completedFields++;
            }
        }
        $profileCompletion = round(($completedFields / count($profileFields)) * 100);

        // Get application stats
        $totalApplications = \App\Models\Application::where('candidate_id', auth()->id())->count();
        $acceptedApplications = \App\Models\Application::where('candidate_id', auth()->id())
            ->whereIn('status', ['offered', 'hired'])
            ->count();

        return view('candidate.profile', compact('user', 'profileCompletion', 'totalApplications', 'acceptedApplications'));
    }

    /**
     * Update candidate profile
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string',
            'education' => 'nullable|string',
            'experience' => 'nullable|string',
            'skills' => 'nullable|string',
            'linkedin_url' => 'nullable|url',
            'github_url' => 'nullable|url',
            'portfolio_url' => 'nullable|url',
        ]);

        $user->update($validated);

        return redirect()
            ->route('candidate.profile')
            ->with('success', 'Profil berhasil diperbarui!');
    }
}
