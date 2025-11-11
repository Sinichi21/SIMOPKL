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
                <form id="resetForm" method="POST" action="{{ route('admin.password.email') }}" enctype="multipart/form-data">
                    <input type="hidden" name="email" value="{{$user->email}}">
                </form>
                <form id="userForm" method="POST" action="{{ route('user.update') }}" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="{{$user->id}}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-12 col-md-6 form-group">
                                    <label for="fullname">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="fullname" name="fullname" required Value="{{ $user->admin ? $user->admin->fullname : $user->awardee->fullname }}">
                                </div>
                                <div class="col-12 col-md-6 form-group">
                                    <label for="bpiNumber">No BPI</label>
                                    <input type="number" class="form-control" id="bpiNumber" name="bpiNumber" maxlength="10" {{ $user->admin ? 'disabled' : '' }} Value="{{ $user->admin ? "" : $user->awardee->bpi_number }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6 form-group">
                                    <label for="username">Nama Panggilan</label>
                                    <input type="text" class="form-control" id="username" name="username" {{ $user->admin ? 'disabled' : '' }} Value="{{ $user->admin ? "" : $user->awardee->username }}">
                                </div>
                                <div class="col-12 col-md-3 form-group">
                                    <label for="degree">Jenjang</label>
                                    <select class="form-control" {{ $user->admin ? 'disabled' : '' }} id="degree" name="degree">
                                        <option value="{{ $user->awardee ? $user->awardee->degree : "" }}" selected>{{ $user->awardee ? $user->awardee->degree : "" }}</option>
                                        <option value="S1">S1</option>
                                        <option value="S2">S2</option>
                                        <option value="S3">S3</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-3 form-group">
                                    <label for="year">Tahun Awardee BPI</label>
                                    <input type="text" class="form-control" id="year" name="year" {{ $user->admin ? 'disabled' : '' }} Value="{{ $user->admin ? "" : $user->awardee->year }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6 form-group">
                                    <label for="phoneNumber">No Telepon</label>
                                    <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" {{ $user->admin ? 'disabled' : '' }} Value="{{ $user->admin ? "" : $user->awardee->phone_number }}">
                                </div>
                                <div class="col-12 col-md-6 form-group">
                                    <label for="faculty">Fakultas</label>
                                    <select class="form-control" id="faculty" {{ $user->admin ? 'disabled' : '' }} name="faculty">
                                        <option value="{{ $user->admin ? "" : $user->awardee->studyprogram->faculty_id }}" selected>{{ $user->admin ? "" : $user->awardee->studyprogram->faculty->name }}</option>
                                        @foreach ($faculties as $faculty)
                                        <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6 form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}">
                                </div>
                                <div class="col-12 col-md-6 form-group">
                                    <label for="studyProgramId">Program Studi</label>
                                    <select class="form-control" id="studyProgramId" {{ $user->admin ? 'disabled' : '' }} name="studyProgramId">
                                        <option value="{{ $user->admin ? "" : $user->awardee->study_program_id }}" selected>{{ $user->admin ? "" : $user->awardee->studyProgram->name }}</option>
                                        @foreach ($studyPrograms as $studyProgram)
                                        <option value="{{ $studyProgram->id }}" data-faculty="{{ $studyProgram->faculty_id }}">{{ $studyProgram->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6 form-group">
                                    <label for="buktiPendaftaran" class="form-label">Screenshoot Pengumuman Diterima BPI UI Batch 1 / Batch 2 <br>(Bukti Email / Bukti Pendaftaran)</label>
                                    @if ($user->awardee && $user->awardee->bukti_pendaftaran)
                                        <button type="button" class="mt-1 btn btn-primary d-block" data-toggle="modal" data-target="#buktiModal">
                                            Lihat File Sebelumnya
                                        </button>
                                         <!--Modal -->
                                        <div class="modal fade" id="buktiModal" tabindex="-1" role="dialog" aria-labelledby="buktiModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="buktiModalLabel">Bukti Pendaftaran</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="text-center modal-body">
                                                        <img src="{{ asset('storage/' . $user->awardee->bukti_pendaftaran) }}" alt="Bukti Pendaftaran" class="img-fluid">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <p>Tidak ada file</p>
                                    @endif
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="bukti_pendaftaran" id="bukti_pendaftaran" class="custom-file-input">
                                            <label class="mt-2 custom-file-label" for="bukti_pendaftaran">Change File</label>
                                        </div>
                                    </div>
                                    <small class="mt-3 form-text text-muted">Maksimal ukuran file: 1 MB (jpg, jpeg, png, pdf).</small>
                                </div>

                                <div class="col-12 col-md-6 form-group">
                                    <label for="siakNgKtm" class="form-label">Screenshot SIAK NG/KTM</label>
                                    @if ($user->awardee && $user->awardee->siak_ktm)
                                        <button type="button" class="mt-1 btn btn-primary d-block" data-toggle="modal" data-target="#siakModal">
                                            Lihat File Sebelumnya
                                        </button>
                                         <!--Modal-->
                                        <div class="modal fade" id="siakModal" tabindex="-1" role="dialog" aria-labelledby="siakModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="siakModalLabel">SIAK NG/KTM</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="text-center modal-body">
                                                        <img src="{{ asset('storage/' . $user->awardee->siak_ktm) }}" alt="SIAK NG/KTM" class="img-fluid">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <p>Tidak ada file</p>
                                    @endif
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="siak_ktm" id="siak_ktm" class="custom-file-input">
                                            <label class="mt-2 custom-file-label" for="siak_ktm">Change File</label>
                                        </div>
                                    </div>
                                    <small class="mt-3 form-text text-muted">Maksimal ukuran file: 1 MB (jpg, jpeg, png, pdf).</small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6 form-group">
                                    <label for="status">Status Anggota</label>
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" id="statusActive" name="status" value="1" {{ $user->status == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="statusActive">Anggota Aktif</label>
                                        <p>Masih sebagai penerima BPI dan aktif kuliah di UI.</p>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" id="statusInactive" name="status" value="0" {{ $user->status == 0 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="statusInactive">Anggota Biasa</label>
                                        <p>Sudah menjadi Alumni.</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 form-group">
                                    <label for="passwordReset">Kata Sandi</label>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-warning" id="btnResetPassword">Reset Kata Sandi</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 text-right">
                        <button type="button" class="btn btn-secondary" id="btnCancel">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
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

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#faculty').change(function() {
                var selectedFaculty = $(this).val();
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

            // Handle form submission
            $('#userForm').submit(function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda akan menyimpan perubahan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, simpan!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('user.update') }}",
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                // console.log('Response success:', response);
                                Swal.fire(
                                    'Berhasil!',
                                    'User berhasil diedit.',
                                    'success'
                                ).then(() => {
                                    window.location.href = "{{ route('user.index') }}";
                                });
                            },
                            error: function(response) {
                                Swal.fire(
                                    // console.log('Response error:', response);
                                    'Gagal!',
                                    'Ada kesalahan saat mengedit user.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

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

            $('#btnResetPassword').click(function() {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda akan mereset password user ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, reset!'
                }).then((result) => {
                    if (result.isConfirmed) {

                        var email = "{{ $user->email }}";
                        var token = "{{ csrf_token() }}";

                        $.ajax({
                            url: "{{ route('admin.password.email') }}",
                            type: 'POST',
                            data: {
                                email: email,
                                _token: token,
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Berhasil!',
                                    'Reset password link berhasil dikrimkan',
                                    'success'
                                );
                            },
                            error: function(response) {
                                Swal.fire(
                                    'Gagal!',
                                    'Ada kesalahan saat mereset password user.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            form.on('input', function() {
                var isValid = true;
                form.find('select[required]').each(function() {
                    if (!$(this).val()) {
                        isValid = false;
                    }
                });

                form.find('button[type=submit]').prop('disabled', !isValid);
            });
        });
    </script>
</x-admin.layout>
