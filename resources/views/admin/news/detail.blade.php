<x-admin.layout>
    @section('css')
        <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    @endsection

    <section class="container py-6">
        <div class="row justify-content-center">
            <div class="mb-4 shadow-sm card">
                <div class="card-body">
                    <div class="mb-4 d-flex justify-content-end">
                        <a href="{{ route('news.berita', $news->id) }}" class="mr-2 btn btn-secondary">Kembali</a>
                        <a href="{{ route('news.edit', $news->id) }}" class="btn btn-primary">Edit</a>
                    </div>
                    <h2 class="text-center h3 text-dark">{{ $news->title }}</h2>
                    <div class="gap-2 mt-3 d-flex justify-content-center text-muted">
                        <p class="mb-0" style="margin-right: 10px;">
                            {{ \Carbon\Carbon::parse($news->created_at)->format('M d, Y') }}
                        </p>
                        <span class="d-flex align-items-center">
                            <i class="fas fa-clock text-secondary" style="font-size: 16px; margin-right: 4px;"></i>
                            {{ \Carbon\Carbon::parse($news->created_at)->diffForHumans() }}
                        </span>
                    </div>
                    <img src="{{ $news->image ? asset('storage/' . $news->image) : asset('no-image.jpg') }}"
                         alt="{{ $news->title }}"
                         class="mx-auto mt-4 rounded-lg shadow-sm d-block" style="max-height: 300px; object-fit: cover;">
                    <div class="mt-4">
                        <p class="text-justify lead text-dark">
                            {!! $news->description !!}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-admin.layout>
