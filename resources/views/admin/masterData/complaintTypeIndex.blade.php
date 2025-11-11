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
                    <button type="button" class="btn btn-primary" data-toggle="modal"
                        data-target="#addComplaintTypeModal">
                        Tambah jenis aduan
                    </button>
                </div>
                <div class="modal fade" id="addComplaintTypeModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <form id="addComplaintTypeForm">
                                @csrf
                                @method('POST')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Form Tambah Jenis Aduan</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <input type="text" name="title" class="form-control"
                                        placeholder="Masukkan jenis aduan" aria-describedby="namaJenisAduan">
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
                            <th>Nama Jenis Aduan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    {{-- <tfoot>
                        <tr>
                            <th>No</th>
                            <th>Nama Jenis Aduan</th>
                            <th>Aksi</th>
                        </tr>
                    </tfoot> --}}
                    <tbody>
                        @foreach ($complaintTypes as $complaintType)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $complaintType->title }}</td>
                            <td>
                                <div class="d-flex flex-row flex-wrap flex-lg-nowrap" style="gap: 0.5rem">
                                    <button class="btn btn-warning btn-edit w-100" data-toggle="modal"
                                        data-target="#addComplaintTypeModal"
                                        data-complaint-type="{{$complaintType}}">Edit</button>
                                    <button class="btn btn-danger btn-delete w-100"
                                        data-complaint-type-id="{{$complaintType->id}}">Delete</button>
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
            $('#addComplaintTypeForm').submit(function(e) {
                e.preventDefault();
                
                var form = $(this);
                var formData = form.serialize();

                // Disable submit button
                form.find('button[type=submit]').prop('disabled', true);

                // kalau form dalam mode edit
                if (form.data('service') == 'edit') {
                    var route = "{{ route('complaintType.update') }}";
                    formData += '&complaintTypeId=' + form.data('complaintTypeId');
                } else {
                    var route = "{{ route('complaintType.create') }}";
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
                            var input = form.find('input[name=title]');
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
            $('#addComplaintTypeModal').on('show.bs.modal', function (event) {
                var modal = $(this)
                var form = modal.find('#addComplaintTypeForm')

                // Remove all form data
                form.removeData('service')
                form.removeData('complaintTypeId')
                // Empty input
                modal.find('input[name=title]').val('')

                var button = $(event.relatedTarget) // Button that triggered the modal
                var complaintType = button.data('complaint-type') // Extract info from data-* attributes

                // kalau btn punya data fakultas maka btn adalah edit
                if (complaintType) {
                    modal.find('input[name=title]').val(complaintType.title)
                    form.data('service', 'edit')
                    form.data('complaintTypeId', complaintType.id)
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
                        var data = 'complaintTypeId=' + btn.data('complaint-type-id')

                        $.ajax({
                            type: 'POST',
                            url: "{{ route('complaintType.delete') }}",
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