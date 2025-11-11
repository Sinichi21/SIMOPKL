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
            <div class="text-left">
                <h3>Detail Transaksi</h3>
            </div>
            <h2 class="mb-4 text-center h3 text-dark">{{ $transaction->payment->fee->name }}</h2>
            <div class="gap-2 mt-3 d-flex justify-content-center">
                <span class="badge
                    @if ($transaction->payment->status === 'pending') bg-secondary
                    @elseif ($transaction->payment->status === 'in progress') bg-warning
                    @elseif ($transaction->payment->status === 'waiting confirmation') bg-info
                    @elseif ($transaction->payment->status === 'success') bg-success
                    @elseif ($transaction->payment->status === 'failed') bg-danger @endif">
                    {{ ucfirst($transaction->payment->status) }}
                </span>
            </div>
            <div class="gap-2 mt-3 d-flex justify-content-center text-muted">
                <p class="mb-0" style="margin-right: 10px;">
                    {{ \Carbon\Carbon::parse($transaction->payment->created_at)->format('M d, Y') }}
                </p>
                <span class="d-flex align-items-center">
                    <i class="fas fa-clock text-secondary" style="font-size: 16px; margin-right: 4px;"></i>
                    {{ \Carbon\Carbon::parse($transaction->payment->created_at)->diffForHumans() }}
                </span>
            </div>
            <div class="mb-4">
                <p class="text-center text-gray-500 text-muted">Diulang {{ $transaction->payment->fee->repeat_interval }}x dalam setahun</p>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="detailTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="text-center">
                            <th>Jumlah</th>
                            <th>Jenis Bank/E-Wallet Tujuan</th>
                            <th>Bukti Transfer</th>
                            <th>Note Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center">
                            <td>Rp{{ number_format($transaction->payment->amount, 2, ',', '.') }}</td>
                            <td>{{ $transaction->payment->paymentSetting->name }}</td>
                            <td>
                                @if ($transaction->proof_of_payment)
                                    <img src="{{ asset('storage/'.$transaction->proof_of_payment) }}" alt="Bukti Transfer" style="max-width: 200px; max-height: 150px;"/>
                                @else
                                    <span>Tidak ada bukti transfer</span>
                                @endif
                            </td>
                            <td>{{ $transaction->note ?? 'Belum ada catatan' }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="mt-4 d-flex justify-content-end">
                    <a href="{{ route('payments.index') }}" class="mr-2 btn btn-secondary">Kembali</a>
                    @foreach($fees as $fee)
                        @php
                            $latestPayment = $payments->where('fee_id', $fee->id)->sortByDesc('created_at')->first();
                        @endphp
                        @if ($latestPayment)
                            @if ($latestPayment->status === 'pending')
                                <a href="{{ route('payments.pay', $fee->id) }}" class="btn btn-primary">
                                    Bayar Sekarang
                                </a>
                            @elseif ($latestPayment->status === 'failed')
                                <a href="{{ route('payments.pay', $fee->id) }}" class="btn btn-danger">
                                    Ulangi Pembayaran
                                </a>
                            @endif
                        @else
                            <a href="{{ route('payments.pay', $fee->id) }}" class="btn btn-primary">
                                Bayar Sekarang
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @section('script')
        <script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
    @endsection
</x-awardee.top-nav>
