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
    


    public function index(Request $request)
    {
        $query = JobPosting::with(['position', 'division', 'location', 'creator'])
            ->withCount('applications');

         
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

         
        if ($request->filled('division_id')) {
            $query->where('division_id', $request->division_id);
        }

         
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

    


    public function create()
    {
        $divisions = Division::where('is_active', true)->get();
        $positions = Position::where('is_active', true)->get();
        $locations = Location::where('is_active', true)->get();

        return view('hr.job-postings.create', compact('divisions', 'positions', 'locations'));
    }

    


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

        $validated['quota'] = $validated['vacancies'];
        $validated['experience_level'] = $validated['level'];
        $validated['closed_at'] = $validated['application_deadline'];

        unset(
            $validated['vacancies'],
            $validated['level'],
            $validated['application_deadline'],
            $validated['expected_start_date'],
        );

         
        $validated['code'] = $this->generateJobCode($request->position_id);
        $validated['created_by'] = auth()->id();
        
         
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

    


    public function show(JobPosting $jobPosting)
    {
        $jobPosting->load(['position', 'division', 'location', 'creator', 'applications.candidate']);
        
        return view('hr.job-postings.show', compact('jobPosting'));
    }

    


    public function edit(JobPosting $jobPosting)
    {
        $divisions = Division::where('is_active', true)->get();
        $positions = Position::where('is_active', true)->get();
        $locations = Location::where('is_active', true)->get();

         
        $job = $jobPosting;

        return view('hr.job-postings.edit', compact('jobPosting', 'job', 'divisions', 'positions', 'locations'));
    }

    


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

    


    public function destroy(JobPosting $jobPosting)
    {
         
        if ($jobPosting->applications()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus lowongan yang sudah memiliki pelamar.');
        }

        $oldData = $jobPosting->toArray();
        $jobPosting->delete();

        AuditLog::log('delete', $jobPosting, $oldData, []);

        return redirect()->route('hr.job-postings.index')
            ->with('success', 'Lowongan berhasil dihapus.');
    }

    





    protected function generateJobCode($positionId)
    {
        $position = Position::find($positionId);
        
         
        $prefix = strtoupper($position->code);
        
         
         
        $existingCodes = JobPosting::withTrashed()
            ->where('code', 'like', $prefix . '-%')
            ->pluck('code')
            ->toArray();
        
         
        $existingNumbers = [];
        foreach ($existingCodes as $code) {
            $parts = explode('-', $code);
            if (count($parts) >= 2) {
                $number = (int) end($parts);
                $existingNumbers[] = $number;
            }
        }
        
         
        if (empty($existingNumbers)) {
            $newNumber = 1;
        } else {
            $maxNumber = max($existingNumbers);
            $newNumber = $maxNumber + 1;
        }
        
        $newCode = $prefix . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        
         
         
        $attempts = 0;
        while (JobPosting::withTrashed()->where('code', $newCode)->exists() && $attempts < 100) {
            $newNumber++;
            $newCode = $prefix . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
            $attempts++;
        }
        
        return $newCode;
    }
}
