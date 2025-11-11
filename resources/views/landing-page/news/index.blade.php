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
        <div
            class="container absolute flex flex-col items-center content-center justify-center p-4 mx-auto text-white -translate-x-1/2 -translate-y-1/2 left-1/2 top-1/2 md:py-28">
            <h1 class="text-5xl antialiased font-bold text-center md:text-6xl">Berita Terbaru</h1>
        </div>
    </div>

    <section class="flex flex-col items-center min-h-screen pb-10">
        <form action="{{ route('news.index') }}" method="GET" class="w-full max-w-md px-5 mt-10 lg:px-0">
            <label for="search-news" class="mb-2 text-sm font-medium text-gray-900 sr-only">Cari</label>
            <div class="relative">
                <div class="absolute inset-y-0 flex items-center pointer-events-none start-0 ps-3">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="search" id="search-news" name="search"
                    class="block w-full p-4 text-sm text-gray-900 border border-gray-300 rounded-lg ps-10 bg-gray-50 focus:ring-amber-500 focus:border-amber-500"
                    placeholder="Cari Berita..." value="{{ request('search') }}" />
                <button type="submit"
                    class="text-white absolute end-2.5 bottom-2.5 bg-amber-600 hover:bg-amber-700 focus:ring-4 focus:outline-none focus:ring-amber-300 font-medium rounded-lg text-sm px-4 py-2">
                    Search
                </button>
            </div>
        </form>

        <div class="container flex flex-col justify-center p-4 mx-auto md:p-8">
            <div class="grid grid-cols-1 gap-6 mt-8 sm:grid-cols-2 lg:grid-cols-3">
                @if ($news->isEmpty())
                    <p class="font-semibold text-center">Tidak ada berita yang ditemukan</p>
                @else
                @foreach ($news as $newsItem)
                    <div class="flex flex-col transition-transform bg-white border rounded-lg shadow hover:shadow-lg hover:-translate-y-2">
                        <a href="{{ route('news.show', $newsItem->id) }}" class="relative w-full">
                            <a href="{{ route('news.show', $newsItem->id) }}" class="relative w-full">
                            <img src="{{ $newsItem->image ? asset('storage/' . $newsItem->image) : asset('no-image.jpg') }}"
                                alt="{{ $newsItem->title }}"
                                class="object-cover w-full h-40 rounded-t-lg">
                        </a>
                        <div class="p-4">
                            <!-- Judul -->
                            <h4 class="text-lg font-semibold text-gray-800">
                                <a href="{{ route('news.show', $newsItem->id) }}" class="hover:underline">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($newsItem->title), 65) }}
                                </a>
                            </h4>
                            <div class="flex items-center gap-2 mt-2 text-sm text-gray-500">
                                <p class="text-gray-500 ">{{ \Carbon\Carbon::parse($newsItem->created_at)->format('M d, Y') }}</p>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-amber-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-6 14h6m-7-5a2 2 0 0 1-4 0 2 2 0 0 1 4 0Zm9 0a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z" />
                                    </svg>
                                    {{ \Carbon\Carbon::parse($newsItem->created_at)->diffForHumans() }}
                                </span>
                            </div>
                            <p class="mt-3 text-sm text-gray-600">
                                {{ \Illuminate\Support\Str::limit(strip_tags($newsItem->description), 120) }}
                            </p>
                            <div class="flex items-center justify-end mt-4">
                                <a href="{{ route('news.show', ['id' => $newsItem->id]) }}" class="flex items-center text-blue-600 hover:underline">
                                    Selengkapnya
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 ml-1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
                @endif
            </div>
        </div>

        <div class="mt-6">
            {{ $news->links() }}
        </div>
    </section>
</x-landing-page.layout>
