<x-awardee.top-nav>
    @section('css')
        <style>
            button[data-selected="true"] {
                border: 2px solid #f59e0b;
            }
        </style>
    @endsection
    <div class="container my-5">
        <div class="row">
            <div class="col-md-8">
                <h3>Pembayaran</h3>

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

                <form method="POST" action="{{ route('payments.store', $fee) }}" id="payment-form">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3 form-group">
                                <label for="iuran" class="form-label">Nama Iuran</label>
                                <input type="text" class="form-control" value="{{ $fee->name }}" disabled>
                            </div>

                            <div class="mb-3 form-group">
                                <label for="payment_method" class="form-label">Metode Pembayaran</label>
                                <select name="payment_method" id="payment_method" class="form-control" required>
                                    <option value="">-- Pilih Metode --</option>
                                    <option value="virtual_account">Bank Transfer Payment</option>
                                    <option value="ewallet">E-Wallet Payment</option>
                                </select>
                            </div>

                            <div id="virtual_account_section" class="mb-3" style="display: none;">
                                <h5>Bank Transfer Payment</h5>
                                <div class="flex-wrap d-flex">
                                    @foreach ($paymentMethods as $method)
                                        @if ($method->type == 'bank')
                                            <button type="button" class="m-1 btn btn-outline-primary payment-method-btn" name="payment_method" id="payment_method" data-method-id="{{ $method->id }}">
                                                <img src="{{ asset('storage/' . $method->image_url) }}" alt="{{ $method->name }}" class="img-fluid" width="100">
                                            </button>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <div id="e-wallet_section" class="mb-3" style="display: none;">
                                <h5>E-Wallet Payment</h5>
                                <div class="flex-wrap d-flex">
                                    @foreach ($paymentMethods as $method)
                                        @if ($method->type == 'ewallet')
                                            <button type="button" class="m-1 btn btn-outline-primary payment-method-btn" name="payment_method" id="payment_method" data-method-id="{{ $method->id }}">
                                                <img src="{{ asset('storage/' . $method->image_url) }}" alt="{{ $method->name }}" class="img-fluid" width="100">
                                            </button>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <div class="mb-3 form-group">
                                <label for="amount" class="form-label">Jumlah Pembayaran</label>
                                <input type="text" class="form-control" value="Rp {{ number_format($fee->amount, 2) }}" disabled>
                            </div>

                            <button type="button" id="btn-confirm" class="btn btn-primary w-100">Bayar</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5>Detail Transaksi</h5>
                        <hr>
                        <p><strong>Iuran :</strong> {{ $fee->name }}</p>
                        <p><strong>Total :</strong> Rp {{ number_format($fee->amount, 2) }}</p>
                    </div>
                </div>

                <div class="mt-3 card" id="payment-method-details" style="display: none;">
                    <div class="card-body">
                        <h5>Detail Tujuan Pembayaran</h5>
                        <hr>
                        <p><strong>Nama Bank/E-Wallet:</strong> <span id="payment-method-name"></span></p>
                        <p><strong>Nomor Rekening/ID:</strong> <span id="payment-method-id"></span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('script')
        <script>
            const paymentMethodSelect = document.getElementById('payment_method');
            const paymentMethodDetails = document.getElementById('payment-method-details');
            const paymentMethodName = document.getElementById('payment-method-name');
            const paymentMethodId = document.getElementById('payment-method-id');

            function updateConfirmState() {
                const selectedMethod = paymentMethodSelect.value;
                const selectedButton = document.querySelector('.payment-method-btn[data-selected="true"]');
                confirmPaymentBtn.disabled = !(selectedMethod && selectedButton);
            }

            paymentMethodSelect.addEventListener('change', () => {
                document.getElementById('virtual_account_section').style.display = paymentMethodSelect.value === 'virtual_account' ? 'block' : 'none';
                document.getElementById('e-wallet_section').style.display = paymentMethodSelect.value === 'ewallet' ? 'block' : 'none';
                paymentMethodDetails.style.display = 'none';
                updateConfirmState();
            });

            document.querySelectorAll('.payment-method-btn').forEach(button => {
                button.addEventListener('click', () => {
                    document.querySelectorAll('.payment-method-btn').forEach(btn => btn.removeAttribute('data-selected'));

                    button.setAttribute('data-selected', 'true');

                    const methodId = button.getAttribute('data-method-id');
                    const paymentInput = document.querySelector(`input#payment-method-${methodId}`);
                    document.querySelectorAll('input[name="payment_method"]').forEach(input => input.remove());
                    const hiddenInput = document.createElement('input');
                    hiddenInput.setAttribute('type', 'hidden');
                    hiddenInput.setAttribute('name', 'payment_method');
                    hiddenInput.value = methodId;
                    document.querySelector('#payment-form').appendChild(hiddenInput);

                    const selectedPaymentMethod = @json($paymentMethods).find(pm => pm.id == methodId);
                    if (selectedPaymentMethod) {
                        paymentMethodName.textContent = selectedPaymentMethod.name;
                        paymentMethodId.textContent = selectedPaymentMethod.account_number;
                        paymentMethodDetails.style.display = 'block';
                    }

                    updateConfirmState();
                });
            });


            $(document).ready(function() {
                var form = $('#payment-form');

                $('#btn-confirm').click(function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Informasi Penting',
                        text: 'Pembayaran hanya dapat dilakukan dalam waktu 24 jam. Jika tidak ada konfirmasi pembayaran setelah 24 jam, status pembayaran akan dianggap gagal. Apakah Anda ingin melanjutkan?',
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Lanjutkan!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var formData = new FormData(form[0]);

                            $.ajax({
                                url: "{{ route('payments.store', $fee) }}",
                                type: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                    Swal.fire(
                                        'Berhasil!',
                                        'Lakukan konfirmasi pembayaran untuk melanjutkan pembayaran.',
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
        </script>
    @endsection
</x-awardee.top-nav>
