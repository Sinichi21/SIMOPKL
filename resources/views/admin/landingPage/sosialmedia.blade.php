<x-admin.layout>
    @section('css')
    <!-- Custom styles for this page -->
    <link href="{{asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <!-- Tambahkan CSS untuk Bootstrap modal -->
    <link href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    @endsection
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="text-right">
                <h6>{{ \Carbon\Carbon::now()->format('d-m-Y') }}</h6>
            </div>
            <form id="socialMediaForm"> <!-- tambahkan ID unik di sini -->
                @csrf <!-- tambahkan CSRF token -->
                <div class="form-group">
                    <label for="instagram">Instagram:</label>
                    <input type="text" class="form-control" id="instagram" name="instagram" value="{{ $socialMedia['instagram']->url ?? '' }}" placeholder="Instagram URL">
                </div>
                <div class="form-group">
                    <label for="youtube">YouTube:</label>
                    <input type="text" class="form-control" id="youtube" name="youtube" value="{{ $socialMedia['youtube']->url ?? '' }}" placeholder="YouTube URL">
                </div>
                <div class="row justify-content-end mt-3 mr-1">
                    <button type="button" class="btn btn-secondary mr-1" id="btnCancel">Batal</button>
                    <button type="button" class="btn btn-primary ml-1" id="btn-confirm">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    @section('script')
    <!-- Tambahkan JS untuk Bootstrap modal -->
    <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script>
    $(document).ready(function() {
        // $('#btn-confirm').click(function() {
        //     $('#confirmModal').modal('show');
        // });

        $('#btn-confirm').click(function() {
            var formData = $('#socialMediaForm').serialize();

            Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda tidak akan dapat mengembalikan ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, simpan!'
                }).then((result) =>{
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('landingpage.sosialmediasave') }}',
                            type: 'POST',
                            data: formData,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                console.log(response); // Tambahkan ini untuk melihat response
                                Swal.fire(
                                    'Berhasil!',
                                    'Sosial Media berhasil diupdate.',
                                    'success'
                                ).then(() => {
                                    window.location.href = "{{ route('landingpage.sosialmedia') }}";
                                });
                            },
                            error: function(response) {
                                console.log(response); // Tambahkan ini untuk melihat response
                                Swal.fire(
                                    'Gagal!',
                                    'Ada kesalahan saat mengupdate sosial media.',
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
                        $('#socialMediaForm')[0].reset();
                    }
                });
            });
    });
    </script>
    @endsection
</x-admin.layout>
