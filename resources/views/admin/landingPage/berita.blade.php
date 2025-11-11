<x-admin.layout>
    @section('css')
    <!-- Custom styles for this page -->
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <style>
        .card-img-wrapper {
            width: 100%;
            height: 200px;
            overflow: hidden;
        }

        .card-img-top {
            width: 100%;
            height: 100%;
            object-fit: cover;
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
                    <h3>Kelola Berita</h3>
                </div>
            </div>

            <div class="mb-4 row">
                <div class="col-md-8">
                    <label for="search">Search :</label>
                    <input type="text" id="search" class="form-control d-inline" style="width: 250px;" placeholder="Cari judul...">
                </div>
                <div class="text-right col-md-4">
                    <a href="{{ route('news.create') }}" class="btn btn-primary">Tambah Berita</a>
                </div>
            </div>

            <div class="row" id="news-container">
            @foreach ($news as $index => $item)
                <div class="mb-4 col-md-4 calendar-item"
                    data-title="{{ strtolower($item->title) }}"
                    data-month="{{ \Carbon\Carbon::parse($item->created_at)->format('m') }}"
                    data-date="{{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') }}">
                    <div class="shadow-sm card h-100">
                        <div class="card-img-wrapper">
                            @if ($item->image)
                                <img id="news-image-{{ $index }}"
                                     src="{{ asset('storage/' . $item->image) }}"
                                     class="card-img-top"
                                     alt="News Image">
                            @else
                                <img src="https://via.placeholder.com/350x200?text=No+Image"
                                     class="card-img-top" alt="Placeholder Image">
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="text-sm">
                                <div class="mt-3 mb-2 text-gray-500 d-flex text-muted">
                                    <p class="mb-0" style="margin-right: 10px;">
                                        {{ \Carbon\Carbon::parse($item->created_at)->format('M d, Y') }}
                                    </p>
                                    <span class="d-flex align-items-center">
                                        <i class="text-gray-500 fas fa-clock text-secondary" style="font-size: 16px; margin-right: 4px;"></i>
                                        {{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}
                                    </span>
                                </div>
                                <p class="font-weight-bold text-dark">Judul</p>
                                <p>{{ \Illuminate\Support\Str::limit(strip_tags($item->title), 40) }}</p>
                                <p class="font-weight-bold text-dark">Deskripsi</p>
                                <p>{{ \Illuminate\Support\Str::limit(strip_tags($item->description), 100) }}</p>
                            </div>
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('news.edit', $item->id) }}" class="mr-2 btn btn-primary">Edit</a>
                                <a href="{{ route('news.detail', $item->id) }}" class="mr-2 btn btn-info">Detail</a>
                                <button class="btn btn-danger btn-delete" data-id="{{ $item->id }}">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            </div>

            <div class="mt-3 d-flex justify-content-between align-items-center">
                <div>
                    Showing {{ $news->firstItem() }} to {{ $news->lastItem() }} of {{ $news->total() }} results
                </div>
                <div>
                    {{ $news->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>

    @section('script')
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('search');
            const newsContainer = document.getElementById('news-container');
            const calendarItems = newsContainer.querySelectorAll('.calendar-item');

            function filterItems() {
                const searchQuery = searchInput.value.toLowerCase();

                let filteredItems = [];
                calendarItems.forEach(item => {
                    const title = item.getAttribute('data-title');

                    if (title.includes(searchQuery)) {
                        filteredItems.push(item);
                    }
                });

                // Update the news container with the filtered items
                newsContainer.innerHTML = '';
                filteredItems.forEach(item => {
                    newsContainer.appendChild(item);
                });

                // Optionally handle pagination visibility based on filtered results
                const totalResults = filteredItems.length;
                if (totalResults === 0) {
                    newsContainer.innerHTML = '<p>No results found</p>';
                }
            }

            searchInput.addEventListener('input', filterItems);
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
                            url: "{{ route('news.delete', ':id') }}".replace(':id', id),
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
