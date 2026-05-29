<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    


    public function run(): void
    {
         
        $this->call([
             
            RoleSeeder::class,
            DivisionSeeder::class,
            PositionSeeder::class,
            LocationSeeder::class,
            NotificationTemplateSeeder::class,
            SystemConfigSeeder::class,
            
             
            UserSeeder::class,
            
             
            JobPostingSeeder::class,
            ApplicationSeeder::class,
            OfferSeeder::class,
            OfferNegotiationSeeder::class,
            
             
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
