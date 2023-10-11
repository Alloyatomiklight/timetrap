<?php

namespace Database\Seeders;

use App\Models\TimeTracking;
use Illuminate\Database\Seeder;

class TimeTrackingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TimeTracking::create([
            'user_id' => 1,
            'start_time' => now()->subHours(2),
            'end_time' => now()->subHour(),
        ]);

        TimeTracking::create([
            'user_id' => 1,
            'start_time' => now()->subHours(4),
            'end_time' => now()->subHours(3),
        ]);

        TimeTracking::create([
            'user_id' => 2,
            'start_time' => now()->subHours(1),
            'end_time' => now(),
        ]);
    }
}
