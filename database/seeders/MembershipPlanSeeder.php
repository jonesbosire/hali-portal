<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MembershipPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'id'            => Str::uuid(),
                'name'          => 'Associate',
                'slug'          => 'associate',
                'price_usd'     => 0,
                'billing_cycle' => 'annual',
                'description'   => 'Basic access to the HALI partner portal for observer organizations.',
                'features'      => json_encode([
                    'Access to member directory',
                    'View events and announcements',
                    'Receive HALI Bulletins',
                    'Network with 40+ member organizations',
                ]),
                'is_active'     => true,
                'display_order' => 1,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'id'            => Str::uuid(),
                'name'          => 'Partner',
                'slug'          => 'partner',
                'price_usd'     => 500,
                'billing_cycle' => 'annual',
                'description'   => 'Full membership for active HALI partner organizations.',
                'features'      => json_encode([
                    'Everything in Associate',
                    'Post opportunities and resources',
                    'RSVP & register for HALI events',
                    'Organization directory listing',
                    'Access to curated resource library',
                    'Participate in working groups',
                    'Vote at HALI Annual General Meetings',
                ]),
                'is_active'     => true,
                'display_order' => 2,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'id'            => Str::uuid(),
                'name'          => 'Founding Partner',
                'slug'          => 'founding-partner',
                'price_usd'     => 1500,
                'billing_cycle' => 'annual',
                'description'   => 'Premium tier for organizations at the forefront of the HALI mission.',
                'features'      => json_encode([
                    'Everything in Partner',
                    'Featured placement in member directory',
                    'Priority event registration',
                    'Co-branding opportunities',
                    'Seat on HALI Advisory Council',
                    'Dedicated Secretariat liaison',
                    'Annual impact report inclusion',
                    'Logo on HALI flagship publications',
                ]),
                'is_active'     => true,
                'display_order' => 3,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ];

        foreach ($plans as $plan) {
            DB::table('membership_plans')->updateOrInsert(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
