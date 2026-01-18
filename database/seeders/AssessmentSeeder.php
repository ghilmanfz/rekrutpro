<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\Interview;
use Illuminate\Database\Seeder;

class AssessmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get completed interviews that DON'T have assessments yet, ordered by scheduled_at (oldest first)
        $completedInterviews = Interview::where('status', 'completed')
            ->whereDoesntHave('assessment')
            ->orderBy('scheduled_at', 'asc')
            ->with(['application.candidate', 'application.jobPosting'])
            ->get();

        if ($completedInterviews->isEmpty()) {
            $this->command->warn('No completed interviews found.');
            return;
        }

        // Leave the last 2 completed interviews without assessment (pending)
        $total = $completedInterviews->count();
        $toCreate = max(0, $total - 2);

        $created = 0;
        foreach ($completedInterviews->slice(0, $toCreate) as $interview) {
            $title = strtolower($interview->application->jobPosting->title ?? '');

            // deterministic scores based on role keywords
            if (str_contains($title, 'engineer') || str_contains($title, 'developer')) {
                $technical = 85;
                $problem = 80;
                $overall = 82.00;
                $recommendation = 'direkomendasikan';
                $techNotes = 'Candidate shows strong technical fundamentals and good problem solving.';
            } elseif (str_contains($title, 'designer') || str_contains($title, 'ui') || str_contains($title, 'ux')) {
                $technical = 92;
                $problem = 88;
                $overall = 93.00;
                $recommendation = 'sangat_direkomendasikan';
                $techNotes = 'Excellent portfolio and UX thinking.';
            } else {
                $technical = 78;
                $problem = 76;
                $overall = 77.00;
                $recommendation = 'direkomendasikan';
                $techNotes = 'Good fit for the role with room to grow.';
            }

            Assessment::create([
                'interview_id' => $interview->id,
                'interviewer_id' => $interview->interviewer_id,
                'technical_score' => $technical,
                'technical_notes' => $techNotes,
                'communication_skill' => 'baik',
                'problem_solving_score' => $problem,
                'problem_solving_notes' => 'Problem solving is solid and well explained.',
                'teamwork_potential' => 'tinggi',
                'overall_score' => $overall,
                'strengths' => 'Relevant experience and good communication.',
                'weaknesses' => 'Needs minor improvements.',
                'additional_notes' => 'Auto-seeded assessment for demo.',
                'recommendation' => $recommendation,
                'created_at' => $interview->updated_at,
                'updated_at' => $interview->updated_at,
            ]);

            $created++;
        }

        $this->command->info("Successfully created {$created} assessments!");
        $this->command->info("Remaining interviews without assessment: " . ($total - $created));
    }
}
