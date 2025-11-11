<x-admin.layout>
    @section('css')
    <!-- Custom styles for this page -->
    <link href="{{asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    @endsection

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detail Pengaduan</h6>
        </div>
        <div class="card-body">
            <form id="complaint-form">
                @csrf
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <label for="complaint_id">No. Pengaduan</label>
                        <input type="text" class="form-control" id="complaint_id" value="{{$complaint->complaint_id}}"
                            disabled>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <label for="bpi_number">No. BPI</label>
                        <input type="text" class="form-control" id="bpi_number"
                            value="{{$complaint->awardee->bpi_number}}" disabled>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="degree">Degree</label>
                        <input type="text" class="form-control" id="degree" value="{{$complaint->awardee->degree}}"
                            disabled>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <label for="fullname">Fullname</label>
                        <input type="text" class="form-control" id="fullname" value="{{$complaint->awardee->fullname}}"
                            disabled>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="faculty_id">Faculty</label>
                        <input type="text" class="form-control" id="faculty"
                            value="{{$complaint->awardee->studyProgram->faculty->name}}" disabled>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" value="{{$complaint->awardee->username}}"
                            disabled>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="program_study_id">Program Study</label>
                        <input type="text" class="form-control" id="studyProgram"
                            value="{{$complaint->awardee->studyProgram->name}}" disabled>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" value="{{$complaint->awardee->user->email}}"
                            disabled>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="complaint_type_id">Jenis aduan</label>
                        <input type="text" class="form-control" id="complaint-type"
                            value="{{$complaint->complaintType->title}}" disabled>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <label for="content">Dekripsi</label>
                        <textarea class="form-control" id="content" rows="4" disabled>{{$complaint->content}}</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <ul id="media-links">
                            @foreach ($complaint->complaintMedias as $media)
                            <li>
                                <a href="{{asset('storage/'.$media->url)}}">media {{$loop->iteration}}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 text-right">
                        <a href="{{ route('admin.complaint.index') }}" class="btn btn-secondary">Kembali</a>
                        <a href="{{route('admin.thread.show', ['thread' => $complaint->thread->id])}}"
                            class="btn btn-primary">Thread</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @section('script')
    <script type="text/javascript">
        $(document).ready(function() {
        })
    </script>
    @endsection

</x-admin.layout>