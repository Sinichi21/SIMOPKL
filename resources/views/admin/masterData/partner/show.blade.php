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
                <form id="userForm">
                    @csrf
                    <div class="row no-gutters">
                        <!-- Kolom kiri -->
                        <div class="col-lg-8 pr-lg-3">
                            <!-- isi field mitra -->
                            <div class="row">
                                <div class="col-12 form-group">
                                    <label for="partner_name">Nama Mitra</label>
                                    <input type="text" disabled class="form-control" value="{{ $mitra->partner_name }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 form-group">
                                    <label for="email">Email</label>
                                    <input type="email" disabled class="form-control" value="{{ $mitra->email }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 form-group">
                                    <label for="website_address">Website</label>
                                    <input type="text" disabled class="form-control" value="{{ $mitra->website_address }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 form-group">
                                    <label for="phone_number">No Telepon</label>
                                    <input type="text" disabled class="form-control" value="{{ $mitra->phone_number }}">
                                </div>
                                <div class="col-6 form-group">
                                    <label for="whatsapp">Whatsapp</label>
                                    <input type="text" disabled class="form-control" value="{{ $mitra->whatsapp_number }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 form-group">
                                    <label for="address">Alamat</label>
                                    <input type="text" disabled class="form-control" value="{{ $mitra->address }}">
                                </div>
                            </div>
                            <div class="row">
                                <!-- Field Deskripsi -->
                                <div class="col-12 form-group">
                                    <label for="description">Deskripsi</label>
                                    <textarea disabled class="form-control" rows="4">{{ $mitra->description ?? 'Tidak ada deskripsi.' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom kanan -->
                        <div class="col-lg-4">
                            <!-- Card gambar -->
                            <div class="card shadow-sm w-100 mb-3" style="border-radius: 12px;">
                                <div class="card-body d-flex justify-content-center align-items-center" style="height: 260px;">
                                    <img src="{{ $mitra->image_url ? asset('storage/' .$mitra->image_url) : asset('img/perusahaan.png') }}"
                                        class="img-fluid"
                                        style="max-height: 220px; object-fit: contain;">
                                </div>
                            </div>
                </div>
                </div>
                    <div class="row-mt-3">
                        <div class="col-12 form-group">
                            <label for="status">Status Mitra</label>
                            <div class="form-check">
                                <input type="radio" class="form-check-input btn btn-outline-primary" id="statusActive" name="status" value="1" {{ $mitra->status == 1 ? 'checked' : '' }} disabled>
                                <label class="form-check-label" for="statusActive">Mitra Aktif</label>
                                <p>Masih sebagai aktif sebagai mitra PKL Universitas Udayana.</p>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input btn btn-outline-primary" id="statusInactive" name="status" value="0" {{ $mitra->status == 0 ? 'checked' : '' }} disabled>
                                <label class="form-check-label" for="statusInactive">Mitra Tidak Aktif</label>
                                <p>Sudah tidak aktif sebagai mitra PKL Universitas Udayana(mohon ajukan ulang MoU di laman pendaftaran).</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 text-right">
                        <a href="{{ route('partner.index') }}">
                            <button type="button" class="btn btn-secondary">Kembali</button>
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('input[name="status"]').each(function() {
                if ($(this).is(':checked')) {
                    const label = $(this).next('label');
                    if ($(this).val() == '1') {
                        label.text('Mitra Aktif').css('color', 'green');
                    } else {
                        label.text('Mitra Tidak Aktif').css('color', 'red');
                    }
                }
            });

            $('input[name="status"]').click(function() {
                const label = $(this).next('label');
                if ($(this).val() == '1') {
                    label.text('Mitra Aktif').css('color', 'green');
                } else {
                    label.text('Mitra Tidak Aktif').css('color', 'red');
                }
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
