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
                     <button id="exportExcel" class="btn btn-primary" onclick="window.location.href='{{ route('partner.export', ['status' => 'Aktif']) }}'">
                        Export Excel
                    </button>
                </div>
                <div>
                    <label for="filterToggle" class="sr-only">Toggle Filter</label>
                    <button id="filterToggle" class="btn btn-secondary">Filter</button>
                </div>
                <div id="filterContainer" class="d-none">
                    <select id="statusFilter" class="form-control">
                        <option value="">Semua</option>
                        <option value="Anggota Aktif">Mitra Aktif</option>
                        <option value="Anggota Biasa">Mitra Non Aktif</option>
                    </select>
                </div>
                <div class="text-right mb-3">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createPeriodModal">
                        Tambah Periode
                    </button>
                </div>

                <div class="modal fade" id="createPeriodModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <form id="createPeriodForm">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title">Tambah Periode</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>

                                <div class="modal-body">
                                    <input type="text" name="name" class="form-control mb-2" placeholder="Nama Periode" required>
                                    <input type="date" name="start_date" class="form-control mb-2" required>
                                    <input type="date" name="end_date" class="form-control mb-2" required>
                                    <select name="status" class="form-control" required>
                                        <option value="1">Periode Aktif</option>
                                        <option value="0">Periode Non Aktif</option>
                                    </select>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="editPeriodModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <form id="editPeriodForm">
                                @csrf
                                @method('POST')
                                <input type="hidden" name="periodId">

                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Periode</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>

                                <div class="modal-body">
                                    <input type="text" name="name" class="form-control mb-2" required>
                                    <input type="date" name="start_date" class="form-control mb-2" required>
                                    <input type="date" name="end_date" class="form-control mb-2" required>
                                    <select name="status" class="form-control" required>
                                        <option value="1">Periode Aktif</option>
                                        <option value="0">Periode Non Aktif</option>
                                    </select>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


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
                                    <a href="{{route('Period.show', ['periode' => $periode->id])}}" class="w-100">
                                        <button class="btn btn-primary btn-edit w-100">Detail</button>
                                    </a>
                                    <button class="btn btn-warning btn-edit w-100"
                                            data-toggle="modal"
                                            data-target="#editPeriodModal"
                                            data-id="{{ $periode->id }}"
                                            data-name="{{ $periode->name }}"
                                            data-start="{{ $periode->start_date }}"
                                            data-end="{{ $periode->end_date }}"
                                            data-status="{{ $periode->status }}">
                                        Edit
                                    </button>
                                    <button class="btn btn-info btn-toggle-status w-100"
                                        data-id="{{$periode->id}}">{{$periode->status == '0' ? 'Non Aktif' :
                                        'Aktif'}}</button>
                                    <button class="btn btn-danger btn-delete w-100"
                                        data-periode-id="{{$periode->id}}">Delete</button>
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
        // export excel
        document.getElementById('exportExcel').addEventListener('click', function() {
            var wb = XLSX.utils.table_to_book(document.getElementById('periodData'), {sheet:"Sheet1"});
            XLSX.writeFile(wb, 'periode_data.xlsx');
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $(document).ready(function () {

            // CREATE
            $('#createPeriodForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('Period.create') }}",
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(data) {
                        Swal.fire("Sukses", data.msg, "success").then(() => location.reload());
                    }
                });
            });

            // Buka modal edit & isi data
            $('#editPeriodModal').on('show.bs.modal', function(event){
                var btn = $(event.relatedTarget);
                $('#editPeriodForm input[name=periodId]').val(btn.data('id'));
                $('#editPeriodForm input[name=name]').val(btn.data('name'));
                $('#editPeriodForm input[name=start_date]').val(btn.data('start'));
                $('#editPeriodForm input[name=end_date]').val(btn.data('end'));
                $('#editPeriodForm select[name=status]').val(btn.data('status'));
            });

            // UPDATE
            $('#editPeriodForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('Period.update') }}",
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(data) {
                        Swal.fire("Sukses", data.msg, "success").then(() => location.reload());
                    }
                });
            });

            // DELETE
            $('.btn-delete').click(function(){
                var id = $(this).data('periode-id');
                Swal.fire({
                    title: "Hapus?",
                    text: "Data tidak dapat dikembalikan!",
                    icon: "warning",
                    showCancelButton: true
                }).then((res)=>{
                    if(res.isConfirmed){
                        $.ajax({
                            url: "{{ route('Period.delete') }}",
                            type: 'POST',
                            data: { periodId: id },
                            success: function(data){
                                Swal.fire("Dihapus", data.msg, "success").then(() => location.reload());
                            }
                        })
                    }
                })
            });

        });

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
                    var selectedCategory = $('#kategoriFilter').val();
                    var selectedStatus = $('#statusFilter').val();
                    var categoryData = data[4];
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

            document.getElementById('periodFilter').addEventListener('change', function() {
                updateUrlParameter('period_id', this.value);
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
