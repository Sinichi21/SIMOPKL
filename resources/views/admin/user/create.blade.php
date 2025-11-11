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

        /* status radio button */
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
                <!-- Loading Modal -->
                <div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="loadingModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content" style="border-radius: 15px; padding: 20px; text-align: center;">
                            <div class="modal-body">
                                <!-- Icon -->
                                <div style="font-size: 3rem; color: #ffcc00;">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </div>
                                <!-- Message -->
                                <p class="mt-3" style="font-size: 1.5rem; font-weight: 500;">Loading user created...</p>
                            </div>
                        </div>
                    </div>
                </div>
                <form id="userForm" method="POST" action="{{ route('user.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- Form Fields -->
                            <div class="row">
                                <div class="col-12 col-md-6 form-group">
                                    <label for="fullname">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="fullname" name="fullname" required placeholder="Edogawa Conan">
                                </div>
                                <div class="col-12 col-md-6 form-group">
                                    <label for="bpiNumber">No BPI</label>
                                    <input type="number" class="form-control" id="bpiNumber" name="bpiNumber" placeholder="202327091086" maxlength="10">
                                </div>
                            </div>
                            <!-- More Form Fields -->
                            <div class="row">
                                <div class="col-12 col-md-6 form-group">
                                    <label for="username">Nama Panggilan</label>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Conan">
                                </div>
                                <div class="col-12 col-md-3 form-group">
                                    <label for="degree">Jenjang</label>
                                    <select class="form-control" id="degree" name="degree">
                                        <option selected>-- Pilih jenjang --</option>
                                        <option value="S1">S1</option>
                                        <option value="S2">S2</option>
                                        <option value="S3">S3</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-3 form-group">
                                    <label for="year">Tahun Awardee BPI</label>
                                    <select name="year" id="year" class="form-control">
                                        @foreach ($years as $year)
                                        <option value="{{$year}}">{{$year}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6 form-group">
                                    <label for="phoneNumber">No Telepon</label>
                                    <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" required placeholder="08567768788">
                                </div>
                                <div class="col-12 col-md-6 form-group">
                                    <label for="faculty">Fakultas</label>
                                    <select class="form-control" id="faculty" name="faculty">
                                        <option selected>-- Pilih Fakultas --</option>
                                        @foreach ($faculties as $faculty)
                                        <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6 form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="example@student.ui.ac.id">
                                </div>
                                <div class="col-12 col-md-6 form-group">
                                    <label for="studyProgramId">Program Studi</label>
                                    <select class="form-control" id="studyProgramId" name="studyProgramId">
                                        <option value="" selected>-- Pilih Program Studi --</option>
                                        @foreach ($studyPrograms as $studyProgram)
                                        <option value="{{ $studyProgram->id }}" data-faculty="{{ $studyProgram->faculty_id }}">{{ $studyProgram->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6 form-group">
                                    <label for="password">Kata Sandi</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" id="togglePassword"><i class="fa fa-eye"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 form-group">
                                    <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirmation"><i class="fa fa-eye"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6 form-group">
                                    <label for="bukti_pendaftaran" class="form-label">
                                        Upload Screenshoot Pengumuman DITERIMA BPI UI Batch 1 / Batch 2 (Bukti Email atau Akun Pendaftaran)
                                    </label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="bukti_pendaftaran" id="bukti_pendaftaran" class="custom-file-input" required>
                                            <label class="custom-file-label" for="bukti_pendaftaran">Choose File</label>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Maksimal ukuran file: 1 MB (jpg, jpeg, png).</small>
                                </div>
                                <div class="col-12 col-md-6 form-group">
                                    <label for="siak_ktm" class="form-label">
                                        Upload Screenshoot SIAK NG/KTM
                                    </label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="siak_ktm" id="siak_ktm" class="custom-file-input" required>
                                            <label class="custom-file-label" for="siak_ktm">Choose File</label>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Maksimal ukuran file: 1 MB (jpg, jpeg, png).</small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6 form-group">
                                    <label for="status">Status</label>
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" id="statusActive" name="status" value="1">
                                        <label class="form-check-label" for="statusActive">Anggota Aktif</label>
                                        <p>Masih sebagai penerima BPI dan aktif kuliah di UI.</p>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" id="statusInactive" name="status" value="0">
                                        <label class="form-check-label" for="statusInactive">Anggota Biasa</label>
                                        <p>Sudah menjadi Alumni.</p>
                                    </div>
                                </div>
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
        document.addEventListener('DOMContentLoaded', function() {
            const bpiInput = document.getElementById('bpiNumber');

            bpiInput.addEventListener('input', function() {
                bpiInput.value = bpiInput.value.replace(/[^\d]/g, '');

                if (bpiInput.value.length > 20) {
                    bpiInput.value = bpiInput.value.slice(0, 20);
                }
            });

            bpiInput.addEventListener('keypress', function(event) {
                const char = String.fromCharCode(event.which);
                if (!/\d/.test(char)) {
                    event.preventDefault();
                }
            });
        });

        $(document).ready(function() {
            var form = $('#userForm');

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
                            url: "{{ route('user.store') }}",
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                Swal.fire(
                                    'Berhasil!',
                                    'User berhasil ditambahkan.',
                                    'success'
                                ).then(() => {
                                    window.location.href = "{{ route('user.index') }}";
                                });
                            },
                            error: function(response) {
                                Swal.fire(
                                    'Gagal!',
                                    'Ada kesalahan saat menambahkan user.',
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
                        window.location.href = '{{ route('user.index') }}';
                    }
                });
            });

            // Tombol toggle password
            $('#togglePassword').click(function() {
                var passwordField = $('#password');
                var icon = $(this).find('i');
                if (passwordField.attr('type') === 'password') {
                    passwordField.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordField.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            $('#togglePasswordConfirmation').click(function() {
                var passwordField = $('#password_confirmation');
                var icon = $(this).find('i');
                if (passwordField.attr('type') === 'password') {
                    passwordField.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordField.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            // Disable the study program select by default
                $('#studyProgramId').prop('disabled', true);

                $('#faculty').change(function() {
                    var selectedFaculty = $(this).val();

                    if (selectedFaculty) {
                        $('#studyProgramId').prop('disabled', false);
                    } else {
                        $('#studyProgramId').prop('disabled', true).val('');
                    }

                    $('#studyProgramId option').each(function() {
                        var facultyId = $(this).data('faculty');
                        if (facultyId == selectedFaculty || facultyId == '') {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                    $('#studyProgramId').val('');
                });

            // Menampilkan nama file yang dipilih pada input file
            $('.custom-file-input').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
            });
        });
    </script>
</x-admin.layout>
