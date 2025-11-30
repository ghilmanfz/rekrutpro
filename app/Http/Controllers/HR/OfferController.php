<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\AuditLog;
use App\Models\Offer;
use App\Models\OfferNegotiation;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * Display a listing of offers
     */
    public function index(Request $request)
    {
        $query = Offer::with(['application.candidate', 'application.jobPosting', 'offeredBy']);

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
            'salary' => 'required|numeric|min:0',
            'start_date' => 'required|date|after:today',
            'benefits' => 'nullable|string',
            'internal_notes' => 'nullable|string',
            'valid_until' => 'required|date|after:start_date',
        ]);

        $validated['status'] = 'pending';
        $validated['offered_by'] = auth()->id();

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
            'offeredBy',
            'negotiations.candidate',
            'negotiations.reviewer'
        ]);

        return view('hr.offers.show', compact('offer'));
    }

    /**
     * Show the form for editing the specified offer
     */
    public function edit(Offer $offer)
    {
        // Only allow editing pending offers
        if ($offer->status !== 'pending') {
            return redirect()->route('hr.offers.show', $offer)
                ->with('error', 'Hanya penawaran dengan status "Menunggu" yang bisa diedit.');
        }

        $offer->load([
            'application.candidate',
            'application.jobPosting'
        ]);

        return view('hr.offers.edit', compact('offer'));
    }

    /**
     * Update the specified offer
     */
    public function update(Request $request, Offer $offer)
    {
        // Only allow updating pending offers
        if ($offer->status !== 'pending') {
            return redirect()->route('hr.offers.show', $offer)
                ->with('error', 'Hanya penawaran dengan status "Menunggu" yang bisa diedit.');
        }

        $validated = $request->validate([
            'position_title' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'contract_type' => 'required|in:full_time,part_time,contract,internship',
            'benefits' => 'nullable|string',
            'internal_notes' => 'nullable|string',
            'valid_until' => 'required|date|after:start_date',
        ]);

        $oldData = $offer->toArray();
        $offer->update($validated);

        AuditLog::log('update', $offer, $oldData, $validated);

        return redirect()->route('hr.offers.show', $offer)
            ->with('success', 'Penawaran kerja berhasil diperbarui.');
    }

    /**
     * Approve a negotiation
     */
    public function approveNegotiation(Request $request, OfferNegotiation $negotiation)
    {
        if ($negotiation->status !== 'pending') {
            return redirect()->back()->with('error', 'Negosiasi ini sudah diproses.');
        }

        $validated = $request->validate([
            'hr_notes' => 'nullable|string|max:1000',
        ]);

        $oldNegotiation = $negotiation->toArray();
        $oldOffer = $negotiation->offer->toArray();

        // Update negotiation status
        $negotiation->update([
            'status' => 'approved',
            'hr_notes' => $validated['hr_notes'] ?? null,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        // Update offer with new salary
        $negotiation->offer->update([
            'salary' => $negotiation->proposed_salary,
        ]);

        AuditLog::log('update', $negotiation, $oldNegotiation, [
            'status' => 'approved',
            'action' => 'HR menyetujui negosiasi gaji'
        ]);

        AuditLog::log('update', $negotiation->offer, $oldOffer, [
            'salary' => $negotiation->proposed_salary,
            'action' => 'Update gaji berdasarkan negosiasi yang disetujui'
        ]);

        return redirect()->back()->with('success', 'Negosiasi disetujui. Gaji penawaran telah diperbarui.');
    }

    /**
     * Reject a negotiation
     */
    public function rejectNegotiation(Request $request, OfferNegotiation $negotiation)
    {
        if ($negotiation->status !== 'pending') {
            return redirect()->back()->with('error', 'Negosiasi ini sudah diproses.');
        }

        $validated = $request->validate([
            'hr_notes' => 'nullable|string|max:1000',
        ]);

        $oldData = $negotiation->toArray();

        // Update negotiation status
        $negotiation->update([
            'status' => 'rejected',
            'hr_notes' => $validated['hr_notes'] ?? null,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        AuditLog::log('update', $negotiation, $oldData, [
            'status' => 'rejected',
            'action' => 'HR menolak negosiasi gaji'
        ]);

        return redirect()->back()->with('success', 'Negosiasi ditolak.');
    }
}
