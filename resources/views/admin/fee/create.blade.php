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

        .table-wrapper {
            max-height: 300px;  /* Tentukan tinggi maksimum yang diinginkan */
            overflow-y: auto;   /* Aktifkan scroll vertikal */
            display: block;
        }

        thead th {
            position: sticky;
            top: 0;
            background-color: #f8f9fa; /* Background agar terlihat kontras */
            z-index: 1; /* Pastikan th berada di atas konten */
        }

        table {
            width: 100%;
            table-layout: fixed;
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
                <form id="feeForm" method="POST" action="{{ route('fee.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- Form Fields -->
                            <div class="form-group">
                                <label for="name">Nama Iuran</label>
                                <input type="text" class="form-control" id="name" name="name" required placeholder="Contoh Iuran">
                            </div>

                            <div class="form-group">
                                <label for="amount">Nominal</label>
                                <input type="text"
                                    class="form-control"
                                    id="amount"
                                    name="amount"
                                    required
                                    placeholder="Masukkan nominal dalam Rp."
                                    oninput="formatCurrency(this)">
                            </div>

                            <div class="form-group">
                                <label for="repeat_interval">Diulang setiap</label>
                                <input class="form-control" type="number" name="repeat_interval" id="repeat_interval" required placeholder="Dalam angka. Contoh : 2">
                            </div>

                            <div class="form-group">
                                <label for="users">Pilih Pengguna yang Wajib Bayar</label>
                                <div class="table-wrapper">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="selectAll"></th>
                                                <th>Nama</th>
                                                <th>Email</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($users as $user)
                                            <tr>
                                                <td><input type="checkbox" class="user-checkbox" name="users[]" value="{{ $user->id }}"></td>
                                                <td>{{ $user->awardee->fullname }}</td>
                                                <td>{{ $user->email }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-right">
                        <button type="button" class="mr-1 btn btn-secondary" id="btnCancel">Batal</button>
                        <button type="submit" class="ml-1 btn btn-primary" id="btn-confirm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function formatCurrency(input) {
            let value = input.value.replace(/[^,\d]/g, '');

            let [integer, decimal] = value.split(',');
            integer = integer.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            input.value = decimal ? `Rp ${integer},${decimal}` : `Rp ${integer}`;
        }

        $(document).ready(function() {
            // Menangani checkbox "Pilih Semua"
            $('#selectAll').change(function() {
                var isChecked = $(this).prop('checked');
                $('.user-checkbox').prop('checked', isChecked);
            });

            var form = $('#feeForm');

            $('#btn-confirm').click(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Apakah Anda yakin ingin menambahkan dokumen?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, simpan!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var formData = new FormData(form[0]);

                        $.ajax({
                            url: "{{ route('fee.store') }}",
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                Swal.fire(
                                    'Berhasil!',
                                    'data berhasil ditambahkan.',
                                    'success'
                                ).then(() => {
                                    window.location.href = "{{ route('fee.index') }}";
                                });
                            },
                            error: function(response) {
                                Swal.fire(
                                    'Gagal!',
                                    'Ada kesalahan saat menambahkan data.',
                                    'error'
                                );
                                console.log(response);
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
                        window.location.href = '{{ route('fee.index') }}';
                    }
                });
            });
        });
    </script>
</x-admin.layout>
