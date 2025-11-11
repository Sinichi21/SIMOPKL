<x-awardee.top-nav>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex">
                <a href="{{route('complaint.index')}}">Back</a>
                <p class="m-0 ml-4 font-weight-bold text-primary">{{$thread->complaint->complaintType->title}}</p>
                <p class="ml-auto m-0">{{$thread->complaint->complaint_id}}</p>
            </div>
        </div>
        <div class="card-body">
            <div id="chat-container" style="max-height: 500px; overflow-y: auto;">
                {{-- Ringkasan pengaduan box --}}
                <div>
                    <div class="text-right">
                        <p class="m-0 ml-2">
                            <small>{{$thread->created_at}}</small>
                        </p>
                    </div>
                    <div class="d-flex align-items-end justify-content-end mb-3">
                        <div class="chat-box text-dark rounded p-2 overflow-hidden border border-primary"
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
                    @if ($chat->by == 'admin')
                    <div class="d-flex flex-wrap">
                        <p class="m-0">
                            {{$chat->user->awardee ? $chat->user->awardee->fullname : $chat->user->admin->fullname}}:
                        </p>
                        <p class="m-0 ml-2">
                            <small>{{$chat->created_at}}</small>
                        </p>
                    </div>
                    @endif
                    @if ($chat->by == 'awardee')
                    <div class="d-flex flex-wrap justify-content-end">
                        <p class="m-0">
                            {{$chat->user->awardee ? $chat->user->awardee->fullname : $chat->user->admin->fullname}}:
                        </p>
                        <p class="m-0 ml-2">
                            <small>{{$chat->created_at}}</small>
                        </p>
                    </div>
                    @endif
                    <div
                        class=" d-flex {{ $chat->user->id == Auth::id() && $chat->by == 'awardee' ? 'justify-content-end' : 'justify-content-start' }}">
                        <div class="chat-box text-dark rounded p-2 overflow-hidden {{$chat->user->id == Auth::id() && $chat->by == 'awardee' ? 'border border-primary' : ''}}"
                            style="background: {{ $chat->user->id == Auth::id() && $chat->by == 'awardee' ? '#ffffff' : '#f5f5f5' }}">
                            <p class="mb-1">{{ $chat->chat }}</p>
                            @foreach($chat->medias as $media)
                            <a href="{{ asset('storage/'.$media->url) }}" target="_blank">{{ $media->filename }}</a><br>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
                {{-- Chat box --}}
            </div>
            <form id="chat-form">
                <input type="hidden" name="threadId" value="{{$thread->id}}">
                <input type="hidden" name="by" value="awardee">
                @csrf
                <div class="row">
                    <div class="col-12 d-flex mb-2">
                        <textarea class="form-control" rows="1" id="chat-input" name="chat" placeholder="Ketik sesuatu"
                            required style="resize: vertical"></textarea>
                        {{-- <input type="text" class="form-control" id="chat-input" name="chat"
                            placeholder="Ketik sesuatu" required> --}}
                        <button id="upload-btn" type="button" class="btn ml-1"><i class="fas fa-upload"></i></button>
                        <button class="btn btn-primary ml-1" style="height: 38px" type="submit">Kirim</button>
                    </div>
                    <div class="col">
                        <ul id="media-names"></ul>
                    </div>
                    <input type="file" accept="image/png, image/jpeg, application/pdf" class="d-none" name="medias[]"
                        multiple id="media">
                </div>
            </form>
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
</x-awardee.top-nav>