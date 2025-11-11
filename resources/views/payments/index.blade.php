<x-awardee.top-nav>
    @section('css')
        <link href="{{asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
        <style>
            .badge{
                color: white;
            }
        </style>
    @endsection
    <h3>Daftar Iuran</h3>
    <div class="row">
        @foreach($fees as $fee)
            <div class="mb-3 col-md-3">
                <div class="shadow card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $fee->name }}</h5>
                        <p class="card-text">Rp {{ number_format($fee->amount, 2) }}</p>
                        @php
                            $latestPayment = $payments->where('fee_id', $fee->id)->sortByDesc('created_at')->first();
                        @endphp
                        @if ($latestPayment)
                            @if ($latestPayment->status === 'pending')
                                <a href="{{ route('payments.pay', $fee->id) }}" class="mb-2 btn btn-primary w-100">
                                    Bayar Sekarang
                                </a>
                            @elseif ($latestPayment->status === 'in progress')
                                <a href="{{ route('payments.confirm', $latestPayment->id) }}" class="mb-2 btn btn-warning w-100">
                                    Konfirmasi Pembayaran
                                </a>
                            @elseif ($latestPayment->status === 'failed')
                                <a href="{{ route('payments.pay', $fee->id) }}" class="mb-2 btn btn-danger w-100">
                                    Ulangi Pembayaran
                                </a>
                            @elseif (in_array($latestPayment->status, ['success', 'waiting confirmation']))
                                <button class="btn btn-success w-100" disabled>
                                    Pembayaran Selesai
                                </button>
                            @endif
                        @else
                            <a href="{{ route('payments.pay', $fee->id) }}" class="mb-2 btn btn-primary w-100">
                                Bayar Sekarang
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mb-4 shadow card">
        <div class="card-body">
            <div class="text-left">
                <h3>Riwayat Pembayaran</h3>
            </div>
            <div class="text-right">
                <h6>{{ \Carbon\Carbon::now()->format('d-m-Y') }}</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="riwayatTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="text-center">
                            <th>No.</th>
                            <th>Nama Iuran</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                            @php
                                $relatedDetails = $transactionDetails->where('payment_id', $payment->id);
                                $hasAction = false;
                            @endphp
                            <tr class="text-center">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $payment->fee->name }}</td>
                                <td>Rp{{ number_format($payment->amount, 2, ',', '.') }}</td>
                                <td>
                                    <span class="badge
                                        @if ($payment->status === 'pending') bg-secondary
                                        @elseif ($payment->status === 'in progress') bg-warning
                                        @elseif ($payment->status === 'waiting confirmation') bg-info
                                        @elseif ($payment->status === 'success') bg-success
                                        @elseif ($payment->status === 'failed') bg-danger @endif">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex-row flex-wrap d-flex" style="gap: 0.5rem">
                                        @if ($payment->status === 'in progress')
                                            @php $hasAction = true; @endphp
                                            <a href="{{ route('payments.detail', $payment->id) }}" class="btn btn-warning w-100">
                                                Detail Payment
                                            </a>
                                        @endif
                                        @if ($payment->status === 'waiting confirmation' && $relatedDetails->isNotEmpty())
                                            @php $hasAction = true; @endphp
                                            <a href="{{ route('transaction.detail', $relatedDetails->first()->id) }}" class="btn btn-info w-100">
                                                Detail Transaksi
                                            </a>
                                        @endif
                                        @if ($payment->status === 'success' && $relatedDetails->isNotEmpty())
                                            @php $hasAction = true; @endphp
                                            <a href="{{ route('transaction.detail', $relatedDetails->first()->id) }}" class="btn btn-info w-100">
                                                Detail Transaksi
                                            </a>
                                        @endif
                                        @if ($payment->status === 'failed' && $relatedDetails->isNotEmpty())
                                            @php $hasAction = true; @endphp
                                            <a href="{{ route('transaction.detail', $relatedDetails->first()->id) }}" class="btn btn-info w-100">
                                                Detail Transaksi
                                            </a>
                                        @endif
                                    </div>
                                    @if (!$hasAction)
                                        <span>Belum ada aksi</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @section('script')
        <script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>

        <script type="text/javascript">
            $(document).ready(function() {
            const hasActionColumn = $('#riwayatTable thead th').length === 5;

            const table = $('#riwayatTable').DataTable({
                columnDefs: [
                    { width: '5%', targets: 0 },
                    { width: '25%', targets: 1 },
                    { width: '15%', targets: 2 },
                    { width: '15%', targets: 3 },
                    ...(hasActionColumn ? [{ width: '15%', targets: 4 }] : []),
                ]
            });
        });
        </script>
    @endsection
</x-awardee.top-nav>
