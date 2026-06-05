<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\AuditLog;
use App\Models\Offer;
use App\Models\OfferNegotiation;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    


    public function index(Request $request)
    {
        $query = $this->filteredOfferQuery($request);

        $offers = $query->latest()->paginate(15);

        return view('hr.offers.index', compact('offers'));
    }

    


    public function export(Request $request)
    {
        $offers = $this->filteredOfferQuery($request)
            ->latest()
            ->get();

        $filename = 'laporan-penawaran-kerja-'.now()->format('Ymd-His').'.xls';

        return response()->streamDownload(function () use ($offers) {
            echo "\xEF\xBB\xBF";
            echo '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body>';
            echo '<table border="1">';
            echo '<thead><tr>';

            foreach ($this->offerExportHeadings() as $heading) {
                echo '<th style="background:#dbeafe;font-weight:bold;">'.$this->excelCell($heading).'</th>';
            }

            echo '</tr></thead><tbody>';

            foreach ($offers as $offer) {
                echo '<tr>';

                foreach ($this->offerExportRow($offer) as $value) {
                    echo '<td>'.$this->excelCell($value).'</td>';
                }

                echo '</tr>';
            }

            echo '</tbody></table></body></html>';
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }

    


    private function filteredOfferQuery(Request $request)
    {
        $query = Offer::with(['application.candidate', 'application.jobPosting', 'offeredBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->whereHas('application.candidate', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        return $query;
    }

    private function offerExportHeadings(): array
    {
        return [
            'Kode Lamaran',
            'Nama Kandidat',
            'Email Kandidat',
            'Posisi Dilamar',
            'Posisi Penawaran',
            'Gaji',
            'Mata Uang',
            'Periode Gaji',
            'Tipe Kontrak',
            'Tanggal Mulai',
            'Berlaku Hingga',
            'Status',
            'Ditawarkan Oleh',
            'Tanggal Dibuat',
            'Tanggal Direspons',
            'Alasan Ditolak',
            'Benefit',
            'Catatan Internal',
        ];
    }

    private function offerExportRow(Offer $offer): array
    {
        return [
            $offer->application?->application_code ?? $offer->application?->code,
            $offer->application?->candidate?->name,
            $offer->application?->candidate?->email,
            $offer->application?->jobPosting?->title,
            $offer->position_title,
            number_format((float) $offer->salary, 0, ',', '.'),
            $offer->salary_currency,
            $this->salaryPeriodLabel($offer->salary_period),
            $this->contractTypeLabel($offer->contract_type),
            $offer->start_date?->format('Y-m-d'),
            $offer->valid_until?->format('Y-m-d'),
            $this->offerStatusLabel($offer->status),
            $offer->offeredBy?->name,
            $offer->created_at?->format('Y-m-d H:i:s'),
            $offer->responded_at?->format('Y-m-d H:i:s'),
            $offer->rejection_reason,
            $this->formatBenefits($offer->benefits),
            $offer->internal_notes,
        ];
    }

    private function offerStatusLabel(?string $status): string
    {
        return [
            'pending' => 'Menunggu',
            'accepted' => 'Diterima',
            'rejected' => 'Ditolak',
            'expired' => 'Kadaluarsa',
        ][$status] ?? (string) $status;
    }

    private function contractTypeLabel(?string $contractType): string
    {
        return [
            'full_time' => 'Full Time',
            'part_time' => 'Part Time',
            'contract' => 'Kontrak',
            'internship' => 'Magang',
            'Permanent' => 'Permanent',
        ][$contractType] ?? (string) $contractType;
    }

    private function salaryPeriodLabel(?string $period): string
    {
        return [
            'monthly' => 'Bulanan',
            'yearly' => 'Tahunan',
            'weekly' => 'Mingguan',
            'daily' => 'Harian',
        ][$period] ?? (string) $period;
    }

    private function formatBenefits($benefits): string
    {
        if (empty($benefits)) {
            return '';
        }

        if (is_array($benefits)) {
            return implode('; ', array_map('strval', $benefits));
        }

        $decoded = json_decode((string) $benefits, true);

        if (is_array($decoded)) {
            return implode('; ', array_map('strval', $decoded));
        }

        return (string) $benefits;
    }

    private function excelCell($value): string
    {
        $value = trim((string) ($value ?? ''));
        $value = preg_replace('/\s+/', ' ', $value);

        if (preg_match('/^[=+\-@]/', $value)) {
            $value = "'".$value;
        }

        return e($value);
    }

    


    public function store(Request $request)
    {
        $validated = $request->validate([
            'application_id' => 'required|exists:applications,id',
            'position_title' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0|max:'.Offer::MAX_SALARY,
            'start_date' => 'required|date|after:today',
            'contract_type' => 'required|in:full_time,part_time,contract,internship',
            'benefits' => 'nullable|string',
            'internal_notes' => 'nullable|string',
            'valid_until' => 'required|date|after_or_equal:today',
        ], $this->salaryValidationMessages());

        if (Offer::where('application_id', $validated['application_id'])->exists()) {
            return redirect()->back()->with('error', 'Penawaran kerja untuk lamaran ini sudah dibuat.');
        }

        $validated['status'] = 'pending';
        $validated['offered_by'] = auth()->id();

        $offer = Offer::create($validated);

         
        $application = Application::find($request->application_id);
        $application->update([
            'status' => 'offered',
            'offered_at' => now(),
            'hired_at' => null,
        ]);

        AuditLog::log('create', $offer, [], $validated);

         
        $application->load(['candidate', 'jobPosting']);
        $candidate = $application->candidate;
        if ($candidate && $candidate->phone) {
            app(NotificationService::class)->sendWhatsApp(
                'offer_sent',
                $candidate->phone,
                $this->whatsAppOfferPayload($offer, [
                    'gaji' => number_format((float) $request->salary, 0, ',', '.'),
                    'salary_range' => $this->formatRupiah($request->salary),
                    'start_date' => $request->start_date,
                ])
            );
        }

        return redirect()->back()->with('success', 'Penawaran kerja berhasil dibuat.');
    }

    


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

    


    public function edit(Offer $offer)
    {
         
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

    


    public function update(Request $request, Offer $offer)
    {
         
        if ($offer->status !== 'pending') {
            return redirect()->route('hr.offers.show', $offer)
                ->with('error', 'Hanya penawaran dengan status "Menunggu" yang bisa diedit.');
        }

        $validated = $request->validate([
            'position_title' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0|max:'.Offer::MAX_SALARY,
            'start_date' => 'required|date',
            'contract_type' => 'required|in:full_time,part_time,contract,internship',
            'benefits' => 'nullable|string',
            'internal_notes' => 'nullable|string',
            'valid_until' => 'required|date|after_or_equal:today',
        ], $this->salaryValidationMessages());

        $oldData = $offer->toArray();
        $offer->update($validated);

        AuditLog::log('update', $offer, $oldData, $validated);

        return redirect()->route('hr.offers.show', $offer)
            ->with('success', 'Penawaran kerja berhasil diperbarui.');
    }

    


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

        if ((float) $negotiation->proposed_salary > Offer::MAX_SALARY) {
            return redirect()->back()->with('error', $this->maxSalaryMessage('Nominal negosiasi'));
        }

          
        $negotiation->update([
            'status' => 'approved',
            'hr_notes' => $validated['hr_notes'] ?? null,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

         
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

        $negotiation->load(['offer.application.candidate', 'offer.application.jobPosting']);
        $candidate = $negotiation->offer->application->candidate;
        if ($candidate && $candidate->phone) {
            app(NotificationService::class)->sendWhatsApp(
                'offer_negotiation_approved',
                $candidate->phone,
                $this->whatsAppOfferPayload($negotiation->offer, [
                    'gaji_negosiasi' => $this->formatRupiah($negotiation->proposed_salary),
                    'gaji_baru' => $this->formatRupiah($negotiation->proposed_salary),
                ])
            );
        }

        return redirect()->back()->with('success', 'Negosiasi disetujui. Gaji penawaran telah diperbarui.');
    }

    


    public function rejectNegotiation(Request $request, OfferNegotiation $negotiation)
    {
        if ($negotiation->status !== 'pending') {
            return redirect()->back()->with('error', 'Negosiasi ini sudah diproses.');
        }

        $validated = $request->validate([
            'hr_notes' => 'nullable|string|max:1000',
            'counter_offer_salary' => 'required|numeric|min:0|max:'.Offer::MAX_SALARY,
        ], [
            'counter_offer_salary.required' => 'Nominal counter offer wajib diisi.',
            'counter_offer_salary.max' => $this->maxSalaryMessage('Nominal counter offer'),
        ]);

        $oldData = $negotiation->toArray();
        $oldOffer = $negotiation->offer->toArray();
        $counterOfferSalary = $validated['counter_offer_salary'];

          
        $negotiation->update([
            'status' => 'rejected',
            'hr_notes' => $validated['hr_notes'] ?? null,
            'counter_offer_salary' => $counterOfferSalary,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        $negotiation->offer->update([
            'salary' => $counterOfferSalary,
        ]);

        AuditLog::log('update', $negotiation, $oldData, [
            'status' => 'rejected',
            'counter_offer_salary' => $counterOfferSalary,
            'action' => 'HR menolak negosiasi gaji dan mengirim counter offer'
        ]);

        AuditLog::log('update', $negotiation->offer, $oldOffer, [
            'salary' => $counterOfferSalary,
            'action' => 'Update gaji berdasarkan counter offer HR'
        ]);

        $negotiation->load(['offer.application.candidate', 'offer.application.jobPosting']);
        $candidate = $negotiation->offer->application->candidate;
        if ($candidate && $candidate->phone) {
            app(NotificationService::class)->sendWhatsApp(
                'offer_negotiation_countered',
                $candidate->phone,
                $this->whatsAppOfferPayload($negotiation->offer, [
                    'gaji_negosiasi' => $this->formatRupiah($negotiation->proposed_salary),
                    'gaji_counter' => $this->formatRupiah($counterOfferSalary),
                    'salary_range' => $this->formatRupiah($counterOfferSalary),
                ])
            );
        }

        return redirect()->back()->with('success', 'Negosiasi ditolak. Counter offer telah dikirim ke kandidat.');
    }

    private function salaryValidationMessages(): array
    {
        return [
            'salary.max' => $this->maxSalaryMessage('Gaji yang ditawarkan'),
        ];
    }

    private function maxSalaryMessage(string $field): string
    {
        return $field.' maksimal Rp '.number_format(Offer::MAX_SALARY, 0, ',', '.').'.';
    }

    private function whatsAppOfferPayload(Offer $offer, array $extra = []): array
    {
        $offer->loadMissing(['application.candidate', 'application.jobPosting']);
        $application = $offer->application;
        $candidate = $application->candidate;

        return array_merge([
            'nama'               => $candidate->full_name ?? $candidate->name,
            'candidate_name'     => $candidate->full_name ?? $candidate->name,
            'kode_lamaran'       => $application->application_code ?? $application->code,
            'application_number' => $application->application_code ?? $application->code,
            'posisi'             => $application->jobPosting->title ?? '',
            'job_title'          => $application->jobPosting->title ?? '',
            'gaji'               => number_format((float) $offer->salary, 0, ',', '.'),
            'gaji_awal'          => $this->formatRupiah($offer->salary),
            'salary_range'       => $this->formatRupiah($offer->salary),
            'start_date'         => $offer->start_date?->format('d/m/Y') ?? '',
            'company_name'       => config('app.name', 'RekrutPro'),
        ], $extra);
    }

    private function formatRupiah($amount): string
    {
        return 'Rp '.number_format((float) $amount, 0, ',', '.');
    }
}
