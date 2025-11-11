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
            <div>
                <div class="text-right mb-3">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addFacultyModal">
                        Tambah fakultas
                    </button>
                </div>
                <div class="modal fade" id="addFacultyModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <form id="addFacultyForm">
                                @csrf
                                @method('POST')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Form Tambah Fakultas</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <input type="text" name="name" class="form-control"
                                        placeholder="Masukkan nama fakultas" aria-describedby="namaFakultas">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="facultyTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Fakultas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    {{-- <tfoot>
                        <tr>
                            <th>No</th>
                            <th>Nama Fakultas</th>
                            <th>Aksi</th>
                        </tr>
                    </tfoot> --}}
                    <tbody>
                        @foreach ($faculties as $faculty)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $faculty->name }}</td>
                            <td>
                                <div class="d-flex flex-row flex-wrap flex-lg-nowrap" style="gap: 0.5rem">
                                    <button class="btn btn-warning btn-edit w-100" data-toggle="modal"
                                        data-target="#addFacultyModal" data-faculty="{{$faculty}}">Edit</button>
                                    <button class="btn btn-danger btn-delete w-100"
                                        data-faculty-id="{{$faculty->id}}">Delete</button>
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
        $(document).ready(function() {
            // Handle form submit for create and edit
            $('#addFacultyForm').submit(function(e) {
                e.preventDefault();
                
                var form = $(this);
                var formData = form.serialize();

                // Disable submit button
                form.find('button[type=submit]').prop('disabled', true);

                // kalau form dalam mode edit
                if (form.data('service') == 'edit') {
                    var route = "{{ route('faculty.update') }}";
                    formData += '&facultyId=' + form.data('facultyId');
                } else {
                    var route = "{{ route('faculty.create') }}";
                }

                $.ajax({
                    url: route,
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);

                        Swal.fire({
                            title: "Berhasil",
                            text: data.msg,
                            icon: "success"
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(data) {
                        console.log(data);
                        // Remove previous error
                        $('.error-message').remove();
                        form.find('.is-invalid').removeClass('is-invalid');
    
                        // Create error message
                        var errors = data.responseJSON.errors;
                        for (const field in errors) {
                            // Add error class to input
                            var input = form.find('input[name=name]');
                            input.addClass('is-invalid');
                            // Add error message below input
                            var element = '<div class="error-message invalid-feedback">'
                            element += errors[field];
                            element += '</div>';
                            input.after(element);

                        }
                    }
                });
            });

            // Mengubah service form menjadi edit
            $('#addFacultyModal').on('show.bs.modal', function (event) {
                var modal = $(this)
                var form = modal.find('#addFacultyForm')

                // Remove all form data
                form.removeData('service')
                form.removeData('facultyId')
                // Empty input
                modal.find('input[name=name]').val('')

                var button = $(event.relatedTarget) // Button that triggered the modal
                var faculty = button.data('faculty') // Extract info from data-* attributes

                // kalau btn punya data fakultas maka btn adalah edit
                if (faculty) {
                    modal.find('input[name=name]').val(faculty.name)
                    form.data('service', 'edit')
                    form.data('facultyId', faculty.id)
                }

            })

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
                        var data = 'facultyId=' + btn.data('faculty-id')

                        $.ajax({
                            type: 'POST',
                            url: "{{ route('faculty.delete') }}",
                            data: data,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
                                // Remove previous error
                                $('.error-message').remove()

                                // Create error message
                                var errors = data.responseJSON.errors
                                var element = '<div class="error-message alert alert-danger" role="alert">'
                                for (const err in errors) {
                                    element += '<li>' + errors[err] + '</li>'
                                }
                                element += '</div>'

                                console.log(errors)
                            }
                        })
                    }
                })
            })
        });
    </script>

    {{-- Tabel fakultas --}}
    <script type="text/javascript">
        $(document).ready(function() {
            $('#facultyTable').DataTable({
                columnDefs: [{width: '5%', targets: 0}, {width: '10%', targets: 2}]
            });
        });
    </script>
    @endsection
</x-admin.layout>