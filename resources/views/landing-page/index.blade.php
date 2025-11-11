<x-landing-page.layout>

    @section('css')
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" rel="stylesheet" />
    <style>

        h1,
        .h1 {
            font-size: 2em !important;
            /* Typically around 32px */
            font-weight: bold !important;
            margin-top: 0.67em !important;
            margin-bottom: 0.67em !important;
            line-height: 1.2 !important;
        }

        h2,
        .h2 {
            font-size: 1.5em !important;
            /* Typically around 24px */
            font-weight: bold !important;
            margin-top: 0.83em !important;
            margin-bottom: 0.83em !important;
            line-height: 1.3 !important;
        }

        h3,
        .h3 {
            font-size: 1.17em !important;
            /* Typically around 18.72px */
            font-weight: bold !important;
            margin-top: 1em !important;
            margin-bottom: 1em !important;
            line-height: 1.4 !important;
        }

        h4,
        .h4 {
            font-size: 1em !important;
            /* Typically around 16px */
            font-weight: bold !important;
            margin-top: 1.33em !important;
            margin-bottom: 1.33em !important;
            line-height: 1.5 !important;
        }

        h5,
        .h5 {
            font-size: 0.83em !important;
            /* Typically around 13.28px */
            font-weight: bold !important;
            margin-top: 1.67em !important;
            margin-bottom: 1.67em !important;
            line-height: 1.6 !important;
        }

        h6,
        .h6 {
            font-size: 0.67em !important;
            /* Typically around 10.72px */
            font-weight: bold !important;
            margin-top: 2.33em !important;
            margin-bottom: 2.33em !important;
            line-height: 1.7 !important;
        }

        .ck.ck-editor__main>.ck-editor__editable {
            border: none !important;
            background: transparent !important;
        }
    </style>
    @endsection

    <section class="splide w-full h-screen absolute top-0 left-0 right-0 z-[-1]">
        <div class="splide__track">
            <div class="h-screen splide__list">
                @foreach ($carouselImages as $carouselImage)
                <img src="{{ asset('storage/' . $carouselImage->url) }}" alt="slideshow image" class="object-cover splide__slide"
                    srcset="" />
                @endforeach
            </div>
        </div>
    </section>
    <section class="mt-[80vh] space-y-36 lg:space-y-56">
        <div class="grid lg:grid-cols-2 gap-4 px-5 lg:px-20 w-[90%] xl:w-[80%] items-center mx-auto justify-center">
            <div class="flex flex-col items-end gap-4">
                <div class="relative overflow-hidden rounded-lg">
                    <img class="object-cover w-full h-auto" src="{{ asset('storage/' . $Image['awardee']->url) }}" alt="" />
                    <a class="absolute top-0 bottom-0 left-0 right-0 p-10 transition-opacity duration-500 opacity-0 bg-gradient-to-l from-red-300/30 to-red-600/50 hover:opacity-100 hover:backdrop-blur-sm"
                        href="{{ route('landing-page.awardee.index') }}">
                        <p class="text-3xl font-bold text-white">Awardee</p>
                        <p class="absolute text-2xl font-semibold text-white bottom-10 right-10">
                            {{ $totaluser }} Awardee
                        </p>
                    </a>
                </div>
                <div class="lg:w-[70%] relative overflow-hidden rounded-lg">
                    <img class="object-cover w-full h-auto" src="{{ asset('storage/' . $Image['faq']->url) }}" alt="" />
                    <a class="absolute top-0 bottom-0 left-0 right-0 p-10 transition-opacity duration-500 opacity-0 bg-gradient-to-l from-red-300/30 to-red-600/50 hover:opacity-100 hover:backdrop-blur-sm"
                        href="{{route('landing-page.faq.index')}}">
                        <p class="text-3xl font-bold text-white">FAQ</p>
                        <p class="absolute text-2xl font-semibold text-white bottom-10 right-10">
                            {{$totalFaq}} FAQ
                        </p>
                    </a>
                </div>
            </div>
            <div class="flex flex-col gap-4">
                <div class="relative overflow-hidden rounded-lg">
                    <img class="object-cover w-full h-auto" src="{{ asset('storage/' . $Image['pengaduan']->url) }}" alt="" />
                    @guest
                    <a class="absolute top-0 bottom-0 left-0 right-0 p-10 transition-opacity duration-500 opacity-0 bg-gradient-to-l from-red-300/30 to-red-600/50 hover:opacity-100 hover:backdrop-blur-sm"
                        href="{{route('complaint.index')}}">
                        <p class="text-3xl font-bold text-white">Pengaduan</p>
                    </a>
                    @endguest
                    @auth
                    <a class="absolute top-0 bottom-0 left-0 right-0 p-10 transition-opacity duration-500 opacity-0 bg-gradient-to-l from-red-300/30 to-red-600/50 hover:opacity-100 hover:backdrop-blur-sm"
                        href="{{Auth::user()->role->title == 'admin' && Auth::user()->admin ? route('admin.complaint.index') : route('complaint.index')}}">
                        <p class="text-3xl font-bold text-white">Pengaduan</p>
                    </a>
                    @endauth
                </div>
                <div class="relative overflow-hidden rounded-lg">
                    <img class="object-cover w-full h-auto" src="{{ asset('storage/' . $Image['gallery']->url) }}" alt="" />
                    <a class="absolute top-0 bottom-0 left-0 right-0 p-10 transition-opacity duration-500 opacity-0 bg-gradient-to-l from-red-300/30 to-red-600/50 hover:opacity-100 hover:backdrop-blur-sm"
                        href="https://bit.ly/dokumentasigatheringbpiui2024">
                        <p class="text-3xl font-bold text-white">Gallery</p>
                    </a>
                </div>
            </div>
        </div>
        <div class="px-5 lg:px-32">
            <h1 class="text-3xl font-bold tracking-widest">Tentang BPI UI</h1>
            <div id="about">
                {!! $about->content !!}
            </div>
        </div>
        <div class="px-5 py-10 text-lg text-center text-white bg-blue-400 lg:text-2xl">
            <p>
                Whatsapp BPI UI Official :
                <a href="{{ $waLink }}?text=Hallo" class="transition-all hover:underline hover:text-blue-600">
                    {{$kontak['whatsapp']->url }}</a>
            </p>
        </div>

        <footer class="bg-white">
            <div class="w-full p-4 py-6 mx-auto lg:py-8">
                <div class="grid gap-5 sm:grid-cols-3 xl:px-72 sm:divide-x lg:gap-0">
                    <div class="mb-6 md:mb-0">
                        <a href="#" class="flex items-center">
                            <img src="./assets/logo-bpi.png" class="h-40" alt="BPI UI Logo" />
                        </a>
                    </div>
                    <div class="sm:px-5 lg:px-10">
                        <div>
                            <div>
                                <h2 class="text-sm font-semibold text-gray-900 uppercase">
                                    Kampus Baru Depok
                                </h2>
                                <p>Universitas Indonesia</p>
                                <p>Jawa Barat 16424</p>
                                <p>Indonesia</p>
                            </div>
                            <div class="flex items-center gap-2 mt-10 text-sm">
                                <svg width="18px" height="18px" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path
                                            d="M3 8L8.44992 11.6333C9.73295 12.4886 10.3745 12.9163 11.0678 13.0825C11.6806 13.2293 12.3194 13.2293 12.9322 13.0825C13.6255 12.9163 14.2671 12.4886 15.5501 11.6333L21 8M6.2 19H17.8C18.9201 19 19.4802 19 19.908 18.782C20.2843 18.5903 20.5903 18.2843 20.782 17.908C21 17.4802 21 16.9201 21 15.8V8.2C21 7.0799 21 6.51984 20.782 6.09202C20.5903 5.71569 20.2843 5.40973 19.908 5.21799C19.4802 5 18.9201 5 17.8 5H6.2C5.0799 5 4.51984 5 4.09202 5.21799C3.71569 5.40973 3.40973 5.71569 3.21799 6.09202C3 6.51984 3 7.07989 3 8.2V15.8C3 16.9201 3 17.4802 3.21799 17.908C3.40973 18.2843 3.71569 18.5903 4.09202 18.782C4.51984 19 5.07989 19 6.2 19Z"
                                            stroke="#000000" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                    </g>
                                </svg>
                                <a href="mailto:{{ $kontak['email']->url }}"
                                    class="transition-all hover:underline hover:text-blue-600">{{
                                    $kontak['email']->url }}</a>
                            </div>
                            <div class="flex items-center gap-2 mt-3 text-sm">
                                <svg width="18px" height="18px" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path
                                            d="M16.1007 13.359L15.5719 12.8272H15.5719L16.1007 13.359ZM16.5562 12.9062L17.085 13.438H17.085L16.5562 12.9062ZM18.9728 12.5894L18.6146 13.2483L18.9728 12.5894ZM20.8833 13.628L20.5251 14.2869L20.8833 13.628ZM21.4217 16.883L21.9505 17.4148L21.4217 16.883ZM20.0011 18.2954L19.4723 17.7636L20.0011 18.2954ZM18.6763 18.9651L18.7459 19.7119H18.7459L18.6763 18.9651ZM8.81536 14.7266L9.34418 14.1947L8.81536 14.7266ZM4.00289 5.74561L3.2541 5.78816L3.2541 5.78816L4.00289 5.74561ZM10.4775 7.19738L11.0063 7.72922H11.0063L10.4775 7.19738ZM10.6342 4.54348L11.2346 4.09401L10.6342 4.54348ZM9.37326 2.85908L8.77286 3.30855V3.30855L9.37326 2.85908ZM6.26145 2.57483L6.79027 3.10667H6.79027L6.26145 2.57483ZM4.69185 4.13552L4.16303 3.60368H4.16303L4.69185 4.13552ZM12.0631 11.4972L12.5919 10.9654L12.0631 11.4972ZM16.6295 13.8909L17.085 13.438L16.0273 12.3743L15.5719 12.8272L16.6295 13.8909ZM18.6146 13.2483L20.5251 14.2869L21.2415 12.9691L19.331 11.9305L18.6146 13.2483ZM20.8929 16.3511L19.4723 17.7636L20.5299 18.8273L21.9505 17.4148L20.8929 16.3511ZM18.6067 18.2184C17.1568 18.3535 13.4056 18.2331 9.34418 14.1947L8.28654 15.2584C12.7186 19.6653 16.9369 19.8805 18.7459 19.7119L18.6067 18.2184ZM9.34418 14.1947C5.4728 10.3453 4.83151 7.10765 4.75168 5.70305L3.2541 5.78816C3.35456 7.55599 4.14863 11.144 8.28654 15.2584L9.34418 14.1947ZM10.7195 8.01441L11.0063 7.72922L9.9487 6.66555L9.66189 6.95073L10.7195 8.01441ZM11.2346 4.09401L9.97365 2.40961L8.77286 3.30855L10.0338 4.99296L11.2346 4.09401ZM5.73263 2.04299L4.16303 3.60368L5.22067 4.66736L6.79027 3.10667L5.73263 2.04299ZM10.1907 7.48257C9.66189 6.95073 9.66117 6.95144 9.66045 6.95216C9.66021 6.9524 9.65949 6.95313 9.659 6.95362C9.65802 6.95461 9.65702 6.95561 9.65601 6.95664C9.65398 6.95871 9.65188 6.96086 9.64972 6.9631C9.64539 6.96759 9.64081 6.97245 9.63599 6.97769C9.62634 6.98816 9.61575 7.00014 9.60441 7.01367C9.58174 7.04072 9.55605 7.07403 9.52905 7.11388C9.47492 7.19377 9.41594 7.2994 9.36589 7.43224C9.26376 7.70329 9.20901 8.0606 9.27765 8.50305C9.41189 9.36833 10.0078 10.5113 11.5343 12.0291L12.5919 10.9654C11.1634 9.54499 10.8231 8.68059 10.7599 8.27309C10.7298 8.07916 10.761 7.98371 10.7696 7.96111C10.7748 7.94713 10.7773 7.9457 10.7709 7.95525C10.7677 7.95992 10.7624 7.96723 10.7541 7.97708C10.75 7.98201 10.7451 7.98759 10.7394 7.99381C10.7365 7.99692 10.7335 8.00019 10.7301 8.00362C10.7285 8.00534 10.7268 8.00709 10.725 8.00889C10.7241 8.00979 10.7232 8.0107 10.7223 8.01162C10.7219 8.01208 10.7212 8.01278 10.7209 8.01301C10.7202 8.01371 10.7195 8.01441 10.1907 7.48257ZM11.5343 12.0291C13.0613 13.5474 14.2096 14.1383 15.0763 14.2713C15.5192 14.3392 15.8763 14.285 16.1472 14.1841C16.28 14.1346 16.3858 14.0763 16.4658 14.0227C16.5058 13.9959 16.5392 13.9704 16.5663 13.9479C16.5799 13.9367 16.5919 13.9262 16.6024 13.9166C16.6077 13.9118 16.6126 13.9073 16.6171 13.903C16.6194 13.9008 16.6215 13.8987 16.6236 13.8967C16.6246 13.8957 16.6256 13.8947 16.6266 13.8937C16.6271 13.8932 16.6279 13.8925 16.6281 13.8923C16.6288 13.8916 16.6295 13.8909 16.1007 13.359C15.5719 12.8272 15.5726 12.8265 15.5733 12.8258C15.5735 12.8256 15.5742 12.8249 15.5747 12.8244C15.5756 12.8235 15.5765 12.8226 15.5774 12.8217C15.5793 12.82 15.581 12.8183 15.5827 12.8166C15.5862 12.8133 15.5895 12.8103 15.5926 12.8074C15.5988 12.8018 15.6044 12.7969 15.6094 12.7929C15.6192 12.7847 15.6265 12.7795 15.631 12.7764C15.6403 12.7702 15.6384 12.773 15.6236 12.7785C15.5991 12.7876 15.501 12.8189 15.3038 12.7886C14.8905 12.7253 14.02 12.3853 12.5919 10.9654L11.5343 12.0291ZM9.97365 2.40961C8.95434 1.04802 6.94996 0.83257 5.73263 2.04299L6.79027 3.10667C7.32195 2.578 8.26623 2.63181 8.77286 3.30855L9.97365 2.40961ZM4.75168 5.70305C4.73201 5.35694 4.89075 4.9954 5.22067 4.66736L4.16303 3.60368C3.62571 4.13795 3.20329 4.89425 3.2541 5.78816L4.75168 5.70305ZM19.4723 17.7636C19.1975 18.0369 18.9029 18.1908 18.6067 18.2184L18.7459 19.7119C19.4805 19.6434 20.0824 19.2723 20.5299 18.8273L19.4723 17.7636ZM11.0063 7.72922C11.9908 6.7503 12.064 5.2019 11.2346 4.09401L10.0338 4.99295C10.4373 5.53193 10.3773 6.23938 9.9487 6.66555L11.0063 7.72922ZM20.5251 14.2869C21.3429 14.7315 21.4703 15.7769 20.8929 16.3511L21.9505 17.4148C23.2908 16.0821 22.8775 13.8584 21.2415 12.9691L20.5251 14.2869ZM17.085 13.438C17.469 13.0562 18.0871 12.9616 18.6146 13.2483L19.331 11.9305C18.2474 11.3414 16.9026 11.5041 16.0273 12.3743L17.085 13.438Z"
                                            fill="#1C274C"></path>
                                    </g>
                                </svg>
                                <a href="tel:08123456789"
                                    class="transition-all hover:underline hover:text-blue-600">{{
                                    $kontak['whatsapp']->url }}</a>
                            </div>
                            <div class="flex items-center gap-2 mt-3 text-sm">
                                <svg width="18px" height="18px" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path
                                            d="M6.014 8.00613C6.12827 7.1024 7.30277 5.87414 8.23488 6.01043L8.23339 6.00894C9.14051 6.18132 9.85859 7.74261 10.2635 8.44465C10.5504 8.95402 10.3641 9.4701 10.0965 9.68787C9.7355 9.97883 9.17099 10.3803 9.28943 10.7834C9.5 11.5 12 14 13.2296 14.7107C13.695 14.9797 14.0325 14.2702 14.3207 13.9067C14.5301 13.6271 15.0466 13.46 15.5548 13.736C16.3138 14.178 17.0288 14.6917 17.69 15.27C18.0202 15.546 18.0977 15.9539 17.8689 16.385C17.4659 17.1443 16.3003 18.1456 15.4542 17.9421C13.9764 17.5868 8 15.27 6.08033 8.55801C5.97237 8.24048 5.99955 8.12044 6.014 8.00613Z"
                                            fill="#0F0F0F"></path>
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M12 23C10.7764 23 10.0994 22.8687 9 22.5L6.89443 23.5528C5.56462 24.2177 4 23.2507 4 21.7639V19.5C1.84655 17.492 1 15.1767 1 12C1 5.92487 5.92487 1 12 1C18.0751 1 23 5.92487 23 12C23 18.0751 18.0751 23 12 23ZM6 18.6303L5.36395 18.0372C3.69087 16.4772 3 14.7331 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12C21 16.9706 16.9706 21 12 21C11.0143 21 10.552 20.911 9.63595 20.6038L8.84847 20.3397L6 21.7639V18.6303Z"
                                            fill="#0F0F0F"></path>
                                    </g>
                                </svg>
                                <a href="{{ $waLink }}?text=Hallo"
                                    class="transition-all hover:underline hover:text-blue-600">Whatsapp</a>
                            </div>
                        </div>
                        <div class="flex mt-4 sm:mt-5">
                            <a class="text-white bg-indigo-500 hover:bg-indigo-600 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center me-2"
                                href="{{ $socialMedia['youtube']->url }}">
                                <svg width="16px" height="16px" viewBox="0 -3 20 20" version="1.1"
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    fill="currentColor">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <title>youtube [#168]</title>
                                        <desc>Created with Sketch.</desc>
                                        <defs></defs>
                                        <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g id="Dribbble-Light-Preview"
                                                transform="translate(-300.000000, -7442.000000)" fill="currentColor">
                                                <g id="icons" transform="translate(56.000000, 160.000000)">
                                                    <path
                                                        d="M251.988432,7291.58588 L251.988432,7285.97425 C253.980638,7286.91168 255.523602,7287.8172 257.348463,7288.79353 C255.843351,7289.62824 253.980638,7290.56468 251.988432,7291.58588 M263.090998,7283.18289 C262.747343,7282.73013 262.161634,7282.37809 261.538073,7282.26141 C259.705243,7281.91336 248.270974,7281.91237 246.439141,7282.26141 C245.939097,7282.35515 245.493839,7282.58153 245.111335,7282.93357 C243.49964,7284.42947 244.004664,7292.45151 244.393145,7293.75096 C244.556505,7294.31342 244.767679,7294.71931 245.033639,7294.98558 C245.376298,7295.33761 245.845463,7295.57995 246.384355,7295.68865 C247.893451,7296.0008 255.668037,7296.17532 261.506198,7295.73552 C262.044094,7295.64178 262.520231,7295.39147 262.895762,7295.02447 C264.385932,7293.53455 264.28433,7285.06174 263.090998,7283.18289"
                                                        id="youtube-[#168]"></path>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            </a>
                            <a class="text-white bg-indigo-500 hover:bg-indigo-600 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center me-2"
                                href="{{ $socialMedia['instagram']->url }}">
                                <svg width="16px" height="16px" viewBox="0 0 20 20" version="1.1"
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    fill="currentColor">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <title>instagram [#167]</title>
                                        <desc>Created with Sketch.</desc>
                                        <defs></defs>
                                        <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g id="Dribbble-Light-Preview"
                                                transform="translate(-340.000000, -7439.000000)" fill="currentColor">
                                                <g id="icons" transform="translate(56.000000, 160.000000)">
                                                    <path
                                                        d="M289.869652,7279.12273 C288.241769,7279.19618 286.830805,7279.5942 285.691486,7280.72871 C284.548187,7281.86918 284.155147,7283.28558 284.081514,7284.89653 C284.035742,7285.90201 283.768077,7293.49818 284.544207,7295.49028 C285.067597,7296.83422 286.098457,7297.86749 287.454694,7298.39256 C288.087538,7298.63872 288.809936,7298.80547 289.869652,7298.85411 C298.730467,7299.25511 302.015089,7299.03674 303.400182,7295.49028 C303.645956,7294.859 303.815113,7294.1374 303.86188,7293.08031 C304.26686,7284.19677 303.796207,7282.27117 302.251908,7280.72871 C301.027016,7279.50685 299.5862,7278.67508 289.869652,7279.12273 M289.951245,7297.06748 C288.981083,7297.0238 288.454707,7296.86201 288.103459,7296.72603 C287.219865,7296.3826 286.556174,7295.72155 286.214876,7294.84312 C285.623823,7293.32944 285.819846,7286.14023 285.872583,7284.97693 C285.924325,7283.83745 286.155174,7282.79624 286.959165,7281.99226 C287.954203,7280.99968 289.239792,7280.51332 297.993144,7280.90837 C299.135448,7280.95998 300.179243,7281.19026 300.985224,7281.99226 C301.980262,7282.98483 302.473801,7284.28014 302.071806,7292.99991 C302.028024,7293.96767 301.865833,7294.49274 301.729513,7294.84312 C300.829003,7297.15085 298.757333,7297.47145 289.951245,7297.06748 M298.089663,7283.68956 C298.089663,7284.34665 298.623998,7284.88065 299.283709,7284.88065 C299.943419,7284.88065 300.47875,7284.34665 300.47875,7283.68956 C300.47875,7283.03248 299.943419,7282.49847 299.283709,7282.49847 C298.623998,7282.49847 298.089663,7283.03248 298.089663,7283.68956 M288.862673,7288.98792 C288.862673,7291.80286 291.150266,7294.08479 293.972194,7294.08479 C296.794123,7294.08479 299.081716,7291.80286 299.081716,7288.98792 C299.081716,7286.17298 296.794123,7283.89205 293.972194,7283.89205 C291.150266,7283.89205 288.862673,7286.17298 288.862673,7288.98792 M290.655732,7288.98792 C290.655732,7287.16159 292.140329,7285.67967 293.972194,7285.67967 C295.80406,7285.67967 297.288657,7287.16159 297.288657,7288.98792 C297.288657,7290.81525 295.80406,7292.29716 293.972194,7292.29716 C292.140329,7292.29716 290.655732,7290.81525 290.655732,7288.98792"
                                                        id="instagram-[#167]"></path>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="sm:px-5 lg:px-10">
                        <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase">
                            Tautan
                        </h2>
                        <ul class="font-medium text-gray-500 dark:text-gray-400">
                            <li class="mb-4">
                                <a href="https://beasiswa.kemdikbud.go.id/" class="hover:underline">BPI</a>
                            </li>
                            <li>
                                <a href="https://www.ui.ac.id/" class="hover:underline">Universitas
                                    Indonesia</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <hr class="my-6 border-gray-200 sm:mx-auto lg:my-8" />

                <div class="sm:flex sm:items-center sm:justify-center">
                    <span class="text-sm text-center text-gray-500"><span id="footer-year"></span> Hak Cipta,
                        <a href="#" class="hover:underline">BPI UI</a>.
                    </span>
                </div>
            </div>
        </footer>
    </section>
    <a href="{{ $waLink }}?text=Hallo" rel="noopener noreferrer"
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
    <script>
        $(document).ready(function () {
          // Image slider library
          new Splide(".splide", {
            type: "loop",
            drag: true,
            autoplay: true,
          }).mount();

          $("#scrollTop").fadeToggle();
        });
    </script>
    @endsection

</x-landing-page.layout>
