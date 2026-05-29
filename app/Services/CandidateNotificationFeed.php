<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Interview;
use App\Models\User;
use Illuminate\Support\Collection;

class CandidateNotificationFeed
{
    public function forUser(User $user, ?int $limit = null): Collection
    {
        $items = collect()
            ->merge($this->applicationItems($user))
            ->merge($this->interviewItems($user))
            ->sortByDesc('sort_at')
            ->values()
            ->map(function (array $item, int $index) {
                $item['id'] = $index + 1;
                unset($item['sort_at']);

                return $item;
            });

        return $limit ? $items->take($limit)->values() : $items;
    }

    private function applicationItems(User $user): Collection
    {
        return Application::with('jobPosting')
            ->where('candidate_id', $user->id)
            ->latest('updated_at')
            ->get()
            ->map(fn (Application $application) => $this->applicationItem($application));
    }

    private function interviewItems(User $user): Collection
    {
        return Interview::with('application.jobPosting')
            ->whereHas('application', fn ($query) => $query->where('candidate_id', $user->id))
            ->latest('scheduled_at')
            ->get()
            ->map(fn (Interview $interview) => [
                'icon' => 'calendar',
                'color' => 'blue',
                'title' => 'Interview untuk posisi '.$this->jobTitle($interview->application).' telah dijadwalkan',
                'description' => 'Jadwal: '.$interview->scheduled_at->format('d M Y, H:i'),
                'time' => $interview->updated_at->diffForHumans(),
                'read' => true,
                'sort_at' => $interview->updated_at,
            ]);
    }

    private function applicationItem(Application $application): array
    {
        $title = $this->applicationTitle($application);

        return [
            'icon' => $this->statusIcon($application->status),
            'color' => $this->statusColor($application->status),
            'title' => $title,
            'description' => 'Kode lamaran: '.($application->application_code ?? $application->code),
            'time' => $application->updated_at->diffForHumans(),
            'read' => true,
            'sort_at' => $application->updated_at,
        ];
    }

    private function applicationTitle(Application $application): string
    {
        $jobTitle = $this->jobTitle($application);

        return match ($application->status) {
            Application::STATUS_SUBMITTED => "Lamaran Anda untuk posisi {$jobTitle} telah dikirim",
            Application::STATUS_SCREENING_PASSED => "Lamaran Anda untuk posisi {$jobTitle} lolos screening",
            Application::STATUS_INTERVIEW_SCHEDULED => "Interview untuk posisi {$jobTitle} telah dijadwalkan",
            Application::STATUS_INTERVIEW_PASSED => "Anda lolos interview untuk posisi {$jobTitle}",
            Application::STATUS_OFFERED => "Anda menerima penawaran untuk posisi {$jobTitle}",
            Application::STATUS_HIRED => "Anda diterima untuk posisi {$jobTitle}",
            Application::STATUS_REJECTED_ADMIN,
            Application::STATUS_REJECTED_INTERVIEW,
            Application::STATUS_REJECTED_OFFER => "Lamaran Anda untuk posisi {$jobTitle} belum dapat dilanjutkan",
            default => "Status lamaran Anda untuk posisi {$jobTitle} diperbarui",
        };
    }

    private function statusIcon(string $status): string
    {
        return match ($status) {
            Application::STATUS_SUBMITTED => 'paper-plane',
            Application::STATUS_INTERVIEW_SCHEDULED => 'calendar',
            Application::STATUS_OFFERED, Application::STATUS_HIRED => 'gift',
            Application::STATUS_REJECTED_ADMIN,
            Application::STATUS_REJECTED_INTERVIEW,
            Application::STATUS_REJECTED_OFFER => 'times-circle',
            default => 'info-circle',
        };
    }

    private function statusColor(string $status): string
    {
        return match ($status) {
            Application::STATUS_SUBMITTED => 'blue',
            Application::STATUS_SCREENING_PASSED,
            Application::STATUS_INTERVIEW_SCHEDULED,
            Application::STATUS_INTERVIEW_PASSED => 'green',
            Application::STATUS_OFFERED, Application::STATUS_HIRED => 'purple',
            Application::STATUS_REJECTED_ADMIN,
            Application::STATUS_REJECTED_INTERVIEW,
            Application::STATUS_REJECTED_OFFER => 'red',
            default => 'gray',
        };
    }

    private function jobTitle(?Application $application): string
    {
        return $application?->jobPosting?->title ?? 'lowongan';
    }
}
