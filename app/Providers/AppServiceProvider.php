<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\SocialMediaLink;
use Illuminate\Auth\Passwords\PasswordBroker as IlluminatePasswordBroker;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\YourResetPasswordMail;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // $this->app->singleton('auth.password', function ($app) {
        //     return new PasswordBrokerManager($app);
        // });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Ambil URL Instagram dari database
        $socialMedia = [
            'instagram' => SocialMediaLink::where('social_media', 'ig')->first() ?? (object)['url' => 'https://instagram.com/yourusername'],
            'youtube' => SocialMediaLink::where('social_media', 'yt')->first() ?? (object)['url' => 'https://youtube.com/channel/yourchannel'],
        ];

        $kontak = [
            'whatsapp' => SocialMediaLink::where('social_media', 'wa')->first() ?? (object)['url' => '081234567890'],
            'email' => SocialMediaLink::where('social_media', 'mail')->first() ?? (object)['url' => 'dummy@example.com'],
        ];

        if (isset($kontak['whatsapp'])) {
            $whatsapplink = $kontak['whatsapp']->url !== 'dummy_whatsapp_link' ? 
                     "https://wa.me/+62" . preg_replace('/[^1-9+]/', '', $kontak['whatsapp']->url) : 
                     null;
        } else {
            // Handle the case when WhatsApp link is not found
            $whatsapplink = null;
        }

        // Bagikan data URL ke semua view
        View::share([
            'socialMediaUrl' => $socialMedia,
            'kontak' => $kontak,
            'waLink' => $whatsapplink
        ]);
    }
}
