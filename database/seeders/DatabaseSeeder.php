<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create demo tenant if it doesn't exist
        $demoTenant = Tenant::firstOrCreate(
            ['uuid' => 'DEMO'],
            [
                'name' => 'Demo Tenant',
                'api_key' => 'DEMO',
            ]
        );

        // Data for the DEMO tenant
        (new UsersTableSeeder($demoTenant->uuid))->seed();
        (new TagsTableSeeder($demoTenant->uuid))->seed();
        (new LinksTableSeeder($demoTenant->uuid))->seed();
        (new CommentsTableSeeder($demoTenant->uuid))->seed();
        (new VotesTableSeeder($demoTenant->uuid))->seed();

        // / Create a second tenant for testing
        $testTenant = Tenant::factory()->create([
            'name' => 'Test Tenant',
        ]);

        // Minimal data for the test tenant
        (new UsersTableSeeder($testTenant->uuid))->seed();
        (new TagsTableSeeder($testTenant->uuid))->seed();
    }
}
