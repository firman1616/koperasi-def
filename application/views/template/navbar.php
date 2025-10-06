<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= site_url('Dashboard') ?>">
                <div class="sidebar-brand-icon rotate-n-15">
                    <img src="<?= base_url('assets/image/ksua.png') ?>" alt="Logo" style="width: 40px; height: 40px;">
                </div>
                <div class="sidebar-brand-text mx-3">SYATHIBI </div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="<?= site_url('Dashboard') ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
            <?php if ($akses == 1 || $akses == 3) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('Transaksi') ?>">
                        <i class="fas fa-fw fa-shopping-cart"></i>
                        <span>POS Penjualan</span></a>
                </li>
            <?php } ?>

            <!--
            <?php if ($akses == 1 || $akses == 2) { ?>
            <li class="nav-item">
                <a class="nav-link" href="<?= site_url('#') ?>">
                    <i class="fas fa-fw fa-arrow-left"></i>
                    <span>Pengeluaran</span></a>
            </li>
            <?php } ?> -->

            <?php if ($akses == 1 || $akses == 3 || $akses == 2) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('Peserta/iuran') ?>">
                        <i class="fas fa-fw fa-users"></i>
                        <span>Iuran Anggota</span></a>
                </li>
            
            <?php } ?>


            <?php if ($akses == 1 || $akses == 2 || $akses == 3) { ?>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#laporan"
                        aria-expanded="true" aria-controls="laporan">
                        <i class="fas fa-fw fa-book"></i>
                        <span>Laporan</span>
                    </a>
                    <div id="laporan" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <?php if ($akses == 1 || $akses == 3 || $akses == 2) { ?>
                                <a class="collapse-item" href="<?= site_url('Laporan') ?>">Laporan Penjualan</a>
                            <?php } ?>
                            <?php if ($akses == 1 || $akses == 3) { ?>
                                <a class="collapse-item" href="<?= site_url('Laporan/lap_barang') ?>">Laporan Barang</a>
                            <?php } ?>
                            <?php if ($akses == 1 || $akses == 3) { ?>
                                <a class="collapse-item" href="<?= site_url('Laporan/lap_iuran') ?>">Laporan Iuran</a>
                            <?php } ?>
                            <?php if ($akses == 1 || $akses == 3) { ?>
                                <a class="collapse-item" href="<?= site_url('Transaksi/trans_tempo') ?>">Laporan Tempo</a>
                            <?php } ?>
                        </div>
                    </div>
                </li>
                
                <?php } ?>

            <?php if ($akses == 1 || $akses == 2) { ?>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#trans_lain"
                        aria-expanded="true" aria-controls="trans_lain">
                        <i class="fas fa-fw fa-wallet"></i>
                        <span>Keuangan Koperasi</span>
                    </a>
                    <div id="trans_lain" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item" href="<?= site_url('PemasukanLain') ?>">Pemasukan Keuangan</a>
                            <a class="collapse-item" href="<?= site_url('PengeluaranLain') ?>">Pengeluaran Keuangan</a>
                            <a class="collapse-item" href="<?= site_url('Laporan/lap_keuangan') ?>">Laporan Keuangan</a>
                        </div>
                    </div>
                </li>
                
            <?php } ?>

            <?php if ($akses == 1 || $akses == 2) { ?>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                        aria-expanded="true" aria-controls="collapseTwo">
                        <i class="fas fa-fw fa-cog"></i>
                        <span>Master</span>
                    </a>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item" href="<?= site_url('Peserta') ?>">Anggota</a>
                            <a class="collapse-item" href="<?= site_url('Barang') ?>">Barang</a>
                            <a class="collapse-item" href="<?= site_url('UOM') ?>">UoM (Satuan)</a>
                            <!-- <a class="collapse-item" href="<?= site_url('Kategori') ?>">Kategori</a> -->
                            <a class="collapse-item" href="<?= site_url('User') ?>">User</a>
                        </div>
                    </div>
                </li>
            <?php } ?>


            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>

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
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $name ?></span>
                                <img class="img-profile rounded-circle"
                                    src="<?= base_url() ?>assets/template/img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?= site_url('Login/logout') ?>" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>