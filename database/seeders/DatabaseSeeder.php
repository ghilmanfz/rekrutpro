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
            RoleSeeder::class,
            DivisionSeeder::class,
            PositionSeeder::class,
            LocationSeeder::class,
            UserSeeder::class,
            NotificationTemplateSeeder::class,
        ]);

        $this->command->info('Database seeded successfully!');
    }
}
