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

        <div class="card shadow-lg">
            <div class="card-body">
                <form id="resetForm" method="POST" action="{{ route('password.update') }}" enctype="multipart/form-data">
                    <input type="hidden" name="token" value="{{ $token }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row ">
                                <div class="col-12 col-md-6 form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="&email">
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-12 col-md-6 form-group">
                                    <label for="password">Kata Sandi</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="password">
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-12 col-md-6 form-group">
                                    <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="password">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right mt-4">
                        <button type="button" class="btn btn-secondary" id="btnCancel">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#resetForm').submit(function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda tidak akan dapat mengembalikan ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, simpan!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('password.update') }}",
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                console.log(response); // Tambahkan ini untuk melihat response
                                Swal.fire(
                                    'Berhasil!',
                                    'Reset password link berhasil dikrimkan',
                                    'success'
                                ).then(() => {
                                    window.location.href = "{{ route('user.edit') }}";
                                });
                            },
                            error: function(response) {
                                console.log(response); // Tambahkan ini untuk melihat response
                                Swal.fire(
                                    'Gagal!',
                                    'Ada kesalahan saat mengirimkan reset password link',
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
                        $('#resetForm')[0].reset();
                    }
                });
            });
        });
    </script>
