<x-admin.layout>
    @section('css')
    <style>
        /* Menghilangkan tanda panah naik turun pada input angka */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
        }
        .preview-container {
            margin-top: 15px;
            text-align: center;
        }
        .preview-container img, .preview-container iframe {
            max-width: 100%;
            max-height: 500px;
            margin-top: 10px;
        }
    </style>
    @endsection

    <div class="container mt-4">
        @if(session('msg'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('msg') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="shadow-lg card">
            <div class="card-body">
                <form id="documentForm" method="POST" action="{{ route('doc.update', ['id' => $document->id]) }}" enctype="multipart/form-data">
                    @csrf
                    {{-- @method('PUT') --}}
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="name">Nama Dokumen</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $document->name }}" required placeholder="Contoh Dokumen">
                            </div>

                            <div class="form-group">
                                <label for="description">Deskripsi Dokumen</label>
                                <textarea class="form-control" id="description" name="description" required placeholder="Masukkan deskripsi dokumen" rows="4">{{ $document->description }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="file">Upload File</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="file" id="file" class="custom-file-input" accept=".pdf, .xls, .xlsx">
                                        <label class="custom-file-label" for="file">
                                            {{ basename($document->file_path) ?? 'Choose File' }}
                                        </label>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Maksimal ukuran file: 10 MB (pdf, excel .xls, .xlsx).</small>
                            </div>

                            <div id="filePreview" class="preview-container">
                                @if(pathinfo($document->file_path, PATHINFO_EXTENSION) === 'pdf')
                                    <iframe src="{{ asset('storage/' . $document->file_path) }}" width="100%" height="500px"></iframe>
                                @elseif(in_array(pathinfo($document->file_path, PATHINFO_EXTENSION), ['xls', 'xlsx']))
                                    <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="btn btn-primary">Lihat File Excel</a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-right">
                        <button type="button" class="mr-1 btn btn-secondary" id="btnCancel">Batal</button>
                        <button type="submit" class="ml-1 btn btn-primary" id="btn-confirm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var form = $('#documentForm');

            $('#file').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').html(fileName);

                var filePath = $(this).val();
                var fileExtension = filePath.split('.').pop().toLowerCase();
                var previewContainer = $('#filePreview');
                previewContainer.empty();

                if (fileExtension === 'pdf') {
                    previewContainer.append('<iframe src="' + URL.createObjectURL(this.files[0]) + '" width="100%" height="500px"></iframe>');
                } else if (fileExtension === 'xls' || fileExtension === 'xlsx') {
                    previewContainer.append('<a href="' + URL.createObjectURL(this.files[0]) + '" target="_blank" class="btn btn-primary">Lihat File Excel</a>');
                }
            });

            $('#btn-confirm').click(function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Data akan diperbarui!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, simpan!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var formData = new FormData(form[0]);

                        $.ajax({
                            url: "{{ route('doc.update', ['id' => $document->id]) }}",
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                Swal.fire(
                                    'Berhasil!',
                                    'Dokumen telah berhasil diperbarui.',
                                    'success'
                                ).then(() => {
                                    window.location.href = "{{ route('doc.index') }}";
                                });
                            },
                            error: function(response) {
                                Swal.fire(
                                    'Gagal!',
                                    'Ada kesalahan saat menambahkan dokumen.',
                                    'error'
                                );
                                console.log(response);
                            }
                        });
                    }
                });
            });

            // Tombol Cancel
            $('#btnCancel').click(function() {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda tidak akan menyimpan perubahan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, batalkan!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form[0].reset();
                        window.location.href = '{{ route('doc.index') }}';
                    }
                });
            });
        });
    </script>
</x-admin.layout>
