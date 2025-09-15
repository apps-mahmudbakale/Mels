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
        // Create test user if it doesn't exist
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        
        // Create some additional test users if needed
        if (User::count() < 5) {
            User::factory()->count(4)->create();
        }

        // Run seeders in order
        $this->call([
            NigeriaLocationSeeder::class, // This will handle states and LGAs
            OfficeSeeder::class,          // Create offices before constituencies
            ConstituencySeeder::class,    // This will create constituencies based on states and LGAs
            ProjectSeeder::class,         // Seed projects after all dependencies are set up
        ]);
    }
}
