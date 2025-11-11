<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\About;
use App\Models\Awardee;
use App\Models\Faq;
use App\Models\MenuIcon;
use App\Models\SocialMediaLink;
use App\Models\slideshowImage;
use App\Models\User;
use App\Models\News;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\Log;
use App\Models\CalendarEvent;
use Illuminate\Support\Carbon;
use App\Models\CarouselImage;
use Illuminate\Support\Facades\Log;

class LandingPageController extends Controller
{

    //User Landing Page
    public function index()
    {
        $carouselImages = slideshowImage::all();
        $Images = [
            'awardee' => MenuIcon::where('menu', 'Awardee')->first(),
            'faq' => MenuIcon::where('menu', 'FaQ')->first(),
            'pengaduan' => MenuIcon::where('menu', 'Pengaduan')->first(),
            'gallery' => MenuIcon::where('menu', 'Gallery')->first(),

        ];
        $socialMedia = [
            'instagram' => SocialMediaLink::where('social_media', 'ig')->first(),
            'youtube' => SocialMediaLink::where('social_media', 'yt')->first(),
        ];
        $kontak = [
            'whatsapp' => SocialMediaLink::where('social_media', 'wa')->first(),
            'email' => SocialMediaLink::where('social_media', 'mail')->first(),
        ];
        $about = About::first();

        $users = Awardee::all();

        $news = News::all();

        // $isAdmin = $request->user()->isAdmin(); // Assuming you have a method to check if the user is an admin

        $totalUsers = User::where('role_id', '!=', 1) ->whereHas('awardee') ->where('is_registered', '=', 1)->where('users.status', '=', 1)->count();

        $totalFaq = Faq::where('status', 'publish')->get()->count();

        $whatsapplink = $kontak['whatsapp']->url;
        $whatsapplink = "https://wa.me/+62" . preg_replace('/[^1-9+]/', '', $whatsapplink);

        $news = News::latest()->take(4)->get();

        // calendar
        $now = Carbon::now();
        $today = $now->startOfDay();
        $endOfYear = $today->copy()->endOfYear();
        $startOfNextYear = $endOfYear->copy()->addDay();
        $upcomingEnd = $today->copy()->endOfWeek();
        $soonStart = $upcomingEnd->copy()->addDay();
        $soonEnd = $soonStart->copy()->endOfWeek();

        $calendar = CalendarEvent::all();

        $ongoing = [];
        $upcoming = [];
        $soon = [];
        $future = [];
        $scheduled = [];

        foreach ($calendar as $event) {
            $eventDate = Carbon::parse($event->date);
            $eventStartTime = Carbon::parse($event->start_time);
            $eventEndTime = Carbon::parse($event->end_time);

            if ($now->between($eventStartTime, $eventEndTime)) {
                $ongoing[] = $event;
            }
            // Upcoming events (this week)
            elseif ($eventDate->between($today, $upcomingEnd)) {
                $upcoming[] = $event;
            }
            // Soon events (next week)
            elseif ($eventDate->between($soonStart, $soonEnd)) {
                $soon[] = $event;
            }
            // Future events (beyond next week)
            elseif ($eventDate->greaterThan($soonEnd)) {
                $future[] = $event;
            }
            // Scheduled events (events in the past)
            elseif ($eventDate->isPast()) {
                $scheduled[] = $event;
            }
        }

        $sortedEvents = array_merge($ongoing, $upcoming, $soon, $future, $scheduled);

        // return view('landingpage.index', compact('news'));

        return view('landing-page.index')->with([
            'carouselImages' => $carouselImages,
            'Image' => $Images,
            'socialMedia' => $socialMedia,
            'kontak' => $kontak,
            'about' => $about,
            'waLink' => $whatsapplink,
            'totaluser' => $totalUsers,
            'totalFaq' => $totalFaq,
            'news' => $news,
            'sortedEvents' => $sortedEvents,
        ]);
    }

    //Carolusel Section
    public function caroluselindex()
    {
        $carouselImages = slideshowImage::all();
        return view('admin.landingPage.carolusel', compact('carouselImages'));
    }

    public function caroluselupload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        $image = $request->file('image');
        $path = $image->store('carousel', 'public');

        return response()->json(['url' => $path]);
    }

    public function caroluselsave(Request $request)
    {
        // \Log::info('Request data: ', $request->all());

        $urls = $request->input('urls');

        foreach ($urls as $id => $url) {
            // \Log::info("Processing URL for ID: $id, URL: $url");

            $image = slideshowImage::find($id);
            if ($image) {
                $image->url = $url;
                $image->save();
                // \Log::info("Image updated for ID: $id");
            } else {
                // \Log::info("Image not found for ID: $id");
            }
        }

        return response()->json(['success' => 'Images saved successfully.']);
    }

    //Icon Menu Section
    public function iconmenuindex()
    {
        $Images = MenuIcon::all();
        return view('admin.landingPage.iconmenu', compact('Images'));
    }

    public function iconmenuupload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        $image = $request->file('image');
        $path = $image->store('iconmenu', 'public');

        return response()->json(['url' => $path]);
    }

    public function iconmenusave(Request $request)
    {
        try {
            $urls = $request->input('urls');

            // \Log::info('Received URLs: ', $urls); // Log the received data

            foreach ($urls as $id => $url) {
                // \Log::info("Processing URL for ID: $id, URL: $url");

                $image = MenuIcon::find($id + 1);
                if ($image) {
                    $image->url = $url;
                    $image->save();
                    // \Log::info("Image updated for ID: $id");
                } else {
                    // \Log::info("Image not found for ID: $id");
                }
            }

            return response()->json(['success' => 'Images saved successfully.']);
        } catch (\Exception $e) {
            \Log::error('Failed to save images: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to save images'], 500);
        }
    }

    //Sosial Media Section
    public function sosialmediaindex()
    {
        $socialMedia = [
            'instagram' => SocialMediaLink::where('social_media', 'ig')->first(),
            'youtube' => SocialMediaLink::where('social_media', 'yt')->first(),
        ];

        return view('admin.landingPage.sosialmedia', compact('socialMedia'));
    }

    public function sosialmediasave(Request $request)
    {
        // Log::info('Request Data:', $request->all());

        $this->validate($request, [
            'instagram' => 'nullable|url',
            'youtube' => 'nullable|url',
        ]);

        try {
            // Periksa apakah ada input untuk Instagram
            if ($request->filled('instagram')) {
                SocialMediaLink::updateOrCreate(
                    ['social_media' => 'ig'],
                    ['url' => $request->input('instagram')]
                );
            }

            // Periksa apakah ada input untuk YouTube
            if ($request->filled('youtube')) {
                SocialMediaLink::updateOrCreate(
                    ['social_media' => 'yt'],
                    ['url' => $request->input('youtube')]
                );
            }

            return response()->json(['message' => 'Data media sosial berhasil disimpan'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }

    public function sosialmediacancel(Request $request)
    {
        try {
            // Ambil data media sosial dari database
            $socialMedia = [
                'instagram' => SocialMediaLink::where('social_media', 'ig')->first(),
                'youtube' => SocialMediaLink::where('social_media', 'yt')->first(),
            ];

            return response()->json(['socialMedia' => $socialMedia], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal membatalkan perubahan: ' . $e->getMessage()], 500);
        }
    }
    // Kontak Section
    public function kontakindex()
    {
        $kontak = [
            'whatsapp' => SocialMediaLink::where('social_media', 'wa')->first(),
            'email' => SocialMediaLink::where('social_media', 'mail')->first(),
        ];

        return view('admin.landingPage.kontak', compact('kontak'));
    }

    public function kontaksave(Request $request)
    {
        // Log::info('Request Data:', $request->all());

        $this->validate($request, [
            'whatsapp' => 'nullable|string',
            'email' => 'nullable|string',
        ]);

        try {
            // Periksa apakah ada input untuk Whatsapp
            if ($request->filled('whatsapp')) {
                SocialMediaLink::updateOrCreate(
                    ['social_media' => 'wa'],
                    ['url' => $request->input('whatsapp')]
                );
            }

            // Periksa apakah ada input untuk Email
            if ($request->filled('email')) {
                SocialMediaLink::updateOrCreate(
                    ['social_media' => 'mail'],
                    ['url' => $request->input('email')]
                );
            }

            return response()->json(['message' => 'Data media sosial berhasil disimpan'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }

    public function kontakcancel(Request $request)
    {
        try {
            // Ambil data media sosial dari database
            $kontak = [
                'whatsapp' => SocialMediaLink::where('social_media', 'wa')->first(),
                'email' => SocialMediaLink::where('social_media', 'mail')->first(),
            ];

            return response()->json(['kontak' => $kontak], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal membatalkan perubahan: ' . $e->getMessage()], 500);
        }
    }

    //About Section
    public function tentangindex()
    {
        $about = About::first();

        return view('admin.landingPage.tentang')->with('about', $about);
    }

    public function tentangedit(About $about)
    {
        return view('admin.landingPage.tentang')->with('about', $about);
    }

    public function tentangupdate(Request $request)
    {
        $request->validate([
            'answer' => ['required', 'string']
        ]);

        $about = About::first();

        $about->content = $request->answer;
        $about->save();

        return response()->json([
            'success' => true,
            'msg' => 'About berhasil diedit'
        ], 200);
    }
}
