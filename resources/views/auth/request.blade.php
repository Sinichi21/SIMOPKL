<x-landing-page.layout>

    @section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    @endsection

    <section class="bg-gray-50">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto h-screen lg:py-0">
            <a href="#" class="flex items-center">
                <img src="./assets/logo-bpi.png" class="h-36 lg:h-48" alt="BPI UI Logo" />
            </a>
            @if ($errors->any())
            <div id="alert-error" class="w-full sm:max-w-md bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path d="M14.348 14.849a1 1 0 01-1.414 0L10 11.414l-2.935 2.935a1 1 0 01-1.414-1.414l2.935-2.935-2.935-2.935a1 1 0 011.414-1.414L10 8.586l2.935-2.935a1 1 0 011.414 1.414L11.414 10l2.935 2.935a1 1 0 010 1.414z"/>
                    </svg>
                </span>
            </div>
            @endif
            <div class="w-full bg-white rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                        Ubah Kata Sandi
                    </h1>
                    <form id="resetForm" class="space-y-4 md:space-y-6" action="{{ route('password.email') }}" method="POST">
                        @csrf
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email Anda</label>
                            <input type="email" name="email" id="email"
                                class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-amber-600 focus:border-amber-600  @if($errors->any()) border-red-600 @endif block w-full p-2.5"
                                placeholder="name@company.com" required />
                        </div>
                        <button type="submit"
                            class="w-full text-white bg-amber-600 hover:bg-amber-700 focus:ring-4 focus:outline-none focus:ring-amber-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                            Ubah Kata Sandi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <a href="https://wa.me/628123456789?text=Hallo" target="_blank" rel="noopener noreferrer"
        class="w-16 lg:w-20 aspect-square bg-gradient-to-tr from-green-500 to-emerald-400 fixed bottom-5 lg:bottom-10 rounded-full right-5 lg:right-10 text-white flex justify-center items-center shadow-2xl">
        <svg viewBox="0 0 24 24" fill="#fff" xmlns="http://www.w3.org/2000/svg" class="w-[40px] h-[40px] lg:w-[56px] lg:h-[56px]">
            <path d="M17.6 6.31999C16.8669 5.58141 15.9943 4.99596 15.033 4.59767C14.0716 4.19938 13.0406 3.99622 12 3.99999C10.6089 4.00135 9.24248 4.36819 8.03771 5.06377C6.83294 5.75935 5.83208 6.75926 5.13534 7.96335C4.4386 9.16745 4.07046 10.5335 4.06776 11.9246C4.06507 13.3158 4.42793 14.6832 5.12 15.89L4 20L8.2 18.9C9.35975 19.5452 10.6629 19.8891 11.99 19.9C14.0997 19.9001 16.124 19.0668 17.6222 17.5816C19.1205 16.0965 19.9715 14.0796 19.99 11.97C19.983 10.9173 19.7682 9.87634 19.3581 8.9068C18.948 7.93725 18.3505 7.05819 17.6 6.31999ZM12 18.53C10.8177 18.5308 9.65701 18.213 8.64 17.61L8.4 17.46L5.91 18.12L6.57 15.69L6.41 15.44C5.55925 14.0667 5.24174 12.429 5.51762 10.8372C5.7935 9.24545 6.64361 7.81015 7.9069 6.80322C9.1702 5.79628 10.7589 5.28765 12.3721 5.37368C13.9853 5.4597 15.511 6.13441 16.66 7.26999C17.916 8.49818 18.635 10.1735 18.66 11.93C18.6442 13.6859 17.9355 15.3645 16.6882 16.6006C15.441 17.8366 13.756 18.5301 12 18.53ZM15.61 13.59C15.41 13.49 14.44 13.01 14.26 12.95C14.08 12.89 13.94 12.85 13.81 13.05C13.6144 13.3181 13.404 13.5751 13.18 13.82C13.07 13.96 12.95 13.97 12.75 13.82C11.6097 13.3694 10.6597 12.5394 10.06 11.47C9.85 11.12 10.26 11.14 10.64 10.39C10.6681 10.3359 10.6827 10.2759 10.6827 10.215C10.6827 10.1541 10.6681 10.0941 10.64 10.04C10.64 9.93999 10.19 8.95999 10.03 8.56999C9.87 8.17999 9.71 8.23999 9.58 8.22999H9.19C9.08895 8.23154 8.9894 8.25465 8.898 8.29776C8.8066 8.34087 8.72546 8.403 8.66 8.47999C8.43562 8.69817 8.26061 8.96191 8.14676 9.25343C8.03291 9.54495 7.98287 9.85749 8 10.17C8.0627 10.9181 8.34443 11.6311 8.81 12.22C9.6622 13.4958 10.8301 14.5293 12.2 15.22C12.9185 15.6394 13.7535 15.8148 14.58 15.72C14.8552 15.6654 15.1159 15.5535 15.345 15.3915C15.5742 15.2296 15.7667 15.0212 15.91 14.78C16.0428 14.4856 16.0846 14.1583 16.03 13.84C15.94 13.74 15.81 13.69 15.61 13.59Z" fill="#fff"/>
        </svg>
    </a>
    <button id="scrollTop" class="hidden fixed bottom-24 lg:bottom-32 lg:right-10 right-5 bg-amber-500 hover:bg-amber-700 text-white font-bold py-2 px-4 rounded-lg cursor-pointer z-50" aria-label="Scroll to top">
        ↑ Top
    </button>

    @section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#alert-error span').click(function() {
                $(this).parent().hide();
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#resetForm').submit(function(e) {
                e.preventDefault(); // Prevent the form from submitting normally

                Swal.fire({
                    title: 'Konfirmasi',
                    text: "Apakah Anda yakin ingin mengubah kata sandi?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var formData = new FormData(this);
                        var token = "{{ csrf_token() }}"; // CSRF token for security
                        // Submit the form via AJAX
                        $.ajax({
                            url: {{ route('password.email') }},
                            type: 'POST',
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                Swal.fire(
                                    'Berhasil!',
                                    'Cek email anda untuk mengubah kata sandi.',
                                    'success'
                                    ).then(() => {
                                    window.location.href = "{{ route('login') }}";
                                });
                            },
                            error: function(response) {
                                Swal.fire(
                                    'Gagal!',
                                    'Ada kesalahan saat mereset password user.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>
    @endsection

</x-landing-page.layout>
