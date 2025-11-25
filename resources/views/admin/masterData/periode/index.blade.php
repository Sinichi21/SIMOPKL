<x-admin.layout>
    @section('css')
    <!-- Custom styles for this page -->
    <link href="{{asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    @endsection

    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="mb-4 col-xl-3 col-md-6">
            <div class="py-2 shadow card border-left-primary h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="mr-2 col">
                            <div class="mb-1 text-xs font-weight-bold text-primary text-uppercase">
                                Total Periode</div>
                            <div class="mb-0 text-gray-800 h5 font-weight-bold">{{ $totalPeriods }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="text-gray-300 fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="mb-4 col-xl-3 col-md-6">
            <div class="py-2 shadow card border-left-success h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="mr-2 col">
                            <div class="mb-1 text-xs font-weight-bold text-success text-uppercase">
                                Periode Aktif</div>
                            <div class="mb-0 text-gray-800 h5 font-weight-bold">{{ $totalActivePeriods }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="text-gray-300 fas fa-user-graduate fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="mb-4 col-xl-3 col-md-6">
            <div class="py-2 shadow card border-left-warning h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="mr-2 col">
                            <div class="mb-1 text-xs font-weight-bold text-info text-uppercase">Periode Non Aktif
                            </div>
                            <div class="mb-0 text-gray-800 h5 font-weight-bold">{{ $totalNonActivePeriods }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="text-gray-300 fas fa-user-tie fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-4 shadow card">
        <div class="card-body">
            <div class="text-right">
                <h6>{{ \Carbon\Carbon::now()->format('d-m-Y') }}</h6>
            </div>
            <div class="d-flex justify-content-end flex-wrap mb-2" style="gap: 0.5rem;">
                <!-- Export Excel Button -->
                <div>
                    <label for="filterToggle" class="sr-only">Toggle Filter</label>
                    <button id="filterToggle" class="btn btn-secondary">Filter</button>
                </div>
                <div id="filterContainer" class="d-none">
                    <select id="statusFilter" class="form-control">
                        <option value="">Semua</option>
                        <option value="Anggota Aktif">Periode Aktif</option>
                        <option value="Anggota Biasa">Periode Non Aktif</option>
                    </select>
                </div>
                <a href="{{route('Period.create')}}">
                    <button type="button" class="btn btn-primary">
                        Tambah Baru
                    </button>
                </a>

            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="periodTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Periode</th>
                            <th>Mulai </th>
                            <th>Berakhir</th>
                            <th>status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($periodes as $periode)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $periode->name }}</td>
                            <td>{{ $periode->start_date}}</td>
                            <td>{{ $periode->end_date}}</td>
                            <td>{{ $periode->status == '1' ? 'Periode Aktif' : 'Periode Non Aktif' }}</td>
                            <td>
                                <div class="flex-row flex-wrap d-flex" style="gap: 0.5rem">
                                    <a href="{{route('Period.edit', ['periode' => $periode->id])}}" class="w-100">
                                        <button class="btn btn-warning btn-edit w-100">Edit</button>
                                    </a>
                                    <button type="button" class="btn btn-info btn-status w-100"
                                        data-id="{{$periode->id}}">{{$periode->status == '0' ? 'Non Aktif' :
                                        'Aktif'}}</button>
                                    <button type="button" class="btn btn-danger btn-delete w-100"
                                        data-id="{{ $periode->id }}">
                                        Delete
                                    </button>
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
        // export excel
        document.getElementById('exportExcel').addEventListener('click', function() {
            var wb = XLSX.utils.table_to_book(document.getElementById('periodTable'), {sheet:"Sheet1"});
            XLSX.writeFile(wb, 'period_data.xlsx');
        });

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
                        url: "{{route('Period.delete')}}",
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

        $('.btn-status').click(function() {
            var btn = $(this)
            Swal.fire({
                title: "Apakah Anda Yakin?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya"
                }).then((result) => {
                if (result.isConfirmed) {
                    // Post request
                    var data = 'id=' + btn.data('id')

                    $.ajax({
                        type: 'POST',
                        url: "{{route('Period.update.status')}}",
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

    {{-- Tabel User --}}
    <script type="text/javascript">
        $(document).ready(function() {
            const table = $('#periodTable').DataTable({
                columnDefs: [
                    { width: '5%', targets: 0 },
                    { width: '15%', targets: 6 }
                ]
            });

            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var selectedStatus = $('#statusFilter').val();
                    var statusData = data[5];

                    var categoryMatch = selectedCategory === "" || categoryData === selectedCategory;
                    var statusMatch = selectedStatus === "" || statusData === selectedStatus;

                    return categoryMatch && statusMatch;
                }
            );

            $('#kategoriFilter, #statusFilter').on('change', function() {
                table.draw();
            });

            $('#filterToggle').on('click', function() {
                $('#filterContainer').toggleClass('d-none');
            });

            document.getElementById('facultyFilter').addEventListener('change', function() {
                updateUrlParameter('faculty_id', this.value);
            });

            function updateUrlParameter(param, value) {
                const url = new URL(window.location);
                if (value) {
                    url.searchParams.set(param, value);
                } else {
                    url.searchParams.delete(param);
                }
                window.location.href = url.toString();
            }
        });
    </script>
    @endsection
</x-admin.layout>
