<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $now = Carbon::now();
        $today = $now->startOfDay();
        $endOfYear = $today->copy()->endOfYear();
        $startOfNextYear = $endOfYear->copy()->addDay();
        $upcomingEnd = $today->copy()->endOfWeek();
        $soonStart = $upcomingEnd->copy()->addDay();
        $soonEnd = $soonStart->copy()->endOfWeek();

        $query = CalendarEvent::query();

        if ($request->filled('month')) {
            $query->whereMonth('date', $request->month);
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'LIKE', "%{$search}%");
        }

        $calendar = $query->get();

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
            elseif ($eventDate->between($today, $upcomingEnd)) {
                $upcoming[] = $event;
            }
            elseif ($eventDate->between($soonStart, $soonEnd)) {
                $soon[] = $event;
            }
            elseif ($eventDate->greaterThan($soonEnd)) {
                $future[] = $event;
            }
            elseif ($eventDate->isPast()) {
                $scheduled[] = $event;
            }
        }

        $sortedEvents = array_merge($ongoing, $upcoming, $soon, $future, $scheduled);

        $perPage = 9;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $sortedEventsPaginated = array_slice($sortedEvents, ($currentPage - 1) * $perPage, $perPage);

        $calendarPagination = new LengthAwarePaginator(
            $sortedEventsPaginated,
            count($sortedEvents),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        );

        return view('landing-page.calendar.index',[
            'calendarPagination' => $calendarPagination,
        ]);
    }

    public function indexAdmin()
    {
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

        $perPage = 9;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $sortedEventsPaginated = array_slice($sortedEvents, ($currentPage - 1) * $perPage, $perPage);

        $calendarPagination = new LengthAwarePaginator(
            $sortedEventsPaginated,
            count($sortedEvents),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        );

        $from = ($currentPage - 1) * $perPage + 1;
        $to = min($currentPage * $perPage, count($sortedEvents));
        $paginationText = "Showing $from to $to of " . count($sortedEvents) . " results";

        return view('admin.landingPage.kalender', compact('calendarPagination', 'paginationText'));
    }

    public function create()
    {
        return view('admin.calendar.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required', 'date', function ($attribute, $value, $fail) {
                if (Carbon::parse($value)->isBefore(Carbon::today())) {
                    $fail('Tanggal tidak boleh kurang dari hari ini.');
                }
            }],
            'title' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'location' => 'required|string|max:255',
        ], [
            'date.required' => 'Tanggal harus diisi.',
            'date.date' => 'Format tanggal tidak valid.',
            'start_time.date_format' => 'Format jam mulai harus HH:MM.',
            'end_time.date_format' => 'Format jam selesai harus HH:MM.',
        ]);

        CalendarEvent::create([
            'date' => $request->date,
            'title' => $request->title,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Berita berhasil disimpan'
        ], 201);
    }

    public function edit($id)
    {
        $event = CalendarEvent::findOrFail($id);
        return view('admin.calendar.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => ['required', 'date', function ($attribute, $value, $fail) {
                if (Carbon::parse($value)->isBefore(Carbon::today())) {
                    $fail('Tanggal tidak boleh kurang dari hari ini.');
                }
            }],
            'title' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'location' => 'required|string|max:255',
        ], [
            'date.required' => 'Tanggal harus diisi.',
            'date.date' => 'Format tanggal tidak valid.',
            'start_time.date_format' => 'Format jam mulai harus HH:MM.',
            'end_time.date_format' => 'Format jam selesai harus HH:MM.',
        ]);

        $event = CalendarEvent::findOrFail($id);

        $event->update([
            'date' => $request->input('date'),
            'title' => $request->input('title'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'location' => $request->input('location'),
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Berita berhasil diperbarui'
        ], 200);
    }

    public function delete($id)
    {
        $event = CalendarEvent::findOrFail($id);

        $event->delete();

        return redirect()->route('index.kalender')->with('success', 'Calendar deleted successfully.');
    }

    public function search(Request $request)
    {
        $query = Event::query();

        if ($request->has('year') && $request->year) {
            $query->whereYear('date', $request->year);
        }

        if ($request->has('month') && $request->month) {
            $query->whereMonth('date', $request->month);
        }

        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $events = $query->orderBy('date', 'asc')->get();

        return response()->json(['events' => view('partials.event-list', compact('events'))->render()]);
    }

}
