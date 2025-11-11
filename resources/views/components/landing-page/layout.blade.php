<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Website Official BPI UI</title>
    <link rel="apple-touch-icon" sizes="57x57" href="{{asset('assets/favicon/apple-icon-57x57.png')}}" />
    <link rel="apple-touch-icon" sizes="60x60" href="./assets/favicon/apple-icon-60x60.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="./assets/favicon/apple-icon-72x72.png" />
    <link rel="apple-touch-icon" sizes="76x76" href="./assets/favicon/apple-icon-76x76.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="./assets/favicon/apple-icon-114x114.png" />
    <link rel="apple-touch-icon" sizes="120x120" href="./assets/favicon/apple-icon-120x120.png" />
    <link rel="apple-touch-icon" sizes="144x144" href="./assets/favicon/apple-icon-144x144.png" />
    <link rel="apple-touch-icon" sizes="152x152" href="./assets/favicon/apple-icon-152x152.png" />
    <link rel="apple-touch-icon" sizes="180x180" href="./assets/favicon/apple-icon-180x180.png" />
    <link rel="icon" type="image/png" sizes="192x192" href="./assets/favicon/android-icon-192x192.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="./assets/favicon/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="96x96" href="./assets/favicon/favicon-96x96.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="./assets/favicon/favicon-16x16.png" />
    {{-- <link rel="manifest" href="./assets/favicon/manifest.json" /> --}}
    <link rel="manifest" href="{{ asset('assets/favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff" />
    <meta name="msapplication-TileImage" content="./assets/favicon/ms-icon-144x144.png" />
    <meta name="theme-color" content="#ffffff" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script defer src="{{asset('js/landing-page/script.js')}}"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" />

    
    @yield('css')
</head>

<body style="font-family: 'Poppins', sans-serif">
    <header class="absolute top-0 left-0 right-0 z-50">
        <nav class="fixed top-0 w-full px-4 transition-all duration-300 ease-in-out bg-white border-gray-200 shadow lg:px-6"
            id="navbar">
            <div class="flex flex-wrap items-center justify-between max-w-screen-xl mx-auto">
                <a href="/" class="flex items-center">
                    <img src="{{asset('assets/logo-bpi.png')}}" class="h-20 lg:h-24" alt="BPI UI Logo" />
                </a>
                <div class="flex items-center lg:order-2 lg:hidden">
                    <button data-collapse-toggle="mobile-menu-2" type="button"
                        class="inline-flex items-center p-2 ml-1 text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200"
                        aria-controls="mobile-menu-2" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <svg class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <div class="items-center justify-between hidden w-full lg:flex lg:w-auto lg:order-1" id="mobile-menu-2">
                    <hr class="mt-3" />
                    <ul class="flex flex-col items-center gap-3 my-5 mt-4 font-medium lg:flex-row lg:gap-0 lg:mt-0">
                        <li class="flex items-center">
                            <a class="text-white bg-indigo-500 hover:bg-indigo-600 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center me-2"
                                href="{{ $socialMediaUrl['youtube']->url }}">
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
                                href="{{ $socialMediaUrl['instagram']->url }}">
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
                        </li>
                        @auth
                        @php
                        $user = Auth::user();
                        @endphp
                        <li>
                            <img id="avatarButton" type="button" data-dropdown-toggle="userDropdown"
                                data-dropdown-placement="bottom-start" class="w-10 h-10 rounded-full cursor-pointer"
                                src="{{$user->pp_url ? asset('storage/'.$user->pp_url) : asset('img/undraw_profile.svg')}}"
                                alt="User dropdown">

                            <!-- Dropdown menu -->
                            <div id="userDropdown"
                                class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                                <div class="px-4 py-3 text-sm text-gray-900">
                                    <div>{{$user->awardee->fullname ?? $user->admin->fullname}}</div>
                                    <div class="font-medium truncate">{{$user->email}}</div>
                                </div>
                                <ul class="py-2 text-sm text-gray-700" aria-labelledby="avatarButton">
                                    @if ($user->awardee)
                                    <li>
                                        <a href="{{route('awardee.profile', ['user' => $user->id])}}"
                                            class="block px-4 py-2 hover:bg-gray-100">Profile</a>
                                    </li>
                                    <li>
                                        <a href="{{route('complaint.index')}}"
                                            class="block px-4 py-2 hover:bg-gray-100">Member area</a>
                                    </li>
                                    @endif
                                    @if ($user->role->title == 'Admin')
                                    <li>
                                        <a href="{{route('admin.index')}}"
                                            class="block px-4 py-2 hover:bg-gray-100">Admin area</a>
                                    </li>
                                    @endif
                                    <li>
                                        <a href="{{route('logout')}}"
                                            class="block px-4 py-2 hover:bg-gray-100">Logout</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        @endauth
                        @guest
                        <li class="flex items-center">
                            <a href="{{route('login')}}"
                                class="focus:outline-none text-white bg-indigo-400 hover:bg-indigo-500 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2">Masuk
                            </a>
                        </li>
                        <li class="flex items-center">
                            <a href="{{route('register.index')}}"
                                class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2">Daftar
                            </a>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    {{-- Content placeholder --}}
    {{ $slot }}

    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.js"></script>
    @yield('script')
</body>

</html>
