<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\OfferNegotiation;
use App\Models\AuditLog;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    


    public function accept(Offer $offer)
    {
         
        if ($offer->application->candidate_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

         
        if ($offer->status !== 'pending') {
            return redirect()->back()->with('error', 'Penawaran ini tidak dapat diterima.');
        }

        $oldData = $offer->toArray();

         
        $offer->update([
            'status' => 'accepted',
            'responded_at' => now(),
        ]);

         
        $offer->application->update([
            'status' => 'hired',
            'hired_at' => now(),
        ]);

        AuditLog::log('update', $offer, $oldData, [
            'status' => 'accepted',
            'action' => 'Kandidat menerima penawaran'
        ]);

         
        $offer->load(['application.candidate', 'application.jobPosting']);
        $candidate = $offer->application->candidate;
        if ($candidate && $candidate->phone) {
            app(NotificationService::class)->sendWhatsApp(
                'offer_accepted',
                $candidate->phone,
                [
                    'nama'               => $candidate->full_name ?? $candidate->name,
                    'candidate_name'     => $candidate->full_name ?? $candidate->name,
                    'posisi'             => $offer->application->jobPosting->title ?? '',
                    'job_title'          => $offer->application->jobPosting->title ?? '',
                    'start_date'         => $offer->start_date ?? '',
                    'company_name'       => config('app.name', 'RekrutPro'),
                ]
            );
        }

        return redirect()->route('candidate.applications.show', $offer->application_id)
            ->with('success', 'Selamat! Anda telah menerima penawaran kerja.');
    }

    


    public function reject(Request $request, Offer $offer)
    {
         
        if ($offer->application->candidate_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

         
        if ($offer->status !== 'pending') {
            return redirect()->back()->with('error', 'Penawaran ini tidak dapat ditolak.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'nullable|string|max:1000',
        ]);

        $oldData = $offer->toArray();

         
        $offer->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'] ?? null,
            'responded_at' => now(),
        ]);

         
        $offer->application->update([
            'status' => 'rejected_offer',
        ]);

        AuditLog::log('update', $offer, $oldData, [
            'status' => 'rejected',
            'action' => 'Kandidat menolak penawaran'
        ]);

         
        $offer->load(['application.candidate', 'application.jobPosting']);
        $candidate = $offer->application->candidate;
        if ($candidate && $candidate->phone) {
            app(NotificationService::class)->sendWhatsApp(
                'offer_rejected',
                $candidate->phone,
                [
                    'nama'               => $candidate->full_name ?? $candidate->name,
                    'candidate_name'     => $candidate->full_name ?? $candidate->name,
                    'posisi'             => $offer->application->jobPosting->title ?? '',
                    'job_title'          => $offer->application->jobPosting->title ?? '',
                    'company_name'       => config('app.name', 'RekrutPro'),
                ]
            );
        }

        return redirect()->route('candidate.applications.show', $offer->application_id)
            ->with('success', 'Anda telah menolak penawaran kerja.');
    }

    


    public function negotiate(Request $request, Offer $offer)
    {
         
        if ($offer->application->candidate_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

         
        if ($offer->status !== 'pending') {
            return redirect()->back()->with('error', 'Penawaran ini tidak dapat dinegosiasikan.');
        }

         
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
