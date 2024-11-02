<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/dashboard') }}">
                <img src="{{ asset('custom/assetsFoto/logo.png') }}" alt="Logo Klinik" class="logo-fluid"
                    style="height: 50px; width: auto; margin-right: 15px;margin-left: 15px;">

            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Dashboard Menu</h4>
                </li>
                <li class="nav-item {{ Request::path() == 'dashboard' ? 'active' : '' }}">
                    <a href="{{ url('dashboard') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                @if (auth()->user()->role == 'admin')
                    <li class="nav-section">
                        <span class="sidebar-mini-icon">
                            <i class="fa fa-ellipsis-h"></i>
                        </span>
                        <h4 class="text-section">Main Menu</h4>
                    </li>
                    <li class="nav-item {{ Request::path() == 'users' ? 'active' : '' }}">
                        <a href="{{ url('users') }}">
                            <i class="fas fa-users"></i>
                            <p>User</p>
                        </a>
                    </li>
                    <li class="nav-item {{ Request::path() == 'jenis-dokumen' ? 'active' : '' }}">
                        <a href="{{ url('jenis-dokumen') }}">
                            <i class="fas fa-file-alt"></i>
                            <p>Jenis Dokumen</p>
                        </a>
                    </li>
                @endif

                @if (auth()->user()->role == 'user')
                    <li class="nav-item {{ Request::path() == 'anggota-tim' ? 'active' : '' }}">
                        <a href="{{ url('anggota-tim') }}">
                            <i class="fas fa-users-cog"></i>
                            <p>Anggota Tim</p>
                        </a>
                    </li>
                @endif

                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Pengajuan</h4>
                </li>

                @if (auth()->user()->role == 'user')
                    <li class="nav-item {{ Request::path() == 'dokumen' ? 'active' : '' }}">
                        <a href="{{ url('dokumen') }}">
                            <i class="fas fa-file-upload"></i>
                            <p>Pengajuan Dokumen</p>
                        </a>
                    </li>
                @endif

                @if (auth()->user()->role == 'reviewer')
                    <li class="nav-item {{ Request::path() == 'review' ? 'active' : '' }}">
                        <a href="{{ url('review') }}">
                            <i class="fas fa-file-upload"></i>
                            <p>Review Dokumen</p>
                        </a>
                    </li>
                @endif
                <li class="nav-item {{ Request::path() == 'riwayat' ? 'active' : '' }}">
                    <a href="{{ url('riwayat') }}">
                        <i class="fas fa-history"></i>
                        <p>Riwayat</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>

</div>
<!-- End Sidebar -->

<style>
    .sidebar {
        transition: all 0.3s ease;
    }

    .brand-text {
        transition: opacity 0.3s ease, width 0.3s ease;
    }

    .sidebar.collapsed .brand-text {
        opacity: 0;
        width: 0;
        overflow: hidden;
    }

    @media (max-width: 991px) {
        .logo-header .navbar-brand {
            max-width: calc(100% - 60px);
        }

        .brand-text {
            font-size: 18px !important;
        }
    }

    @media (max-width: 575px) {
        .brand-text {
            font-size: 16px !important;
        }
    }

    @media (max-width: 350px) {
        .brand-text {
            display: none;
        }

        .logo-fluid {
            margin: 0;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            display: none !important;
        }

        .logo-header {
            position: relative;
            justify-content: center;
            padding: 10px 0;
        }

        .navbar-brand {
            width: 100%;
            justify-content: center;
        }

        .nav-toggle,
        .topbar-toggler {
            visibility: hidden;
        }
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('admin/assets/js/your-sidebar-script.js') }}"></script>

<script>
    $(document).ready(function() {
        $('.toggle-sidebar, .sidenav-toggler, .topbar-toggler').on('click', function() {
            $('.sidebar').toggleClass('collapsed');
        });
    });
</script>
