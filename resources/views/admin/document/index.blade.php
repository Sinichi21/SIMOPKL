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
                <a href="{{ route('doc.create') }}">
                    <button type="button" class="btn btn-primary">
                        Tambah Baru
                    </button>
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="docTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Dokumen</th>
                            <th>Deskripsi</th>
                            <th>Waktu Upload</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($documents as $document)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $document->name }}</td>
                            <td>{{ $document->description }}</td>
                            <td>{{ $document->uploaded_at->setTimezone('Asia/Jakarta')->translatedFormat('j F Y, \p\u\k\u\l H.i T') }}</td>
                            <td>
                                <div class="flex-row flex-wrap d-flex" style="gap: 0.5rem">
                                    @if(pathinfo($document->file_path, PATHINFO_EXTENSION) === 'pdf')
                                        <a href="{{ route('doc.show', $document->id) }}" class="btn btn-primary">Lihat Dokumen</a>
                                    @elseif(in_array(pathinfo($document->file_path, PATHINFO_EXTENSION), ['xls', 'xlsx']))
                                        <a href="{{ asset('storage/' . $document->file_path) }}" download class="btn btn-primary">Download Excel</a>
                                    @endif
                                    <a href="{{route('doc.edit', $document->id )}}" class="w-100">
                                        <button class="btn btn-warning btn-edit w-100">Edit</button>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-delete w-100"
                                        data-id="{{ $document->id }}">
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
                        url: "{{route('doc.delete')}}",
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
            const table = $('#docTable').DataTable({
                columnDefs: [{width: '5%', targets: 0}, {width: '10%', targets: 4}]
            });
        });
    </script>
    @endsection
</x-admin.layout>
