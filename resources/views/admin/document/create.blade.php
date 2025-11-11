<x-admin.layout>
    @section('css')
    <style>
        /* Menghilangkan tanda panah naik turun pada input angka */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
          -webkit-appearance: none;
          margin: 0;
        }

        /* Untuk Firefox */
        input[type=number] {
          -moz-appearance: textfield;
        }

        /* Preview styling */
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
                <form id="documentForm" method="POST" action="{{ route('doc.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- Form Fields -->
                            <div class="form-group">
                                <label for="name">Nama Dokumen</label>
                                <input type="text" class="form-control" id="name" name="name" required placeholder="Contoh Dokumen">
                            </div>

                            <div class="form-group">
                                <label for="description">Deskripsi Dokumen</label>
                                <textarea class="form-control" id="description" name="description" required placeholder="Masukkan deskripsi dokumen" rows="4"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="file">Upload File</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="file" id="file" class="custom-file-input" accept=".pdf, .xls, .xlsx" required>
                                        <label class="custom-file-label" for="file">Choose File</label>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Maksimal ukuran file: 10 MB (pdf, excel .xls, .xlsx).</small>
                            </div>

                            <!-- Preview Container -->
                            <div id="filePreview" class="preview-container"></div>

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

    <!-- Include SheetJS and jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            var form = $('#documentForm');

            // File change event untuk preview file
            $('#file').on('change', function() {
                var file = this.files[0];
                var fileName = file.name;
                var fileType = file.type;
                var reader = new FileReader();

                $('#file').next('.custom-file-label').text(fileName);
                $('#filePreview').empty();

                // Preview PDF
                if (fileType === 'application/pdf') {
                    var iframe = $('<iframe/>', {
                        src: URL.createObjectURL(file),
                        width: '100%',
                        height: '500px'
                    });
                    $('#filePreview').append(iframe);
                }
                // Preview Excel
                else if (fileType === 'application/vnd.ms-excel' || fileType === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
                    reader.onload = function(e) {
                        var data = e.target.result;
                        var workbook = XLSX.read(data, { type: 'binary' });

                        var sheet = workbook.Sheets[workbook.SheetNames[0]];
                        var html = XLSX.utils.sheet_to_html(sheet);

                        const { jsPDF } = window.jspdf;
                        var pdf = new jsPDF();
                        pdf.html(html, {
                            callback: function(pdf) {
                                var iframe = $('<iframe/>', {
                                    src: pdf.output('datauristring'),
                                    width: '100%',
                                    height: '500px'
                                });
                                $('#filePreview').append(iframe);
                            },
                            margin: [10, 10, 10, 10],
                            x: 10,
                            y: 10
                        });
                    };
                    reader.readAsBinaryString(file);
                }
            });

            // Confirm before submit
            $('#btn-confirm').click(function(e) {
                e.preventDefault(); // Mencegah pengiriman form otomatis

                Swal.fire({
                    title: 'Apakah Anda yakin ingin menambahkan dokumen?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, simpan!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Menyiapkan data untuk dikirim via AJAX
                        var formData = new FormData(form[0]);

                        $.ajax({
                            url: "{{ route('doc.store') }}",
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                Swal.fire(
                                    'Berhasil!',
                                    'Data berhasil ditambahkan.',
                                    'success'
                                ).then(() => {
                                    window.location.href = "{{ route('doc.index') }}";
                                });
                            },
                            error: function(response) {
                                Swal.fire(
                                    'Gagal!',
                                    'Ada kesalahan saat menambahkan data.',
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
