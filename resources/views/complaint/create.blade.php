<x-awardee.top-nav>
    @section('css')
    <!-- Custom styles for this page -->
    <link href="{{asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    @endsection

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tambah Pengaduan</h6>
        </div>
        <div class="card-body">
            <form id="complaint-form">
                <input type="hidden" name="awardeeId" value="{{$awardee->id}}">
                @csrf
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <label for="complaint_id">No. Pengaduan</label>
                        <input type="text" class="form-control" id="complaint_id" disabled>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <label for="bpi_number">No. BPI</label>
                        <input type="text" class="form-control" id="bpi_number" value="{{$awardee->bpi_number}}"
                            disabled>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="degree">Degree</label>
                        <input type="text" class="form-control" id="degree" value="{{$awardee->degree}}" disabled>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <label for="fullname">Fullname</label>
                        <input type="text" class="form-control" id="fullname" value="{{$awardee->fullname}}" disabled>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="faculty_id">Faculty</label>
                        <input type="text" class="form-control" id="faculty"
                            value="{{$awardee->studyProgram->faculty->name}}" disabled>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" value="{{$awardee->username}}" disabled>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="program_study_id">Program Study</label>
                        <input type="text" class="form-control" id="studyProgram"
                            value="{{$awardee->studyProgram->name}}" disabled>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" value="{{$awardee->user->email}}" disabled>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="complaint_type_id">Jenis aduan</label>
                        <select class="form-control" id="complaint_type_id" name="complaintTypeId" required>
                            <option value="">Pilih jenis aduan</option>
                            @foreach ($complaintTypes as $complaintType)
                            <option value="{{$complaintType->id}}">{{$complaintType->title}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <label for="content">Dekripsi</label>
                        <textarea class="form-control" id="content" name="content" rows="4" required></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group mt-3">
                            <input type="file" accept="image/png, image/jpeg, application/pdf" class="form-control-file"
                                name="medias[]" multiple id="media">
                        </div>
                        <ul id="media-names"></ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 text-right">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('complaint.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            // Menampilkan nama media yang diupload
            $('#media').on('change', function() {
                var filenames = [];
                for (var i = 0; i < this.files.length; i++) {
                    filenames.push(this.files[i].name);
                }

                var list = '';

                filenames.map(filename => {
                    list += '<li>' + filename + '</li>'
                })

                $('#media-names').html(list);
            });

            $('#complaint-form').submit(function(e) {
                e.preventDefault()

                var form = $(this);
                var data = new FormData(form[0]);

                // Disable submit button
                $('form').find('button[type=submit]').prop('disabled', true);

                $.ajax({
                    url: "{{route('complaint.store')}}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{csrf_token()}}"
                    },
                    data: data,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);

                        Swal.fire({
                            title: "Berhasil",
                            text: data.msg,
                            icon: "success"
                        }).then(() => {
                            location.replace("{{route('complaint.index')}}");
                        });
                    },
                    error: function(data) {
                        console.log(data);
                    }
                })
            })
        })
    </script>

    <script>
        $(document).ready(function() {
            var dropzone = window.Dropzone;

            // let myDropzone = new Dropzone("div.complaint-dropzone", { url: "/file/post"});

            Dropzone.options.complaintForm = { // The camelized version of the ID of the form element
                // The configuration we've talked about above
                autoProcessQueue: false,
                uploadMultiple: true,
                parallelUploads: 100,
                maxFiles: 100,
                previewsContainer: ".dropzone-previews",

                // The setting up of the dropzone
                init: function() {
                    var myDropzone = this;

                    // First change the button to actually tell Dropzone to process the queue.
                    this.element.querySelector("button[type=submit]").addEventListener("click", function(e) {
                        // Make sure that the form isn't actually being sent.
                        e.preventDefault();
                        e.stopPropagation();
                        myDropzone.processQueue();
                    });

                    // Listen to the sendingmultiple event. In this case, it's the sendingmultiple event instead
                    // of the sending event because uploadMultiple is set to true.
                    this.on("sendingmultiple", function() {
                        // Gets triggered when the form is actually being sent.
                        // Hide the success button or the complete form.
                    });
                    this.on("successmultiple", function(files, response) {
                        // Gets triggered when the files have successfully been sent.
                        // Redirect user or notify of success.
                    });
                    this.on("errormultiple", function(files, response) {
                        // Gets triggered when there was an error sending the files.
                        // Maybe show form again, and notify user of error
                    });
                }
            }
        })
    </script>
    @endsection

</x-awardee.top-nav>