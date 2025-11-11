<x-landing-page.layout>
    <div>
        @session('msg')
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
        <div class="lg-card shadow-lg">
            <div class="card-body">
                <form action="{{route('admin.profile.update')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="userId" value="{{$user->id}}">
                    <input type="hidden" name="adminId" value="{{$user->admin->id}}">
                    <div class="row">
                        <div class="col-lg-9">
                            <div class="row">
                                <div class="col-12 col-md-6 form-group">
                                    <label for="email">Email</label>
                                    <input type="email" disabled class="form-control" id="email"
                                        value="{{ Auth::user()->email }}">
                                </div>
                                <div class="col-12 col-md-6 form-group">
                                    <label for="fullname">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="fullname" name="fullname" required
                                        value="{{ Auth::user()->admin->fullname }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6 form-group">
                                    <label for="role">Role</label>
                                    <input type="text" disabled class="form-control" id="role"
                                        value="{{ Auth::user()->role->title }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6 form-group">
                                    <label for="password">Ganti Kata Sandi</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                                <div class="col-12 col-md-6 form-group">
                                    <label for="confirmPassword">Konfirmasi Kata Sandi</label>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 px-4 text-center">
                            <div class="border w-100 mx-auto">
                                <img id="profileImg"
                                    src="{{ Auth::user()->admin->pp_url ? asset('storage/'.Auth::user()->admin->pp_url) : asset('img/undraw_profile.svg') }}"
                                    alt="Profile Picture" class="w-100">
                            </div>
                            <div class="form-group mt-3">
                                <input type="file" accept="image/png, image/jpeg" class="form-control-file" name="ppImg"
                                    id="ppImg">
                            </div>
                        </div>
                    </div>
                    <div class=" text-right mt-4">
                        <button type="button" class="btn btn-secondary">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
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
        })
    </script>
</x-landing-page.layout>
