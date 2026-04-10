<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MemberUserSeeder extends Seeder
{
    public function run(): void
    {
        $orgIds = Organization::pluck('id', 'name');

        $members = [
            [
                'name'              => 'Amina Wanjiku',
                'email'             => 'amina@kensap.org',
                'password'          => Hash::make('password'),
                'role'              => 'member',
                'status'            => 'active',
                'email_verified_at' => now(),
                'title'             => 'Program Director',
                'bio'               => 'Amina leads KenSAP\'s scholar selection and university placement programs, having worked in education access for over 8 years.',
                'phone'             => '+254 722 000 001',
                'org'               => 'KenSAP',
            ],
            [
                'name'              => 'Chidi Okonkwo',
                'email'             => 'chidi@tonyelumelufoundation.org',
                'password'          => Hash::make('password'),
                'role'              => 'member',
                'status'            => 'active',
                'email_verified_at' => now(),
                'title'             => 'Head of Youth Programs',
                'bio'               => 'Chidi manages TEF\'s entrepreneurship training programs across West Africa, supporting over 5,000 young entrepreneurs annually.',
                'phone'             => '+234 801 000 001',
                'org'               => 'Tony Elumelu Foundation',
            ],
            [
                'name'              => 'Sipho Dlamini',
                'email'             => 'sipho@leapschool.org.za',
                'password'          => Hash::make('password'),
                'role'              => 'member',
                'status'            => 'active',
                'email_verified_at' => now(),
                'title'             => 'Academic Affairs Manager',
                'bio'               => 'Sipho oversees curriculum and academic partnerships for LEAP\'s network of science and maths schools across South Africa.',
                'org'               => 'LEAP Science and Maths Schools',
            ],
            [
                'name'              => 'Fatima Diallo',
                'email'             => 'fatima@camfed.org',
                'password'          => Hash::make('password'),
                'role'              => 'member',
                'status'            => 'active',
                'email_verified_at' => now(),
                'title'             => 'East Africa Regional Director',
                'bio'               => 'Fatima leads CAMFED\'s operations in Tanzania, Uganda, and Ethiopia, overseeing programmes reaching over 1 million learners.',
                'org'               => 'CAMFED International',
            ],
            [
                'name'              => 'Kwame Asante',
                'email'             => 'kwame@ashesi.edu.gh',
                'password'          => Hash::make('password'),
                'role'              => 'member',
                'status'            => 'active',
                'email_verified_at' => now(),
                'title'             => 'Director of Admissions',
                'bio'               => 'Kwame leads admissions and scholarship programs at Ashesi University, with a focus on widening access for talented students across West Africa.',
                'org'               => 'Ashesi University Foundation',
            ],
            [
                'name'              => 'Zawadi Muthoni',
                'email'             => 'zawadi@zawadiafrica.org',
                'password'          => Hash::make('password'),
                'role'              => 'member',
                'status'            => 'active',
                'email_verified_at' => now(),
                'title'             => 'Executive Director',
                'bio'               => 'Zawadi founded the Zawadi Africa Education Fund in 2005 and has since supported hundreds of outstanding African women scholars to access international higher education.',
                'org'               => 'Zawadi Africa Education Fund',
            ],
            [
                'name'              => 'Emmanuel Habimana',
                'email'             => 'emmanuel@reb.rw',
                'password'          => Hash::make('password'),
                'role'              => 'member',
                'status'            => 'active',
                'email_verified_at' => now(),
                'title'             => 'Scholarships Coordinator',
                'bio'               => 'Emmanuel coordinates Rwanda\'s national scholarship programs, managing awards for thousands of Rwandan students each year.',
                'org'               => 'Rwanda Education Board',
            ],
            [
                'name'              => 'Aisha Kamara',
                'email'             => 'aisha.demo@haliaccess.net',
                'password'          => Hash::make('password'),
                'role'              => 'friend',
                'status'            => 'active',
                'email_verified_at' => now(),
                'title'             => 'Education Consultant',
                'bio'               => 'Aisha is an independent education consultant and HALI friend, advising foundations and governments on scholarship program design across Sub-Saharan Africa.',
                'org'               => null,
            ],
        ];

        foreach ($members as $data) {
            $orgName = $data['org'];
            unset($data['org']);

            $user = User::firstOrCreate(
                ['email' => $data['email']],
                $data
            );

            // Link to organization
            if ($orgName && isset($orgIds[$orgName])) {
                $user->organizations()->syncWithoutDetaching([
                    $orgIds[$orgName] => ['role' => 'primary_contact', 'is_primary' => true, 'joined_at' => now()],
                ]);
            }
        }
    }
}
