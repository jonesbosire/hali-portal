<?php

namespace Database\Seeders;

use App\Models\Opportunity;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;

class OpportunitySeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'super_admin')->first();

        $orgIds = Organization::pluck('id', 'name');

        $opportunities = [
            [
                'title'            => 'Program Officer — Scholarship Access',
                'organization_id'  => $orgIds['KenSAP'] ?? null,
                'posted_by'        => $admin?->id,
                'type'             => 'job',
                'description'      => "KenSAP is seeking an experienced Program Officer to join our team in Nairobi. You will manage scholar selection, academic coaching, university placement, and alumni relations for our cohort of students applying to selective U.S. colleges.\n\nThis is a full-time role with significant opportunity for growth within one of East Africa's most respected scholarship organizations.",
                'requirements'     => "- Bachelor's degree required; Master's preferred\n- 3+ years experience in education, scholarship, or international development\n- Strong written and verbal communication in English\n- Experience working with high-achieving secondary school students\n- Knowledge of the U.S. college admissions landscape is a significant plus",
                'location'         => 'Nairobi, Kenya',
                'salary_range'     => 'KES 120,000 – 160,000/month',
                'application_url'  => 'https://kensap.org/jobs',
                'deadline_at'      => now()->addDays(25),
                'status'           => 'active',
                'is_members_only'  => false,
            ],
            [
                'title'            => 'Gates Cambridge Scholarship 2027 — Now Open',
                'organization_id'  => $orgIds['African Leadership Academy'] ?? null,
                'posted_by'        => $admin?->id,
                'type'             => 'scholarship',
                'description'      => "The Gates Cambridge Scholarship is one of the world's most prestigious international scholarship programs. It funds outstanding applicants from outside the UK to pursue a full-time postgraduate degree at the University of Cambridge.\n\nAwards are made on the basis of intellectual ability, leadership capacity, commitment to improving the lives of others, and reasons for wanting to study at Cambridge.",
                'requirements'     => "- Must be a citizen of any country outside the United Kingdom\n- Must apply to the University of Cambridge for a full-time postgraduate degree\n- Must demonstrate outstanding intellectual ability\n- Must show a commitment to improving the lives of others\n- Relevant research experience strongly preferred",
                'location'         => 'Cambridge, United Kingdom',
                'salary_range'     => 'Full funding (tuition + stipend + flights)',
                'application_url'  => 'https://www.gatescambridge.org',
                'deadline_at'      => now()->addDays(60),
                'status'           => 'active',
                'is_members_only'  => false,
            ],
            [
                'title'            => 'Mastercard Foundation Scholars Program — University of Cape Town',
                'organization_id'  => $orgIds['MasterCard Foundation Scholars Program'] ?? null,
                'posted_by'        => $admin?->id,
                'type'             => 'scholarship',
                'description'      => "The Mastercard Foundation Scholars Program at the University of Cape Town provides full scholarships for academically talented African students with demonstrated financial need to pursue undergraduate and postgraduate studies.\n\nThe program includes academic support, leadership development, mentorship, and an internship component.",
                'requirements'     => "- Must be a citizen of a Sub-Saharan African country\n- Must demonstrate academic excellence (minimum 80% average or equivalent)\n- Must demonstrate financial need\n- Must be committed to returning to Africa post-graduation\n- Leadership experience in community or school activities required",
                'location'         => 'Cape Town, South Africa',
                'salary_range'     => 'Full scholarship (tuition, accommodation, living expenses)',
                'application_url'  => 'https://www.uct.ac.za/mastercard-scholars',
                'deadline_at'      => now()->addDays(45),
                'status'           => 'active',
                'is_members_only'  => false,
            ],
            [
                'title'            => 'ALA Summer Fellowship — Education Innovation',
                'organization_id'  => $orgIds['African Leadership Academy'] ?? null,
                'posted_by'        => $admin?->id,
                'type'             => 'fellowship',
                'description'      => "The African Leadership Academy Summer Fellowship places outstanding young African professionals within ALA's academic, entrepreneurship, and leadership programmes for 8 weeks during June–August.\n\nFellows work directly with ALA faculty and staff to design and implement programme improvements, research curriculum innovations, and mentor ALA students. This is a residential fellowship based at ALA's campus in Johannesburg.",
                'requirements'     => "- Must be an African citizen aged 24–32\n- Must hold a Bachelor's degree (Master's preferred)\n- Must have 2+ years of professional experience in education, social enterprise, or related field\n- Strong facilitation and mentorship skills\n- Previous experience in a boarding school or residential programme is an advantage",
                'location'         => 'Johannesburg, South Africa',
                'salary_range'     => 'Stipend + accommodation + meals',
                'application_url'  => 'https://africanleadershipacademy.org/fellowship',
                'deadline_at'      => now()->addDays(18),
                'status'           => 'active',
                'is_members_only'  => true,
            ],
            [
                'title'            => 'M&E Intern — Education Programs',
                'organization_id'  => $orgIds['Equity Group Foundation'] ?? null,
                'posted_by'        => $admin?->id,
                'type'             => 'internship',
                'description'      => "Equity Group Foundation is looking for a Monitoring & Evaluation Intern to support the Wings to Fly scholarship programme. You will assist in data collection, analysis, and reporting on scholar outcomes.\n\nThis is a 6-month paid internship based at the Equity Centre in Nairobi with potential for extension.",
                'requirements'     => "- Currently enrolled in or recently graduated from a degree programme in Statistics, Social Science, Development Studies, or related field\n- Proficiency in Excel and/or SPSS/STATA\n- Strong attention to detail and data accuracy\n- Excellent written English\n- Interest in education and social impact",
                'location'         => 'Nairobi, Kenya (on-site)',
                'salary_range'     => 'KES 30,000/month',
                'application_url'  => 'https://equitygroupfoundation.com/internships',
                'deadline_at'      => now()->addDays(14),
                'status'           => 'active',
                'is_members_only'  => true,
            ],
            [
                'title'            => 'Volunteer Mentor — University Applicant Coaching',
                'organization_id'  => $orgIds['Kenya Education Fund'] ?? null,
                'posted_by'        => $admin?->id,
                'type'             => 'volunteer',
                'description'      => "KEF is building a pool of volunteer mentors to provide one-on-one coaching to Kenyan students applying to universities in the U.S., UK, and Canada. Mentors commit to 2–4 hours per week for one academic year.\n\nAll mentors attend a two-day onboarding workshop (virtual) before being matched with students.",
                'requirements'     => "- Must be a graduate of a university in the U.S., UK, Canada, or Australia\n- Must be available for 2–4 hours/week via Zoom\n- Passion for education equity and youth development\n- Patience and strong listening skills\n- Preference given to African alumni of international universities",
                'location'         => 'Remote (Zoom)',
                'salary_range'     => 'Volunteer (unpaid)',
                'application_url'  => 'https://kenyaeducationfund.org/volunteer',
                'deadline_at'      => now()->addDays(30),
                'status'           => 'active',
                'is_members_only'  => false,
            ],
            [
                'title'            => 'Chevening Scholarship 2027 — UK Government',
                'organization_id'  => null,
                'posted_by'        => $admin?->id,
                'type'             => 'scholarship',
                'description'      => "Chevening is the UK government's international awards programme, offering fully funded scholarships to study any eligible master's degree at any UK university. Chevening Scholars are selected for their leadership potential and are chosen to represent their countries as future leaders.\n\nChevening is funded by the Foreign, Commonwealth & Development Office (FCDO) and partner organisations.",
                'requirements'     => "- Citizen of a Chevening-eligible country\n- Bachelor's degree with at least a 2:1 equivalent\n- At least 2 years of full-time work experience\n- Return to your home country for at least 2 years after your scholarship\n- Applied to 3 different UK universities and courses",
                'location'         => 'United Kingdom (any UK university)',
                'salary_range'     => 'Full funding (tuition + living + return flights)',
                'application_url'  => 'https://www.chevening.org/scholarships',
                'deadline_at'      => now()->addDays(50),
                'status'           => 'active',
                'is_members_only'  => false,
            ],
        ];

        foreach ($opportunities as $data) {
            Opportunity::firstOrCreate(
                ['title' => $data['title']],
                $data
            );
        }
    }
}
