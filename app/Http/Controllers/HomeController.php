<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    


    public function index()
    {
         
        $featuredJobs = JobPosting::with(['position', 'division', 'location'])
            ->published()
            ->latest('published_at')
            ->take(6)
            ->get();

        $totalJobs = JobPosting::published()->count();

        return view('public.home', compact('featuredJobs', 'totalJobs'));
    }
}
