<x-landing-page.layout>

    <div class="mx-auto h-[8em] w-full bg-cover bg-center bg-no-repeat relative mt-14 lg:mt-20"">
        <div class=" absolute top-0 w-full h-full"></div>
    <div
        class="container flex flex-col flex-wrap content-center items-center left-1/2 -translate-x-1/2 top-1/2 -translate-y-1/2 justify-center p-4 mx-auto md:py-28 text-black absolute">
        <h1 class="text-4xl antialiased font-bold text-center md:text-5xl">
            Awardee BPI UI
        </h1>
    </div>
    </div>

    <section class="pb-10 min-h-screen flex flex-col items-center">
        <div class="max-w-md w-full px-5 lg:px-0 mt-10">
            <button id="filterButton" class="px-4 py-2 bg-blue-500 text-white rounded">Filter</button>
            <input type="text" id="search" class="ml-2 px-4 py-2 border rounded"
                placeholder="Pencarian Nama Awardee...">
            <div id="filterPanel" class="mb-4 hidden">
                <label for="year">Year:</label>
                <select id="year" class="ml-2 px-4 py-2 border rounded">
                    <option value="">All</option>
                    @foreach ($years as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
                <label for="faculty">Faculty:</label>
                <select id="faculty" class="ml-2 px-4 py-2 border rounded">
                    <option value="">All</option>
                    @foreach ($faculties as $faculty)
                    <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                    @endforeach
                </select>
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
            $('#search').on('keyup', function() {
                let query = $(this).val();

                $.ajax({
                    url: "{{ route('landing-page.awardee.search') }}", // Adjust the route name accordingly
                    method: "GET",
                    data: {
                        search: query
                    },
                    success: function(data) {
                        console.log(data);
                        $('#userList').html(data);
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
