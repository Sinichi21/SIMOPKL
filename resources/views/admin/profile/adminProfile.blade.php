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

    <div class="container py-5">

        <div>
            @session('success')
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{$value}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endsession
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        <!-- Left Column - Profile Picture -->
        <form method="POST" action="{{route('awardee.profile.update')}}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="userId" value="{{$user->id}}">

            <div class="row">
                <div class="px-4 mb-4 text-center col-md-4 mb-md-0">
                    <div class="mx-auto border w-100">
                        <img id="profileImg"
                            src="{{ $user->pp_url ? asset('storage/'.$user->pp_url) : asset('img/undraw_profile.svg') }}"
                            alt="{{ $user->awardee->fullname }}" class="w-100">
                    </div>
                    <div class="mt-3 form-group">
                        <input type="file" accept="image/png, image/jpeg" class="form-control-file" name="ppImg"
                            id="ppImg">
                    </div>
                </div>

                <!-- Right Column - Profile Information -->
                <div class="col-md-8">

                    <div class="form-group">
                        <label for="fullname">Nama Lengkap</label>
                        <input type="text" class="form-control" id="fullname" name="fullname"
                            value="{{ $user->awardee->fullname }}">
                    </div>

                    <div class="form-group">
                        <label for="username">Nama Panggilan</label>
                        <input type="text" class="form-control" id="username" name="username"
                            value="{{ $user->awardee->username }}">
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" value="{{ $user->email }}" disabled>
                    </div>

                    <div class="form-group">
                        <label for="phone">Nomor Telepon</label>
                        <input type="text" class="form-control" id="phone" name="phoneNumber"
                            value="{{ $user->awardee->phone_number }}">
                    </div>

                    <div class="form-group">
                        <label for="bpi-number">No BPI</label>
                        <input type="text" class="form-control" id="bpi-number" name="bpiNumber"
                            value="{{ $user->awardee->bpi_number }}" disabled>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="degree">Jenjang Pendidikan</label>
                            <select class="form-control" name="degree" id="degree">
                                <option value="s1" @if ($user->awardee->degree == 'S1') selected @endif>S1</option>
                                <option value="s2" @if ($user->awardee->degree == 'S2') selected @endif>S2</option>
                                <option value="s3" @if ($user->awardee->degree == 'S3') selected @endif>S3</option>
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="year">Tahun Awardee BPI</label>
                            <input type="text" class="form-control" id="year" name="year"
                                value="{{ $user->awardee->year }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="faculty">Fakultas</label>
                        <select class="form-control" id="faculty">
                            @foreach ($faculties as $faculty)
                            @if ($faculty->id == $user->awardee->studyProgram->faculty->id)
                            <option value="{{$faculty->id}}" selected>{{$faculty->name}}</option>
                            @else
                            <option value="{{$faculty->id}}">{{$faculty->name}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="study-program">Program Studi</label>
                        <select class="form-control" name="studyProgramId" id="study-program"
                            value="{{ $user->awardee->studyProgram->id }}">
                            @foreach ($studyPrograms as $studyProgram)
                            @if ($studyProgram->id == $user->awardee->studyProgram->id)
                            <option value="{{$studyProgram->id}}" data-faculty-id="{{$studyProgram->faculty->id}}"
                                selected>
                                {{$studyProgram->name}}
                            </option>
                            @else
                            <option value="{{$studyProgram->id}}" data-faculty-id="{{$studyProgram->faculty->id}}">
                                {{$studyProgram->name}}
                            </option>
                            @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
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

                    <div class="text-right form-group">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            // Handle show upload image
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader()

                    reader.onload = function (e) {
                        $('#profileImg').attr('src', e.target.result)
                    }

                    reader.readAsDataURL(input.files[0])
                }
            }

            $('#ppImg').change(function() {
                readURL(this)
            })

            // Filter program studi berdasarkan fakultas
            function filterStudyProgram(facultyId) {
                $('#study-program option').each(function() {
                    // Show all options first
                    $(this).show();

                    // Hide the options that don't match the selected faculty
                    if ($(this).data('faculty-id') != facultyId && $(this).data('faculty-id') != '') {
                        $(this).hide();
                    }
                });
            }

            // Apply filter berdasarkan fakultas saat ini
            filterStudyProgram($('#faculty').val())

            // Apply filter ketika fakultas dipilih
            $('#faculty').change(function() {
                var selectedFaculty = $(this).val()
                $('#study-program').val('')

                filterStudyProgram(selectedFaculty)
            });
        })
    </script>
    @endsection

</x-admin.layout>
