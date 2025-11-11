<x-admin.layout>
    @section('css')
    <!-- Custom styles for this page -->
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    @endsection

    <div class="mb-4 shadow card">
        <div class="card-body">
            <div class="text-right">
                <h6>{{ \Carbon\Carbon::now()->format('d-m-Y') }}</h6>
            </div>
            <div class="mb-3 d-flex justify-content-end" style="gap: 0.5rem">
                <a href="{{ route('fee.create') }}">
                    <button type="button" class="btn btn-primary">
                        Tambah Baru
                    </button>
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="feeTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Iuran</th>
                            <th>Nominal</th>
                            <th>Diulang Setiap</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($fees as $fee)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $fee->name }}</td>
                            <td>Rp {{ number_format($fee->amount, 2, ',', '.') }}</td>
                            <td>{{ $fee->repeat_interval }}x dalam Setahun</td>
                            <td>
                                <div class="flex-row flex-wrap d-flex" style="gap: 0.5rem">
                                    <a href="{{ route('fee.edit', $fee->id ) }}" class="w-100">
                                        <button class="btn btn-warning btn-edit w-100">Edit</button>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-delete w-100"
                                        data-id="{{ $fee->id }}">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @section('script')
    <!-- Page level plugins -->
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script type="text/javascript">
        // Tombol hapus
        $('.btn-delete').click(function() {
            var btn = $(this)
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Tindakan Anda tidak dapat diurungkan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, hapus!"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Post request
                    var data = 'id=' + btn.data('id')

                    $.ajax({
                        type: 'POST',
                        url: "{{route('fee.delete')}}",
                        data: data,
                        headers: {
                            'X-CSRF-TOKEN': "{{csrf_token()}}"
                        },
                        dataType: 'json',
                        success: function(data) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Data telah berhasil dihapus",
                                icon: "success"
                            }).then(() => {
                                location.reload()
                            })
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    })
                }
            })
        })
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            const table = $('#feeTable').DataTable({
                columnDefs: [{width: '5%', targets: 0}, {width: '10%', targets: 4}]
            });
        });
    </script>
    @endsection
</x-admin.layout>
