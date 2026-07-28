<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Medical', 'slug' => 'medical', 'icon' => 'medical-cross', 'description' => 'Medical bills, surgeries, and healthcare expenses'],
            ['name' => 'Education', 'slug' => 'education', 'icon' => 'school', 'description' => 'School fees, tuition, and educational expenses'],
            ['name' => 'Emergency', 'slug' => 'emergency', 'icon' => 'alert-circle', 'description' => 'Emergency relief and disaster response'],
            ['name' => 'Business', 'slug' => 'business', 'icon' => 'briefcase', 'description' => 'Business startup and expansion'],
            ['name' => 'Funeral', 'slug' => 'funeral', 'icon' => 'heart', 'description' => 'Funeral and burial expenses'],
            ['name' => 'Wedding', 'slug' => 'wedding', 'icon' => 'heart', 'description' => 'Wedding and ceremony expenses'],
            ['name' => 'Community', 'slug' => 'community', 'icon' => 'people', 'description' => 'Community development and projects'],
            ['name' => 'Religious', 'slug' => 'religious', 'icon' => 'church', 'description' => 'Church and religious activities'],
            ['name' => 'Sports', 'slug' => 'sports', 'icon' => 'trophy', 'description' => 'Sports teams and athletic events'],
            ['name' => 'Politics', 'slug' => 'politics', 'icon' => 'shield', 'description' => 'Political campaigns and civic initiatives'],
            ['name' => 'Other', 'slug' => 'other', 'icon' => 'ellipsis', 'description' => 'Other fundraising causes'],
        ];

        foreach ($categories as $i => $category) {
            Category::FirstOrCreate(
                ['slug' => $category['slug']],
                array_merge($category, ['sort_order' => $i + 1]));
        }
    }
}
