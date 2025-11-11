<x-landing-page.news>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Main Content Section -->
    <section class="flex flex-col items-center min-h-screen pb-2 mt-10 lg:mt-24">
        <div class="container flex flex-col justify-center p-4 mx-auto md:p-10">
            <div class="flex flex-col gap-8 sm:px-8 lg:px-50 xl:px-90">

                <!-- Main News Item -->
                <div class="p-5 bg-white rounded-lg shadow-sm">
                    <div style="flex:1 1 auto;padding:var(--bs-card-spacer-y) var(--bs-card-spacer-x);color:var(--bs-card-color)">
                        <h2 class="text-3xl font-bold text-center text-gray-800">{{ $news->title }}</h2>
                        <div class="flex items-center justify-center gap-2 mt-4 text-sm text-gray-500">
                            <p class="text-gray-500">
                                {{ \Carbon\Carbon::parse($news->created_at)->format('M d, Y') }}
                            </p>
                            <span class="flex items-center justify-center gap-1"style="margin-top: -17px;">
                                <svg class="w-4 h-4 text-amber-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-6 14h6m-7-5a2 2 0 0 1-4 0 2 2 0 0 1 4 0Zm9 0a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z" />
                                </svg>
                                {{ \Carbon\Carbon::parse($news->created_at)->diffForHumans() }}
                            </span>
                        </div>

                    <img src="{{ $news->image ? asset('storage/' . $news->image) : asset('no-image.jpg') }}" alt="{{ $news->title }}" class="object-cover w-full h-auto mt-6 rounded-lg shadow-lg">
                    <div class="p-4">
                        <p class="text-justify leading-relaxed text-dark" style="white-space: pre-wrap;">{!! $news->description !!}</p>
                    </div>
                </div>

                <!-- Other News Items -->
                <div class="mt-16">
                    <h3 class="text-2xl font-bold text-center">Berita Lainnya</h3>
                    <div class="flex items-center justify-center mt-4 text-center">
                        <a href="{{ route('news.index') }}" class="flex items-center text-lg font-medium text-blue-500 hover:text-blue-700 no-underline">
                            Lihat Semua Berita
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 ml-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                    <div class="grid grid-cols-1 gap-8 mt-8 sm:grid-cols-2 lg:grid-cols-3">
                        @if (isset($otherNews) && $otherNews->isNotEmpty())
                            @foreach ($otherNews as $otherNewsItem)
                            <div class="flex flex-col items-center p-6 transition-transform bg-white border rounded-lg shadow-sm hover:shadow-md hover:-translate-y-1">
                                    <a href="{{ route('news.show', $otherNewsItem->id) }}" class="w-full text-left no-underline">
                                        <img src="{{ $otherNewsItem->image ? asset('storage/' . $otherNewsItem->image) : asset('no-image.jpg') }}" alt="{{ $otherNewsItem->title }}" class="object-cover w-full h-40 mb-4 rounded-lg">
                                        <h4 class="text-lg font-semibold text-gray-800">{{ \Illuminate\Support\Str::limit(strip_tags($otherNewsItem->title), 65) }}</h4>
                                        <div class="flex items-center gap-2 mt-2 text-sm text-gray-500">
                                            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($otherNewsItem->created_at)->format('d M Y') }}</p>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4 text-amber-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-6 14h6m-7-5a2 2 0 0 1-4 0 2 2 0 0 1 4 0Zm9 0a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z" />
                                                </svg>
                                                {{ \Carbon\Carbon::parse($otherNewsItem->created_at)->diffForHumans() }}
                                            </span>
                                        </div>
                                        <p class="mt-3 text-sm text-gray-600">{{ \Illuminate\Support\Str::limit(strip_tags($otherNewsItem->description), 120) }}</p>
                                        <div class="flex items-center justify-end mt-4">
                                            <a href="{{ route('news.show', ['id' => $otherNewsItem->id]) }}" class="flex items-center text-blue-600 hover:underline no-underline">
                                                Selengkapnya
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 ml-1">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        @else
                            <p class="text-center text-gray-500 col-span-full">Belum ada berita lainnya.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-landing-page.news>
