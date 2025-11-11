<x-admin.layout>

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
                                        value="{{ $user->email }}">
                                </div>
                                <div class="col-12 col-md-6 form-group">
                                    <label for="fullname">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="fullname" name="fullname" required
                                        value="{{ $user->admin->fullname }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6 form-group">
                                    <label for="role">Role</label>
                                    <input type="text" disabled class="form-control" id="role"
                                        value="{{ $user->role->title }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6 form-group">
                                    <label for="password">Ganti Kata Sandi</label>
                                    <div class="d-flex flex-row">
                                        <input type="password" class="form-control" id="password" name="password">
                                        <button id="hide-password-toggle" type="button"
                                            class="px-2 border-0 bg-transparent" data-hide="true">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 form-group">
                                    <label for="confirmPassword">Konfirmasi Kata Sandi</label>
                                    <div class="d-flex flex-row">
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation">
                                        <button id="hide-password-confirmation-toggle" type="button"
                                            class="px-2 border-0 bg-transparent" data-hide="true">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 px-4 text-center">
                            <div class="border w-100 mx-auto">
                                <img id="profileImg"
                                    src="{{ $user->pp_url ? asset('storage/'.$user->pp_url) : asset('img/undraw_profile.svg') }}"
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

    @section('script')
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

            $('#hide-password-toggle').click(function() {
                var hide = $(this).data('hide')

                $(this).data('hide', !hide)

                hide = !hide
                
                var input = $(this).siblings('input');
                
                if (hide) {
                    input.attr('type', 'password');
                    $(this).html('<i class="fas fa-eye-slash"></i>');
                } else {
                    input.attr('type', 'text');
                    $(this).html('<i class="fas fa-eye"></i>');
                }
            })

            $('#hide-password-confirmation-toggle').click(function() {
                var hide = $(this).data('hide')

                $(this).data('hide', !hide)

                hide = !hide
                
                var input = $(this).siblings('input');
                
                if (hide) {
                    input.attr('type', 'password');
                    $(this).html('<i class="fas fa-eye-slash"></i>');
                } else {
                    input.attr('type', 'text');
                    $(this).html('<i class="fas fa-eye"></i>');
                }
            })
        })
    </script>
    @endsection

</x-admin.layout>