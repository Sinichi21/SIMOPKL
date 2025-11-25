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
                                <p class="mt-3" style="font-size: 1.5rem; font-weight: 500;">Loading periode created...</p>
                            </div>
                        </div>
                    </div>
                </div>
                <form id="periodForm" method="POST" action="{{ route('Period.update') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="periodId" value="{{ $periode->id }}">
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- Form Fields -->
                            <div class="row">
                                <div class="col-12 col-md-6 form-group">
                                    <label for="name">Nama Periode</label>
                                    <input type="text" class="form-control" id="name" name="name" required value="{{ $periode->name }}">
                                </div>
                                <div class="col-12 col-md-6 form-group">
                                    <label for="start_date">Mulai</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" required Value="{{ $periode->start_date }}">
                                </div>
                            </div>
                            <!-- More Form Fields -->
                            <div class="row">
                                <div class="col-12 col-md-6 form-group">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value=1 {{ $periode->status == 1 ? 'selected' : '' }}>Aktif</option>
                                        <option value=0 {{ $periode->status == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 form-group">
                                    <label for="end_date">Berakhir</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" required value="{{ $periode->end_date }}">
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
        $(document).ready(function() {
            var form = $('#periodForm');

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
                            url: "{{ route('Period.update') }}",
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
                                    window.location.href = "{{ route('Period.index') }}";
                                });
                            },
                            error: function(response) {
                                Swal.fire(
                                    'Gagal!',
                                    'Ada kesalahan saat Mengedit Periode.',
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
                        window.location.href = '{{ route('Period.index') }}';
                    }
                });
            });

        });
    </script>
</x-admin.layout>
