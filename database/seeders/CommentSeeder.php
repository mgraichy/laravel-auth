<?php

namespace Database\Seeders;

use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $numberOfVideos = DB::table('videos')->select('id')->get()->count() + 1;
        $comments = [];
        for ($videoId = 1; $videoId < $numberOfVideos; $videoId++) {
            for ($numberCommentsPerVideo = 0; $numberCommentsPerVideo < 5; $numberCommentsPerVideo++) {
                $pic = random_int(1, 100);
                $comments[] = [
                    'video_id' => $videoId,
                    'name' => fake()->name(),
                    'comment' => fake()->paragraph(),
                    'picture' => "https://picsum.photos/id/$pic/100",
                    'created_at' => CarbonImmutable::now(),
                    'updated_at' => CarbonImmutable::now(),
                ];
            }
        }

        DB::table('comments')->insert($comments);
    }
}
