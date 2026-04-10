<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $organizations = [
            // Kenya
            ['name' => 'Equity Group Foundation', 'country' => 'Kenya', 'region' => 'East Africa', 'founding_year' => 2008, 'students_supported' => 8500, 'description' => 'Equity Group Foundation transforms lives through access to quality education and leadership development programs.', 'website_url' => 'https://equitygroupfoundation.com'],
            ['name' => 'KenSAP', 'country' => 'Kenya', 'region' => 'East Africa', 'founding_year' => 2004, 'students_supported' => 350, 'description' => 'Kenya Scholar-Athletes Program prepares exceptional Kenyan students for admission to selective U.S. colleges and universities.', 'website_url' => 'https://kensap.org'],
            ['name' => 'M-Pesa Foundation Academy', 'country' => 'Kenya', 'region' => 'East Africa', 'founding_year' => 2016, 'students_supported' => 900, 'description' => 'A model school transforming education in Africa through technology and innovation.'],
            ['name' => 'Kenya Education Fund', 'country' => 'Kenya', 'region' => 'East Africa', 'founding_year' => 2001, 'students_supported' => 1200, 'description' => 'Providing scholarships and mentorship to high-achieving students from low-income families in Kenya.', 'website_url' => 'https://kenyaeducationfund.org'],
            ['name' => 'Zawadi Africa Education Fund', 'country' => 'Kenya', 'region' => 'East Africa', 'founding_year' => 2005, 'students_supported' => 280, 'description' => 'Supporting outstanding African women scholars with access to international higher education opportunities.', 'website_url' => 'https://zawadiafrica.org'],
            ['name' => 'Jomo Kenyatta Foundation', 'country' => 'Kenya', 'region' => 'East Africa', 'founding_year' => 1966, 'students_supported' => 5000, 'description' => 'Kenya\'s oldest education foundation, supporting academic excellence and publishing educational materials.'],

            // South Africa
            ['name' => 'African Leadership Academy', 'country' => 'South Africa', 'region' => 'Southern Africa', 'founding_year' => 2008, 'students_supported' => 3200, 'description' => 'ALA is a pan-African school developing the next generation of African leaders through the African Leadership Programme.', 'website_url' => 'https://africanleadershipacademy.org'],
            ['name' => 'LEAP Science and Maths Schools', 'country' => 'South Africa', 'region' => 'Southern Africa', 'founding_year' => 1994, 'students_supported' => 2000, 'description' => 'Providing exceptional science and mathematics education to underserved South African youth.', 'website_url' => 'https://leapschool.org.za'],
            ['name' => 'Allan Gray Orbis Foundation', 'country' => 'South Africa', 'region' => 'Southern Africa', 'founding_year' => 2005, 'students_supported' => 1800, 'description' => 'Developing South Africa\'s future generation of entrepreneur minds through scholarship and mentorship programs.', 'website_url' => 'https://allangrayorbis.org'],
            ['name' => 'Bursary Office — University of Cape Town', 'country' => 'South Africa', 'region' => 'Southern Africa', 'founding_year' => 1829, 'students_supported' => 4500, 'description' => 'UCT provides extensive bursary and financial support programs for high-achieving students from disadvantaged backgrounds.'],

            // Nigeria
            ['name' => 'Mastercard Foundation Scholars Program — Nigeria', 'country' => 'Nigeria', 'region' => 'West Africa', 'founding_year' => 2012, 'students_supported' => 600, 'description' => 'Supporting academically talented young Africans with the financial means and skills to succeed in higher education.'],
            ['name' => 'Tony Elumelu Foundation', 'country' => 'Nigeria', 'region' => 'West Africa', 'founding_year' => 2010, 'students_supported' => 18000, 'description' => 'Empowering African entrepreneurs through seed capital, mentoring and business skills training.', 'website_url' => 'https://tonyelumelufoundation.org'],
            ['name' => 'Lagos State Scholarship Board', 'country' => 'Nigeria', 'region' => 'West Africa', 'founding_year' => 1978, 'students_supported' => 3000, 'description' => 'Administering scholarship programs for brilliant Lagos students pursuing higher education locally and internationally.'],
            ['name' => 'Fate Foundation', 'country' => 'Nigeria', 'region' => 'West Africa', 'founding_year' => 2000, 'students_supported' => 25000, 'description' => 'Nigeria\'s leading entrepreneurship and educational support organization empowering Nigerians to drive economic growth.', 'website_url' => 'https://fatefoundation.org'],

            // Ghana
            ['name' => 'Mastercard Foundation Scholars Program — Ghana', 'country' => 'Ghana', 'region' => 'West Africa', 'founding_year' => 2013, 'students_supported' => 420, 'description' => 'Partnering with leading African universities to provide scholarships for talented young people from disadvantaged backgrounds.'],
            ['name' => 'Ghana Education Trust Fund', 'country' => 'Ghana', 'region' => 'West Africa', 'founding_year' => 2000, 'students_supported' => 7500, 'description' => 'Providing financial support for Ghanaians pursuing higher education at accredited institutions.', 'website_url' => 'https://getfund.gov.gh'],
            ['name' => 'Ashesi University Foundation', 'country' => 'Ghana', 'region' => 'West Africa', 'founding_year' => 2002, 'students_supported' => 1200, 'description' => 'Training a new generation of ethical, entrepreneurial leaders who will transform Africa through innovative education.', 'website_url' => 'https://ashesi.edu.gh'],

            // Tanzania
            ['name' => 'Tanzanian Education Support Trust', 'country' => 'Tanzania', 'region' => 'East Africa', 'founding_year' => 2003, 'students_supported' => 850, 'description' => 'Supporting talented Tanzanian youth with access to quality secondary and higher education opportunities.'],
            ['name' => 'School of St Jude', 'country' => 'Tanzania', 'region' => 'East Africa', 'founding_year' => 2002, 'students_supported' => 1800, 'description' => 'Providing free, quality education to the most academically talented and financially disadvantaged students in Arusha, Tanzania.', 'website_url' => 'https://schoolofstjude.org'],

            // Uganda
            ['name' => 'Uganda Martyrs University Foundation', 'country' => 'Uganda', 'region' => 'East Africa', 'founding_year' => 1993, 'students_supported' => 2200, 'description' => 'Supporting higher education access for talented Ugandan students through scholarships and academic excellence programs.'],
            ['name' => 'Aga Khan Education Services — Uganda', 'country' => 'Uganda', 'region' => 'East Africa', 'founding_year' => 1905, 'students_supported' => 3500, 'description' => 'Providing high-quality education to communities in Uganda and East Africa through a network of schools and programs.'],

            // Ethiopia
            ['name' => 'Ethiopian Education Fund', 'country' => 'Ethiopia', 'region' => 'East Africa', 'founding_year' => 2006, 'students_supported' => 1500, 'description' => 'Enabling Ethiopia\'s brightest young students to access quality education and international opportunities.'],
            ['name' => 'Mastercard Foundation Scholars — Addis Ababa University', 'country' => 'Ethiopia', 'region' => 'East Africa', 'founding_year' => 2015, 'students_supported' => 250, 'description' => 'Providing comprehensive scholarships and support services at Addis Ababa University for talented disadvantaged students.'],

            // Rwanda
            ['name' => 'Rwanda Education Board', 'country' => 'Rwanda', 'region' => 'East Africa', 'founding_year' => 2011, 'students_supported' => 12000, 'description' => 'Coordinating Rwanda\'s education sector and managing national scholarship programs for talented Rwandan students.', 'website_url' => 'https://reb.rw'],
            ['name' => 'African Institute for Mathematical Sciences — Rwanda', 'country' => 'Rwanda', 'region' => 'East Africa', 'founding_year' => 2016, 'students_supported' => 400, 'description' => 'Advancing mathematical and scientific talent across Africa through postgraduate training and research programs.', 'website_url' => 'https://aims.ac.rw'],

            // Senegal
            ['name' => 'Dakar Institute of Technology', 'country' => 'Senegal', 'region' => 'West Africa', 'founding_year' => 2008, 'students_supported' => 1100, 'description' => 'Training the next generation of West African technology leaders through cutting-edge programs.'],

            // Côte d'Ivoire
            ['name' => 'Fondation Orange — Côte d\'Ivoire', 'country' => "Côte d'Ivoire", 'region' => 'West Africa', 'founding_year' => 2012, 'students_supported' => 900, 'description' => 'Supporting digital literacy and education programs for youth across Francophone West Africa.'],

            // Zimbabwe
            ['name' => 'CAMFED International', 'country' => 'Zimbabwe', 'region' => 'Southern Africa', 'founding_year' => 1993, 'students_supported' => 4200000, 'description' => 'Campaign for Female Education — a global organization that combats poverty and inequality through the education of girls.', 'website_url' => 'https://camfed.org'],
            ['name' => 'Old Mutual Zimbabwe Foundation', 'country' => 'Zimbabwe', 'region' => 'Southern Africa', 'founding_year' => 2002, 'students_supported' => 1600, 'description' => 'Investing in education and healthcare to transform lives and build sustainable communities in Zimbabwe.'],

            // Zambia
            ['name' => 'Zambia Education Scholarship Trust', 'country' => 'Zambia', 'region' => 'Southern Africa', 'founding_year' => 1975, 'students_supported' => 2800, 'description' => 'Providing scholarships and bursaries to Zambian students excelling academically but facing financial constraints.'],

            // Mozambique
            ['name' => 'Aga Khan Development Network — Mozambique', 'country' => 'Mozambique', 'region' => 'Southern Africa', 'founding_year' => 1980, 'students_supported' => 1900, 'description' => 'Supporting education, health and economic development programs for communities throughout Mozambique.'],

            // Botswana
            ['name' => 'Botswana Scholarship Authority', 'country' => 'Botswana', 'region' => 'Southern Africa', 'founding_year' => 1993, 'students_supported' => 3000, 'description' => 'Administering government scholarship programs enabling Botswanans to access tertiary education locally and abroad.', 'website_url' => 'https://bsa.org.bw'],

            // Cameroon
            ['name' => 'Cameroon Foundation for Education', 'country' => 'Cameroon', 'region' => 'Central Africa', 'founding_year' => 2007, 'students_supported' => 780, 'description' => 'Empowering talented Cameroonian students through scholarships, mentorship and career guidance programs.'],

            // DRC
            ['name' => 'Fondation Agir pour l\'Éducation — DRC', 'country' => 'Democratic Republic of Congo', 'region' => 'Central Africa', 'founding_year' => 2010, 'students_supported' => 650, 'description' => 'Supporting educational access and excellence for high-achieving students across the Democratic Republic of Congo.'],

            // Malawi
            ['name' => 'Malawi Scholarship Committee', 'country' => 'Malawi', 'region' => 'Southern Africa', 'founding_year' => 1964, 'students_supported' => 1400, 'description' => 'Managing national scholarship awards for outstanding Malawian students pursuing higher education opportunities.'],

            // Madagascar
            ['name' => 'Fondation H — Madagascar', 'country' => 'Madagascar', 'region' => 'Southern Africa', 'founding_year' => 2014, 'students_supported' => 320, 'description' => 'Providing academic support and scholarships to gifted Malagasy students from underprivileged backgrounds.'],

            // International (multi-country)
            ['name' => 'MasterCard Foundation Scholars Program', 'country' => 'Canada', 'region' => 'International', 'founding_year' => 2012, 'students_supported' => 35000, 'description' => 'The Mastercard Foundation Scholars Program supports young Africans with the talent and drive to succeed through scholarships and leadership development.', 'website_url' => 'https://mastercardfdn.org/all/scholars'],
            ['name' => 'Aga Khan Foundation — East Africa', 'country' => 'Kenya', 'region' => 'East Africa', 'founding_year' => 1967, 'students_supported' => 15000, 'description' => 'Developing and promoting innovative solutions to problems that impede social development across East Africa.', 'website_url' => 'https://akdn.org'],
        ];

        foreach ($organizations as $data) {
            Organization::firstOrCreate(
                ['name' => $data['name']],
                $data
            );
        }
    }
}
