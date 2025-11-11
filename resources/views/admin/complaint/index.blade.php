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
            <div class="d-flex flex-wrap justify-content-end mb-3" style="row-gap: 0.25rem">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"
                        aria-expanded="false">
                        Filter Tanggal
                    </button>
                    <div class="dropdown-menu">
                        <div class="px-2">
                            <div class="form-group">
                                <label for="dateFromPicker">Dari Tanggal</label>
                                <input type="date" class="form-control" id="dateFromPicker" name="dateFrom">
                            </div>
                            <div class="form-group">
                                <label for="dateToPicker">Sampai Tanggal</label>
                                <input type="date" class="form-control" id="dateToPicker" name="dateTo">
                            </div>
                            <button type="button" class="btn btn-success w-100" id="applyDateFilter">Terapkan</button>
                        </div>
                    </div>
                </div>
                <div class="ml-1">
                    <select id="complaintFilter" class="form-control" style="width: 7rem">
                        <option value="">Semua</option>
                        <optgroup label="Jenis aduan">
                            @foreach ($complaintTypes as $complaintType)
                            <option value="{{$complaintType->title}}">{{$complaintType->title}}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Jenjang">
                            <option value="S1">S1</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                        </optgroup>
                    </select>
                </div>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary ml-1 generate-report-btn" data-service="pdf"
                    data-toggle="modal" data-target="#exportModal">
                    Export PDF
                </button>
                <button type="button" class="btn btn-primary ml-1 generate-report-btn" data-service="excel"
                    data-toggle="modal" data-target="#exportModal">
                    Export Excel
                </button>

                <!-- Modal -->
                <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exportModalLabel">Setting</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="generate-report-form" method="GET" action="">
                                    @csrf
                                    <div class="form-group">
                                        <label for="dateFromPickerForm">Dari Tanggal</label>
                                        <input type="date" class="form-control" id="dateFromPickerForm" name="dateFrom">
                                    </div>
                                    <div class="form-group">
                                        <label for="dateToPickerForm">Sampai Tanggal</label>
                                        <input type="date" class="form-control" id="dateToPickerForm" name="dateTo">
                                    </div>
                                    <button type="submit" class="btn btn-success w-100">Generate</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="faqTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal & waktu</th>
                            <th>No. Pengaduan</th>
                            <th>No BPI</th>
                            <th>Nama</th>
                            <th>Jenjang</th>
                            <th>Jenis Aduan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    {{-- <tfoot>
                        <tr>
                            <th>No</th>
                            <th>Timestamp</th>
                            <th>No. Pengaduan</th>
                            <th>No BPI</th>
                            <th>Nama</th>
                            <th>Jenjang</th>
                            <th>Jenis Aduan</th>
                            <th>Aksi</th>
                        </tr>
                    </tfoot> --}}
                    <tbody>
                        @foreach ($complaints as $complaint)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $complaint->created_at }}</td>
                            <td>{{ $complaint->complaint_id }}</td>
                            <td>{{ $complaint->awardee->bpi_number }}</td>
                            <td>{{ $complaint->awardee->fullname }}</td>
                            <td>
                                {{$complaint->awardee->degree}} {{$complaint->awardee->studyProgram->faculty->name}}
                            </td>
                            <td>{{ $complaint->complaintType->title }}</td>
                            <td>
                                <div class="d-flex flex-row flex-wrap" style="gap: 0.5rem">
                                    <a href="{{route('admin.complaint.show', ['complaint' => $complaint->id])}}"
                                        class="w-100 btn btn-primary">
                                        Detail
                                    </a>
                                    <a href="{{route('admin.thread.show', ['thread' => $complaint->thread->id])}}"
                                        class="w-100 btn btn-warning">
                                        Thread
                                    </a>
                                    <button type="button" class="btn btn-danger btn-delete w-100"
                                        data-id="{{$complaint->id}}">Delete</button>
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
                        url: "{{route('complaint.delete')}}",
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

    {{-- Tabel fakultas --}}
    <script type="text/javascript">
        $(document).ready(function() {
            const table = $('#faqTable').DataTable({
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
                    var filter1 = data[5];
                    var filter2 = data[6];

                    if (selectedFilter === "" || filter1.includes(selectedFilter) || selectedFilter === filter2) {
                        return true;
                    }
                    return false;
                }
            );

            // Filter date
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var dateFrom = $('#dateFromPicker').val();
                    var dateTo = $('#dateToPicker').val();
                    var timestamp = data[1];

                    if (!dateFrom && !dateTo) {
                        return true;
                    }

                    // var dateFrom = new Date(dateFrom).getTime();
                    // var dateTo = new Date(dateTo).getTime();
                    // var timestamp = new Date(timestamp).getTime();

                    var dateFromParsed = dateFrom ? new Date(dateFrom).setHours(0, 0, 0, 0) : null;
                    var dateToParsed = dateTo ? new Date(dateTo).setHours(23, 59, 59, 999) : null;
                    var timestampParsed = new Date(timestamp).getTime();

                    // console.log(dateFromParsed);
                    // console.log(timestampParsed);
                    // console.log(dateToParsed);

                    if ((dateFromParsed === null || timestampParsed >= dateFromParsed) &&
                    (dateToParsed === null || timestampParsed <= dateToParsed)) {
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

            $('#dateFromPickerForm').on('change', function() {
                var dateFrom = $(this).val();
                $('#dateToPickerForm').attr('min', dateFrom);
            });
        });
    </script>
    @endsection
</x-admin.layout>