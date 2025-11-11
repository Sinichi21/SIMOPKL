<x-admin.layout>
    <div class="p-0 container-fluid" style="height: 100vh;">
        <div class="row h-100">
            <div class="col-12 d-flex justify-content-center align-items-center">
                @if(pathinfo($document->file_path, PATHINFO_EXTENSION) === 'pdf')
                    <iframe src="{{ asset('storage/' . $document->file_path) }}" width="100%" height="100%"></iframe>
                @elseif(in_array(pathinfo($document->file_path, PATHINFO_EXTENSION), ['xls', 'xlsx']))
                    <!-- Link untuk melihat file Excel di Microsoft Excel Online -->
                    <iframe src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode(asset('storage/' . $document->file_path)) }}" width="100%" height="100%"></iframe>
                @endif
            </div>
        </div>
    </div>
</x-admin.layout>
