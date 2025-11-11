<x-admin.layout>
    @section('css')
    <!-- Custom styles for this page -->
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    @endsection

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="text-right">
                <h6>{{ \Carbon\Carbon::now()->format('d-m-Y') }}</h6>
            </div>
            <div class="text-left mb-4">
                <h3>Image Slide Show</h3>
            </div>
            <div class="row">
                @foreach ($carouselImages as $index => $image)
                    <div class="col-md-4">
                        <div class="card">
                            <img id="carousel-image-{{ $index }}" src="{{ asset('storage/' . $image->url) }}" class="card-img-top placeholder-image" alt="Carousel Image">
                            <div class="card-body">
                                <div class="form-group mt-3">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg" class="form-control-file" onchange="uploadImage(event, {{ $index }})">
                                    <small id="fileHelp-{{ $index }}" class="form-text text-muted">File harus berformat PNG atau JPG dan ukuran maksimal 1MB.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row justify-content-end mt-3">
                <button class="btn btn-secondary mr-1" id="btn-cancel" onclick="window.location.reload()">Batal</button>
                <button class="btn btn-primary ml-1" id="btn-save" onclick="saveChanges()">Simpan</button>
            </div>
        </div>
    </div>

    @section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let imageUrls = {};

        function uploadImage(event, index) {
            let formData = new FormData();
            formData.append('image', event.target.files[0]);

            fetch('{{ route("landingpage.carouselupload") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.url) {
                    const imageUrl = `{{ asset('storage') }}/${data.url}`;
                    document.getElementById(`carousel-image-${index}`).src = imageUrl;
                    imageUrls[index + 1] = data.url; // Mengatur indeks mulai dari 1
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Failed to upload image', 'error');
            });
        }

        function saveChanges() {
            fetch('{{ route("landingpage.carouselsave") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ urls: imageUrls })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Success', data.success, 'success').then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire('Error', 'Failed to save changes', 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Failed to save changes', 'error');
            });
        }
    </script>
    @endsection
</x-admin.layout>
