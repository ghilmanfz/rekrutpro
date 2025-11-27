<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisions = [
            [
                'name' => 'Teknologi Informasi',
                'code' => 'IT',
                'description' => 'Divisi pengembangan dan infrastruktur IT',
                'is_active' => true,
            ],
            [
                'name' => 'Sumber Daya Manusia',
                'code' => 'HR',
                'description' => 'Divisi pengelolaan SDM dan rekrutmen',
                'is_active' => true,
            ],
            [
                'name' => 'Pemasaran',
                'code' => 'MKT',
                'description' => 'Divisi pemasaran dan promosi',
                'is_active' => true,
            ],
            [
                'name' => 'Keuangan',
                'code' => 'FIN',
                'description' => 'Divisi keuangan dan akuntansi',
                'is_active' => true,
            ],
            [
                'name' => 'Operasional',
                'code' => 'OPS',
                'description' => 'Divisi operasional dan produksi',
                'is_active' => true,
            ],
            [
                'name' => 'Penjualan',
                'code' => 'SALES',
                'description' => 'Divisi penjualan dan distribusi',
                'is_active' => true,
            ],
        ];

        foreach ($divisions as $division) {
            Division::updateOrCreate(
                ['code' => $division['code']],
                $division
            );
        }
    }
}
