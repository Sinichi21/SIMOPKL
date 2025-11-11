<x-landing-page.layout>

    <div class="relative mt-24 lg:mt-32">
        <div class="mx-auto h-[5em] w-full bg-cover bg-center bg-no-repeat relative">
            <div class="absolute top-0 w-full h-full my-auto"></div>
            <div class="container relative z-10 flex flex-col items-center justify-center p-4 mx-auto text-black">
                <h1 class="text-4xl antialiased font-bold text-center md:text-5xl">
                    Awardee BPI UI
                </h1>
            </div>
        </div>
    </div>

    <section class="flex flex-col items-center min-h-screen pb-10">
        <div class="w-full max-w-3xl px-5 mt-10 lg:px-0">
            <div class="flex items-center mb-4 space-x-2">
                <button id="filterButton" class="px-4 py-2 text-white rounded bg-amber-500 hover:bg-amber-600 focus:ring-4 focus:outline-none focus:ring-yellow-300">Filter</button>
                <input type="text" id="search" class="w-full px-4 py-2 border rounded" placeholder="Pencarian Nama Awardee...">
                <button id="searchButton" class="px-4 py-2 text-white rounded bg-amber-500 hover:bg-amber-600 focus:ring-4 focus:outline-none focus:ring-yellow-300">Search</button>
            </div>
            <div id="filterPanel" class="hidden">
                <div class="mb-4">
                    <label for="year" class="block mb-1">Tahun:</label>
                    <select id="year" class="w-full px-4 py-2 border rounded">
                        <option value="">All</option>
                        @foreach ($years as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="faculty" class="block mb-1">Fakultas:</label>
                    <select id="faculty" class="w-full px-4 py-2 border rounded">
                        <option value="">All</option>
                        @foreach ($faculties as $faculty)
                            <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div id="userList" class="container flex flex-col justify-center p-4 mx-auto md:p-8">
            @include('landing-page.awardee.partials.users', ['users' => $users])
        </div>
    </section>

    @section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#filterButton').on('click', function() {
                $('#filterPanel').toggle();
            });

            $('#searchButton').on('click', function() {
                let query = $('#search').val();
                let year = $('#year').val();
                let faculty = $('#faculty').val();

                $.ajax({
                    url: "{{ route('landing-page.awardee.search') }}",
                    method: "GET",
                    data: {
                        search: query,
                        year: year,
                        faculty: faculty
                    },
                    success: function(data) {
                        $('#userList').html(data.users);
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            });
        });
    </script>
    @endsection

</x-landing-page.layout>
