<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $coordinator = User::where('role', 'secretariat')->first();

        $categories = PostCategory::all()->keyBy('slug');

        $posts = [
            [
                'title'     => 'HALI Indaba 2026 — Registration Now Open',
                'slug'      => 'hali-indaba-2026-registration-open',
                'excerpt'   => 'Registration is now open for HALI Indaba 2026, our flagship annual conference on advancing access to international higher education across Sub-Saharan Africa.',
                'content'   => "<h2>Join Us in Nairobi This May</h2>\n<p>We are delighted to announce that registration is now open for <strong>HALI Indaba 2026</strong>, the flagship annual convening of the HALI Access Network, to be held in Nairobi, Kenya on 8–9 May 2026.</p>\n<p>This year's theme — <em>Unlocking Pathways to Global Higher Education</em> — reflects the urgency and opportunity of this moment for our students, our programs, and our network. With shifting immigration policies, evolving scholarship landscapes, and growing demand for our work, HALI Indaba 2026 will be more important than ever.</p>\n<h3>What to Expect</h3>\n<ul>\n<li>Keynote addresses from global higher education leaders</li>\n<li>Panel discussions on U.S. and UK admissions policy changes</li>\n<li>Working group sessions led by member organizations</li>\n<li>HALI Awards Ceremony recognizing outstanding members</li>\n<li>Networking dinner and cultural evening</li>\n</ul>\n<p>Registration closes <strong>30 April 2026</strong>. Early registration is strongly encouraged.</p>",
                'type'      => 'update',
                'status'    => 'published',
                'published_at' => now()->subDays(2),
                'is_featured'  => true,
                'category_slug' => 'events-conferences',
                'author_id' => $admin?->id,
            ],
            [
                'title'     => 'From Kibera to Cambridge: How KenSAP Changed My Life',
                'slug'      => 'from-kibera-to-cambridge-kensap',
                'excerpt'   => 'Alumni feature: James Ochieng shares his journey from Kibera slums to Cambridge University, and how the Kenya Scholar-Athletes Program opened doors he never thought possible.',
                'content'   => "<p>Growing up in Kibera, Kenya's largest informal settlement, the idea of attending a university like Cambridge was about as realistic as travelling to the moon. My mother sold second-hand clothes in Toi Market. My father worked night shifts at a security company in Westlands. We were a family of five in a two-room house with no running water and frequent electricity outages.</p>\n<p>But I could read. And I could run. And in 2019, a teacher at Nairobi Primary School noticed both.</p>\n<blockquote>\"You have the grades for KenSAP,\" she told me. \"You have nothing to lose by applying.\"</blockquote>\n<p>She was right. I applied. I was accepted. And three years later, I sat in the Great Hall at Trinity College, Cambridge, for my matriculation ceremony.</p>\n<h3>The KenSAP Difference</h3>\n<p>KenSAP didn't just help me with applications. They prepared me for an entirely different world — SAT prep, essay coaching, financial aid negotiation, interview preparation, and cultural orientation. Perhaps most importantly, they gave me a community of peers who understood exactly what I was going through.</p>\n<p>Today I'm completing my second year of Economics at Cambridge, and I've already been accepted into the Goldman Sachs internship program for this summer. None of this would have been possible without KenSAP and the wider HALI network that supports them.</p>",
                'type'      => 'story',
                'status'    => 'published',
                'published_at' => now()->subDays(5),
                'is_featured'  => false,
                'category_slug' => 'member-stories',
                'author_id' => $coordinator?->id ?? $admin?->id,
            ],
            [
                'title'     => 'Understanding the 2026 U.S. Visa Landscape for African Students',
                'slug'      => 'us-visa-landscape-african-students-2026',
                'excerpt'   => 'The HALI Secretariat breaks down recent changes to U.S. student visa policy and what they mean for students from Sub-Saharan Africa.',
                'content'   => "<p>The U.S. student visa landscape has shifted significantly in early 2026, and HALI member organizations need to understand these changes to properly advise and support their students.</p>\n<h3>Key Changes</h3>\n<p>The U.S. Department of State has introduced updated processing timelines for F-1 student visas, with particular impacts on applicants from Nigeria, Ghana, and Kenya. Processing times have increased from an average of 3 weeks to 6-8 weeks in many consular posts.</p>\n<h3>What This Means for Your Students</h3>\n<ul>\n<li>Encourage students to apply for visas <em>immediately</em> upon receiving their I-20</li>\n<li>Do not wait until summer — April/May applications should target March–April processing</li>\n<li>Ensure all financial documentation is airtight and clearly demonstrates intent to return home</li>\n<li>Consider SEVIS fee payment in advance</li>\n</ul>\n<h3>Resources Available</h3>\n<p>The HALI Secretariat has updated the Visa Preparation Checklist in the Resource Library. Member organizations can also request one-on-one consultation sessions with our visa guidance specialist — contact secretariat@haliaccess.net.</p>",
                'type'      => 'update',
                'status'    => 'published',
                'published_at' => now()->subDays(8),
                'is_featured'  => false,
                'category_slug' => 'policy-advocacy',
                'author_id' => $admin?->id,
            ],
            [
                'title'     => 'African Leadership Academy Opens Applications for 2027 Cohort',
                'slug'      => 'ala-opens-applications-2027',
                'excerpt'   => 'The African Leadership Academy is now accepting applications for its Class of 2027 — a two-year pre-university program for Africa\'s most promising young leaders.',
                'content'   => "<p>The <strong>African Leadership Academy (ALA)</strong> in Johannesburg, South Africa, has officially opened applications for the Class of 2027. ALA is a two-year pre-university school that identifies and develops Africa's most promising young leaders through the African Leadership Programme (ALP).</p>\n<h3>About ALA</h3>\n<p>ALA brings together 200+ students from across Africa and beyond each year, providing a rigorous academic curriculum, entrepreneurship training, and leadership development in a residential setting. ALA graduates consistently achieve outstanding results in university admissions, with alumni at Oxford, Harvard, MIT, Yale, Princeton, and many of the world's leading institutions.</p>\n<h3>Application Timeline</h3>\n<ul>\n<li><strong>Online Application Opens:</strong> 1 March 2026</li>\n<li><strong>Application Deadline:</strong> 30 June 2026</li>\n<li><strong>Interviews:</strong> August–September 2026</li>\n<li><strong>Decisions:</strong> November 2026</li>\n</ul>\n<p>HALI member organizations are encouraged to identify and nominate exceptional students for consideration. For nomination support, contact the HALI Secretariat.</p>",
                'type'      => 'update',
                'status'    => 'published',
                'published_at' => now()->subDays(10),
                'is_featured'  => false,
                'category_slug' => 'scholarships',
                'author_id' => $coordinator?->id ?? $admin?->id,
            ],
            [
                'title'     => 'New Research: The ROI of Scholarship Support Programs in East Africa',
                'slug'      => 'research-roi-scholarship-programs-east-africa',
                'excerpt'   => 'A new study commissioned by the HALI Secretariat quantifies the long-term economic and social return of investment in scholarship support organizations across East Africa.',
                'content'   => "<p>The HALI Access Network has commissioned a comprehensive longitudinal study examining the economic and social return on investment of scholarship preparation and support programs across Kenya, Uganda, Tanzania, and Rwanda.</p>\n<h3>Key Findings</h3>\n<p>The study, conducted by researchers at the University of Nairobi's School of Economics, surveyed 1,200 alumni from HALI member organization programs over a 10-year period. Key findings include:</p>\n<ul>\n<li>Alumni earn <strong>3.2x more</strong> than their non-program peers within 5 years of graduation</li>\n<li><strong>78% of alumni</strong> return to work in Sub-Saharan Africa within 3 years of completing their degree</li>\n<li>Alumni contribute an estimated <strong>$4.7M annually</strong> in tax revenue and social enterprise investment in their home countries</li>\n<li>For every $1 invested in program support, <strong>$18 in long-term economic value</strong> is generated</li>\n</ul>\n<h3>Implications for Funders</h3>\n<p>These findings provide compelling evidence for philanthropic and government investment in scholarship support organizations. The full report is available to HALI members in the Resource Library.</p>",
                'type'      => 'update',
                'status'    => 'published',
                'published_at' => now()->subDays(14),
                'is_featured'  => true,
                'category_slug' => 'research-insights',
                'author_id' => $admin?->id,
            ],
            [
                'title'     => 'Spotlight: School of St Jude\'s Remarkable 2025 University Placement Results',
                'slug'      => 'school-of-st-jude-2025-placement-results',
                'excerpt'   => 'The School of St Jude in Arusha, Tanzania achieved outstanding university placement results for its Class of 2025, with 100% of graduates earning places at universities globally.',
                'content'   => "<p>We are proud to celebrate the extraordinary university placement achievements of the <strong>School of St Jude</strong> in Arusha, Tanzania — one of HALI's founding member organizations.</p>\n<p>St Jude's Class of 2025, comprising 187 graduates, achieved a 100% university placement rate — with students earning offers from institutions across Tanzania, Kenya, Uganda, the United Kingdom, the United States, Australia, and China.</p>\n<h3>Notable Placements</h3>\n<ul>\n<li>3 students admitted to UK Russell Group universities (University of Edinburgh, University of Manchester, King's College London)</li>\n<li>2 students admitted to U.S. liberal arts colleges on full scholarships</li>\n<li>12 students admitted to the University of Dar es Salaam on merit-based awards</li>\n<li>First-ever St Jude graduate admitted to the University of Melbourne</li>\n</ul>\n<p>\"We are immensely proud of this cohort,\" said St Jude's Founder Gemma Sisia. \"These young people have worked incredibly hard, and they represent Tanzania's brightest future.\"</p>",
                'type'      => 'story',
                'status'    => 'published',
                'published_at' => now()->subDays(18),
                'is_featured'  => false,
                'category_slug' => 'member-stories',
                'author_id' => $coordinator?->id ?? $admin?->id,
            ],
            [
                'title'     => 'HALI Welcomes 4 New Member Organizations in Q1 2026',
                'slug'      => 'hali-new-members-q1-2026',
                'excerpt'   => 'The HALI Access Network is delighted to welcome four new member organizations from Cameroon, Mozambique, Madagascar, and Senegal.',
                'content'   => "<p>The HALI Access Network is growing. We are delighted to formally welcome four new member organizations who joined our network in the first quarter of 2026:</p>\n<ul>\n<li><strong>Cameroon Foundation for Education</strong> (Yaoundé, Cameroon) — Supporting talented Cameroonian students with scholarships and university guidance since 2007</li>\n<li><strong>Aga Khan Development Network — Mozambique</strong> (Maputo, Mozambique) — Providing comprehensive education and community development programs across Mozambique</li>\n<li><strong>Fondation H</strong> (Antananarivo, Madagascar) — A pioneering scholarship organization supporting gifted Malagasy students from disadvantaged backgrounds</li>\n<li><strong>Dakar Institute of Technology</strong> (Dakar, Senegal) — Training West Africa's next generation of technology leaders</li>\n</ul>\n<p>These additions expand the HALI network to 41 member organizations across 22 countries in Sub-Saharan Africa.</p>\n<p>\"We are thrilled to welcome these organizations to the network,\" said HALI Executive Director. \"Each brings unique expertise and perspective that will strengthen our collective mission.\"</p>",
                'type'      => 'update',
                'status'    => 'published',
                'published_at' => now()->subDays(22),
                'is_featured'  => false,
                'category_slug' => 'secretariat-updates',
                'author_id' => $admin?->id,
            ],
            [
                'title'     => 'How CAMFED Uses Technology to Track and Support 4 Million Learners',
                'slug'      => 'camfed-technology-learner-tracking',
                'excerpt'   => 'A deep dive into how CAMFED International has built a world-class data system to track, support, and celebrate the educational journeys of over 4 million learners across Africa.',
                'content'   => "<p>With a reach spanning over 4 million learners across five African countries, <strong>CAMFED International</strong> faces a data management challenge that few organizations in the world can match. How do you track the progress of millions of students, manage thousands of program officers, and demonstrate impact to funders — all while operating in low-bandwidth rural environments?</p>\n<p>We sat down with CAMFED's Director of Technology to understand how they've built a world-class impact measurement system.</p>\n<h3>The CAMA Network</h3>\n<p>Central to CAMFED's model is the CAMA (CAMFED Association) network — a community of women who are CAMFED program graduates. Each CAMA member serves as a mentor, data collector, and community connector. The CAMA app, built on Android and designed for low-bandwidth environments, enables over 200,000 CAMA members to record student data, flag at-risk learners, and receive guidance in real time.</p>\n<h3>Lessons for HALI Members</h3>\n<p>CAMFED's experience offers valuable lessons for any organization seeking to scale their impact measurement:</p>\n<ol>\n<li>Design for your environment — offline-first technology is essential</li>\n<li>Involve beneficiaries as data stewards, not just data subjects</li>\n<li>Focus on actionable data, not comprehensive data</li>\n<li>Invest in training as heavily as technology</li>\n</ol>",
                'type'      => 'story',
                'status'    => 'published',
                'published_at' => now()->subDays(28),
                'is_featured'  => false,
                'category_slug' => 'technology',
                'author_id' => $coordinator?->id ?? $admin?->id,
            ],
            [
                'title'     => 'MasterCard Foundation Scholars Program: 2026 Partnership Update',
                'slug'      => 'mastercard-foundation-scholars-2026-update',
                'excerpt'   => 'The MasterCard Foundation Scholars Program shares an update on 2026 partnerships, expanded university cohorts, and their alumni leadership initiative.',
                'content'   => "<p>The <strong>MasterCard Foundation Scholars Program</strong> — the world's largest university scholarship program focused on African youth — has shared an update on key developments in 2026 with HALI Network members.</p>\n<h3>Expanded University Partnerships</h3>\n<p>The Scholars Program has added three new university partners for the 2026 cohort, bringing the total to 47 partner institutions globally, including new additions in Canada, Germany, and South Africa.</p>\n<h3>Alumni Leadership Initiative</h3>\n<p>A new $15M Alumni Leadership Initiative has been launched, providing seed grants and mentorship to Scholars alumni who are founding social enterprises in their home countries. Over 200 alumni enterprises have already been supported.</p>\n<h3>Collaboration with HALI Members</h3>\n<p>The Scholars Program is actively seeking HALI member organizations to serve as \"feeder pipeline\" partners — organizations whose graduates consistently demonstrate the profile of strong Scholars Program candidates. Interested organizations should contact partnerships@mastercardfdn.org.</p>",
                'type'      => 'update',
                'status'    => 'published',
                'published_at' => now()->subDays(35),
                'is_featured'  => false,
                'category_slug' => 'partnerships',
                'author_id' => $admin?->id,
            ],
            [
                'title'     => 'Equity Group Foundation\'s Wings to Fly: 10 Years of Transforming Lives',
                'slug'      => 'equity-wings-to-fly-10-years',
                'excerpt'   => 'As the Wings to Fly scholarship program marks its 10th anniversary, we look back at a decade of impact and the thousands of Kenyan students whose futures were changed forever.',
                'content'   => "<p>Ten years ago, the <strong>Equity Group Foundation</strong> launched the Wings to Fly program with an audacious goal: to ensure that no talented Kenyan student would miss out on secondary education due to poverty. A decade later, the numbers speak for themselves.</p>\n<h3>A Decade by the Numbers</h3>\n<ul>\n<li><strong>8,500+ scholars</strong> supported over 10 years</li>\n<li><strong>98% completion rate</strong> at sponsored secondary schools</li>\n<li><strong>89% of scholars</strong> admitted to university (vs. 32% national average)</li>\n<li><strong>67 scholars</strong> admitted to international universities in the U.S., UK, and Canada</li>\n<li>Over <strong>KES 4.2 billion</strong> in scholarships distributed</li>\n</ul>\n<h3>The Network Effect</h3>\n<p>Perhaps Wings to Fly's most powerful impact has been the alumni network it's created. Over 5,000 Wings to Fly alumni are now working professionals, many in leadership roles across Kenya's public and private sectors. The Wings to Fly Alumni Association now actively mentors current scholars — creating a self-sustaining cycle of upliftment.</p>\n<p>\"Wings to Fly is proof that when you invest in a young person's potential, the returns extend far beyond that individual,\" said Dr. James Mwangi, Group CEO of Equity Bank. \"These scholars are transforming families, communities, and ultimately, our nation.\"</p>",
                'type'      => 'story',
                'status'    => 'published',
                'published_at' => now()->subDays(42),
                'is_featured'  => false,
                'category_slug' => 'member-stories',
                'author_id' => $coordinator?->id ?? $admin?->id,
            ],
        ];

        foreach ($posts as $data) {
            $categorySlug = $data['category_slug'];
            unset($data['category_slug']);

            $post = Post::firstOrCreate(
                ['slug' => $data['slug']],
                $data
            );

            // Attach category
            if (isset($categories[$categorySlug])) {
                $post->categories()->syncWithoutDetaching([$categories[$categorySlug]->id]);
            }
        }
    }
}
