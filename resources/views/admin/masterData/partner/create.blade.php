<x-admin.layout>
    <style>
        .form-check-input {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .form-check-input {
            width: 14px;
            height: 14px;
            accent-color: orange;
            cursor: pointer;
        }
    </style>
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
                <form id="partnerForm">
                    @csrf
                    <div class="row no-gutters">
                        <!-- Kolom kiri -->
                        <div class="col-lg-8 pr-lg-3">
                            <!-- isi field mitra -->
                            <div class="row">
                                <div class="col-12 form-group">
                                    <label for="partner_name">Nama Mitra</label>
                                    <input type="text" id="partner_name" name="partner_name" class="form-control" placeholder="PT. SIMOPKL unggul">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" class="form-control" placeholder="contact@simopkl.my">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 form-group">
                                    <label for="website_address">Website</label>
                                    <input type="text" id="website_address" name="website_address" class="form-control" placeholder="www.simopkl.my">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 form-group">
                                    <label for="phoneNumber">No Telepon</label>
                                    <input type="text" id="phoneNumber" name="phoneNumber" class="form-control" placeholder="08123456789">
                                </div>
                                <div class="col-6 form-group">
                                    <label for="Whatsapp_number">Whatsapp</label>
                                    <input type="text" id="Whatsapp_number" name="Whatsapp_number" class="form-control" placeholder="08123456789">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 form-group">
                                    <label for="address">Alamat</label>
                                    <input type="text" id="address" name="address" class="form-control" placeholder="JL. Indonesia Unggul, Denpasar, Bali">
                                </div>
                            </div>
                            <div class="row">
                                <!-- Field Deskripsi -->
                                <div class="col-12 form-group">
                                    <label for="description">Deskripsi</label>
                                    <textarea class="form-control" name="description" id="description" rows="4" placeholder="Deskripsi perusahaan"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom kanan -->
                        <div class="col-lg-4">
                            <!-- Card gambar -->
                            <label for="ppImg">Logo Mitra</label>
                            <div class="card shadow-sm w-100 mb-3" style="border-radius: 12px;">
                                <div class="card-body d-flex justify-content-center align-items-center" style="height: 260px;">
                                    <img src="{{ asset('img/perusahaan.png') }}"
                                        class="img-fluid"
                                        style="max-height: 220px; object-fit: contain;">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="logo_mitra" id="logo_mitra" class="custom-file-input" required>
                                        <label class="custom-file-label" for="logo_mitra">Choose File</label>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Maksimal ukuran file: 1 MB (jpg, jpeg, png).</small>
                            </div>
                        </div>
                    </div>
                    <div class="row-mt-3">
                        <div class="col-12 form-group">
                            <label for="status">Status Mitra</label>
                            <div class="form-check">
                                <input type="radio" class="form-check-input btn btn-outline-primary" id="statusActive" name="status" value="1">
                                <label class="form-check-label" for="statusActive">Mitra Aktif</label>
                                <p>Masih sebagai aktif sebagai mitra PKL Universitas Udayana.</p>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input btn btn-outline-primary" id="statusInactive" name="status" value="0">
                                <label class="form-check-label" for="statusInactive">Mitra Tidak Aktif</label>
                                <p>Sudah tidak aktif sebagai mitra PKL Universitas Udayana(mohon ajukan ulang MoU di laman pendaftaran).</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 text-right">
                        <button type="button" class="mr-1 btn btn-secondary" id="btnCancel">Batal</button>
                        <button type="button" class="ml-1 btn btn-primary" id="btn-confirm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            var form = $('#partnerForm');

            form.find('button[type=submit]').prop('disabled', true);

            form.on('input', function() {
                var isValid = true;
                form.find('input[required], select[required]').each(function() {
                    if (!$(this).val()) {
                        isValid = false;
                    }
                });

                form.find('button[type=submit]').prop('disabled', !isValid);
            });

            $('#btn-confirm').click(function() {
                var formData = new FormData(form[0]);

                Swal.fire({
                    title: 'Apakah Anda yakin ingin menambahkan data?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, simpan!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // modal loading
                        console.log('Modal loading show');
                        $('#loadingModal').modal('show');

                        $.ajax({
                            url: "{{ route('partner.store') }}",
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                Swal.fire(
                                    'Berhasil!',
                                    'Mitra berhasil ditambahkan.',
                                    'success'
                                ).then(() => {
                                    window.location.href = "{{ route('partner.index') }}";
                                });
                            },
                            error: function(response) {
                                Swal.fire(
                                    'Gagal!',
                                    'Ada kesalahan saat menambahkan Mitra.',
                                    'error'
                                );
                                console.log(response);
                            },
                            complete: function() {
                                $('#loadingModal').modal('hide');
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
                        window.location.href = '{{ route('partner.index') }}';
                    }
                });
            });

            // Menampilkan nama file yang dipilih pada input file
            $('.custom-file-input').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
            });
        });
    </script>

    <script>
        function openPrintPage(fileUrl) {
            var newWindow = window.open(fileUrl, '_blank');
            if (newWindow) {
                newWindow.focus();
                newWindow.onload = function() {
                    newWindow.print();
                };
            } else {
                alert('Please allow popups for this website');
            }
        }
    </script>
</x-admin.layout>
