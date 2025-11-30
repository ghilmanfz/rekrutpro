<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\JobPosting;
use App\Models\Location;
use Illuminate\Http\Request;

class PublicJobController extends Controller
{
    /**
     * Display public job listings
     */
    public function index(Request $request)
    {
        $query = JobPosting::with(['position', 'division', 'location'])
            ->published()
            ->withCount('applications');

        // Filter by division
        if ($request->filled('division')) {
            $query->where('division_id', $request->division);
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->where('location_id', $request->location);
        }

        // Filter by employment type
        if ($request->filled('type')) {
            $query->where('employment_type', $request->type);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $jobs = $query->latest('published_at')->paginate(12);
        
        // Get filter options
        $divisions = Division::where('is_active', true)->get();
        $locations = Location::where('is_active', true)->get();

        return view('public.jobs.index', compact('jobs', 'divisions', 'locations'));
    }

    /**
     * Display single job detail
     */
    public function show($id)
    {
        $job = JobPosting::with(['position', 'division', 'location'])
            ->published()
            ->findOrFail($id);

        // Check if user already applied (if authenticated)
        $hasApplied = false;
        if (auth()->check() && auth()->user()->role->name === 'candidate') {
            $hasApplied = $job->applications()
                ->where('candidate_id', auth()->id())
                ->exists();
        }

        // Get related jobs (same division, limit 3)
        $relatedJobs = JobPosting::with(['position', 'division', 'location'])
            ->published()
            ->where('division_id', $job->division_id)
            ->where('id', '!=', $job->id)
            ->take(3)
            ->get();

        return view('public.jobs.show', compact('job', 'relatedJobs', 'hasApplied'));
    }
}
