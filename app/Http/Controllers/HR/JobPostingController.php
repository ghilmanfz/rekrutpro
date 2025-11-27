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

        return view('hr.job-postings.edit', compact('jobPosting', 'divisions', 'positions', 'locations'));
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
            'benefits' => 'nullable|string',
            'vacancies' => 'required|integer|min:1',
            'employment_type' => 'required|in:full_time,part_time,contract,internship',
            'level' => 'required|in:entry,junior,mid,senior,lead,manager',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'application_deadline' => 'required|date',
            'expected_start_date' => 'nullable|date',
        ]);

        $oldData = $jobPosting->toArray();
        
        // Handle action button (draft or publish)
        if ($request->action === 'publish') {
            $validated['status'] = 'active';
            if (!$jobPosting->published_at) {
                $validated['published_at'] = now();
            }
        } else {
            $validated['status'] = 'draft';
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
     */
    protected function generateJobCode($positionId)
    {
        $position = Position::find($positionId);
        $prefix = strtoupper(substr($position->code, 0, 3));
        
        // Get last job code with same prefix
        $lastJob = JobPosting::where('code', 'like', $prefix . '%')
            ->orderBy('code', 'desc')
            ->first();

        if ($lastJob) {
            $lastNumber = (int) substr($lastJob->code, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return $prefix . '-' . $newNumber;
    }
}
