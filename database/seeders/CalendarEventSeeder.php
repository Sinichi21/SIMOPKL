<?php

namespace Database\Seeders;

use App\Models\CalendarEvent;
use Illuminate\Database\Seeder;

class CalendarEventSeeder extends Seeder
{
    public function run()
    {
        CalendarEvent::create([
            'title' => 'Webinar Internasional Handling Climate Change (Climate Action)',
            'date' => '2024-07-04',
            'start_time' => '15:00:00',
            'end_time' => '16:30:00',
            'location' => 'Online',
            'description' => 'Diskusi tentang perubahan iklim dan tindakan yang bisa diambil.',
        ]);

        CalendarEvent::create([
            'title' => 'FACULTY ACADEMIC SYSTEM ORIENTATION',
            'date' => '2024-07-15',
            'start_time' => '00:00:00',
            'end_time' => '23:59:59',
            'location' => 'Villa 1000 Cisarua',
            'description' => 'Orientasi sistem akademik fakultas.',
        ]);

        CalendarEvent::create([
            'title' => 'Webinar Internasional Handling Climate Change (Climate Action)',
            'date' => '2024-07-04',
            'start_time' => '15:00:00',
            'end_time' => '16:30:00',
            'location' => 'Online',
            'description' => 'Diskusi tentang perubahan iklim dan tindakan yang bisa diambil.',
        ]);
        
    }
}

