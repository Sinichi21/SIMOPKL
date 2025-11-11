<x-admin.layout>
    @section('css')
    <!-- Custom styles for this page -->
    <link href="{{asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <style>
        /* Center align table content */
        #logTable td, #logTable th {
            text-align: center;
        }
    </style>
    @endsection

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="text-right">
                <h6>{{ \Carbon\Carbon::now()->format('d-m-Y') }}</h6>
            </div>
            <div class="d-flex flex-wrap justify-content-end mb-3" style="row-gap: 0.25rem">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                        Filter Tanggal
                    </button>
                    <div class="dropdown-menu">
                        <div class="px-2">
                            <div class="form-group">
                                <label for="datePicker">Pilih Tanggal</label>
                                <input type="date" class="form-control" id="datePicker" name="date">
                            </div>
                            <button type="button" class="btn btn-success w-100" id="applyDateFilter">Terapkan</button>
                        </div>
                    </div>
                </div>
                <div class="ml-1">
                    <button class="btn btn-danger generate-report-btn" id="deleteSelected">Hapus Log</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="logTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll" style="float:left;"></th>
                            <th>No</th>
                            <th>Waktu Log</th>
                            <th>Pengguna</th>
                            <th>Aksi</th>
                            <th>Menu</th>
                            <th>Deskripsi Log</th>
                            <th>Alamat IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $index => $log)
                            <tr>
                                <td><input type="checkbox" class="selectItem" value="{{ $log->id }}" style="float:left;"></td>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $log->created_at->format('d M Y H:i:s') }}</td>
                                <td>@if ($log->user) {{ $log->user->email }} @endif</td>
                                <td>
                                    @switch($log->action)
                                        @case('view')
                                            <span class="badge badge-secondary">View</span>
                                            @break
                                        @case('create')
                                            <span class="badge badge-primary">Create</span>
                                            @break
                                        @case('update')
                                            <span class="badge badge-info">Update</span>
                                            @break
                                        @case('delete')
                                            <span class="badge badge-warning">Delete</span>
                                            @break
                                        @case('login')
                                            <span class="badge badge-success">Login</span>
                                            @break
                                        @case('logout')
                                            <span class="badge badge-danger">Logout</span>
                                            @break
                                        @default
                                            <span class="badge badge-dark">{{ ucfirst($log->action) }}</span>
                                    @endswitch
                                </td>
                                <td>{{ $log->module }}</td>
                                <td>{{ $log->description }}</td>
                                <td>{{ $log->ip_address }}</td>
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
        $(document).ready(function() {
            const table = $('#logTable').DataTable({
                "order": [[0, "asc"]],
                columnDefs: [
                    { width: '5%', targets: 0 },
                    { width: '10%', targets: 7 },
                    { orderable: false, targets: [0] }
                ]
            });
        
            $('#selectAll').on('click', function() {
                $('.selectItem').prop('checked', this.checked);
            });
        
            $('.selectItem').on('click', function() {
                if (!this.checked) {
                    $('#selectAll').prop('checked', false);
                }
            });
        
            $('#deleteSelected').on('click', function() {
                let selectedIds = [];
                $('.selectItem:checked').each(function() {
                    selectedIds.push($(this).val());
                });
        
                if (selectedIds.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No logs selected',
                        text: 'Please select at least one log to delete.'
                    });
                    return;
                }
        
                Swal.fire({
                    title: "Apakah Anda yakin?",
                    text: "Tindakan ini tidak dapat diurungkan!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, hapus!",
                    cancelButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('logs.delete') }}",
                            type: "POST",
                            data: {
                                ids: selectedIds,
                                _token: "{{ csrf_token() }}"
                            },
                            dataType: 'json',
                            success: function(response) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: response.success,
                                    icon: "success"
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: "error",
                                    title: "Error",
                                    text: "An error occurred while deleting the logs. Please try again."
                                });
                            }
                        });
                    }
                });
            });
        
            $('#applyDateFilter').on('click', function() {
                const selectedDate = $('#datePicker').val();
        
                $.fn.dataTable.ext.search = []
        
                if (selectedDate) {
                    const filterDate = new Date(selectedDate);
                    const nextDay = new Date(filterDate);
                    nextDay.setDate(nextDay.getDate() + 1);
        
                    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                        const logDate = new Date(data[2]);
                        return logDate >= filterDate && logDate < nextDay;
                    });
        
                    table.draw();
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Date',
                        text: 'Please select a date to filter.'
                    });
                }
            });
        });
    </script>
    @endsection
</x-admin.layout>
