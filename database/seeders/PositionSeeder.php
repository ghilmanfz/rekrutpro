<?php

namespace Database\Seeders;

use App\Models\Position;
use App\Models\Division;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            // IT Division
            ['name' => 'Software Engineer', 'code' => 'SE', 'division_name' => 'Teknologi Informasi'],
            ['name' => 'Frontend Developer', 'code' => 'FE', 'division_name' => 'Teknologi Informasi'],
            ['name' => 'Backend Developer', 'code' => 'BE', 'division_name' => 'Teknologi Informasi'],
            ['name' => 'Full Stack Developer', 'code' => 'FS', 'division_name' => 'Teknologi Informasi'],
            ['name' => 'DevOps Engineer', 'code' => 'DO', 'division_name' => 'Teknologi Informasi'],
            ['name' => 'QA Engineer', 'code' => 'QA', 'division_name' => 'Teknologi Informasi'],
            ['name' => 'UI/UX Designer', 'code' => 'UX', 'division_name' => 'Teknologi Informasi'],
            ['name' => 'Data Analyst', 'code' => 'DA', 'division_name' => 'Teknologi Informasi'],
            
            // HR Division
            ['name' => 'HR Manager', 'code' => 'HRM', 'division_name' => 'Sumber Daya Manusia'],
            ['name' => 'HR Specialist', 'code' => 'HRS', 'division_name' => 'Sumber Daya Manusia'],
            ['name' => 'Recruitment Specialist', 'code' => 'REC', 'division_name' => 'Sumber Daya Manusia'],
            ['name' => 'Training & Development', 'code' => 'TND', 'division_name' => 'Sumber Daya Manusia'],
            ['name' => 'Talent Acquisition', 'code' => 'TA', 'division_name' => 'Sumber Daya Manusia'],
            
            // Marketing Division
            ['name' => 'Marketing Manager', 'code' => 'MM', 'division_name' => 'Pemasaran'],
            ['name' => 'Digital Marketing Specialist', 'code' => 'DM', 'division_name' => 'Pemasaran'],
            ['name' => 'Content Writer', 'code' => 'CW', 'division_name' => 'Pemasaran'],
            ['name' => 'Social Media Specialist', 'code' => 'SM', 'division_name' => 'Pemasaran'],
            ['name' => 'SEO Specialist', 'code' => 'SEO', 'division_name' => 'Pemasaran'],
            ['name' => 'Brand Manager', 'code' => 'BM', 'division_name' => 'Pemasaran'],
            
            // Finance Division
            ['name' => 'Finance Manager', 'code' => 'FM', 'division_name' => 'Keuangan'],
            ['name' => 'Accountant', 'code' => 'ACC', 'division_name' => 'Keuangan'],
            ['name' => 'Financial Analyst', 'code' => 'FA', 'division_name' => 'Keuangan'],
            ['name' => 'Tax Specialist', 'code' => 'TX', 'division_name' => 'Keuangan'],
            ['name' => 'Budget Analyst', 'code' => 'BA', 'division_name' => 'Keuangan'],
            
            // Operations Division
            ['name' => 'Operations Manager', 'code' => 'OM', 'division_name' => 'Operasional'],
            ['name' => 'Business Analyst', 'code' => 'BSA', 'division_name' => 'Operasional'],
            ['name' => 'Project Manager', 'code' => 'PM', 'division_name' => 'Operasional'],
            ['name' => 'Product Manager', 'code' => 'PDM', 'division_name' => 'Operasional'],
            ['name' => 'Operations Specialist', 'code' => 'OPS', 'division_name' => 'Operasional'],
            
            // Sales Division
            ['name' => 'Sales Manager', 'code' => 'SAM', 'division_name' => 'Penjualan'],
            ['name' => 'Sales Executive', 'code' => 'SAE', 'division_name' => 'Penjualan'],
            ['name' => 'Account Manager', 'code' => 'AM', 'division_name' => 'Penjualan'],
            ['name' => 'Business Development', 'code' => 'BD', 'division_name' => 'Penjualan'],
            ['name' => 'Customer Success', 'code' => 'CSS', 'division_name' => 'Penjualan'],
        ];

        foreach ($positions as $position) {
            $division = Division::where('name', $position['division_name'])->first();
            
            if ($division) {
                Position::create([
                    'name' => $position['name'],
                    'code' => $position['code'],
                    'division_id' => $division->id,
                    'description' => "Position for {$position['name']} in {$position['division_name']} division",
                ]);
            }
        }

        $this->command->info('Positions seeded successfully!');
    }
}
