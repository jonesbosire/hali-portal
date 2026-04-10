<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,       // 1. Admin accounts (no FK deps)
            OrganizationSeeder::class,    // 2. Member organizations
            MembershipPlanSeeder::class,  // 3. Subscription plans
            PostCategorySeeder::class,    // 4. Post categories
            MemberUserSeeder::class,      // 5. Sample member users linked to orgs
            EventSeeder::class,           // 6. Upcoming events (depends on admin user)
            PostSeeder::class,            // 7. Sample posts (depends on admin user + categories)
            OpportunitySeeder::class,     // 8. Sample opportunities (depends on orgs + admin user)
        ]);
    }
}
