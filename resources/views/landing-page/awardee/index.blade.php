<x-landing-page.layout>
    @section('css')
        <style>
            .pagination .page-item {
                margin: 0 5px;
            }

            .pagination a {
                padding: 0.5rem;
                border-radius: 0.25rem;
                text-decoration: none;
            }

            .pagination .active {
                font-weight: bold;
            }
        </style>
    @endsection

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
                <button id="filterButton" class="px-4 py-2 text-white rounded bg-amber-500 hover:bg-amber-600 focus:ring-4 focus:outline-none focus:ring-yellow-300">
                    Filter
                </button>

                <form method="GET" action="{{ route('landing-page.awardee.index') }}" class="flex flex-1 space-x-2">
                    <input type="text" name="search" value="{{ request('search') }}" class="w-full px-4 py-2 border rounded" placeholder="Pencarian Nama Awardee...">
                    <button type="submit" id="searchButton" class="px-4 py-2 text-white rounded bg-amber-500 hover:bg-amber-600 focus:ring-4 focus:outline-none focus:ring-yellow-300">
                        Search
                    </button>
                </form>
            </div>

            <div id="filterPanel" class="mb-4">
                <form method="GET" action="{{ route('landing-page.awardee.index') }}">
                    <div class="mb-4">
                        <div class="mb-4">
                            <label for="year" class="block mb-1">Tahun:</label>
                            <select name="year" id="year" class="w-full px-4 py-2 border rounded">
                                <option value="">Semua</option>
                                @foreach ($years as $year)
                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="faculty" class="block mb-1">Fakultas:</label>
                            <select name="faculty" id="faculty" class="w-full px-4 py-2 border rounded">
                                <option value="">Semua</option>
                                @foreach ($faculties as $faculty)
                                    <option value="{{ $faculty->id }}" {{ request('faculty') == $faculty->id ? 'selected' : '' }}>{{ $faculty->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="hidden">Apply Filters</button>
                </form>
            </div>
        </div>

        <div id="userList" class="container flex flex-col justify-center p-4 mx-auto md:p-8">
            <div class="flex flex-wrap justify-center gap-4">
                @foreach ($users as $user)
                    <div class="text-center user-item" data-name="{{ $user->awardee->fullname }}" data-username="{{ $user->awardee->username }}" data-year="{{ $user->awardee->year }}" data-faculty="{{ $user->awardee->studyProgram->faculty }}">
                        <a href="{{ route('landing-page.awardee.show', ['user' => $user->id]) }}" class="inline-block">
                            <div class="mx-auto overflow-hidden rounded-full w-52 h-52">
                                <img src="{{ $user->pp_url ? asset('storage/' . $user->pp_url) : asset('img/undraw_profile.svg') }}"
                                    alt="{{ $user->fullname }}"
                                    class="object-cover w-full h-full">
                            </div>
                            <p class="mt-2">{{ $user->awardee->username }}</p>
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="mt-4 pagination">
                {{ $users->links() }}
            </div>
        </div>
    </section>

    @section('script')
    <script>
        $(document).ready(function() {
            if (!$('#year').val() && !$('#faculty').val()) {
                $('#filterPanel').hide();
            }

            $('#filterButton').on('click', function() {
                $('#filterPanel').toggle();
            });

            $('#year, #faculty').on('change', function() {
                $(this).closest('form').submit();
            });
        });

        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', function () {
            const searchValue = searchInput.value.toLowerCase();
            const userItems = document.querySelectorAll('.user-item');

            userItems.forEach(item => {
                const name = item.getAttribute('data-name').toLowerCase();
                const username = item.getAttribute('data-username').toLowerCase();
                const faculty = item.getAttribute('data-faculty').toLowerCase();
                const year = item.getAttribute('data-year').toLowerCase();

                if (name.includes(searchValue) || username.includes(searchValue) || faculty.includes(searchValue) || year.includes(searchValue)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    </script>
    @endsection
</x-landing-page.layout>
