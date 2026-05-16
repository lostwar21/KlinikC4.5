<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> | Sistem Klasifikasi C4.5</title>
    <link rel="icon" type="image/png" href="<?php echo base_url('assets/img/logo.png'); ?>">
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom Style -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/dashboard.css'); ?>">
</head>
<body>

<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg bg-white fixed-top top-navbar shadow-sm" style="z-index: 1050;">
    <div class="container-fluid px-0 h-100">
        <!-- Sidebar Brand Area (Left side matching sidebar width) -->
        <div class="d-flex align-items-center justify-content-center h-100" style="width: 260px; background-color: white;">
            <img src="<?php echo base_url('assets/img/logo.png'); ?>" alt="Logo" width="40">
            <h5 class="mb-0 text-dark-teal fw-bold ms-2 mt-1" style="letter-spacing: -1px; line-height: 1;">RSGM<br><span class="small opacity-50">USU</span></h5>
        </div>

        <!-- Main Navbar Content -->
        <div class="d-flex align-items-center flex-grow-1 px-4">
            <!-- Hamburger Button (Mobile) -->
            <button class="btn btn-link text-dark-teal d-lg-none me-3 p-0" id="sidebarToggle" type="button">
                <i class="fas fa-bars fs-3"></i>
            </button>
            
            <!-- Center Title -->
            <h5 class="mb-0 text-dark-teal fw-semibold d-none d-md-block opacity-75">Sistem Klasifikasi Keluhan Pasien</h5>

            <!-- Right Profile -->
            <div class="ms-auto d-flex align-items-center">
                <span class="text-dark fw-semibold d-none d-sm-inline-block"><?php echo $this->session->userdata('nama_lengkap') ?: 'Admin'; ?></span>
                <span class="text-muted mx-3 d-none d-sm-inline-block">|</span>
                <a href="<?php echo site_url('auth/logout'); ?>" class="text-muted text-decoration-none d-flex align-items-center hover-teal">
                    <span class="d-none d-sm-inline me-2 font-weight-bold">Logout</span>
                    <i class="fas fa-sign-out-alt fs-5"></i>
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Sidebar Overlay for Mobile -->
<div class="sidebar-overlay d-lg-none" id="sidebarOverlay"></div>

<!-- Sidebar -->
<div class="sidebar shadow-sm" id="sidebar">
    <div class="nav flex-column px-3 mt-2">
        <a href="<?php echo site_url('dashboard'); ?>" class="nav-link <?php echo ($active == 'dashboard') ? 'active' : ''; ?>">
            <i class="fas fa-home"></i> Dashboard
        </a>

        <!-- Data Pasien (Akses: Admin & Petugas) -->
        <?php if($this->session->userdata('level') != 'pemilik'): ?>
        <a href="<?php echo site_url('pasien'); ?>" class="nav-link <?php echo ($active == 'pasien') ? 'active' : ''; ?>">
            <i class="fas fa-users"></i> Data Pasien
        </a>
        <?php endif; ?>

        <!-- Rekam Medis (Akses: Semua, tapi Petugas hanya bisa Lihat/Pendaftaran) -->
        <a href="<?php echo site_url('rekam_medis'); ?>" class="nav-link <?php echo ($active == 'rekam_medis') ? 'active' : ''; ?>">
            <i class="fas fa-clipboard-list"></i> Rekam Medis
        </a>

        <!-- Menu Khusus Pemilik & Admin (Clinical/Algoritma) -->
        <?php if($this->session->userdata('level') == 'admin' || $this->session->userdata('level') == 'pemilik'): ?>
        <div class="sidebar-title">Algoritma</div>
        
        <a href="<?php echo site_url('dataset'); ?>" class="nav-link <?php echo ($active == 'dataset') ? 'active' : ''; ?>">
            <i class="fas fa-database"></i> Dataset
        </a>
        
        <!-- Klasifikasi Dropdown -->
        <?php $klasifikasi_active = in_array($active, ['proses','pohon','aturan','klasifikasi']); ?>
        <a href="#klasifikasiMenu" class="nav-link d-flex justify-content-between align-items-center <?php echo $klasifikasi_active ? 'active' : ''; ?>" data-bs-toggle="collapse" role="button" aria-expanded="<?php echo $klasifikasi_active ? 'true' : 'false'; ?>">
            <span><i class="fas fa-microchip"></i> Klasifikasi</span>
            <i class="fas fa-chevron-down submenu-arrow"></i>
        </a>
        <div class="collapse <?php echo $klasifikasi_active ? 'show' : ''; ?>" id="klasifikasiMenu">
            <a href="<?php echo site_url('klasifikasi/proses'); ?>" class="nav-link submenu-link <?php echo ($active == 'proses') ? 'active' : ''; ?>">
                Proses C4.5
            </a>
            <a href="<?php echo site_url('klasifikasi/pohon'); ?>" class="nav-link submenu-link <?php echo ($active == 'pohon') ? 'active' : ''; ?>">
                Pohon Hasil
            </a>
            <a href="<?php echo site_url('klasifikasi/aturan'); ?>" class="nav-link submenu-link <?php echo ($active == 'aturan') ? 'active' : ''; ?>">
                Aturan
            </a>
        </div>
        
        <div class="sidebar-title">Sistem</div>
        <a href="<?php echo site_url('uji_model'); ?>" class="nav-link <?php echo ($active == 'uji') ? 'active' : ''; ?>">
            <i class="fas fa-vial"></i> Uji Model
        </a>
        <?php endif; ?>

        <!-- Laporan (Akses: Semua) -->
        <a href="<?php echo site_url('laporan'); ?>" class="nav-link <?php echo ($active == 'laporan') ? 'active' : ''; ?>">
            <i class="fas fa-chart-bar"></i> Laporan
        </a>

        <!-- Pengaturan (Akses: Admin) -->
        <?php if($this->session->userdata('level') == 'admin'): ?>
        <a href="<?php echo site_url('pengaturan'); ?>" class="nav-link <?php echo ($active == 'pengaturan') ? 'active' : ''; ?>">
            <i class="fas fa-cog"></i> Pengaturan
        </a>
        <?php endif; ?>
    </div>

    <div class="sidebar-footer mt-auto px-3 pb-3 pt-4 border-top">
        <div style="font-size: 0.7rem; opacity: 0.6; line-height: 1.4;">
            &copy; <?php echo date('Y'); ?><br>
            <strong>RUMAH SAKIT GIGI DAN MULUT USU</strong><br>
            Universitas Sumatera Utara
        </div>
    </div>
</div>

<div class="main-content">
