<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call seeders to create sample data
        $this->call([
            AdminUserSeeder::class,
            MemberSeeder::class,
            LoanSeeder::class,
        ]);

        // Create a test user
        User::factory()->create([
            'name' => 'Test Member',
            'email' => 'member@acics.com',
            'role' => 'member',
        ]);
    }
}
