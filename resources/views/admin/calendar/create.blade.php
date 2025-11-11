<x-admin.layout>
    @section('css')
    <!-- Include Flatpickr and ClockPicker styles -->
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/bootstrap-clockpicker.min.css" rel="stylesheet">

    <style>
        .flatpickr-input {
            background-color: #ffffff !important;
            color: #000000;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }

        .flatpickr-input:focus {
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
    </style>
    @endsection

    <div class="mb-4 shadow card">
        <div class="card-body">
            <div class="mb-4 text-left">
                <h3>Tambah Acara Kalender</h3>
            </div>

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

            <!-- Form to create a new event -->
            <form action="{{ route('calendar.store') }}" method="POST" id="calendarForm">
                @csrf

                <div class="form-group">
                    <label for="date" class="form-label">Tanggal Acara</label>
                    <input
                        type="text"
                        class="form-control flatpickr-input @error('date') is-invalid @enderror"
                        id="date"
                        name="date"
                        placeholder="Pilih Tanggal Acara"
                        value="{{ old('date') }}"
                        required>
                </div>

                <div class="form-group">
                    <label for="title" class="form-label">Judul Acara</label>
                    <input
                        type="text"
                        class="form-control @error('title') is-invalid @enderror"
                        id="title"
                        name="title"
                        placeholder="Masukkan Judul Acara"
                        value="{{ old('title') }}"
                        required>
                </div>

                <div class="form-group">
                    <label for="start_time" class="form-label">Waktu Mulai</label>
                    <div class="input-group clockpicker">
                        <input
                            type="text"
                            class="form-control @error('start_time') is-invalid @enderror"
                            id="start_time"
                            name="start_time"
                            placeholder="Pilih Waktu Mulai"
                            value="{{ old('start_time') }}"
                            required>
                        <span class="input-group-text">
                            <i class="fas fa-clock"></i>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="end_time" class="form-label">Waktu Selesai</label>
                    <div class="input-group clockpicker">
                        <input
                            type="text"
                            class="form-control @error('end_time') is-invalid @enderror"
                            id="end_time"
                            name="end_time"
                            placeholder="Pilih Waktu Selesai"
                            value="{{ old('end_time') }}"
                            required>
                        <span class="input-group-text">
                            <i class="fas fa-clock"></i>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="location" class="form-label">Lokasi Acara</label>
                    <input
                        type="text"
                        class="form-control @error('location') is-invalid @enderror"
                        id="location"
                        name="location"
                        placeholder="Masukkan Lokasi Acara"
                        value="{{ old('location') }}"
                        required>
                </div>

                <div class="text-right">
                    <button type="button" class="btn btn-secondary" id="btnCancel">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn-confirm">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    @section('script')
    <!-- Include Flatpickr and ClockPicker scripts -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/bootstrap-clockpicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            var form = $('#calendarForm');

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

            flatpickr('#date', {
                dateFormat: 'Y-m-d',
                minDate: 'today',
                altInput: true,
                altFormat: "d F Y",
            });

            $('.clockpicker').clockpicker({
                autoclose: true,
                placement: 'bottom',
                align: 'left',
                donetext: 'Pilih'
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
                        $.ajax({
                            url: "{{ route('calendar.store') }}",
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                Swal.fire(
                                    'Berhasil!',
                                    'Kalender Acara berhasil ditambahkan.',
                                    'success'
                                ).then(() => {
                                    window.location.href = '{{ route('index.kalender') }}';
                                });
                            },
                            error: function(response) {
                                Swal.fire(
                                    'Gagal!',
                                    'Ada kesalahan saat menambahkan kalender acara.',
                                    'error'
                                );
                                console.log(response);
                            }
                        });
                    }
                });
            });

            // Tombol Batal
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
                        window.location.href = '{{ route('index.kalender') }}';
                    }
                });
            });
        });
    </script>
    @endsection
</x-admin.layout>
