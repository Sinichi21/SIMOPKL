<x-admin.layout>
    @section('css')
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <style>
        .card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .card-body {
            flex-grow: 1;
        }
        .button {
            margin-top: auto;
            display: flex;
            justify-content: flex-end;
        }
    </style>
    @endsection

    <div class="mb-4 shadow card">
        <div class="card-body">
            <div class="text-right">
                <h6>{{ \Carbon\Carbon::now()->format('d-m-Y') }}</h6>
            </div>

            <div class="mb-4 d-flex justify-content-between align-items-center">
                <div>
                    <h3>Acara Kalender</h3>
                </div>
                <div class="mb-0 form-group">
                    <label for="search">Search :</label>
                    <input type="text" id="search" class="form-control d-inline" style="width: 250px;" placeholder="Cari judul...">
                </div>
            </div>

            <div class="mb-4 row">
                <div class="col-md-8">
                    <div class="form-inline">
                        <div class="mr-2 form-group">
                            <label for="month" class="mr-2">Bulan :</label>
                            <select id="month" class="form-control">
                                <option value="">Semua</option>
                                @foreach (range(1, 12) as $month)
                                    <option value="{{ $month }}">{{ DateTime::createFromFormat('!m', $month)->format('F') }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mr-2 form-group">
                            <label for="date" class="mr-2">Tanggal :</label>
                            <input type="date" id="date" class="form-control">
                        </div>
                        <button id="resetFilters" class="btn btn-secondary">Reset</button>
                    </div>
                </div>
                <div class="text-right col-md-4">
                    <a href="{{ route('calendar.create') }}" class="btn btn-primary">Tambah Acara</a>
                </div>
            </div>

            <div class="row" id="calendar-list">
                @php
                    $now = \Carbon\Carbon::now('Asia/Jakarta');
                    $today = $now->startOfDay();
                    $endOfYear = $today->copy()->endOfYear();
                    $startOfNextYear = $endOfYear->copy()->addDay();
                    $upcomingEnd = $today->copy()->endOfWeek();
                    $soonStart = $upcomingEnd->copy()->addDay();
                    $soonEnd = $soonStart->copy()->endOfWeek();
                @endphp

                @foreach ($calendarPagination as $event)
                    @php
                        $eventDate = \Carbon\Carbon::parse($event->date);
                        $eventStartTime = \Carbon\Carbon::parse($event->date . ' ' . $event->start_time);
                        $eventEndTime = \Carbon\Carbon::parse($event->date . ' ' . $event->end_time);
                        $now = \Carbon\Carbon::now('Asia/Jakarta');
                    @endphp

                    <!-- Ongoing (Event that is happening now) -->
                    @if ($now->greaterThanOrEqualTo($eventStartTime) && $now->lessThanOrEqualTo($eventEndTime))
                        <div class="mb-4 col-md-4 calendar-item" data-title="{{ strtolower($event->title) }}" data-month="{{ $eventDate->format('m') }}" data-date="{{ $eventDate->toDateString() }}">
                            <div class="p-4 shadow card">
                                <span class="mb-3 text-center rounded badge"
                                    style="color: #991b1b; background-color: #fee2e2; max-width: 95px; font-size: 15px;">
                                    Ongoing
                                </span>
                                <h2 class="mb-2 text-xl font-semibold text-gray-800">{{ $eventDate->format('d M Y') }}</h2>
                                <p class="mb-2 text-lg font-medium text-gray-900">{{ $event->title }}</p>
                                <p class="mb-2 text-gray-600">{{ $eventStartTime->format('g:i a') }} - {{ $eventEndTime->format('g:i a') }} WIB</p>
                                <p class="mb-4 text-gray-600">Lokasi : {{ $event->location }}</p>
                                <div class="button">
                                    <a href="{{ route('calendar.edit', $event->id) }}" class="mr-2 btn btn-primary">Edit</a>
                                    <button class="btn btn-danger btn-delete" data-id="{{ $event->id }}">Delete</button>
                                </div>
                            </div>
                        </div>

                    <!-- Upcoming (Event happening this week) -->
                    @elseif ($eventDate->between($today, $upcomingEnd))
                        <div class="mb-4 col-md-4 calendar-item" data-title="{{ strtolower($event->title) }}" data-month="{{ $eventDate->format('m') }}" data-date="{{ $eventDate->toDateString() }}">
                            <div class="p-4 shadow card">
                                <span class="mb-3 text-center rounded badge"
                                    style="color: #166534; background-color: #dcfce7; max-width: 95px; font-size: 15px;">
                                    Upcoming
                                </span>
                                <h2 class="mb-2 text-xl font-semibold text-gray-800">{{ $eventDate->format('d M Y') }}</h2>
                                <p class="mb-2 text-lg font-medium text-gray-900">{{ $event->title }}</p>
                                <p class="mb-2 text-gray-600">{{ $eventStartTime->format('g:i a') }} - {{ $eventEndTime->format('g:i a') }} WIB</p>
                                <p class="mb-4 text-gray-600">Lokasi : {{ $event->location }}</p>
                                <div class="button">
                                    <a href="{{ route('calendar.edit', $event->id) }}" class="mr-2 btn btn-primary">Edit</a>
                                    <button class="btn btn-danger btn-delete" data-id="{{ $event->id }}">Delete</button>
                                </div>
                            </div>
                        </div>

                    <!-- Soon (Event happening next week) -->
                    @elseif ($eventDate->between($soonStart, $soonEnd))
                        <div class="mb-4 col-md-4 calendar-item" data-title="{{ strtolower($event->title) }}" data-month="{{ $eventDate->format('m') }}" data-date="{{ $eventDate->toDateString() }}">
                            <div class="p-4 shadow card">
                                <span class="mb-3 text-center rounded badge"
                                    style="color: #1e40af; background-color: #dbeafe; max-width: 60px; font-size: 15px;">
                                    Soon
                                </span>
                                <h2 class="mb-2 text-xl font-semibold text-gray-800">{{ $eventDate->format('d M Y') }}</h2>
                                <p class="mb-2 text-lg font-medium text-gray-900">{{ $event->title }}</p>
                                <p class="mb-2 text-gray-600">{{ $eventStartTime->format('g:i a') }} - {{ $eventEndTime->format('g:i a') }} WIB</p>
                                <p class="mb-4 text-gray-600">Lokasi : {{ $event->location }}</p>
                                <div class="button">
                                    <a href="{{ route('calendar.edit', $event->id) }}" class="mr-2 btn btn-primary">Edit</a>
                                    <button class="btn btn-danger btn-delete" data-id="{{ $event->id }}">Delete</button>
                                </div>
                            </div>
                        </div>

                    <!-- Scheduled (Event happening in the future beyond next week or next year) -->
                    @elseif ($eventDate->greaterThan($soonEnd))
                        <div class="mb-4 col-md-4 calendar-item" data-title="{{ strtolower($event->title) }}" data-month="{{ $eventDate->format('m') }}" data-date="{{ $eventDate->toDateString() }}">
                            <div class="p-4 shadow card">
                                <span class="mb-3 text-center rounded badge"
                                    style="color: #1e40af; background-color: #dbeafe; max-width: 60px; font-size: 15px;">
                                    Soon
                                </span>
                                <h2 class="mb-2 text-xl font-semibold text-gray-800">{{ $eventDate->format('d M Y') }}</h2>
                                <p class="mb-2 text-lg font-medium text-gray-900">{{ $event->title }}</p>
                                <p class="mb-2 text-gray-600">{{ $eventStartTime->format('g:i a') }} - {{ $eventEndTime->format('g:i a') }} WIB</p>
                                <p class="mb-4 text-gray-600">Lokasi : {{ $event->location }}</p>
                                <div class="button">
                                    <a href="{{ route('calendar.edit', $event->id) }}" class="mr-2 btn btn-primary">Edit</a>
                                    <button class="btn btn-danger btn-delete" data-id="{{ $event->id }}">Delete</button>
                                </div>
                            </div>
                        </div>

                    <!-- Past (Event in the past) -->
                    @elseif ($eventDate->isPast())
                        <div class="mb-4 col-md-4 calendar-item" data-title="{{ strtolower($event->title) }}" data-month="{{ $eventDate->format('m') }}" data-date="{{ $eventDate->toDateString() }}">
                            <div class="p-4 shadow card">
                                <span class="mb-3 text-center rounded badge"
                                    style="color: #1e293b; background-color: #f3f4f6; max-width: 100px; font-size: 15px;">
                                    Scheduled
                                </span>
                                <h2 class="mb-2 text-xl font-semibold text-gray-800">{{ $eventDate->format('d M Y') }}</h2>
                                <p class="mb-2 text-lg font-medium text-gray-900">{{ $event->title }}</p>
                                <p class="mb-2 text-gray-600">{{ $eventStartTime->format('g:i a') }} - {{ $eventEndTime->format('g:i a') }} WIB</p>
                                <p class="mb-4 text-gray-600">Lokasi : {{ $event->location }}</p>
                                <div class="button">
                                    <a href="{{ route('calendar.edit', $event->id) }}" class="mr-2 btn btn-primary">Edit</a>
                                    <button class="btn btn-danger btn-delete" data-id="{{ $event->id }}">Delete</button>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="mt-4 d-flex justify-content-between align-items-center">
                <div class="text-left">
                    {{ $paginationText }}
                </div>
                <div class="text-right">
                    {{ $calendarPagination->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>

    @section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('search');
            const monthFilter = document.getElementById('month');
            const dateFilter = document.getElementById('date');
            const resetButton = document.getElementById('resetFilters');
            const calendarItems = document.querySelectorAll('.calendar-item');

            function padZero(value) {
                return value.toString().padStart(2, '0');
            }

            function filterItems() {
                const searchQuery = searchInput.value.toLowerCase();
                const selectedMonth = monthFilter.value ? padZero(monthFilter.value) : '';
                const selectedDate = dateFilter.value || '';

                calendarItems.forEach(item => {
                    const title = item.getAttribute('data-title').toLowerCase();
                    const month = item.getAttribute('data-month');
                    const date = item.getAttribute('data-date');

                    const matchesTitle = title.includes(searchQuery);
                    const matchesMonth = selectedMonth === "" || month === selectedMonth;
                    const matchesDate = selectedDate === "" || date === selectedDate;

                    if (matchesTitle && matchesMonth && matchesDate) {
                        item.style.display = "block";
                    } else {
                        item.style.display = "none";
                    }
                });
            }

            searchInput.addEventListener('input', filterItems);
            monthFilter.addEventListener('change', filterItems);
            dateFilter.addEventListener('change', filterItems);

            resetButton.addEventListener('click', function () {
                searchInput.value = '';
                monthFilter.value = '';
                dateFilter.value = '';
                filterItems();
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.btn-delete').click(function() {
                var btn = $(this);
                var id = btn.data('id');

                Swal.fire({
                    title: "Apakah Anda yakin?",
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, hapus!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('calendar.delete', ':id') }}".replace(':id', id),
                            type: 'POST',
                            data: {
                                _method: 'DELETE',
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Data telah berhasil dihapus.",
                                    icon: "success"
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                console.log(xhr);
                                Swal.fire({
                                    title: "Error!",
                                    text: "Terjadi kesalahan saat menghapus data.",
                                    icon: "error"
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
    @endsection
</x-admin.layout>
