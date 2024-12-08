<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $joblistings = include database_path('seeders\data\job_listings.php');
        $userIds = User::pluck('id')->toArray();

        foreach ($joblistings as &$listing) {
            $listing['user_id'] = $userIds[array_rand($userIds)];
            $listing['created_at'] = now();
            $listing['updated_at'] = now();
        }

        DB::table('job_listings')->insert($joblistings);
        echo 'Jobs created';
    }
}
