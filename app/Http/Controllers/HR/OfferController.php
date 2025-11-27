<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\AuditLog;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * Display a listing of offers
     */
    public function index(Request $request)
    {
        $query = Offer::with(['application.candidate', 'application.jobPosting', 'createdBy']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by candidate name
        if ($request->filled('search')) {
            $query->whereHas('application.candidate', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $offers = $query->latest()->paginate(15);

        return view('hr.offers.index', compact('offers'));
    }

    /**
     * Store a newly created offer
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'application_id' => 'required|exists:applications,id',
            'position_title' => 'required|string|max:255',
            'salary_offered' => 'required|numeric|min:0',
            'start_date' => 'required|date|after:today',
            'benefits' => 'nullable|string',
            'notes' => 'nullable|string',
            'valid_until' => 'required|date|after:start_date',
        ]);

        $validated['status'] = 'pending';
        $validated['created_by'] = auth()->id();
        $validated['offered_at'] = now();

        $offer = Offer::create($validated);

        // Update application status
        $application = Application::find($request->application_id);
        $application->update([
            'status' => 'offered',
            'offered_at' => now(),
        ]);

        AuditLog::log('create', $offer, [], $validated);

        // TODO: Send offer letter to candidate

        return redirect()->back()->with('success', 'Penawaran kerja berhasil dibuat.');
    }

    /**
     * Display the specified offer
     */
    public function show(Offer $offer)
    {
        $offer->load([
            'application.candidate',
            'application.jobPosting.position',
            'application.jobPosting.division',
            'createdBy'
        ]);

        return view('hr.offers.show', compact('offer'));
    }

    /**
     * Update the specified offer
     */
    public function update(Request $request, Offer $offer)
    {
        $validated = $request->validate([
            'position_title' => 'required|string|max:255',
            'salary_offered' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'benefits' => 'nullable|string',
            'notes' => 'nullable|string',
            'valid_until' => 'required|date|after:start_date',
            'status' => 'nullable|in:pending,accepted,rejected,expired',
        ]);

        $oldData = $offer->toArray();

        // Update status timestamps
        if (isset($validated['status'])) {
            if ($validated['status'] === 'accepted' && !$offer->accepted_at) {
                $validated['accepted_at'] = now();
                
                // Update application to hired
                $offer->application->update([
                    'status' => 'hired',
                    'hired_at' => now(),
                ]);
            } elseif ($validated['status'] === 'rejected' && !$offer->rejected_at) {
                $validated['rejected_at'] = now();
            }
        }

        $offer->update($validated);

        AuditLog::log('update', $offer, $oldData, $validated);

        return redirect()->back()->with('success', 'Penawaran kerja berhasil diperbarui.');
    }
}
