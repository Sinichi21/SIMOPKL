<x-awardee.top-nav>
    @section('css')
        <link href="{{asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
        <style>
            .badge{
                color: white;
            }
        </style>
    @endsection
    <div class="mb-4 shadow card">
        <div class="card-body">
            <!-- Loading Modal -->
            <div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="loadingModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content" style="border-radius: 15px; padding: 20px; text-align: center;">
                        <div class="modal-body">
                            <div style="font-size: 3rem; color: #ffcc00;">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                            <p class="mt-3" style="font-size: 1.5rem; font-weight: 500;">Loading payment confirm...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal reject -->
            <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="rejectModalLabel">Alasan Penolakan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="rejectForm">
                                <input type="hidden" id="rejectTransactionId" name="id">
                                <div class="form-group">
                                    <label for="rejectNote">Catatan</label>
                                    <textarea class="form-control" id="rejectNote" name="note" rows="3" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-danger">Kirim</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Loading Reject -->
            <div class="modal fade" id="loadingRejectModal" tabindex="-1" role="dialog" aria-labelledby="loadingRejectModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content" style="border-radius: 15px; padding: 20px; text-align: center;">
                        <div class="modal-body">
                            <!-- Icon -->
                            <div style="font-size: 3rem; color: #ffcc00;">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                            <!-- Message -->
                            <p class="mt-3" style="font-size: 1.5rem; font-weight: 500;">Loading rejecting payment...</p>
                        </div>
                     </div>
                </div>
            </div>

            <div class="text-left">
                <h3>Detail Transaksi</h3>
            </div>
            <h2 class="mb-4 text-center h3 text-dark">{{ $payment->fee->name }}</h2>
            <div class="gap-2 mt-3 d-flex justify-content-center">
                <span class="badge
                    @if ($payment->status === 'pending') bg-secondary
                    @elseif ($payment->status === 'in progress') bg-warning
                    @elseif ($payment->status === 'waiting confirmation') bg-info
                    @elseif ($payment->status === 'success') bg-success
                    @elseif ($payment->status === 'failed') bg-danger @endif">
                    {{ ucfirst($payment->status) }}
                </span>
            </div>
            <div class="gap-2 mt-3 d-flex justify-content-center text-muted">
                <p class="mb-0" style="margin-right: 10px;">
                    {{ \Carbon\Carbon::parse($payment->created_at)->format('M d, Y') }}
                </p>
                <span class="d-flex align-items-center">
                    <i class="fas fa-clock text-secondary" style="font-size: 16px; margin-right: 4px;"></i>
                    {{ \Carbon\Carbon::parse($payment->created_at)->diffForHumans() }}
                </span>
            </div>
            <div class="mb-4">
                <p class="text-center text-gray-500 text-muted">Diulang {{ $payment->fee->repeat_interval }}x dalam setahun</p>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="detailTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="text-center">
                            <th>Nominal</th>
                            <th>Jenis Bank/E-Wallet Tujuan</th>
                            <th>Bukti Transfer</th>
                            <th>Note Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center">
                            <td>Rp{{ number_format($payment->amount, 2, ',', '.') }}</td>
                            <td>{{ $payment->paymentSetting->name }}</td>
                            <td>
                                @if ($payment->proof_of_payment)
                                    <img src="{{ asset('storage/'.$transaction->proof_of_payment) }}" alt="Bukti Transfer" style="max-width: 200px; max-height: 150px;"/>
                                @else
                                    <span>Tidak ada bukti transfer</span>
                                @endif
                            </td>
                            <td>{{ $payment->note ?? 'Belum ada catatan' }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="mt-4 d-flex justify-content-end">
                    <a href="{{ route('confirm-payment.index') }}" class="mr-2 btn btn-secondary">Kembali</a>
                    <button type="button" class="mr-2 btn btn-info btn-status" data-id="{{$payment->id}}">Confirm</button>
                    <button type="button" class="btn btn-danger btn-delete" data-id="{{$payment->id}}">Reject</button>
                </div>
            </div>
        </div>
    </div>

    @section('script')
        <script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>

            <script type="text/javascript">
        // Tombol hapus
        $('.btn-delete').click(function() {
            var paymentId = $(this).data('id');
            $('#rejectTransactionId').val(paymentId);
            $('#rejectModal').modal('show');
        });

        $('#rejectForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $('#rejectModal').modal('hide');
            $('#loadingRejectModal').modal('show');

            $.ajax({
                type: 'POST',
                url: "{{ route('confirm-payment.reject', ['payment' => '__paymentId__']) }}".replace('__paymentId__', $('#rejectTransactionId').val()),
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function(response) {
                    Swal.fire({
                        title: "Rejected!",
                        text: response.msg,
                        icon: "success"
                    }).then(() => {
                        window.location.href = "{{ route('confirm-payment.index') }}";
                    });
                },
                error: function(error) {
                    console.error(error);
                },
                complete: function() {
                    $('#loadingRejectModal').modal('hide');
                }
            });
        });

        $('.btn-status').click(function() {
            var btn = $(this)
            Swal.fire({
                title: "Apakah Anda yakin?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya"
                }).then((result) => {
                if (result.isConfirmed) {
                    $('#loadingModal').modal('show');

                    var data = 'id=' + btn.data('id')

                    $.ajax({
                        type: 'POST',
                        url: "{{ route('confirm-payment.approve', ['payment' => '__paymentId__']) }}".replace('__paymentId__', btn.data('id')),
                        data: data,
                        headers: {
                            'X-CSRF-TOKEN': "{{csrf_token()}}"
                        },
                        dataType: 'json',
                        success: function(data) {
                            Swal.fire({
                                title: "Updated!",
                                text: data.msg,
                                icon: "success"
                            }).then(() => {
                                window.location.href = "{{ route('confirm-payment.index') }}";
                            })
                        },
                        error: function(data) {
                            console.log(data);
                        },
                        complete: function() {
                            $('#loadingModal').modal('hide');
                        }
                    })
                }
            })
        })
    </script>
    @endsection
</x-awardee.top-nav>
