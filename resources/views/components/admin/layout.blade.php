<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>BPI UI</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('css/sb-admin-2-new.min.css') }}" rel="stylesheet">

    {{-- JQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    {{-- Custom style for specific page --}}
    @yield('css')

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
                <div class="sidebar-brand-icon" style="width: 5rem">
                    <img src="{{asset('assets/logo-bpi.png')}}" alt="" class="h-auto w-100">
                </div>
            </a>

            <!-- Divider -->
            <hr class="my-0 sidebar-divider">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ Request::routeIs('admin.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('admin.index')}}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>

            {{-- Pengaduan --}}
            <li class="nav-item {{ Request::routeIs('admin.complaint.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('admin.complaint.index')}}">
                    <i class="fas fa-comments"></i>
                    <span>Pengaduan</span></a>
            </li>

            {{-- Faq --}}
            <li class="nav-item {{ Request::routeIs('faq.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('faq.index')}}">
                    <i class="fas fa-question-circle"></i>
                    <span>FAQ</span></a>
            </li>

            {{-- Dokumen --}}
            <li class="nav-item {{ Request::routeIs('doc.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('doc.index')}}">
                    <i class="fas fa-file"></i>
                    <span>Dokumen</span></a>
            </li>

            {{-- @php
                $isPaymentActive = Request::routeIs('fee.index') || Request::routeIs('payment-settings.index') || Request::routeIs('confirm-payment.index');
            @endphp
            <li class="nav-item {{ $isPaymentActive ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOne"
                    aria-expanded="true" aria-controls="collapseOne">
                    <i class="fas fa-fw fa-receipt"></i>
                    <span>Fee</span>
                </a>
                <div id="collapseOne" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="py-2 bg-white rounded collapse-inner">
                        <h6 class="collapse-header">Kustom Landing Page:</h6>
                        <a class="collapse-item {{ Request::routeIs('fee.index') ? 'active' : '' }}"
                            href="{{route('fee.index')}}">Jenis Iuran</a>
                        <a class="collapse-item {{ Request::routeIs('payment-settings.index') ? 'active' : '' }}"
                            href="{{route('payment-settings.index')}}">Jenis Rekening/E-Wallet</a>
                        <a class="collapse-item {{ Request::routeIs('confirm-payment.index') ? 'active' : '' }}"
                            href="{{route('confirm-payment.index')}}">Konfirmasi Pembayaran</a>
                    </div>
                </div>
            </li> --}}

            {{-- Landing Page --}}
            @php
                $isLandingPageActive = Request::routeIs('landingpage.*');
            @endphp

            <li class="nav-item {{ $isLandingPageActive ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="far fa-newspaper"></i>
                    <span>Landing Page</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="py-2 bg-white rounded collapse-inner">
                        <h6 class="collapse-header">Kustom Landing Page:</h6>
                        <a class="collapse-item {{ Request::routeIs('landingpage.carolusel') ? 'active' : '' }}"
                            href="{{route('landingpage.carolusel')}}">Carousel</a>
                        <a class="collapse-item {{ Request::routeIs('landingpage.iconmenu') ? 'active' : '' }}"
                            href="{{route('landingpage.iconmenu')}}">Icon Menu</a>
                        <a class="collapse-item {{ Request::routeIs('landingpage.tentang') ? 'active' : '' }}"
                            href="{{route('landingpage.tentang')}}">Tentang</a>
                        <a class="collapse-item {{ Request::routeIs('landingpage.sosialmedia') ? 'active' : '' }}"
                            href="{{route('landingpage.sosialmedia')}}">Sosial Media</a>
                        <a class="collapse-item {{ Request::routeIs('landingpage.kontak') ? 'active' : '' }}"
                            href="{{route('landingpage.kontak')}}">Kontak</a>
                        <a class="collapse-item" href="{{ route('news.berita') }}">Berita</a>
                        <a class="collapse-item" href="{{ route('index.kalender') }}">Kalender</a>
                    </div>
                </div>
            </li>

            {{-- User --}}
            @php
                $isUserActive = Request::routeIs('user.*');
            @endphp

            <li class="nav-item {{ $isUserActive ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseThree"
                    aria-expanded="true" aria-controls="collapseThree">
                    <i class="fas fa-user-graduate"></i>
                    <span>User</span>
                </a>
                <div id="collapseThree" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="py-2 bg-white rounded collapse-inner">
                        <h6 class="collapse-header">Kustom Landing Page:</h6>
                        <a class="collapse-item {{ Request::routeIs('user.index') ? 'active' : '' }}"
                            href="{{route('user.index')}}">User</a>
                        <a class="collapse-item {{ Request::routeIs('user.approve') ? 'active' : '' }}"
                            href="{{route('user.approve')}}">Approve</a>
                    </div>
                </div>
            </li>

            {{-- Master data --}}
            @php
               $isMasterDataActive = Request::routeIs('faculty.index') || Request::routeIs('studyProgram.index') || Request::routeIs('complaintType.index') || Request::routeIs('partner.index') || Request::routeIs('Period.index');
            @endphp

            <li class="nav-item {{ $isMasterDataActive ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFour"
                    aria-expanded="true" aria-controls="collapseFour">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Master Data</span>
                </a>
                <div id="collapseFour" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="py-2 bg-white rounded collapse-inner">
                        <a class="collapse-item {{ Request::routeIs('faculty.index') ? 'active' : '' }}"
                            href="{{route('faculty.index')}}">Fakultas</a>
                        <a class="collapse-item {{ Request::routeIs('studyProgram.index') ? 'active' : '' }}"
                            href="{{route('studyProgram.index')}}">Program Studi</a>
                        <a class="collapse-item {{ Request::routeIs('complaintType.index') ? 'active' : '' }}"
                            href="{{route('complaintType.index')}}">Jenis Aduan</a>
                        <a class="collapse-item {{ Request::routeIs('partner.index') ? 'active' : '' }}"
                            href="{{route('partner.index')}}">Partner</a>
                        <a class="collapse-item {{ Request::routeIs('Period.index') ? 'active' : '' }}"
                            href="{{route('Period.index')}}">Periode</a>
                    </div>
                </div>
            </li>

            <!-- Log Activities -->
            @php
                $user = Auth::user();
            @endphp

            @if ($user->isSuperAdmin())
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.logs') }}">
                        <i class="fas fa-history"></i>
                        <span>Log Activities</span>
                    </a>
                </li>
            @endif

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="border-0 rounded-circle" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="mb-4 bg-white shadow navbar navbar-expand navbar-light topbar static-top">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="mr-3 btn btn-link d-md-none rounded-circle">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="ml-auto navbar-nav">

                        @php
                        $user = Auth::user();
                        @endphp

                        <!-- Nav Item - Alerts -->
                        <li class="mx-1 nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-2x fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter" style="font-size: 1.5rem;">{{$notifications->count()}}</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="shadow dropdown-list dropdown-menu dropdown-menu-right animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Notifikasi
                                </h6>
                                @foreach ($notifications as $notification)
                                <a class="dropdown-item d-flex align-items-center"
                                    href="{{route('notifications.read', ['id' => $notification->id])}}">
                                    <div>
                                        <div class="text-gray-500 small">{{$notification->created_at->format('j F Y')}}
                                        </div>
                                        <span class="font-weight-bold">{{$notification->type == 'thread-chat' ?
                                            'Terdapat pesan pada thread dengan nomor pengaduan
                                            '.$notification->data['thread']['complaint']['complaint_id'] : 'Terdapat
                                            pengaduan baru dengan nomor
                                            '.$notification->data['complaint']['complaint_id']}}</span>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </li>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 text-gray-600 d-none d-lg-inline small">
                                    {{$user->awardee->fullname ?? $user->admin->fullname}}
                                </span>
                                <img class="img-profile rounded-circle"
                                    src="{{$user->pp_url ? asset('storage/'.$user->pp_url) : asset('img/undraw_profile.svg')}}">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="shadow dropdown-menu dropdown-menu-right animated--grow-in"
                                aria-labelledby="userDropdown">
                                <div class="px-4">
                                    <div>{{$user->awardee->fullname ?? $user->admin->fullname}}</div>
                                    <div>{{$user->email}}</div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{route('admin.profile')}}">
                                    Profile
                                </a>
                                <a class="dropdown-item" href="{{route('index')}}">
                                    Global area
                                </a>
                                @if ($user->Awardee)
                                <a class="dropdown-item" href="{{route('complaint.index')}}">
                                    Member area
                                </a>
                                @endif
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    {{-- Content --}}
                    {{ $slot }}

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="bg-white sticky-footer">
                <div class="container my-auto">
                    <div class="my-auto text-center copyright">
                        <span>Copyright &copy; BPI UI {{date('Y')}}</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="rounded scroll-to-top" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="{{route('logout')}}">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    {{-- SWAL --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Custom scripts --}}
    @yield('script')

    <!-- Page level plugins -->
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('js/demo/chart-pie-demo.js') }}"></script>

</body>

</html>
