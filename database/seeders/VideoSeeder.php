<?php

namespace Database\Seeders;

use App\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sampleVideos = [
            [
                'id' => '7534567890123456789',
                'username' => 'dance_queen',
                'url' => 'https://www.tiktok.com/@dance_queen/video/7534567890123456789',
                'cover' => 'tiktok/7534567890123456789.jpeg',
                'downloads' => 25,
            ],
            [
                'id' => '7534567890123456790',
                'username' => 'comedy_king',
                'url' => 'https://www.tiktok.com/@comedy_king/video/7534567890123456790',
                'cover' => 'tiktok/7534567890123456790.jpeg',
                'downloads' => 18,
            ],
            [
                'id' => '7534567890123456791',
                'username' => 'food_lover',
                'url' => 'https://www.tiktok.com/@food_lover/video/7534567890123456791',
                'cover' => 'tiktok/7534567890123456791.jpeg',
                'downloads' => 32,
            ],
            [
                'id' => '7534567890123456792',
                'username' => 'travel_vibes',
                'url' => 'https://www.tiktok.com/@travel_vibes/video/7534567890123456792',
                'cover' => 'tiktok/7534567890123456792.jpeg',
                'downloads' => 15,
            ],
            [
                'id' => '7534567890123456793',
                'username' => 'fitness_guru',
                'url' => 'https://www.tiktok.com/@fitness_guru/video/7534567890123456793',
                'cover' => 'tiktok/7534567890123456793.jpeg',
                'downloads' => 22,
            ],
        ];

        foreach ($sampleVideos as $videoData) {
            Video::updateOrCreate(
                ['id' => $videoData['id']],
                $videoData
            );
        }
    }
}
