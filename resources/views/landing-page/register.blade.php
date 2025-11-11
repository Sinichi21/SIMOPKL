<x-landing-page.layout>

    @section('css')
    <style>
        /* Menghilangkan tanda panah naik turun pada input angka */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
          -webkit-appearance: none;
          margin: 0;
        }

        /* Untuk Firefox */
        input[type=number] {
          -moz-appearance: textfield;
        }
      </style>
    @endsection

    <section class="flex h-full min-h-screen bg-gray-50">
        <div class="flex flex-col items-center justify-center py-8 mx-auto my-auto md:h-fit mt-28 lg:py-0">
            <a href="#" class="flex items-center">
                <img src="./assets/logo-bpi.png" class="h-36 lg:h-48" alt="BPI UI Logo" />
            </a>
            @if ($errors->any())
            <div id="alert-error"
                class="relative w-full px-4 py-3 text-red-700 bg-red-100 border border-red-400 rounded sm:max-w-4xl">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="w-6 h-6 text-red-500 fill-current" role="button" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20">
                        <title>Close</title>
                        <path
                            d="M14.348 14.849a1 1 0 01-1.414 0L10 11.414l-2.935 2.935a1 1 0 01-1.414-1.414l2.935-2.935-2.935-2.935a1 1 0 011.414-1.414L10 8.586l2.935-2.935a1 1 0 011.414 1.414L11.414 10l2.935 2.935a1 1 0 010 1.414z" />
                    </svg>
                </span>
            </div>
            @endif
            @session('success')
            <div id="alert-success"
                class="relative w-full px-4 py-3 text-green-700 bg-green-100 border border-green-400 rounded sm:max-w-4xl">
                {{session('success')}}
                {{-- <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="w-6 h-6 text-green-500 fill-current" role="button" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20">
                        <title>Close</title>
                        <path
                            d="M14.348 14.849a1 1 0 01-1.414 0L10 11.414l-2.935 2.935a1 1 0 01-1.414-1.414l2.935-2.935-2.935-2.935a1 1 0 011.414-1.414L10 8.586l2.935-2.935a1 1 0 011.414 1.414L11.414 10l2.935 2.935a1 1 0 010 1.414z" />
                    </svg>
                </span> --}}
                <div class="text-end">
                    <a href="{{ route('index') }}"
                        class="inline-block px-4 py-2 font-bold text-white bg-green-500 rounded hover:bg-green-700">OK</a>
                </div>
            </div>
            @endsession
            @if (!session('success'))
            <div class="w-full bg-white rounded-lg shadow md:mt-0 sm:max-w-4xl xl:p-0">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                       Buat Akun Baru
                    </h1>
                    <form id="register-form" class="space-y-4 md:space-y-6" action="{{route('register.store')}}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="flex flex-col gap-5 sm:flex-row">
                            <div class="w-full space-y-4">
                                <div>
                                    <label for="fullname" class="block mb-2 text-sm font-medium text-gray-900">Nama
                                        Lengkap</label>
                                    <input type="fullname" name="fullname" id="fullname"
                                        class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5"
                                        required="" value="{{old('fullname')}}" />
                                </div>
                                <div>
                                    <label for="username" class="block mb-2 text-sm font-medium text-gray-900">Nama
                                        Panggilan</label>
                                    <input type="username" name="username" id="username"
                                        class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5"
                                        required="" value="{{old('username')}}" />
                                </div>
                                <div>
                                    <label for="phone" class="block mb-2 text-sm font-medium text-gray-900">Nomor
                                        Telepon</label>
                                    <input type="phone" name="phoneNumber" id="phone"
                                        class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5"
                                        required="" value="{{old('phoneNumber')}}" />
                                </div>
                                <div>
                                    <label for="email"
                                        class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                                    <input type="email" name="email" id="email"
                                        class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 @error('email') border-red-600 @enderror block w-full p-2.5"
                                        required="" value="{{old('email')}}" />
                                </div>
                                <div>
                                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Kata
                                        Sandi</label>
                                    <input type="password" name="password" id="password"
                                        class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 @error('password') border-red-600 @enderror block w-full p-2.5"
                                        required="" />
                                </div>
                                <div>
                                    <label for="confirm-password"
                                        class="block mb-2 text-sm font-medium text-gray-900">Konfirmasi Kata
                                        Sandi</label>
                                    <input type="password" name="password_confirmation" id="confirm-password"
                                        class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 @error('password') border-red-600 @enderror block w-full p-2.5"
                                        required="" />
                                </div>
                            </div>
                            <div class="w-full space-y-4">
                                <div>
                                    <label for="year" class="block mb-2 text-sm font-medium text-gray-900">Tahun Angkatan</label>
                                    <select name="year" id="year"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                        @foreach ($years as $year)
                                        <option value="{{$year}}">{{$year}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="nim" class="block mb-2 text-sm font-medium text-gray-900">NIM</label>
                                    <input type="number" name="bpiNumber" id="nim" maxlength="10"
                                        class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5"
                                        required="" value="{{old('bpiNumber')}}" />
                                </div>
                                <div>
                                    <label for="degree"
                                        class="block mb-2 text-sm font-medium text-gray-900">Jenjang</label>
                                    <select id="degree" name="degree"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                        <option value="" disabled {{ old('degree') == '' ? 'selected' : '' }}>Pilih Jenjang</option>
                                        <option value="S1" {{ old('degree') == 'S1' ? 'selected' : '' }}>S1</option>
                                        <option value="S2" {{ old('degree') == 'S2' ? 'selected' : '' }}>S2</option>
                                        <option value="S3" {{ old('degree') == 'S3' ? 'selected' : '' }}>S3</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="faculty"
                                        class="block mb-2 text-sm font-medium text-gray-900">Fakultas</label>
                                    <select id="faculty" name="faculty"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                        <option selected disabled>Pilih Fakultas</option>
                                        @foreach ($faculties as $faculty)
                                            <option value="{{ $faculty->id }}" {{ old('faculty') == $faculty->id ? 'selected' : '' }}>
                                                {{ $faculty->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="study-program" class="block mb-2 text-sm font-medium text-gray-900">
                                        Program Studi
                                    </label>
                                    <select id="study-program" name="studyProgramId"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        {{ old('faculty') ? '' : 'disabled' }}>
                                        <option selected disabled>Pilih Program Studi</option>
                                        @foreach ($studyPrograms as $studyProgram)
                                        <option value="{{ $studyProgram->id }}" data-faculty-id="{{ $studyProgram->faculty_id }}" {{ old('studyProgramId') == $studyProgram->id ? 'selected' : '' }}>
                                            {{ $studyProgram->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="w-full space-y-4">
                                {{-- <div>
                                    <label for="pernyataan_pkl" class="block mb-2 text-sm font-medium text-gray-900">
                                        Form 2.A. FORM Pernyataan PKL (Sudah di ttd oleh Pembimbing Akademik) <a href="https://docs.google.com/document/d/1y4WCefbgSUAPKQyj3uUNhVXn-6mT0hZ7/edit?usp=sharing&ouid=100372399089026266077&rtpof=true&sd=true" target="_blank" class="text-indigo-600 underline"> (Download Form 2.A disini)</a>
                                    </label>
                                    <input type="file" name="pernyataan_pkl" id="pernyataan_pkl" accept=".jpg,.jpeg,.png,.pdf"
                                           class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-indigo-600 focus:border-indigo-600"
                                           required />
                                    <p class="mt-1 text-sm text-gray-500">Maksimal ukuran file: 1 MB (jpg, jpeg, png, pdf).</p>
                                </div>
                                <div>
                                    <label for="permohonan_pkl" class="block mb-2 text-sm font-medium text-gray-900">
                                        Form 2.B. FORM Permohonan PKL (Sudah di ttd oleh Pembimbing Akademik) <a href="https://docs.google.com/document/d/193rXgbiQZLikrbmvsJN8lbVEPbAdeNyCXyUfizatcB4/edit?usp=drive_link" target="_blank" class="text-indigo-600 underline"> (Download Form 2.B disini)</a>
                                    </label>
                                    <input type="file" name="permohonan_pkl" id="permohonan_pkl" accept=".jpg,.jpeg,.png,.pdf"
                                           class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-indigo-600 focus:border-indigo-600"
                                           required />
                                    <p class="mt-1 text-sm text-gray-500">Maksimal ukuran file: 1 MB (jpg, jpeg, png, pdf).</p>
                                </div>
                                <div>
                                    <label for="penerimaan_pkl" class="block mb-2 text-sm font-medium text-gray-900">
                                        Surat Penerimaan PKL (Surat Penerimaan PKL adalah surat pernyataan yang menyatakan mahasiswa diterima di instansi PKL untuk melaksanakan kegiatan PKL selama 2 bulan) 
                                    </label>
                                    <input type="file" name="penerimaan_pkl" id="penerimaan_pkl" accept=".jpg,.jpeg,.png,.pdf"
                                           class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-indigo-600 focus:border-indigo-600"
                                           required />
                                    <p class="mt-1 text-sm text-gray-500">Maksimal ukuran file: 1 MB (jpg, jpeg, png, pdf).</p>
                                </div> --}}
                                <div>
                                    <label for="transkrip_nilai" class="block mb-2 text-sm font-medium text-gray-900">
                                        Transkrip Nilai (Upload transkrip nilai minimal sudah menempuh 80 sks lulus)
                                    </label>
                                    <input type="file" name="transkrip_nilai" id="transkrip_nilai" accept=".jpg,.jpeg,.png,.pdf"
                                           class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-indigo-600 focus:border-indigo-600"
                                           required />
                                    <p class="mt-1 text-sm text-gray-500">Maksimal ukuran file: 1 MB (jpg, jpeg, png, pdf).</p>
                                </div>
                            </div>
                        </div>
                        <div class="ml-3 text-sm">
                        </div>
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="terms" aria-describedby="terms" type="checkbox"
                                    class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-indigo-300"
                                    required="" />
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="terms" class="font-light text-gray-500">
                                    Saya menerima Syarat dan Ketentuan
                                </label>
                            </div>
                        </div>
                        <button type="submit"
                            class="w-full text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                            Buat Akun
                        </button>
                        <p class="text-sm font-light text-gray-500">
                            Sudah memiliki akun?
                            <a href="{{route('login')}}" class="font-medium text-indigo-600 hover:underline">Masuk ke
                                akunmu</a>
                        </p>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </section>
    <a href="{{ $waLink }}?text=Hallo" target="_blank" rel="noopener noreferrer"
        class="fixed flex items-center justify-center w-16 text-white rounded-full shadow-2xl lg:w-20 aspect-square bg-gradient-to-tr from-green-500 to-emerald-400 bottom-5 lg:bottom-10 right-5 lg:right-10">
        <svg viewBox="0 0 24 24" fill="#fff" xmlns="http://www.w3.org/2000/svg"
            class="w-[40px] h-[40px] lg:w-[56px] lg:h-[56px]">
            <g id="SVGRepo_bgCarrier" strokeWidth="0"></g>
            <g id="SVGRepo_tracerCarrier" strokeLinecap="round" strokeLinejoin="round"></g>
            <g id="SVGRepo_iconCarrier">
                <path
                    d="M17.6 6.31999C16.8669 5.58141 15.9943 4.99596 15.033 4.59767C14.0716 4.19938 13.0406 3.99622 12 3.99999C10.6089 4.00135 9.24248 4.36819 8.03771 5.06377C6.83294 5.75935 5.83208 6.75926 5.13534 7.96335C4.4386 9.16745 4.07046 10.5335 4.06776 11.9246C4.06507 13.3158 4.42793 14.6832 5.12 15.89L4 20L8.2 18.9C9.35975 19.5452 10.6629 19.8891 11.99 19.9C14.0997 19.9001 16.124 19.0668 17.6222 17.5816C19.1205 16.0965 19.9715 14.0796 19.99 11.97C19.983 10.9173 19.7682 9.87634 19.3581 8.9068C18.948 7.93725 18.3505 7.05819 17.6 6.31999ZM12 18.53C10.8177 18.5308 9.65701 18.213 8.64 17.61L8.4 17.46L5.91 18.12L6.57 15.69L6.41 15.44C5.55925 14.0667 5.24174 12.429 5.51762 10.8372C5.7935 9.24545 6.64361 7.81015 7.9069 6.80322C9.1702 5.79628 10.7589 5.28765 12.3721 5.37368C13.9853 5.4597 15.511 6.13441 16.66 7.26999C17.916 8.49818 18.635 10.1735 18.66 11.93C18.6442 13.6859 17.9355 15.3645 16.6882 16.6006C15.441 17.8366 13.756 18.5301 12 18.53ZM15.61 13.59C15.41 13.49 14.44 13.01 14.26 12.95C14.08 12.89 13.94 12.85 13.81 13.05C13.6144 13.3181 13.404 13.5751 13.18 13.82C13.07 13.96 12.95 13.97 12.75 13.82C11.6097 13.3694 10.6597 12.5394 10.06 11.47C9.85 11.12 10.26 11.14 10.64 10.39C10.6681 10.3359 10.6827 10.2759 10.6827 10.215C10.6827 10.1541 10.6681 10.0941 10.64 10.04C10.64 9.93999 10.19 8.95999 10.03 8.56999C9.87 8.17999 9.71 8.23999 9.58 8.22999H9.19C9.08895 8.23154 8.9894 8.25465 8.898 8.29776C8.8066 8.34087 8.72546 8.403 8.66 8.47999C8.43562 8.69817 8.26061 8.96191 8.14676 9.25343C8.03291 9.54495 7.98287 9.85749 8 10.17C8.0627 10.9181 8.34443 11.6311 8.81 12.22C9.6622 13.4958 10.8301 14.5293 12.2 15.22C12.9185 15.6394 13.7535 15.8148 14.58 15.72C14.8552 15.6654 15.1159 15.5535 15.345 15.3915C15.5742 15.2296 15.7667 15.0212 15.91 14.78C16.0428 14.4856 16.0846 14.1583 16.03 13.84C15.94 13.74 15.81 13.69 15.61 13.59Z"
                    fill="#fff"></path>
            </g>
        </svg>
    </a>
    <button id="scrollTop"
        class="fixed z-50 hidden px-4 py-2 font-bold text-white rounded-lg cursor-pointer bottom-24 lg:bottom-32 lg:right-10 right-5 bg-indigo-500 hover:bg-indigo-700"
        aria-label="Scroll to top">
        ↑ Top
    </button>

    @section('script')
    <script type="text/javascript">
       // Batasi panjang input BPI Number hingga 10 karakter
        document.addEventListener('DOMContentLoaded', function() {
            const nimInput = document.getElementById('nim');

            // Menghapus karakter non-numeric setelah input diubah
            nimInput.addEventListener('input', function() {
                // Hapus karakter non-numeric dari nilai input
                nimInput.value = nimInput.value.replace(/[^\d]/g, '');

                // Batasi panjang input hingga 10 karakter
                if (nimInput.value.length > 21) {
                    nimInput.value = nimInput.value.slice(0, 21);
                }
            });

            // Mencegah karakter non-numeric untuk dimasukkan
            nimInput.addEventListener('keypress', function(event) {
                const char = String.fromCharCode(event.which);
                if (!/\d/.test(char)) {
                    event.preventDefault(); // Cegah input karakter non-numeric
                }
            });
        });

        $(document).ready(function() {
            // Handle alert close
            $(document).ready(function() {
                $('#alert-error span, #alert-success span').click(function() {
                    $(this).parent().hide()
                })
            })

            // Filter program studi berdasarkan fakultas yang dipilih
            $('#faculty').change(function() {
                var selectedFaculty = $(this).val()
                $('#study-program').val('')
                $('#study-program').removeAttr('disabled')

                $('#study-program option').each(function() {
                    // Show all options first
                    $(this).show();

                    // Hide the options that don't match the selected faculty
                    if ($(this).data('faculty-id') != selectedFaculty && $(this).data('faculty-id') != '') {
                        $(this).hide();
                    }
                });
            });
        })

        document.addEventListener('DOMContentLoaded', function () {
            // const pernyataanPklInput = document.getElementById('pernyataan_pkl');
            // const permohonanPklInput = document.getElementById('permohonan_pkl');
            // const penerimaanPklInput = document.getElementById('penerimaan_pkl');
            const transkripNilaiInput = document.getElementById('transkrip_nilai');

            const form = document.querySelector('form');
            form.addEventListener('submit', function () {
                // localStorage.setItem('pernyataan_pkl', pernyataanPklInput.value);
                // localStorage.setItem('permohonan_pkl', permohonanPklInput.value);
                // localStorage.setItem('penerimaan_pkl', penerimaanPklInput.value);
                localStorage.setItem('transkrip_nilai', transkripNilaiInput.value);
            });

            // if (localStorage.getItem('pernyataan_pkl')) {
            //     pernyataanPklInput.value = localStorage.getItem('pernyataan_pkl');
            // }

            // if (localStorage.getItem('permohonan_pkl')) {
            //     permohonanPklInput.value = localStorage.getItem('permohonan_pkl');
            // }

            // if (localStorage.getItem('penerimaan_pkl')) {
            //     penerimaanPklInput.value = localStorage.getItem('penerimaan_pkl');
            // }

            if (localStorage.getItem('transkrip_nilai')) {
                transkripNilaiInput.value.value = localStorage.getItem('transkrip_nilai');
            }
        });
    </script>
    @endsection

</x-landing-page.layout>
