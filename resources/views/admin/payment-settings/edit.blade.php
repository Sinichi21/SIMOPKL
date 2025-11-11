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

        .table-wrapper {
            max-height: 300px;  /* Tentukan tinggi maksimum yang diinginkan */
            overflow-y: auto;   /* Aktifkan scroll vertikal */
            display: block;
        }

        thead th {
            position: sticky;
            top: 0;
            background-color: #f8f9fa; /* Background agar terlihat kontras */
            z-index: 1; /* Pastikan th berada di atas konten */
        }

        table {
            width: 100%;
            table-layout: fixed;
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
                <form id="feeForm" method="POST" action="{{ route('payment-settings.update', $paymentSetting->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- Form Fields -->
                            <div class="form-group">
                                <label for="type" class="form-label">Tipe</label>
                                <select name="type" id="type" class="form-control" required>
                                    <option value="bank" {{ $paymentSetting->type == 'bank' ? 'selected' : '' }}>Bank</option>
                                    <option value="ewallet" {{ $paymentSetting->type == 'ewallet' ? 'selected' : '' }}>E-Wallet</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ $paymentSetting->name }}" required>
                            </div>
                            <div class="form-group">
                                <label for="account_number" class="form-label">Nomor Rekening/ID</label>
                                <input type="text" name="account_number" id="account_number" class="form-control" value="{{ $paymentSetting->account_number }}" required>
                            </div>
                            <div class="form-group">
                                <label for="image_file" class="form-label">Upload Gambar</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="image_file" id="image_file" class="custom-file-input" accept="image/png">
                                        <label class="custom-file-label" for="image_file">Choose File</label>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Maksimal ukuran file: 1 MB (PNG).</small>
                                @if ($paymentSetting->image_url)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $paymentSetting->image_url) }}" alt="Logo" width="50">
                                </div>
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
            var form = $('#feeForm');

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
                            url: "{{ route('payment-settings.update', ['id' => $paymentSetting->id]) }}",
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                Swal.fire(
                                    'Berhasil!',
                                    'Data telah berhasil diperbarui.',
                                    'success'
                                ).then(() => {
                                    window.location.href = "{{ route('payment-settings.index') }}";
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
                        window.location.href = '{{ route('payment-settings.index') }}';
                    }
                });
            });

            $('.custom-file-input').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
            });
        });
    </script>
</x-admin.layout>
