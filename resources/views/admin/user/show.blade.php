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
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-12 col-md-6 form-group">
                                    <label for="fullname">Nama Lengkap</label>
                                    <input type="text" disabled class="form-control" id="fullname" name="fullname" Value="{{ $user->admin ? $user->admin->fullname : $user->awardee->fullname }}">
                                </div>
                                <div class="col-12 col-md-6 form-group">
                                    <label for="bpiNumber">No BPI</label>
                                    <input type="text" disabled class="form-control" id="bpiNumber" name="bpiNumber" Value="{{ $user->admin ? "" : $user->awardee->bpi_number }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6 form-group">
                                    <label for="username">Nama Panggilan</label>
                                    <input type="text" disabled class="form-control" id="username" name="username" Value="{{ $user->admin ? "" : $user->awardee->username }}">
                                </div>
                                <div class="col-12 col-md-3 form-group">
                                    <label for="degree">Jenjang</label>
                                    <input type="text" disabled class="form-control" id="degree" name="degree" Value="{{ $user->awardee ? $user->awardee->degree : "" }}">
                                </div>
                                <div class="col-12 col-md-3 form-group">
                                    <label for="year">Tahun Awardee BPI</label>
                                    <input type="text" disabled class="form-control" id="year" name="year" Value="{{ $user->admin ? "" : $user->awardee->year }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6 form-group">
                                    <label for="phoneNumber">No Telepon</label>
                                    <input type="text" disabled class="form-control" id="phoneNumber" name="phoneNumber"  Value="{{ $user->admin ? "" : $user->awardee->phone_number }}">
                                </div>
                                <div class="col-12 col-md-6 form-group">
                                    <label for="Faculty">Fakultas</label>
                                    <input type="text" disabled class="form-control" id="Faculty" name="Faculty"  Value="{{ $user->admin ? "" : $user->awardee->studyprogram->faculty->name }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6 form-group">
                                    <label for="email">Email</label>
                                    <input type="email" disabled class="form-control" id="email" name="email" Value="{{ $user->email }}">
                                </div>
                                <div class="col-12 col-md-6 form-group">
                                    <label for="studyProgramId">Program Studi</label>
                                    <input type="text" disabled class="form-control" id="studyProgramId" name="studyProgramId"  Value="{{ $user->admin ? "" : $user->awardee->studyProgram->name }}">
                                </div>
                            </div>
                            <div class="row">
                                <!-- Bukti Pendaftaran -->
                                <div class="col-12 col-md-6 form-group">
                                    <label for="buktiPendaftaran">Screenshoot Pengumuman Diterima BPI UI Batch 1 / Batch 2 <br>(Bukti Email / Bukti Pendaftaran)</label>
                                    @if ($user->awardee && $user->awardee->bukti_pendaftaran)
                                        <!-- Button untuk membuka modal -->
                                        <button type="button" class="mt-2 btn btn-primary d-block" data-toggle="modal" data-target="#buktiPendaftaranModal">
                                            Lihat File
                                        </button>

                                        <!-- Modal Bootstrap -->
                                        <div class="modal fade" id="buktiPendaftaranModal" tabindex="-1" role="dialog" aria-labelledby="buktiPendaftaranModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="buktiPendaftaranModalLabel">Bukti Pendaftaran</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="text-center modal-body">
                                                        <img src="{{ asset('storage/' . $user->awardee->bukti_pendaftaran) }}" alt="Bukti Pendaftaran" class="img-fluid">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <a href="{{ asset('storage/' . $user->awardee->bukti_pendaftaran) }}" class="btn btn-secondary" target="_blank" download>Download</a>
                                                        <button type="button" class="btn btn-info" onclick="openPrintPage('{{ asset('storage/' . $user->awardee->bukti_pendaftaran) }}')">Print</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <p>Tidak ada file</p>
                                    @endif
                                </div>

                                <!-- SIAK NG/KTM -->
                                <div class="col-12 col-md-6 form-group">
                                    <label for="siakNgKtm">Screenshot SIAK NG/KTM</label>
                                    @if ($user->awardee && $user->awardee->siak_ktm)
                                        <!-- Button untuk membuka modal -->
                                        <button type="button" class="mt-2 btn btn-primary d-block" data-toggle="modal" data-target="#siakKtmModal">
                                            Lihat File
                                        </button>

                                        <!-- Modal SIAK NG/KTM -->
                                        <div class="modal fade" id="siakKtmModal" tabindex="-1" role="dialog" aria-labelledby="siakKtmModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="siakKtmModalLabel">SIAK NG/KTM</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="text-center modal-body">
                                                        <img src="{{ asset('storage/' . $user->awardee->siak_ktm) }}" alt="SIAK NG/KTM" class="img-fluid">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <a href="{{ asset('storage/' . $user->awardee->siak_ktm) }}" class="btn btn-secondary" target="_blank" download>Download</a>
                                                        <button type="button" class="btn btn-info" onclick="openPrintPage('{{ asset('storage/' . $user->awardee->siak_ktm) }}')">Print</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <p>Tidak ada file</p>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 form-group">
                                    <label for="status">Status Anggota</label>
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input btn btn-outline-primary" id="statusActive" name="status" value="1" {{ $user->status == 1 ? 'checked' : '' }} disabled>
                                        <label class="form-check-label" for="statusActive">Anggota Aktif</label>
                                        <p>Masih sebagai penerima BPI dan aktif kuliah di UI.</p>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input btn btn-outline-primary" id="statusInactive" name="status" value="0" {{ $user->status == 0 ? 'checked' : '' }} disabled>
                                        <label class="form-check-label" for="statusInactive">Anggota Biasa</label>
                                        <p>Sudah menjadi Alumni.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 text-right">
                        <a href="{{ $user->role->title == 'default' ? route('user.approve') : route('user.index') }}">
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
                        label.text('Anggota Aktif').css('color', 'green');
                    } else {
                        label.text('Anggota Biasa').css('color', 'red');
                    }
                }
            });

            $('input[name="status"]').click(function() {
                const label = $(this).next('label');
                if ($(this).val() == '1') {
                    label.text('Anggota Aktif').css('color', 'green');
                } else {
                    label.text('Anggota Biasa').css('color', 'red');
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
