<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\SocialMediaLink;
use Illuminate\Database\Seeder;

class SocialMediaLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SocialMediaLink::create([
            'url' => '08123456789',
            'social_media' => 'wa',
        ]);
        SocialMediaLink::create([
            'url' => 'bpi@ui.ac.id',
            'social_media' => 'mail',
        ]);
        SocialMediaLink::create([
            'url' => 'https://www.youtube.com/@bpiui',
            'social_media' => 'yt',
        ]);
        SocialMediaLink::create([
            'url' => 'https://www.instagram.com/bpi.ui/',
            'social_media' => 'ig',
        ]);
    }
}
