<x-awardee.top-nav>
    <div class="container my-5">
        <div class="row">
            <div class="col-md-8">
                <h3>Konfirmasi Pembayaran</h3>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('payments.confirm.store', $payment) }}" id="confirm-form" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3 form-group">
                                <label for="proof_of_payment" class="form-label">Bukti Transfer</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="proof_of_payment" id="proof_of_payment" class="custom-file-input" required>
                                        <label class="custom-file-label" for="proof_of_payment">Choose File</label>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Maksimal ukuran file: 1 MB (jpg, jpeg, png).</small>
                            </div>

                            <div class="mb-3 form-group">
                                <label for="transaction_note" class="form-label">Catatan Transaksi</label>
                                <textarea name="transaction_note" id="transaction_note" class="form-control" required></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100" id="btn-confirm">Konfirmasi Pembayaran</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5>Detail Transaksi</h5>
                        <hr>
                        <p><strong>Iuran :</strong> {{ $payment->fee->name }}</p>
                        <p><strong>Total :</strong> Rp {{ number_format($payment->fee->amount, 2) }}</p>
                    </div>
                </div>

                <div class="mt-3 card" id="payment-method-details">
                    <div class="card-body">
                        <h5>Detail Tujuan Pembayaran</h5>
                        <hr>
                        <p><strong>Nama Bank/E-Wallet :</strong> {{ $payment->paymentSetting->name }}</p>
                        <p><strong>Nomor Rekening/ID :</strong> {{ $payment->paymentSetting->account_number }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('script')
        <script>
            $(document).ready(function() {
                var form = $('#confirm-form');

                $('#btn-confirm').click(function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Apakah Anda yakin ingin konfirmasi pembayaran?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, yakin!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var formData = new FormData(form[0]);

                            $.ajax({
                                url: "{{ route('payments.confirm.store', $payment) }}",
                                type: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                    Swal.fire(
                                        'Berhasil!',
                                        'Pembayaran Anda telah berhasil diproses. Silahkan untuk menunggu konfirmasi selanjutnya.',
                                        'success'
                                    ).then(() => {
                                        window.location.href = "{{ route('payments.index') }}";
                                    });
                                },
                                error: function(response) {
                                    Swal.fire(
                                        'Gagal!',
                                        'Ada kesalahan saat memproses pembayaran.',
                                        'error'
                                    );
                                    console.log(response);
                                }
                            });
                        }
                    });
                });
            });

            $('.custom-file-input').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
            });
        </script>
    @endsection
</x-awardee.top-nav>
