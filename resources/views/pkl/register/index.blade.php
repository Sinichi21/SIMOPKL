<x-awardee.top-nav>
    @section('css')
    <!-- Custom styles for this page -->
    <link href="{{asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    @endsection

    <div class="mb-4 shadow card">
        <div class="card-body">
            <div class="text-right">
                <h6>{{ \Carbon\Carbon::now()->format('d-m-Y') }}</h6>
            </div>
            <div class="mb-2 d-flex justify-content-end">
                <a href="{{route('complaint.create')}}">
                    <button type="button" class="gap-1 btn btn-primary w-100">
                        Tambah Baru
                    </button>
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="registrationTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No. Registrasi</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Periode</th>
                            <th>Mitra</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    {{-- <tfoot>
                        <tr>
                            <th>No</th>
                            <th>No. Registrasi</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Periode</th>
                            <th>Mitra</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </tfoot> --}}
                    <tbody>
                        @foreach ($registrations as $registration)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $registration->registration_number }}</td>
                            <td>{{ $registration->awardee->nim }}</td>
                            <td>{{ $registration->awardee->fullname }}</td>
                            <td>{{ $registration->periode->name }}</td>
                            <td>
                                {{$registration->mitra->name}}
                            </td>
                            <td>{{ $registration->status }}</td>
                            <td>
                                <div class="flex-row flex-wrap d-flex" style="gap: 0.5rem">
                                    <a href="{{route('Registration.show', ['register' => $registration->id])}}"
                                        class="w-100 btn btn-primary">
                                        Detail
                                    </a>
                                    <a href="{{route('Registration.edit', ['register' => $registration->id])}}"
                                        class="w-100">
                                        <button class="btn btn-warning w-100">Edit</button>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-delete w-100"
                                        data-id="{{$registration->id}}">Delete</button>
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
    <script src=" {{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}">
    </script>

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
                        url: "{{route('Registration.delete')}}",
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

        // Handle tombol generate
        $('.generate-report-btn').click(function() {
            var btn = $(this)
            var service = btn.data('service')
            var generateReportForm = $('#generate-report-form')

            if (service === 'pdf') {
                generateReportForm.attr('action', "{{route('report.pdf')}}")
            } else (
                generateReportForm.attr('action', "{{route('report.excel')}}")
            )
        })
    </script>

    {{-- Tabel registrasi --}}
    <script type="text/javascript">
        $(document).ready(function() {
            const table = $('#registrationTable').DataTable({
                columnDefs: [
                    {width: '5%', targets: 0},
                    {width: '10%', targets: 7},
                    {orderable: false, targets: [1, 2, 3, 4, 5, 6, 7] }
                ]
            });

            // Custom filtering function for faculty
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var selectedFilter = $('#complaintFilter').val();
                    var filter1 = data[5]; // Use the index for "Nama Fakultas" column
                    var filter2 = data[6];

                    if (selectedFilter === "" || filter1.includes(selectedFilter) || selectedFilter === filter2) {
                        return true;
                    }
                    return false;
                }
            );

            // Event listener for filter dropdown
            $('#complaintFilter').on('change', function() {
                table.draw();
            });

            // Event listener for date range filter
            $('#applyDateFilter').on('click', function() {
                table.draw();
                $('.dropdown-menu').removeClass('show');
            });

            $('#dateFromPicker').on('change', function() {
                var dateFrom = $(this).val();
                $('#dateToPicker').attr('min', dateFrom);
            });
        });
    </script>
    @endsection
</x-awardee.top-nav>
