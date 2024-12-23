<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
                'video' => 'bday-bot-540p.mp4',
                'views' => '930K',
                'comment' => fake()->paragraphs(3, true),
                'created_at' => CarbonImmutable::now(),
                'updated_at' => CarbonImmutable::now(),
            ],
            [
                'user_id' => $user->id,
                'client_id' => 1,
                'title' => 'A Small Town',
                'video' => 'town-540p.mp4',
                'views' => '32K',
                'comment' => fake()->paragraphs(3, true),
                'created_at' => CarbonImmutable::now(),
                'updated_at' => CarbonImmutable::now(),
            ],
            [
                'user_id' => $user->id,
                'client_id' => 1,
                'title' => 'Traffic',
                'video' => 'traffic-540p.mp4',
                'views' => '23K',
                'comment' => fake()->paragraphs(3, true),
                'created_at' => CarbonImmutable::now(),
                'updated_at' => CarbonImmutable::now(),
            ],
        ];

        return $videos;
    }
}
