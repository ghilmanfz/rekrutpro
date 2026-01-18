<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Urutan seeding penting karena ada foreign key dependencies
        $this->call([
            // Master Data
            RoleSeeder::class,
            DivisionSeeder::class,
            PositionSeeder::class,
            LocationSeeder::class,
            NotificationTemplateSeeder::class,
            SystemConfigSeeder::class,
            
            // Users
            UserSeeder::class,
            
            // Recruitment Process Data
            JobPostingSeeder::class,
            ApplicationSeeder::class,
            InterviewSeeder::class,
            AssessmentSeeder::class,
            OfferSeeder::class,
            OfferNegotiationSeeder::class,
            
            // Audit Logs
            AuditLogSeeder::class,
        ]);

        $this->command->info('🎉 Database seeded successfully!');
        $this->command->info('');
        $this->command->info('📧 Default Login Credentials:');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('Super Admin: admin@rekrutpro.com / password');
        $this->command->info('HR:          hr@rekrutpro.com / password');
        $this->command->info('Interviewer: interviewer@rekrutpro.com / password');
        $this->command->info('Candidate 1: candidate1@example.com / password');
        $this->command->info('Candidate 2: candidate2@example.com / password');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    }
}
