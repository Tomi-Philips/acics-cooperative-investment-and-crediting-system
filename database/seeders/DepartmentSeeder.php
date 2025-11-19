<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'code' => 'ACCT',
                'title' => 'Accounting',
                'description' => 'Handles financial records and transactions',
                'is_active' => true,
            ],
            [
                'code' => 'HR',
                'title' => 'Human Resources',
                'description' => 'Manages employee relations and recruitment',
                'is_active' => true,
            ],
            [
                'code' => 'IT',
                'title' => 'Information Technology',
                'description' => 'Supports technology infrastructure and software',
                'is_active' => true,
            ],
            [
                'code' => 'MKTG',
                'title' => 'Marketing',
                'description' => 'Promotes products and services',
                'is_active' => true,
            ],
            [
                'code' => 'OPER',
                'title' => 'Operations',
                'description' => 'Manages day-to-day business activities',
                'is_active' => true,
            ],
            [
                'code' => 'ADMIN',
                'title' => 'Administration',
                'description' => 'Oversees administrative functions',
                'is_active' => true,
            ],
        ];

        foreach ($departments as $dept) {
            Department::updateOrCreate(
                ['code' => $dept['code']],
                $dept
            );
        }
    }
}