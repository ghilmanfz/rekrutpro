<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Division;
use App\Models\JobPosting;
use App\Models\Location;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobPostingController extends Controller
{
    /**
     * Display a listing of job postings
     */
    public function index(Request $request)
    {
        $query = JobPosting::with(['position', 'division', 'location', 'creator'])
            ->withCount('applications');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by division
        if ($request->filled('division_id')) {
            $query->where('division_id', $request->division_id);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        $jobPostings = $query->latest()->paginate(15);
        $divisions = Division::where('is_active', true)->get();

        return view('hr.job-postings.index', compact('jobPostings', 'divisions'));
    }

    /**
     * Show the form for creating a new job posting
     */
    public function create()
    {
        $divisions = Division::where('is_active', true)->get();
        $positions = Position::where('is_active', true)->get();
        $locations = Location::where('is_active', true)->get();

        return view('hr.job-postings.create', compact('divisions', 'positions', 'locations'));
    }

    /**
     * Store a newly created job posting
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'position_id' => 'required|exists:positions,id',
            'division_id' => 'required|exists:divisions,id',
            'location_id' => 'required|exists:locations,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'benefits' => 'nullable|string',
            'vacancies' => 'required|integer|min:1',
            'employment_type' => 'required|in:full_time,part_time,contract,internship',
            'level' => 'required|in:entry,junior,mid,senior,lead,manager',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'application_deadline' => 'required|date',
            'expected_start_date' => 'nullable|date',
        ]);

        // Generate unique code
        $validated['code'] = $this->generateJobCode($request->position_id);
        $validated['created_by'] = auth()->id();
        
        // Handle action button (draft or publish)
        if ($request->action === 'publish') {
            $validated['status'] = 'active';
            $validated['published_at'] = now();
        } else {
            $validated['status'] = 'draft';
        }

        $jobPosting = JobPosting::create($validated);

        AuditLog::log('create', $jobPosting, [], $validated);

        return redirect()->route('hr.job-postings.index')
            ->with('success', 'Lowongan berhasil dibuat dengan kode: ' . $jobPosting->code);
    }

    /**
     * Display the specified job posting
     */
    public function show(JobPosting $jobPosting)
    {
        $jobPosting->load(['position', 'division', 'location', 'creator', 'applications.candidate']);
        
        return view('hr.job-postings.show', compact('jobPosting'));
    }

    /**
     * Show the form for editing the job posting
     */
    public function edit(JobPosting $jobPosting)
    {
        $divisions = Division::where('is_active', true)->get();
        $positions = Position::where('is_active', true)->get();
        $locations = Location::where('is_active', true)->get();

        // Alias for view compatibility
        $job = $jobPosting;

        return view('hr.job-postings.edit', compact('jobPosting', 'job', 'divisions', 'positions', 'locations'));
    }

    /**
     * Update the job posting
     */
    public function update(Request $request, JobPosting $jobPosting)
    {
        $validated = $request->validate([
            'position_id' => 'required|exists:positions,id',
            'division_id' => 'required|exists:divisions,id',
            'location_id' => 'required|exists:locations,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'responsibilities' => 'nullable|string',
            'benefits' => 'nullable|string',
            'quota' => 'required|integer|min:1',
            'employment_type' => 'required|in:full_time,part_time,contract,internship',
            'experience_level' => 'required|in:entry,junior,mid,senior,lead,manager',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'closed_at' => 'required|date',
            'status' => 'nullable|in:draft,active,closed,archived',
        ]);

        $oldData = $jobPosting->toArray();
        
        // Handle status and published_at
        if (!isset($validated['status'])) {
            $validated['status'] = 'draft';
        }
        
        if ($validated['status'] === 'active' && !$jobPosting->published_at) {
            $validated['published_at'] = now();
        }

        $jobPosting->update($validated);

        AuditLog::log('update', $jobPosting, $oldData, $validated);

        return redirect()->route('hr.job-postings.index')
            ->with('success', 'Lowongan berhasil diperbarui.');
    }

    /**
     * Remove the job posting
     */
    public function destroy(JobPosting $jobPosting)
    {
        // Only allow deletion if no applications
        if ($jobPosting->applications()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus lowongan yang sudah memiliki pelamar.');
        }

        $oldData = $jobPosting->toArray();
        $jobPosting->delete();

        AuditLog::log('delete', $jobPosting, $oldData, []);

        return redirect()->route('hr.job-postings.index')
            ->with('success', 'Lowongan berhasil dihapus.');
    }

    /**
     * Generate unique job code
     * 
     * IMPORTANT: Uses withTrashed() to include soft-deleted records
     * This prevents generating duplicate codes when old job postings are soft-deleted
     */
    protected function generateJobCode($positionId)
    {
        $position = Position::find($positionId);
        
        // Use full position code as prefix (already unique per position)
        $prefix = strtoupper($position->code);
        
        // Get all job codes with this prefix and extract numbers
        // CRITICAL: Include soft-deleted records to prevent duplicate codes
        $existingCodes = JobPosting::withTrashed()
            ->where('code', 'like', $prefix . '-%')
            ->pluck('code')
            ->toArray();
        
        // Extract all numbers from existing codes
        $existingNumbers = [];
        foreach ($existingCodes as $code) {
            $parts = explode('-', $code);
            if (count($parts) >= 2) {
                $number = (int) end($parts);
                $existingNumbers[] = $number;
            }
        }
        
        // Find the next available number
        if (empty($existingNumbers)) {
            $newNumber = 1;
        } else {
            $maxNumber = max($existingNumbers);
            $newNumber = $maxNumber + 1;
        }
        
        $newCode = $prefix . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        
        // Extra safety: ensure uniqueness with retry logic
        // CRITICAL: Also check soft-deleted records here
        $attempts = 0;
        while (JobPosting::withTrashed()->where('code', $newCode)->exists() && $attempts < 100) {
            $newNumber++;
            $newCode = $prefix . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
            $attempts++;
        }
        
        return $newCode;
    }
}
