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
                <div class="d-flex justify-content-end mb-3" style="gap: 0.5rem">
                    <div>
                        <select id="facultyFilter" class="form-control">
                            <option value="">Semua Fakultas</option>
                            @foreach ($faculties as $faculty)
                            <option value="{{ $faculty->name }}">{{ $faculty->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary" data-toggle="modal"
                        data-target="#addStudyProgramModal">
                        Tambah Program Studi
                    </button>
                </div>
                <div class="modal fade" id="addStudyProgramModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <form id="addStudyProgramForm">
                                @csrf
                                @method('POST')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Form Tambah Program Studi</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <input type="text" name="name" class="form-control"
                                        placeholder="Masukkan nama program studi" aria-describedby="namaProgramStudi">
                                    <select name="facultyId" class="form-control mt-2">
                                        <option selected disabled>Pilih fakultas</option>
                                        @foreach ($faculties as $faculty)
                                        <option value="{{$faculty->id}}">{{$faculty->name}}</option>
                                        @endforeach
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
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="facultyTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Program Studi</th>
                            <th>Nama Fakultas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    {{-- <tfoot>
                        <tr>
                            <th>No</th>
                            <th>Nama Program Studi</th>
                            <th>Nama Fakultas</th>
                            <th>Aksi</th>
                        </tr>
                    </tfoot> --}}
                    <tbody>
                        @foreach ($studyPrograms as $program)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $program->name }}</td>
                            <td>{{ $program->faculty->name }}</td>
                            <td>
                                <div class="d-flex flex-row flex-wrap flex-lg-nowrap" style="gap: 0.5rem">
                                    <button class="btn btn-warning btn-edit w-100" data-toggle="modal"
                                        data-target="#addStudyProgramModal"
                                        data-study-program="{{$program}}">Edit</button>
                                    <button class="btn btn-danger btn-delete w-100"
                                        data-study-program-id="{{$program->id}}">Delete</button>
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
            $('#addStudyProgramForm').submit(function(e) {
                e.preventDefault();
                
                var form = $(this);
                var formData = form.serialize();

                // Disable submit button
                form.find('button[type=submit]').prop('disabled', true);

                // kalau form dalam mode edit
                if (form.data('service') == 'edit') {
                    var route = "{{ route('studyProgram.update') }}";
                    formData += '&studyProgramId=' + form.data('studyProgramId');
                } else {
                    var route = "{{ route('studyProgram.create') }}";
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
                            var input = form.find(`[name=${field}]`);
                            input.addClass('is-invalid');
                            
                            // Add error message below the input/select
                            var element = `<div class="error-message invalid-feedback">${errors[field]}</div>`;
                            input.after(element);
                        }
                    }
                });
            });

            // Mengubah service form menjadi edit
            $('#addStudyProgramModal').on('show.bs.modal', function (event) {
                var modal = $(this)
                var form = modal.find('#addStudyProgramForm')

                // Remove all form data
                form.removeData('service')
                form.removeData('studyProgramId')
                // Empty input
                modal.find('input[name=name]').val('')
                modal.find('select').val('')

                var button = $(event.relatedTarget) // Button that triggered the modal
                var studyProgram = button.data('study-program') // Extract info from data-* attributes

                // kalau btn punya data fakultas maka btn adalah edit
                if (studyProgram) {
                    modal.find('input[name=name]').val(studyProgram.name)
                    modal.find('select').val(studyProgram.faculty_id)
    
                    form.data('service', 'edit')
                    form.data('studyProgramId', studyProgram.id)
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
                        var data = 'studyProgramId=' + btn.data('study-program-id')

                        $.ajax({
                            type: 'POST',
                            url: "{{ route('studyProgram.delete') }}",
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
            var table = $('#facultyTable').DataTable({
                columnDefs: [{width: '5%', targets: 0}, {width: '10%', targets: 3}]
            });

            // Custom filtering function for faculty
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var selectedFaculty = $('#facultyFilter').val();
                    var facultyName = data[2]; // Use the index for "Nama Fakultas" column

                    if (selectedFaculty === "" || facultyName === selectedFaculty) {
                        return true;
                    }
                    return false;
                }
            );

            // Event listener for filter dropdown
            $('#facultyFilter').on('change', function() {
                table.draw();
            });
        });
    </script>
    @endsection
</x-admin.layout>