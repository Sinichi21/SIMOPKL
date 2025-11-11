<x-awardee.top-nav>
    @section('css')
    <!-- Custom styles for this page -->
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    @endsection

    <div class="mb-4 shadow card">
        <div class="card-body">
            <div class="text-right">
                <h6>{{ \Carbon\Carbon::now()->format('d-m-Y') }}</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="docTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Dokumen</th>
                            <th>Deskripsi</th>
                            <th>Waktu Upload</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($documents as $document)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $document->name }}</td>
                            <td>{{ $document->description }}</td>
                            <td>{{ $document->uploaded_at->setTimezone('Asia/Jakarta')->translatedFormat('j F Y, \p\u\k\u\l H.i T') }}</td>
                            <td>
                                <div class="flex-row flex-wrap d-flex" style="gap: 0.5rem">
                                    @if(pathinfo($document->file_path, PATHINFO_EXTENSION) === 'pdf')
                                        <a href="{{ route('document.show', $document->id) }}" class="btn btn-primary">Lihat Dokumen</a>
                                    @elseif(in_array(pathinfo($document->file_path, PATHINFO_EXTENSION), ['xls', 'xlsx']))
                                        <a href="{{ asset('storage/' . $document->file_path) }}" download class="btn btn-primary">Download Excel</a>
                                    @endif
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
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            const table = $('#docTable').DataTable({
                columnDefs: [{width: '5%', targets: 0}, {width: '10%', targets: 4}]
            });
        });
    </script>
    @endsection
</x-awardee.top-nav>
