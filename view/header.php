<?php
/**
 * View Header & Navigasi Global
 * Tugas Praktikum DPBO Modul 8 - Universitas Pendidikan Indonesia
 *
 * Komponen ini memuat:
 * 1. Tag <head> HTML5, font 'Plus Jakarta Sans', Bootstrap 5 CDN, dan stylesheet custom.
 * 2. Banner identitas praktikan (Nama, NIM, Kelas) untuk verifikasi tugas.
 * 3. Navbar terintegrasi logo resmi UPI FPMIPA dan penanda rute halaman aktif.
 * 4. Pengecekan status koneksi basis data & pesan alert notifikasi CRUD.
 */
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITCareer - Platform Persiapan Karir IT | UPI FPMIPA</title>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Style & Deep Navy Theme -->
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- 1. Header Identitas Tugas Praktikum (Wajib untuk Verifikasi Asistensi) -->
    <div class="student-banner">
        <div class="container d-flex justify-content-between align-items-center flex-wrap">
            <span>Praktikum DPBO Modul 8 | Nama: Zufar Ahmad Maulidy | NIM: 2400285 | Kelas: C1</span>
            <span class="opacity-75">PHP Native OOP &amp; PDO</span>
        </div>
    </div>

    <!-- 2. Navbar dengan Logo UPI FPMIPA & Brand ITCareer -->
    <nav class="navbar navbar-expand-lg navbar-itcareer sticky-top">
        <div class="container d-flex align-items-center justify-content-between">
            <!-- Sisi Kiri: Logo UPI FPMIPA + Divider + Teks ITCareer -->
            <a class="brand-wrapper" href="index.php?page=tracks">
                <img src="assets/img/logo-upi-fpmipa.png" alt="Logo UPI FPMIPA" class="logo-upi-fpmipa">
                <span class="brand-divider"></span>
                <span class="brand-text">
                    <span class="brand-it">IT</span><span class="brand-career">Career</span>
                </span>
            </a>

            <!-- Toggle Menu Mobile -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigasi Menu Sisi Kanan -->
            <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
                <!-- Menu navigasi diposisikan di sisi kanan (ms-auto) dengan penanda link aktif -->
                <div class="nav-links-end ms-auto my-2 my-lg-0">
                    <a class="nav-link-itcareer <?= (!isset($_GET['page']) || $_GET['page'] === 'tracks') ? 'active' : '' ?>" href="index.php?page=tracks">
                        Jalur Karir
                    </a>
                    <a class="nav-link-itcareer <?= (isset($_GET['page']) && $_GET['page'] === 'courses') ? 'active' : '' ?>" href="index.php?page=courses">
                        Kursus IT
                    </a>
                    <a class="nav-link-itcareer <?= (isset($_GET['page']) && $_GET['page'] === 'enrollments') ? 'active' : '' ?>" href="index.php?page=enrollments">
                        Talenta &amp; Kesiapan
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- 3. Konten Utama Aplikasi -->
    <main class="container my-4 flex-grow-1">
        <!-- Notifikasi error jika koneksi basis data gagal diinisialisasi -->
        <?php if (!empty($dbError)): ?>
            <div class="alert alert-danger card-custom p-3 mb-4">
                <strong>Koneksi Database Gagal:</strong> <?= htmlspecialchars($dbError); ?>
                <div class="small mt-1">Pastikan MySQL server berjalan di <code>127.0.0.1:3306</code> dan database <code>db_it_career</code> telah di-import.</div>
            </div>
        <?php endif; ?>

        <!-- Notifikasi umpan balik operasi CRUD (Success / Deleted) -->
        <?php if (isset($_GET['msg'])): ?>
            <?php if ($_GET['msg'] === 'success'): ?>
                <div class="alert alert-success alert-dismissible fade show card-custom p-3 mb-4" role="alert">
                    <strong>Berhasil!</strong> Data telah tersimpan dengan aman ke database.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php elseif ($_GET['msg'] === 'deleted'): ?>
                <div class="alert alert-info alert-dismissible fade show card-custom p-3 mb-4" role="alert">
                    <strong>Informasi:</strong> Data telah dihapus dari database.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
        <?php endif; ?>