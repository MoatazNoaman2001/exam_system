<div>
    <!-- Walk as if you are kissing the Earth with your feet. - Thich Nhat Hanh -->
</div>
{{-- resources/views/layouts/admin.blade.php --}}
@extends('layouts.app')

@section('styles')
<!-- Additional admin-specific CSS -->
<link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
<!-- Custom styles for admin template -->
<link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
@endsection

@section('body-class', 'sidebar-toggled')

@section('content')
<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
            <div class="sidebar-brand-icon">
                <i class="fas fa-lock"></i>
            </div>
            <div class="sidebar-brand-text mx-3">Admin Panel</div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Management
        </div>

        <!-- Users -->
        <li class="nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.users') }}">
                <i class="fas fa-fw fa-users"></i>
                <span>Users</span>
            </a>
        </li>

        <!-- Content -->
        <li class="nav-item {{ request()->routeIs('admin.domains*') || request()->routeIs('admin.slides*') ? 'active' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseContent" aria-expanded="true" aria-controls="collapseContent">
                <i class="fas fa-fw fa-book"></i>
                <span>Content</span>
            </a>
            <div id="collapseContent" class="collapse {{ request()->routeIs('admin.domains*') || request()->routeIs('admin.slides*') ? 'show' : '' }}" aria-labelledby="headingContent" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item {{ request()->routeIs('admin.domains*') ? 'active' : '' }}" href="{{ route('admin.domains') }}">Domains</a>
                    <a class="collapse-item {{ request()->routeIs('admin.slides*') ? 'active' : '' }}" href="{{ route('admin.slides') }}">Slides</a>
                </div>
            </div>
        </li>

        <!-- Assessments -->
        <li class="nav-item {{ request()->routeIs('admin.exams*') || request()->routeIs('admin.quiz-attempts*') || request()->routeIs('admin.test-attempts*') ? 'active' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAssessments" aria-expanded="true" aria-controls="collapseAssessments">
                <i class="fas fa-fw fa-clipboard-check"></i>
                <span>Assessments</span>
            </a>
            <div id="collapseAssessments" class="collapse {{ request()->routeIs('admin.exams*') || request()->routeIs('admin.quiz-attempts*') || request()->routeIs('admin.test-attempts*') ? 'show' : '' }}" aria-labelledby="headingAssessments" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item {{ request()->routeIs('admin.exams*') ? 'active' : '' }}" href="{{ route('admin.exams') }}">Exams</a>
                    <a class="collapse-item {{ request()->routeIs('admin.quiz-attempts*') ? 'active' : '' }}" href="{{ route('admin.quiz-attempts') }}">Quiz Attempts</a>
                    <a class="collapse-item {{ request()->routeIs('admin.test-attempts*') ? 'active' : '' }}" href="{{ route('admin.test-attempts') }}">Test Attempts</a>
                </div>
            </div>
        </li>

        <!-- Notifications -->
        <li class="nav-item {{ request()->routeIs('admin.notifications*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.notifications') }}">
                <i class="fas fa-fw fa-bell"></i>
                <span>Notifications</span>
            </a>
        </li>

        <!-- Analytics -->
        <li class="nav-item {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.analytics') }}">
                <i class="fas fa-fw fa-chart-line"></i>
                <span>Analytics</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">

                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->username }}</span>
                            <img class="img-profile rounded-circle" src="https://source.unsplash.com/QAB-WJcbgJk/60x60">
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                Profile
                            </a>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                Settings
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>

                </ul>

            </nav>
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">

                {{-- @include('partials.alerts') --}}

                @yield('admin-content')

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Your Website {{ date('Y') }}</span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>
<!-- Additional admin-specific JS -->
<!-- Bootstrap core JavaScript-->
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Core plugin JavaScript-->
<script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

<!-- Custom scripts for all pages-->
<script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

<!-- Page level plugins -->
<script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>

@endsection