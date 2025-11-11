<x-admin.layout>
    <style>
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
        }

        #loadingModal .modal-content {
            max-width: 600px;
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        #loadingModal .modal-body {
            text-align: center;
        }

        /* Icon Styling */
        #loadingModal .modal-body i {
            color: #ffcc00;
        }

        /* Text Styling */
        #loadingModal .modal-body p {
            font-size: 1.25rem;
            color: #333;
            margin-top: 15px;
        }
    </style>

    @section('css')
    <link href="{{asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
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

            <div class="text-right">
                <h6>{{ \Carbon\Carbon::now()->format('d-m-Y') }}</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="confirmTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="text-center">
                            <th>No.</th>
                            <th>Nama Awardee</th>
                            <th>Email</th>
                            <th>No. BPI</th>
                            <th>Nama Iuran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                       @foreach ($pendingPayments as $payment)
                            <tr class="text-center">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $payment->user->awardee->fullname }}</td>
                                <td>{{ $payment->user->email }}</td>
                                <td>{{ $payment->user->awardee->bpi_number }}</td>
                                <td>{{ $payment->fee->name }}</td>
                            <td>
                                <div class="flex-row flex-wrap d-flex" style="gap: 0.5rem">
                                    <a href="{{route('confirm-payment.show', $payment->id)}}" class="w-100">
                                        <button class="btn btn-primary btn-edit w-100">Detail</button>
                                    </a>
                                    <button type="button" class="btn btn-info btn-status w-100"
                                        data-id="{{$payment->id}}">Confirm</button>
                                    <button type="button" class="btn btn-danger btn-delete w-100"
                                        data-id="{{$payment->id}}">Reject</button>
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
                        location.reload();
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
                                location.reload()
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

    <script type="text/javascript">
        $(document).ready(function() {
            const table = $('#confirmTable').DataTable({
                columnDefs: [
                    { width: '5%', targets: 0 },
                    { width: '10%', targets: 5 }
                ]
            });

            function updateUrlParameter(param, value) {
                const url = new URL(window.location);
                if (value) {
                    url.searchParams.set(param, value);
                } else {
                    url.searchParams.delete(param);
                }
                window.location.href = url.toString();
            }
        });
    </script>
    @endsection
</x-admin.layout>
