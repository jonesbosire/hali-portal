<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'super_admin')->first();

        $events = [
            [
                'title'                  => 'HALI Indaba 2026 — Unlocking Pathways to Global Higher Education',
                'slug'                   => 'hali-indaba-2026',
                'description'            => "HALI Indaba 2026 is the flagship annual convening of the HALI Access Network, bringing together member organizations, higher education partners, and student alumni from across Sub-Saharan Africa.\n\nThis two-day conference will explore emerging scholarship landscapes, policy advocacy, and best practices in supporting low-income, high-achieving students to access and thrive in international higher education.\n\nProgramme Highlights:\n- Keynote: The Future of African Talent in a Shifting Global Landscape\n- Panel: Navigating U.S. & UK Admissions Post-Policy Changes\n- Workshop: Building Stronger Alumni Networks\n- Member Working Group Sessions\n- HALI Awards Ceremony\n- Networking Dinner & Cultural Evening",
                'type'                   => 'conference',
                'location_type'          => 'in_person',
                'venue_name'             => 'Radisson Blu Hotel, Nairobi',
                'venue_address'          => 'Upper Hill Road, Nairobi, Kenya',
                'start_datetime'         => now()->addDays(45)->setTime(8, 0),
                'end_datetime'           => now()->addDays(46)->setTime(18, 0),
                'registration_closes_at' => now()->addDays(38),
                'max_attendees'          => 250,
                'status'                 => 'published',
                'is_featured'            => true,
                'is_members_only'        => false,
                'created_by'             => $admin?->id,
            ],
            [
                'title'                  => 'Webinar: Scholarship Application Best Practices 2026',
                'slug'                   => 'webinar-scholarship-best-practices-2026',
                'description'            => "Join the HALI Secretariat for a practical webinar designed for program officers and counselors working with students applying to international scholarships and universities in 2026.\n\nWe'll cover:\n- Updated requirements for top scholarship programs (Gates Cambridge, Rhodes, Chevening, MasterCard Foundation)\n- Common application mistakes and how to avoid them\n- Interview preparation strategies\n- Q&A with scholarship alumni",
                'type'                   => 'webinar',
                'location_type'          => 'virtual',
                'virtual_link'           => 'https://zoom.us/j/placeholder',
                'start_datetime'         => now()->addDays(12)->setTime(14, 0),
                'end_datetime'           => now()->addDays(12)->setTime(16, 30),
                'registration_closes_at' => now()->addDays(10),
                'max_attendees'          => 500,
                'status'                 => 'published',
                'is_featured'            => false,
                'is_members_only'        => true,
                'created_by'             => $admin?->id,
            ],
            [
                'title'                  => 'East Africa Member Meetup — Nairobi Hub',
                'slug'                   => 'east-africa-meetup-nairobi-2026',
                'description'            => "An informal networking and knowledge-sharing meetup for HALI member organizations based in or visiting Nairobi. Connect with fellow program officers, share updates from your programs, and discuss upcoming collaboration opportunities.\n\nLight refreshments will be provided. Limited to 40 participants.",
                'type'                   => 'other',
                'location_type'          => 'in_person',
                'venue_name'             => 'African Leadership Academy Hub, Nairobi',
                'venue_address'          => 'Westlands, Nairobi, Kenya',
                'start_datetime'         => now()->addDays(20)->setTime(17, 30),
                'end_datetime'           => now()->addDays(20)->setTime(20, 0),
                'registration_closes_at' => now()->addDays(17),
                'max_attendees'          => 40,
                'status'                 => 'published',
                'is_featured'            => false,
                'is_members_only'        => true,
                'created_by'             => $admin?->id,
            ],
            [
                'title'                  => 'Workshop: Data & Impact Measurement for Education Programs',
                'slug'                   => 'workshop-data-impact-measurement-2026',
                'description'            => "A half-day skills-building workshop on measuring and communicating impact for education support programs.\n\nTopics covered:\n- Designing meaningful outcome indicators\n- Collecting longitudinal alumni data\n- Creating compelling impact reports for funders\n- Tools: Salesforce NPSP, Airtable, Google Data Studio\n\nParticipants are encouraged to bring a current reporting challenge from their organization.",
                'type'                   => 'workshop',
                'location_type'          => 'hybrid',
                'venue_name'             => 'HALI Secretariat Office, Nairobi',
                'venue_address'          => 'Westlands, Nairobi, Kenya',
                'virtual_link'           => 'https://zoom.us/j/placeholder',
                'start_datetime'         => now()->addDays(30)->setTime(9, 0),
                'end_datetime'           => now()->addDays(30)->setTime(13, 0),
                'registration_closes_at' => now()->addDays(25),
                'max_attendees'          => 60,
                'status'                 => 'published',
                'is_featured'            => false,
                'is_members_only'        => true,
                'created_by'             => $admin?->id,
            ],
            [
                'title'                  => 'Webinar: Supporting Students Through the U.S. Financial Aid Process',
                'slug'                   => 'webinar-us-financial-aid-2026',
                'description'            => "Understanding the U.S. financial aid system is critical for international students. In this webinar, our financial aid experts break down how Need-Blind and Need-Aware admissions policies affect African students, how to interpret award letters, and how to make the case for more aid.\n\nGuest speakers include financial aid officers from Yale, Williams College, and Pomona College.",
                'type'                   => 'webinar',
                'location_type'          => 'virtual',
                'virtual_link'           => 'https://zoom.us/j/placeholder',
                'start_datetime'         => now()->addDays(8)->setTime(16, 0),
                'end_datetime'           => now()->addDays(8)->setTime(17, 30),
                'registration_closes_at' => now()->addDays(6),
                'max_attendees'          => 300,
                'status'                 => 'published',
                'is_featured'            => false,
                'is_members_only'        => false,
                'created_by'             => $admin?->id,
            ],
        ];

        foreach ($events as $data) {
            Event::firstOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
