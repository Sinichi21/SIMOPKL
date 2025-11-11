<!-- readmore.blade.php -->
<div class="container mx-auto p-4">
    <div class="bg-white shadow-md rounded-lg p-6">
        <!-- Gambar Berita -->
        <img src="{{ $newsItem->image_url ? asset('storage/' . $newsItem->image_url) : asset('no-image.jpg') }}"
             alt="{{ $newsItem->title }}"
             class="object-cover w-full h-64 mb-4 rounded-lg">

        <!-- Judul Berita -->
        <h1 class="text-3xl font-bold mb-4">{{ $newsItem->title }}</h1>

        <!-- Tanggal Berita -->
        <p class="text-gray-500 mb-4">{{ \Carbon\Carbon::parse($newsItem->created_at)->format('M d, Y') }}</p>

        <!-- Deskripsi Lengkap -->
        <p class="text-gray-700">{{ $newsItem->description }}</p>

        <!-- Tombol Kembali ke Daftar Berita -->
        <div class="mt-4">
            <a href="{{ route('news.index') }}" class="text-blue-500 hover:text-blue-700">Kembali ke Daftar Berita</a>
        </div>
    </div>
</div>
