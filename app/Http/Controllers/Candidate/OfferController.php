<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\OfferNegotiation;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * Accept an offer
     */
    public function accept(Offer $offer)
    {
        // Verify offer belongs to current candidate
        if ($offer->application->candidate_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Only pending offers can be accepted
        if ($offer->status !== 'pending') {
            return redirect()->back()->with('error', 'Penawaran ini tidak dapat diterima.');
        }

        $oldData = $offer->toArray();

        // Update offer status
        $offer->update([
            'status' => 'accepted',
            'responded_at' => now(),
        ]);

        // Update application status to hired
        $offer->application->update([
            'status' => 'hired',
            'hired_at' => now(),
        ]);

        AuditLog::log('update', $offer, $oldData, [
            'status' => 'accepted',
            'action' => 'Kandidat menerima penawaran'
        ]);

        return redirect()->route('candidate.applications.show', $offer->application_id)
            ->with('success', 'Selamat! Anda telah menerima penawaran kerja.');
    }

    /**
     * Reject an offer
     */
    public function reject(Request $request, Offer $offer)
    {
        // Verify offer belongs to current candidate
        if ($offer->application->candidate_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Only pending offers can be rejected
        if ($offer->status !== 'pending') {
            return redirect()->back()->with('error', 'Penawaran ini tidak dapat ditolak.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'nullable|string|max:1000',
        ]);

        $oldData = $offer->toArray();

        // Update offer status
        $offer->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'] ?? null,
            'responded_at' => now(),
        ]);

        // Update application status
        $offer->application->update([
            'status' => 'rejected_offer',
        ]);

        AuditLog::log('update', $offer, $oldData, [
            'status' => 'rejected',
            'action' => 'Kandidat menolak penawaran'
        ]);

        return redirect()->route('candidate.applications.show', $offer->application_id)
            ->with('success', 'Anda telah menolak penawaran kerja.');
    }

    /**
     * Submit a negotiation request
     */
    public function negotiate(Request $request, Offer $offer)
    {
        // Verify offer belongs to current candidate
        if ($offer->application->candidate_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Only pending offers can be negotiated
        if ($offer->status !== 'pending') {
            return redirect()->back()->with('error', 'Penawaran ini tidak dapat dinegosiasikan.');
        }

        // Check if there's already a pending negotiation
        $hasPendingNegotiation = $offer->negotiations()
            ->where('status', 'pending')
            ->exists();

        if ($hasPendingNegotiation) {
            return redirect()->back()->with('error', 'Anda sudah memiliki negosiasi yang sedang diproses.');
        }

        $validated = $request->validate([
            'proposed_salary' => 'required|numeric|min:0',
            'candidate_notes' => 'required|string|max:1000',
        ]);

        // Create negotiation record
        $negotiation = OfferNegotiation::create([
            'offer_id' => $offer->id,
            'candidate_id' => auth()->id(),
            'proposed_salary' => $validated['proposed_salary'],
            'candidate_notes' => $validated['candidate_notes'],
            'status' => 'pending',
        ]);

        AuditLog::log('create', $negotiation, [], $validated);

        return redirect()->route('candidate.applications.show', $offer->application_id)
            ->with('success', 'Negosiasi gaji Anda telah diajukan. HR akan meninjau permintaan Anda.');
    }
}
