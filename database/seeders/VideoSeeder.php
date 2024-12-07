<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'mg@example.com')->first();

        if (!$user) {
            $user = User::create([
                'name' => 'M Graichy',
                'email' => 'mg@example.com',
                'password' => Hash::make('password'),
            ]);
        }
        $videos = $this->createDbVids($user);
        DB::table('videos')->insert($videos);
    }

    protected function createDbVids($user): array
    {
        $videos = [
            [
                'user_id' => $user->id,
                'client_id' => 1,
                'title' => 'The Birthday Bot',
                // 'video' => 'https://www.youtube.com/embed/nNjaXpo7RP0?si=DJa4HMNH6tEu5TrJ',
                'video' => '/videos/bday-bot-540p.mp4',
                'views' => '930K',
                'comment' => fake()->paragraphs(3, true),
                'created_at' => '2024-03-16 17:37:05',
                'updated_at' => '2024-03-16 17:37:05'
            ],
            [
                'user_id' => $user->id,
                'client_id' => 1,
                'title' => 'A Small Town',
                // 'video' => 'https://www.youtube.com/embed/RzpQjPI0RfM?si=QprCAVHruSMA5-me',
                'video' => '/videos/town-540p.mp4',
                'views' => '32K',
                'comment' => fake()->paragraphs(3, true),
                'created_at' => '2024-03-16 17:37:05',
                'updated_at' => '2024-03-16 17:37:05'
            ],
            [
                'user_id' => $user->id,
                'client_id' => 1,
                'title' => 'Traffic',
                // 'video' => 'https://www.youtube.com/embed/T6Y3OcWMUdI?si=y9_RZH9V1TDXsAJ3',
                'video' => '/videos/traffic-540p.mp4',
                'views' => '23K',
                'comment' => fake()->paragraphs(3, true),
                'created_at' => '2024-03-16 17:37:05',
                'updated_at' => '2024-03-16 17:37:05'
            ]
        ];

        return $videos;
    }


}
