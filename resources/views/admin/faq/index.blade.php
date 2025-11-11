<x-admin.layout>
    @section('css')
    <!-- Custom styles for this page -->
    <link href="{{asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    @endsection

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="text-right">
                <h6>{{ \Carbon\Carbon::now()->format('d-m-Y') }}</h6>
            </div>
            <div class="d-flex justify-content-end mb-3" style="gap: 0.5rem">
                <div>
                    <select id="faqFilter" class="form-control">
                        <option value="">Semua</option>
                        <option value="publish">Publish</option>
                        <option value="unpublish">Unpublish</option>
                    </select>
                </div>
                <a href="{{route('faq.create')}}">
                    <button type="button" class="btn btn-primary">
                        Tambah Baru
                    </button>
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="faqTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pertanyaan</th>
                            <th>Status</th>
                            <th>Last Updated</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    {{-- <tfoot>
                        <tr>
                            <th>No</th>
                            <th>Pertanyaan</th>
                            <th>Status</th>
                            <th>Last Updated</th>
                            <th>Aksi</th>
                        </tr>
                    </tfoot> --}}
                    <tbody>
                        @foreach ($faqs as $faq)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $faq->question }}</td>
                            <td>{{ $faq->status }}</td>
                            <td>{{ $faq->updated_at }}</td>
                            <td>
                                <div class="d-flex flex-row flex-wrap" style="gap: 0.5rem">
                                    <a href="{{route('faq.show', ['faq' => $faq->id])}}" class="w-100">
                                        <button class="btn btn-primary btn-edit w-100">Detail</button>
                                    </a>
                                    <a href="{{route('faq.edit', ['faq' => $faq->id])}}" class="w-100">
                                        <button class="btn btn-warning btn-edit w-100">Edit</button>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-delete w-100"
                                        data-id="{{$faq->id}}">Delete</button>
                                    <button type="button" class="btn btn-info btn-status w-100"
                                        data-id="{{$faq->id}}">{{$faq->status == 'publish' ? 'Unpublish' :
                                        'publish'}}</button>
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
    <script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>

    <script type="text/javascript">
        // Tombol hapus
        $('.btn-delete').click(function() {
            var btn = $(this)
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                if (result.isConfirmed) {
                    // Post request
                    var data = 'id=' + btn.data('id')

                    $.ajax({
                        type: 'POST',
                        url: "{{route('faq.delete')}}",
                        data: data,
                        headers: {
                            'X-CSRF-TOKEN': "{{csrf_token()}}"
                        },
                        dataType: 'json',
                        success: function(data) {
                            Swal.fire({
                                title: "Deleted!",
                                text: data.msg,
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

        $('.btn-status').click(function() {
            var btn = $(this)
            Swal.fire({
                title: "Are you sure?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes"
                }).then((result) => {
                if (result.isConfirmed) {
                    // Post request
                    var data = 'id=' + btn.data('id')

                    $.ajax({
                        type: 'POST',
                        url: "{{route('faq.update.status')}}",
                        data: data,
                        headers: {
                            'X-CSRF-TOKEN': "{{csrf_token()}}"
                        },
                        dataType: 'json',
                        success: function(data) {
                            Swal.fire({
                                title: "Updated!",
                                text: data.msg,
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

    {{-- Tabel fakultas --}}
    <script type="text/javascript">
        $(document).ready(function() {
            const table = $('#faqTable').DataTable({
                columnDefs: [{width: '5%', targets: 0}, {width: '10%', targets: 4}]
            });

            // Custom filtering function for faculty
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var selectedStatus = $('#faqFilter').val();
                    var status = data[2]; // Use the index for "Nama Fakultas" column

                    if (selectedStatus === "" || selectedStatus === status) {
                        return true;
                    }
                    return false;
                }
            );

            // Event listener for filter dropdown
            $('#faqFilter').on('change', function() {
                table.draw();
            });
        });
    </script>
    @endsection
</x-admin.layout>