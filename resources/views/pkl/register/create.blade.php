<x-awardee.top-nav>
    @section('css')
    <!-- Custom styles for this page -->
    <link href="{{asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    @endsection

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Registratsi PKL</h6>
        </div>
        <div class="card-body">
            <form id="complaint-form">
                <input type="hidden" name="awardeeId" value="{{$awardee->id}}">
                @csrf
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <label for="registration_number">No. Registrasi</label>
                        <input type="text" class="form-control" id="registration_number" disabled>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <label for="nim">NIM</label>
                        <input type="text" class="form-control" id="nim" value="{{$awardee->nim}}"
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
                        <label for="period_id">Periode</label>
                        <select class="form-control" id="period_id" name="periodId" required>
                            <option value="">Pilih periode PKL</option>
                            @foreach ($periods as $period)
                            <option value="{{$period->id}}">{{$period->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <label for="mitra_id">Mitra</label>
                        <select class="form-control" id="mitra_id" name="mitraId" required>
                            <option value="">Pilih Mitra PKL</option>
                            @foreach ($mitras as $mitra)
                            <option value="{{$mitra->id}}">{{$mitra->partner_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6 text-left d-flex align-items-end mb-3">
                        <a href="{{ route('Awardee.Partner.create') }}" class="btn btn-primary">Tambah Mitra</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6 form-group">
                        <label for="form_2a" class="form-label">
                            Form 2.A. FORM Pernyataan PKL (Sudah di ttd oleh Pembimbing Akademik) <a href="https://docs.google.com/document/d/1y4WCefbgSUAPKQyj3uUNhVXn-6mT0hZ7/edit?usp=sharing&ouid=100372399089026266077&rtpof=true&sd=true" target="_blank" class="text-indigo-600 underline"> (Download Form 2.A disini)</a>
                        </label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="form_2a" id="form_2a" class="custom-file-input" required>
                                <label class="custom-file-label" for="form_2a">Choose File</label>
                            </div>
                        </div>
                        <small class="form-text text-muted">Maksimal ukuran file: 1 MB (jpg, jpeg, png, pdf).</small>
                    </div>
                    <div class="col-12 col-md-6 form-group">
                        <label for="form_2b" class="form-label">
                            Form 2.B. FORM Permohonan PKL (Sudah di ttd oleh Pembimbing Akademik) <a href="https://docs.google.com/document/d/193rXgbiQZLikrbmvsJN8lbVEPbAdeNyCXyUfizatcB4/edit?usp=drive_link" target="_blank" class="text-indigo-600 underline"> (Download Form 2.B disini)</a>
                        </label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="form_2b" id="form_2b" class="custom-file-input" required>
                                <label class="custom-file-label" for="form_2b">Choose File</label>
                            </div>
                        </div>
                        <small class="form-text text-muted">Maksimal ukuran file: 1 MB (jpg, jpeg, png, pdf).</small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6 form-group">
                        <label for="transkrip_nilai" class="form-label">
                            Transkrip Nilai Terakhir Pada SIMAK-NG <a href="https://simak-ng.unud.ac.id/" target="_blank" class="text-indigo-600 underline"> (Download Transkrip Nilai disini)</a>
                        </label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="transkrip_nilai" id="transkrip_nilai" class="custom-file-input" required>
                                <label class="custom-file-label" for="transkrip_nilai">Choose File</label>
                            </div>
                        </div>
                        <small class="form-text text-muted">Maksimal ukuran file: 1 MB (jpg, jpeg, png, pdf).</small>
                    </div>
                    <div class="col-12 col-md-6 form-group">
                        <label for="sk_penerimaan_mitra" class="form-label">
                            Surat Penerimaan PKL dari Mitra PKL 
                        </label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="sk_penerimaan_mitra" id="sk_penerimaan_mitra" class="custom-file-input" required>
                                <label class="custom-file-label" for="sk_penerimaan_mitra">Choose File</label>
                            </div>
                        </div>
                        <small class="form-text text-muted">Maksimal ukuran file: 1 MB (jpg, jpeg, png. pdf).</small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 text-right">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('Registration.index') }}" class="btn btn-secondary">Cancel</a>
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