<?php

namespace Database\Seeders;

use App\Enums\CampaignStatus;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        


        $this->call([
            RoleSeeder::class,
            CategorySeeder::class,
        ]);

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@supportsphere.co.ke',
            'phone' => '254700000000',
            'password' => bcrypt('password'),
            'kyc_status' => 'verified',
        ]);
        $admin->assignRole('super_admin');

        $fundraiser1 = User::create([
            'name' => 'James Ochieng',
            'email' => 'james@example.com',
            'phone' => '254712345678',
            'password' => bcrypt('password'),
            'kyc_status' => 'verified',
        ]);
        $fundraiser1->assignRole('fundraiser');

        $fundraiser2 = User::create([
            'name' => 'Mary Wanjiku',
            'email' => 'mary@example.com',
            'phone' => '254723456789',
            'password' => bcrypt('password'),
            'kyc_status' => 'verified',
        ]);
        $fundraiser2->assignRole('fundraiser');

        $fundraiser3 = User::create([
            'name' => 'Peter Kamau',
            'email' => 'peter@example.com',
            'phone' => '254734567890',
            'password' => bcrypt('password'),
            'kyc_status' => 'partial',
        ]);
        $fundraiser3->assignRole('fundraiser');

        $donors = [];
        for ($i = 1; $i <= 5; $i++) {
            $donor = create([
                'name' => "Donor {$i}",
                'email' => "donor{$i}@example.com",
                'phone' => "2547456{$i}0001",
                'password' => bcrypt('password'),
            ]);
            $donor->assignRole('donor');
            $donors[] = $donor;
        }

        $categories = Category::all();

        $campaignsData = [
            [
                'user_id' => $fundraiser1->id,
                'category_id' => $categories->where('slug', 'medical')->first()->id,
                'title' => 'Surgery Fund for Baby Amina',
                'story' => "Little Amina is a 2-year-old girl born with a congenital heart defect. She urgently needs corrective surgery at the Nairobi Hospital. Her parents are reaching out to well-wishers to help raise KES 1.5 million for the procedure and post-operative care. Every contribution brings her closer to a healthy, normal life.",
                'target_amount' => 1500000,
                'raised_amount' => 985000,
                'status' => CampaignStatus::Active,
                'expiry_date' => now()->addDays(45),
                'verified_at' => now()->subDays(3),
            ],
            [
                'user_id' => $fundraiser1->id,
                'category_id' => $categories->where('slug', 'education')->first()->id,
                'title' => 'Bright Future Scholarship Fund',
                'story' => "Help us send 10 bright students from Kibera to university. These students have excelled in their KCSE exams but lack the funds to pursue higher education. Your contribution will cover tuition, accommodation, and learning materials for their four-year degree programs.",
                'target_amount' => 2500000,
                'raised_amount' => 1450000,
                'status' => CampaignStatus::Active,
                'expiry_date' => now()->addDays(30),
                'verified_at' => now()->subDays(7),
            ],
            [
                'user_id' => $fundraiser2->id,
                'category_id' => $categories->where('slug', 'emergency')->first()->id,
                'title' => 'Flood Relief for Tana River Families',
                'story' => "Recent floods in Tana River County have displaced over 500 families, leaving them without food, shelter, or clean water. We are raising funds to provide emergency relief supplies including food hampers, blankets, mosquito nets, and water purification tablets to the affected families.",
                'target_amount' => 800000,
                'raised_amount' => 620000,
                'status' => CampaignStatus::Active,
                'expiry_date' => now()->addDays(14),
                'verified_at' => now()->subDays(2),
            ],
            [
                'user_id' => $fundraiser2->id,
                'category_id' => $categories->where('slug', 'business')->first()->id,
                'title' => 'Mama Mboga Market Expansion',
                'story' => "Mama Grace has been running a small vegetable stall in Kawangware for 10 years. She now has an opportunity to lease a larger space and expand her business to serve more customers. The funds will go towards shop rent, initial stock, and a basic cooling system to reduce food spoilage.",
                'target_amount' => 350000,
                'raised_amount' => 350000,
                'status' => CampaignStatus::Active,
                'expiry_date' => now()->addDays(20),
                'verified_at' => now()->subDays(5),
            ],
            [
                'user_id' => $fundraiser3->id,
                'category_id' => $categories->where('slug', 'funeral')->first()->id,
                'title' => 'Decent Burial for Mzee Jonah',
                'story' => "Mzee Jonah, a 78-year-old retired teacher, passed away last week. The family is struggling to raise funds for a dignified burial. We are appealing for contributions towards the funeral service, casket, transport, and refreshments for mourners. Help us give Mzee Jonah the send-off he deserves.",
                'target_amount' => 500000,
                'raised_amount' => 285000,
                'status' => CampaignStatus::Active,
                'expiry_date' => now()->addDays(10),
                'verified_at' => now()->subDays(1),
            ],
            [
                'user_id' => $fundraiser3->id,
                'category_id' => $categories->where('slug', 'community')->first()->id,
                'title' => 'Clean Water for Nyamira Village',
                'story' => "Nyamira Village lacks access to clean drinking water. Residents walk 5km daily to fetch water from a contaminated river. We are raising funds to drill a borehole, install a solar-powered pump, and build a water distribution point that will serve over 2,000 people.",
                'target_amount' => 1200000,
                'raised_amount' => 450000,
                'status' => CampaignStatus::Active,
                'expiry_date' => now()->addDays(60),
                'verified_at' => now()->subDays(4),
            ],
            [
                'user_id' => $fundraiser1->id,
                'category_id' => $categories->where('slug', 'wedding')->first()->id,
                'title' => 'Harambee for Kevin & Achieng Wedding',
                'story' => "Kevin and Achieng are finally tying the knot after 7 years together! Due to economic challenges, they need help to make their special day memorable. Contributions will cover the venue, catering, wedding attire, and photography. Let's celebrate love together!",
                'target_amount' => 400000,
                'raised_amount' => 180000,
                'status' => CampaignStatus::Active,
                'expiry_date' => now()->addDays(25),
                'verified_at' => now()->subDays(6),
            ],
            [
                'user_id' => $fundraiser2->id,
                'category_id' => $categories->where('slug', 'religious')->first()->id,
                'title' => 'Church Roof Renovation Fund',
                'story' => "Our local church in Kisumu has served the community for 40 years, but the roof is badly damaged during recent storms and leaks heavily during rains. We are raising funds to replace the roof and repair the ceiling before the next rainy season. The church also serves as a community hall for events.",
                'target_amount' => 900000,
                'raised_amount' => 320000,
                'status' => CampaignStatus::Active,
                'expiry_date' => now()->addDays(50),
                'verified_at' => now()->subDays(8),
            ],
        ];

        foreach ($campaignsData as $data) {
            $data['slug'] = Str::slug($data['title']) . '-' . uniqid();
            Campaign::create($data);
        }

        $campaignIds = Campaign::pluck('id')->toArray();
        $donationMethods = ['mpesa', 'mpesa', 'mpesa', 'card', 'airtel'];

        foreach ($campaignIds as $campaignId) {
            $numDonations = rand(5, 15);
            $donorPool = $donors;

            for ($i = 0; $i < $numDonations; $i++) {
                $donor = $donorPool[array_rand($donorPool)];
                $amount = fake()->randomElement([200, 500, 1000, 2000, 5000, 10000, 20000]);
                $method = $donationMethods[array_rand($donationMethods)];
                $fee = round($amount * 0.05, 2);
                $netAmount = $amount - $fee;

                Donation::create([
                    'campaign_id' => $campaignId,
                    'user_id' => $donor->id,
                    'donor_name' => $donor->name,
                    'donor_email' => $donor->email,
                    'donor_phone' => $donor->phone,
                    'amount' => $amount,
                    'fee' => $fee,
                    'net_amount' => $netAmount,
                    'payment_method' => $method,
                    'payment_ref' => strtoupper(uniqid('TXN-')),
                    'status' => 'completed',
                    'created_at' => fake()->dateTimeBetween('-30 days', 'now'),
                ]);
            }
        }
    }
}
