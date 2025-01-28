<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Job;
use App\Models\User;

class BookmarkSeeder extends Seeder
{
    public function run(): void
    {
        $testUser = User::where('email', 'test@test.com')->firstOrFail();
        $jobIds = Job::pluck('id')->toArray();
        $randomJobIds = array_rand($jobIds, 3);

        foreach ($randomJobIds as $jobId) {
            $testUser->bookmarkedJobs()->attach($jobIds[$jobId]);
        }
    }
}
