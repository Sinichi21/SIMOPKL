<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\SocialMediaLink;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * GUARD PALING AMAN:
         * - Artisan (optimize, migrate, key:generate) → AMAN
         * - Table belum ada → AMAN
         */
        if (
            app()->runningInConsole() ||
            !Schema::hasTable('social_media_links')
        ) {
            View::share([
                'socialMediaUrl' => [
                    'instagram' => (object)['url' => 'https://instagram.com/yourusername'],
                    'youtube'   => (object)['url' => 'https://youtube.com/channel/yourchannel'],
                ],
                'kontak' => [
                    'whatsapp' => (object)['url' => '081234567890'],
                    'email'    => (object)['url' => 'dummy@example.com'],
                ],
                'waLink' => null,
            ]);

            return;
        }

        /**
         * QUERY AMAN (HANYA JALAN JIKA TABLE ADA & WEB REQUEST)
         */
        $socialMedia = [
            'instagram' => SocialMediaLink::where('social_media', 'ig')->first()
                ?? (object)['url' => 'https://instagram.com/yourusername'],
            'youtube' => SocialMediaLink::where('social_media', 'yt')->first()
                ?? (object)['url' => 'https://youtube.com/channel/yourchannel'],
        ];

        $kontak = [
            'whatsapp' => SocialMediaLink::where('social_media', 'wa')->first()
                ?? (object)['url' => '081234567890'],
            'email' => SocialMediaLink::where('social_media', 'mail')->first()
                ?? (object)['url' => 'dummy@example.com'],
        ];

        $waLink = null;
        if (!empty($kontak['whatsapp']->url)) {
            $waLink = 'https://wa.me/+62' . preg_replace('/[^0-9]/', '', $kontak['whatsapp']->url);
        }

        View::share([
            'socialMediaUrl' => $socialMedia,
            'kontak' => $kontak,
            'waLink' => $waLink,
        ]);
    }
}
