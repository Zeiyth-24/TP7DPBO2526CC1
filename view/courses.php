<?php
/**
 * View Entitas 2: Program Kursus IT (Courses)
 * Tugas Praktikum DPBO Modul 8 - Universitas Pendidikan Indonesia
 *
 * Komponen ini menangani:
 * 1. Pengecekan parameter mode edit (?action=edit&id=...) untuk memuat data kursus yang akan diperbarui.
 * 2. Form input/edit yang terintegrasi dengan dropdown relasi Foreign Key (`track_id` -> tabel `tracks`).
 * 3. Tampilan data berbasis Course Card Grid dengan banner gradasi Navy (#253C6D) dan aksen geometris.
 * 4. Penanganan aksi penghapusan dengan konfirmasi keamanan relasi cascading.
 */

$edit_data = null;
// Ambil record kursus spesifik dari basis data jika request membawa parameter edit dan ID valid
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id']) && !empty($courseObj)) {
    $edit_data = $courseObj->getById(intval($_GET['id']));
}
?>

<!-- 1. Header Halaman & Tombol Toggle Form (+ Tambah / Tutup) -->
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h1 class="page-title">Program Kursus &amp; Bootcamp IT</h1>
        <p class="text-muted mb-0">Pelatihan teknis intensif berbasis proyek nyata dengan bimbingan mentor industri.</p>
    </div>
    <div>
        <!-- Tombol pembuka form input (terbuka otomatis saat mode edit) -->
        <button class="btn-pill btn-pill-primary" type="button" data-bs-toggle="collapse" data-bs-target="#formCollapseCourse" aria-expanded="<?= $edit_data ? 'true' : 'false'; ?>">
            <?= $edit_data ? 'Tutup Form' : '+ Tambah Kursus'; ?>
        </button>
    </div>
</div>

<!-- 2. Form CRUD Input / Edit (Bootstrap Collapse Container) -->
<div class="collapse <?= $edit_data ? 'show' : ''; ?> mb-4" id="formCollapseCourse">
    <div class="card-custom p-4">
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
            <h4 class="mb-0" style="color: var(--brand-red);">
                <?= $edit_data ? 'Edit Data Kursus' : 'Form Publikasi Kursus Baru'; ?>
            </h4>
            <span class="badge-pill-blush"><?= $edit_data ? 'Mode Perbarui' : 'Entitas 2'; ?></span>
        </div>

        <!-- Form mengirim data ke router index.php via metode HTTP POST -->
        <form action="index.php?page=courses" method="POST">
            <!-- Hidden inputs untuk tipe aksi dan ID record saat update -->
            <input type="hidden" name="action_type" value="<?= $edit_data ? 'update' : 'create'; ?>">
            <?php if ($edit_data): ?>
                <input type="hidden" name="id" value="<?= $edit_data['id']; ?>">
            <?php endif; ?>

            <div class="row">
                <!-- Dropdown Relasi Foreign Key: Memilih entitas induk (tracks) -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Pilih Jalur Karir (Relasi Foreign Key)</label>
                    <select name="track_id" class="form-select" required>
                        <option value="">-- Pilih Jalur Karir --</option>
                        <?php foreach (($allTracks ?? []) as $tr): ?>
                            <option value="<?= $tr['id']; ?>" <?= ($edit_data && $edit_data['track_id'] == $tr['id']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($tr['track_name']); ?> (<?= htmlspecialchars($tr['difficulty_level']); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Kursus / Pelatihan</label>
                    <input type="text" name="course_name" class="form-control" placeholder="Contoh: REST API dengan Golang & PostgreSQL" required value="<?= $edit_data ? htmlspecialchars($edit_data['course_name']) : ''; ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tech Stack Utama</label>
                    <input type="text" name="tech_stack" class="form-control" placeholder="Contoh: Go, PostgreSQL, Gin, Docker" required value="<?= $edit_data ? htmlspecialchars($edit_data['tech_stack']) : ''; ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Durasi (Minggu)</label>
                    <input type="number" name="duration_weeks" class="form-control" min="1" max="52" placeholder="8" required value="<?= $edit_data ? htmlspecialchars($edit_data['duration_weeks']) : ''; ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Mentor Pembimbing</label>
                    <input type="text" name="mentor_name" class="form-control" placeholder="Contoh: Rian Hidayat" required value="<?= $edit_data ? htmlspecialchars($edit_data['mentor_name']) : ''; ?>">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Target Proyek Portfolio Akhir</label>
                <input type="text" name="portfolio_project" class="form-control" placeholder="Contoh: API Marketplace dengan Autentikasi JWT" required value="<?= $edit_data ? htmlspecialchars($edit_data['portfolio_project']) : ''; ?>">
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn-pill btn-pill-primary">
                    <?= $edit_data ? 'Simpan Perubahan' : 'Terbitkan Kursus'; ?>
                </button>
                <?php if ($edit_data): ?>
                    <a href="index.php?page=courses" class="btn-pill btn-pill-dark">Batal</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- 3. Grid Visual Data Kursus (Thumbnail Banner Gradasi Navy) -->
<?php if (empty($courseList)): ?>
    <div class="card-custom p-5 text-center text-muted">
        <p class="mb-2">Belum ada program kursus yang dipublikasikan.</p>
        <small>Silakan klik tombol <strong>+ Tambah Kursus</strong> untuk menambahkan materi pelatihan baru.</small>
    </div>
<?php else: ?>
    <div class="course-grid">
        <?php foreach ($courseList as $item): ?>
            <div class="course-card">
                <!-- Thumbnail Banner Atas: Gradasi Navy #253C6D dengan Ornamen Geometris -->
                <div class="course-thumbnail">
                    <span class="thumbnail-tag">
                        <?= htmlspecialchars($item['track_name']); ?> &bull; <?= htmlspecialchars($item['difficulty_level']); ?>
                    </span>
                    <h4 class="thumbnail-title"><?= htmlspecialchars($item['course_name']); ?></h4>
                </div>

                <!-- Konten Body Kartu: Informasi Teknis & Detail Proyek -->
                <div class="course-body">
                    <div>
                        <div class="mb-3">
                            <span class="badge-pill-rose"><?= htmlspecialchars($item['tech_stack']); ?></span>
                        </div>

                        <div class="course-meta">
                            <strong>Mentor:</strong> <?= htmlspecialchars($item['mentor_name']); ?>
                        </div>
                        <div class="course-meta">
                            <strong>Durasi:</strong> <?= htmlspecialchars($item['duration_weeks']); ?> Minggu Intensif
                        </div>
                        <div class="course-meta">
                            <strong>Proyek Akhir:</strong> <?= htmlspecialchars($item['portfolio_project']); ?>
                        </div>
                    </div>

                    <!-- Tombol Aksi: Edit & Hapus (dengan konfirmasi penghapusan data relasi) -->
                    <div class="d-flex gap-2 mt-3 pt-3 border-top">
                        <a href="index.php?page=courses&action=edit&id=<?= $item['id']; ?>" class="btn-pill btn-pill-dark btn-pill-sm">
                            Edit
                        </a>
                        <a href="index.php?page=courses&action=delete&id=<?= $item['id']; ?>" class="btn-pill btn-pill-outline btn-pill-sm" onclick="return confirm('Hapus kursus ini? Seluruh data pendaftar pada kursus ini akan ikut terhapus via CASCADE.')">
                            Hapus
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
