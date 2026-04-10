<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Member Stories',       'slug' => 'member-stories',       'color_hex' => '#1A7A8A'],
            ['name' => 'Secretariat Updates',  'slug' => 'secretariat-updates',  'color_hex' => '#0D5C6B'],
            ['name' => 'Research & Insights',  'slug' => 'research-insights',    'color_hex' => '#7C3AED'],
            ['name' => 'Scholarships',         'slug' => 'scholarships',         'color_hex' => '#F5A623'],
            ['name' => 'Events & Conferences', 'slug' => 'events-conferences',   'color_hex' => '#EC4899'],
            ['name' => 'Policy & Advocacy',    'slug' => 'policy-advocacy',      'color_hex' => '#059669'],
            ['name' => 'Technology',           'slug' => 'technology',           'color_hex' => '#3B82F6'],
            ['name' => 'Partnerships',         'slug' => 'partnerships',         'color_hex' => '#D97706'],
        ];

        foreach ($categories as $cat) {
            DB::table('post_categories')->updateOrInsert(
                ['slug' => $cat['slug']],
                array_merge($cat, [
                    'id'         => Str::uuid(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
