<x-awardee.top-nav>

    <div class="container my-5">

        <div class="row justify-content-center">
            <div class="col-md-6">
                @session('success')
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {!! $value !!}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endsession
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Reset Password
                    </div>
                    <div class="card-body">
                        <form action="{{route('awardee.resetPassword.store')}}" method="POST">
                            @csrf
                            <input type="hidden" name="userId" value="{{$user->id}}">

                            <div class="form-group">
                                <label for="old-password">Password lama</label>
                                <input type="password" class="form-control" id="old-password" name="oldPassword"
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="new-password">Password baru</label>
                                <input type="password" class="form-control" id="new-password" name="newPassword"
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="new-password-confirmation">Konfirmasi password baru</label>
                                <input type="password" class="form-control" id="new-password-confirmation"
                                    name="newPassword_confirmation" required>
                            </div>

                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href="{{ route('awardee.profile', ['user' => $user->id]) }}"
                                    class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-awardee.top-nav>