<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            [
                'name' => 'Jakarta Pusat',
                'code' => 'JKP',
                'address' => 'Jl. Sudirman No. 123, Jakarta Pusat',
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
            ],
            [
                'name' => 'Jakarta Selatan',
                'code' => 'JKS',
                'address' => 'Jl. TB Simatupang Kav. 88, Jakarta Selatan',
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
            ],
            [
                'name' => 'Bandung',
                'code' => 'BDG',
                'address' => 'Jl. Soekarno Hatta No. 456, Bandung',
                'city' => 'Bandung',
                'province' => 'Jawa Barat',
            ],
            [
                'name' => 'Surabaya',
                'code' => 'SBY',
                'address' => 'Jl. HR Muhammad No. 789, Surabaya',
                'city' => 'Surabaya',
                'province' => 'Jawa Timur',
            ],
            [
                'name' => 'Yogyakarta',
                'code' => 'YGY',
                'address' => 'Jl. Gejayan No. 321, Yogyakarta',
                'city' => 'Yogyakarta',
                'province' => 'DI Yogyakarta',
            ],
            [
                'name' => 'Semarang',
                'code' => 'SMG',
                'address' => 'Jl. Pemuda No. 654, Semarang',
                'city' => 'Semarang',
                'province' => 'Jawa Tengah',
            ],
            [
                'name' => 'Remote',
                'code' => 'RMT',
                'address' => 'Work from Anywhere',
                'city' => 'Remote',
                'province' => 'Indonesia',
            ],
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }

        $this->command->info('Locations seeded successfully!');
    }
}
