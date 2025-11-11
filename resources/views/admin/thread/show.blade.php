<x-admin.layout>
    <div class="mb-4 shadow card">
        <div class="py-3 card-header">
            <div class="d-flex">
                <a href="{{route('admin.complaint.index')}}">Back</a>
                <p class="m-0 ml-4 font-weight-bold text-primary">{{$thread->complaint->awardee->fullname}}</p>
                <p class="m-0 ml-auto">{{$thread->complaint->complaint_id}}</p>
            </div>
        </div>
        <div class="card-body">
            <div id="chat-container" style="max-height: 500px; overflow-y: auto;">
                {{-- Ringkasan pengaduan box --}}
                <div>
                    <div class="flex-wrap d-flex">
                        <p class="m-0">
                            {{$thread->complaint->awardee->fullname}}:
                        </p>
                        <p class="m-0 ml-2">
                            <small>{{$thread->created_at}}</small>
                        </p>
                    </div>
                    <div class="mb-3 d-flex">
                        <div class="p-2 overflow-hidden border rounded chat-box text-dark border-primary"
                            style="background: #ffffff">
                            <strong>{{$thread->complaint->complaintType->title}}</strong>
                            <p>{{$thread->complaint->content}}</p>
                            @foreach($thread->complaint->complaintMedias as $media)
                            <a href="{{ asset('storage/'.$media->url) }}" target="_blank">
                                attachment {{$loop->iteration}}
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                {{-- Ringkasan pengaduan box --}}
                {{-- Chat box --}}
                @foreach($thread->chats->sortBy('created_at') as $chat)
                <div class="mb-3">
                    {{-- Kalau chat punya current logged in user
                    -- Kalau chat oleh awardee, nama dan waktu sebelah kiri
                    -- Kalau chat oleh admin, waktu sebelah kanan
                    Jika tidak, nama dan waktu sebelah kiri --}}
                    @if ($chat->user->id == Auth::id())
                    @if ($chat->by == 'awardee')
                    <div class="flex-wrap d-flex">
                        <p class="m-0">
                            {{$chat->user->awardee ? $chat->user->awardee->fullname.' (awardee)' :
                            $chat->user->admin->fullname. ' (awardee)'}}:
                        </p>
                        <p class="m-0 ml-2">
                            <small>{{$chat->created_at}}</small>
                        </p>
                    </div>
                    @endif
                    @if ($chat->by == 'admin')
                    <div class="flex-wrap d-flex justify-content-end">
                        <p class="m-0">
                            {{$chat->user->awardee ? $chat->user->awardee->fullname : $chat->user->admin->fullname}}:
                        </p>
                        <p class="m-0 ml-2">
                            <small>{{$chat->created_at}}</small>
                        </p>
                    </div>
                    @endif
                    @else
                    <div class="flex-wrap d-flex">
                        <p class="m-0">
                            {{$chat->user->awardee ? $chat->user->awardee->fullname : $chat->user->admin->fullname}}
                            {{$chat->by == 'admin' ? ' (admin)' : ' (awardee)'}}:
                        </p>
                        <p class="m-0 ml-2">
                            <small>{{$chat->created_at}}</small>
                        </p>
                    </div>
                    @endif
                    <div
                        class="d-flex align-items-end {{ $chat->user->id == Auth::id() && $chat->by == 'admin' ? 'justify-content-end' : 'justify-content-start' }}">
                        <div class="chat-box text-dark rounded p-2 overflow-hidden {{$chat->user->id == Auth::id() && $chat->by == 'admin' ? 'border border-primary' : ''}}"
                            style="background: {{ $chat->user->id == Auth::id() && $chat->by == 'admin' ? '#ffffff' : '#f5f5f5' }}">
                            <p class="mb-1">{{ $chat->chat }}</p>
                            @foreach($chat->medias as $media)
                            <a href="{{ asset('storage/'.$media->url) }}" target="_blank">{{
                                $media->filename }}</a><br>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
                {{-- Chat box --}}
            </div>

            @if ($thread->complaint->awardee->user->is_registered == 1)

            <form id="chat-form">
                <input type="hidden" name="threadId" value="{{$thread->id}}">
                <input type="hidden" name="by" value="admin">
                @csrf
                <div class="row">
                    <div class="mb-2 col-12 d-flex">
                        <textarea class="form-control" rows="1" id="chat-input" name="chat" placeholder="Ketik sesuatu"
                            required style="resize: vertical"></textarea>
                        {{-- <input type="text" class="form-control" id="chat-input" name="chat"
                            placeholder="Ketik sesuatu" required> --}}
                        <button id="upload-btn" type="button" class="ml-1 btn"><i class="fas fa-upload"></i></button>
                        <button class="ml-1 btn btn-primary" style="height: 38px" type="submit">Kirim</button>
                    </div>
                    <div class="col">
                        <ul id="media-names"></ul>
                    </div>
                    <input type="file" accept="image/png, image/jpeg, application/pdf" class="d-none" name="medias[]"
                        multiple id="media">
                </div>
            </form>

            @endif

        </div>
    </div>

    @section('script')
    <script type="text/javascript">
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

        $('#upload-btn').click(function() {
            $('#media').click()
        })

        $(document).ready(function() {
            $('#chat-form').submit(function(e) {
                e.preventDefault()

                var form = $(this)
                var data = new FormData(form[0])

                // Disable submit button
                form.find('button[type=submit]').prop('disabled', true);

                $.ajax({
                    url: "{{route('thread.chat.store')}}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{csrf_token()}}"
                    },
                    data: data,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function() {
                        location.reload()
                    },
                    error: function(data) {
                        console.log(data);
                    }
                })
            })
        })
    </script>
    @endsection
</x-admin.layout>
