<x-landing-page.layout>
            <style>
                .card-label {
                    display: inline-block;
                    padding: 0.25rem 0.75rem;
                    font-size: 0.75rem;
                    font-weight: 600;
                    border-radius: 0.375rem;
                }

                .card-label.green {
                    background-color: #d1fae5;
                    color: #065f46;
                }

                .card-label.blue {
                    background-color: #dbebfa;
                    color: #1e40af;
                }
            </style>
    <div class="mx-auto h-[20rem] w-full bg-cover bg-center bg-no-repeat relative mt-14 lg:mt-20"
        style="background-image: url('./assets/news.jpg')">
        <div class="absolute top-0 w-full h-full bg-black opacity-40"></div>
        <div class="container absolute flex flex-col items-center content-center justify-center p-4 mx-auto text-white -translate-x-1/2 -translate-y-1/2 left-1/2 top-1/2 md:py-28">
            <h1 class="text-5xl antialiased font-bold text-center md:text-6xl">Kalender Acara</h1>
        </div>
    </div>

    <!-- Filter and Search -->
    <section class="flex flex-col items-center min-h-screen pb-10 bg-gray-50">
        <div class="w-full max-w-3xl px-5 mt-10 lg:px-0">
            <div class="flex items-center mb-4 space-x-2">
                <button id="filterButton" class="px-5 py-3 text-white transition-all rounded shadow-md bg-amber-500 hover:bg-amber-600">
                    Filter
                </button>

                <form method="GET" action="{{ route('landing-page.calendar.index') }}" class="flex flex-1 space-x-2">
                    <input type="text" name="search" value="{{ request('search') }}" class="w-full px-4 py-2 border rounded" placeholder="Pencarian Judul Kalender Acara...">
                    <button type="submit" id="searchButton" class="px-5 py-3 text-white transition-all rounded shadow-md bg-amber-500 hover:bg-amber-600">
                        Search
                    </button>
                </form>
            </div>

            <div id="filterPanel" class="flex justify-center mb-4">
                <form method="GET" action="{{ route('landing-page.calendar.index') }}">
                    <select id="filterMonth" name="month" class="px-4 py-3 bg-white border rounded shadow-md">
                        <option value="">Pilih Bulan</option>
                        @foreach (range(1, 12) as $month)
                            <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $month)->format('F') }}
                            </option>
                        @endforeach
                    </select>
                    <input type="date" id="date" name="date" value="{{ request('date') }}" class="px-4 py-3 bg-white border rounded shadow-md" />
                    <button type="submit" class="hidden">Apply Filters</button>
                </form>
            </div>
        </div>

        <!-- Daftar Acara -->
        <div class="container p-6 mx-auto" id="calendar-list">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @php
                    $now = \Carbon\Carbon::now('Asia/Jakarta');
                    $today = $now->startOfDay();
                    $endOfYear = $today->copy()->endOfYear();
                    $startOfNextYear = $endOfYear->copy()->addDay();
                    $upcomingEnd = $today->copy()->endOfWeek();
                    $soonStart = $upcomingEnd->copy()->addDay();
                    $soonEnd = $soonStart->copy()->endOfWeek();
                @endphp

                @foreach ($calendarPagination  as $event)
                    @php
                        $eventDate = \Carbon\Carbon::parse($event->date);
                        $eventStartTime = \Carbon\Carbon::parse($event->date . ' ' . $event->start_time);
                        $eventEndTime = \Carbon\Carbon::parse($event->date . ' ' . $event->end_time);
                        $now = \Carbon\Carbon::now('Asia/Jakarta');
                    @endphp

                    <!-- Ongoing (Event that is happening now) -->
                    @if ($now->greaterThanOrEqualTo($eventStartTime) && $now->lessThanOrEqualTo($eventEndTime))
                        <div class="p-5 transition-all bg-white rounded-lg shadow-lg hover:shadow-2xl"
                            data-title="{{ strtolower($event->title) }}"
                            data-month="{{ $eventDate->format('m') }}"
                            data-date="{{ $eventDate->toDateString() }}"
                            class="calendar-item">
                            <span class="inline-block px-3 py-1 mb-3 text-sm font-medium text-red-800 bg-red-100 rounded">
                                Ongoing
                            </span>
                            <h3 class="text-xl font-semibold text-gray-800">{{ $event->title }}</h3>
                            <p class="mt-2 text-gray-500">
                                {{ \Carbon\Carbon::parse($event->date)->format('l, d M Y') }} |
                                {{ \Carbon\Carbon::parse($event->start_time)->format('g:i a') }} -
                                {{ \Carbon\Carbon::parse($event->end_time)->format('g:i a') }}
                            </p>
                            <p class="mt-2 text-gray-600">Lokasi : {{ $event->location }}</p>
                        </div>

                    <!-- Upcoming (Event happening this week) -->
                    @elseif ($eventDate->between($today, $upcomingEnd))
                        <div class="p-5 transition-all bg-white rounded-lg shadow-lg hover:shadow-2xl"
                            data-title="{{ strtolower($event->title) }}"
                            data-month="{{ $eventDate->format('m') }}"
                            data-date="{{ $eventDate->toDateString() }}"
                            class="calendar-item">
                            <span class="inline-block px-3 py-1 mb-3 text-sm font-medium text-green-800 bg-green-100 rounded">
                                Upcoming
                            </span>
                            <h3 class="text-xl font-semibold text-gray-800">{{ $event->title }}</h3>
                            <p class="mt-2 text-gray-500">
                                {{ \Carbon\Carbon::parse($event->date)->format('l, d M Y') }} |
                                {{ \Carbon\Carbon::parse($event->start_time)->format('g:i a') }} -
                                {{ \Carbon\Carbon::parse($event->end_time)->format('g:i a') }}
                                WIB
                            </p>
                            <p class="mt-2 text-gray-600">Lokasi : {{ $event->location }}</p>
                        </div>

                    <!-- Soon (Event happening next week) -->
                    @elseif ($eventDate->between($soonStart, $soonEnd))
                        <div class="p-5 transition-all bg-white rounded-lg shadow-lg hover:shadow-2xl"
                            data-title="{{ strtolower($event->title) }}"
                            data-month="{{ $eventDate->format('m') }}"
                            data-date="{{ $eventDate->toDateString() }}"
                            class="calendar-item">
                            <span class="inline-block px-3 py-1 mb-3 text-sm font-medium text-blue-800 bg-blue-100 rounded">
                                Soon
                            </span>
                            <h3 class="text-xl font-semibold text-gray-800">{{ $event->title }}</h3>
                            <p class="mt-2 text-gray-500">
                                {{ \Carbon\Carbon::parse($event->date)->format('l, d M Y') }} |
                                {{ \Carbon\Carbon::parse($event->start_time)->format('g:i a') }} -
                                {{ \Carbon\Carbon::parse($event->end_time)->format('g:i a') }}
                                WIB
                            </p>
                            <p class="mt-2 text-gray-600">Lokasi : {{ $event->location }}</p>
                        </div>

                    <!-- Scheduled (Event happening in the future beyond next week or next year) -->
                    @elseif ($eventDate->greaterThan($soonEnd))
                        <div class="p-5 transition-all bg-white rounded-lg shadow-lg hover:shadow-2xl"
                            data-title="{{ strtolower($event->title) }}"
                            data-month="{{ $eventDate->format('m') }}"
                            data-date="{{ $eventDate->toDateString() }}"
                            class="calendar-item">
                            <span class="inline-block px-3 py-1 mb-3 text-sm font-medium text-blue-800 bg-blue-100 rounded">
                                Soon
                            </span>
                            <h3 class="text-xl font-semibold text-gray-800">{{ $event->title }}</h3>
                            <p class="mt-2 text-gray-500">
                                {{ \Carbon\Carbon::parse($event->date)->format('l, d M Y') }} |
                                {{ \Carbon\Carbon::parse($event->start_time)->format('g:i a') }} -
                                {{ \Carbon\Carbon::parse($event->end_time)->format('g:i a') }}
                                WIB
                            </p>
                            <p class="mt-2 text-gray-600">Lokasi : {{ $event->location }}</p>
                        </div>

                    <!-- Past (Event in the past) -->
                    @elseif ($eventDate->isPast())
                        <div class="p-5 transition-all bg-white rounded-lg shadow-lg hover:shadow-2xl"
                            data-title="{{ strtolower($event->title) }}"
                            data-month="{{ $eventDate->format('m') }}"
                            data-date="{{ $eventDate->toDateString() }}"
                            class="calendar-item">
                            <span class="inline-block px-3 py-1 mb-3 text-sm font-medium text-gray-800 bg-gray-100 rounded">
                                Scheduled
                            </span>
                            <h3 class="text-xl font-semibold text-gray-800">{{ $event->title }}</h3>
                            <p class="mt-2 text-gray-500">
                                {{ \Carbon\Carbon::parse($event->date)->format('l, d M Y') }} |
                                {{ \Carbon\Carbon::parse($event->start_time)->format('g:i a') }} -
                                {{ \Carbon\Carbon::parse($event->end_time)->format('g:i a') }}
                                WIB
                            </p>
                            <p class="mt-2 text-gray-600">Lokasi : {{ $event->location }}</p>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $calendarPagination->links() }}
            </div>
        </div>
    </section>

    @section('script')
        <script>
            $(document).ready(function() {
                if ($('#month').val() || $('#filterMonth').val() || $('#date').val()) {
                    $('#filterPanel').show();
                } else {
                    $('#filterPanel').hide();
                }

                $('#filterButton').on('click', function() {
                    $('#filterPanel').toggle();
                });

                $('#month, #date').on('change', function() {
                    $(this).closest('form').submit();
                });
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.getElementById('filterMonth').addEventListener('change', function () {
                    this.form.submit();
                });

                document.getElementById('date').addEventListener('change', function () {
                    this.form.submit();
                });
            });

            const searchInput = document.getElementById('search');
            searchInput.addEventListener('input', function () {
                const searchValue = searchInput.value.toLowerCase();
                const calendarItems = document.querySelectorAll('.calendar-item');

                calendarItems.forEach(item => {
                    const title = item.getAttribute('data-title').toLowerCase();

                    if (title.includes(searchValue)) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        </script>
    @endsection
</x-landing-page.layout>
