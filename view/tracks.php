<?php
/**
 * View Entitas 1: Jalur Karir IT (Career Tracks)
 * Tugas Praktikum DPBO Modul 8 - Universitas Pendidikan Indonesia
 *
 * Komponen ini menangani:
 * 1. Pengecekan parameter mode edit (?action=edit&id=...) untuk memuat data lama ke dalam form.
 * 2. Form input CRUD interaktif yang terbungkus dalam komponen Bootstrap Collapse.
 * 3. Sanitasi output menggunakan htmlspecialchars() untuk menangkal serangan XSS.
 * 4. Tampilan data berbasis Card Grid dengan pola visual Alternating Cards:
 *    - Kartu A: Latar putih beraksen sudut geometris Navy (#253C6D).
 *    - Kartu B: Latar solid Navy (#253C6D) beraksen tombol putih sebagai kartu sorotan.
 */

$edit_data = null;
// Jika request membawa aksi edit dan ID valid, ambil data dari database melalui objek Track
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id']) && !empty($trackObj)) {
    $edit_data = $trackObj->getById(intval($_GET['id']));
}
?>

<!-- 1. Header Halaman & Tombol Toggle Form (+ Tambah / Tutup) -->
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h1 class="page-title">Jalur Karir IT (Career Tracks)</h1>
        <p class="text-muted mb-0">Spesialisasi profesi teknologi dengan kurikulum terstruktur dan standar industri.</p>
    </div>
    <div>
        <!-- Tombol toggle collapse; otomatis terbuka jika sistem sedang dalam mode edit -->
        <button class="btn-pill btn-pill-primary" type="button" data-bs-toggle="collapse" data-bs-target="#formCollapseTrack" aria-expanded="<?= $edit_data ? 'true' : 'false'; ?>">
            <?= $edit_data ? 'Tutup Form' : '+ Tambah Jalur Karir'; ?>
        </button>
    </div>
</div>

<!-- 2. Form CRUD Input / Edit (Bootstrap Collapse Container) -->
<div class="collapse <?= $edit_data ? 'show' : ''; ?> mb-4" id="formCollapseTrack">
    <div class="card-custom p-4">
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
            <h4 class="mb-0" style="color: var(--brand-red);">
                <?= $edit_data ? 'Edit Data Jalur Karir' : 'Form Buka Jalur Karir Baru'; ?>
            </h4>
            <span class="badge-pill-blush"><?= $edit_data ? 'Mode Perbarui' : 'Entitas 1'; ?></span>
        </div>

        <!-- Form mengirim data ke router index.php via metode HTTP POST -->
        <form action="index.php?page=tracks" method="POST">
            <!-- Hidden input menentukan operasi: 'create' data baru atau 'update' data lama -->
            <input type="hidden" name="action_type" value="<?= $edit_data ? 'update' : 'create'; ?>">
            <?php if ($edit_data): ?>
                <input type="hidden" name="id" value="<?= $edit_data['id']; ?>">
            <?php endif; ?>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Jalur Karir</label>
                    <input type="text" name="track_name" class="form-control" placeholder="Contoh: Backend Engineering" required value="<?= $edit_data ? htmlspecialchars($edit_data['track_name']) : ''; ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Target Profesi / Prospek Karir</label>
                    <input type="text" name="career_prospect" class="form-control" placeholder="Contoh: Junior Backend Dev, API Engineer" required value="<?= $edit_data ? htmlspecialchars($edit_data['career_prospect']) : ''; ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Estimasi Standar Gaji</label>
                    <input type="text" name="estimated_salary" class="form-control" placeholder="Contoh: Rp 8.000.000 - Rp 15.000.000" required value="<?= $edit_data ? htmlspecialchars($edit_data['estimated_salary']) : ''; ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tingkat Kesulitan</label>
                    <select name="difficulty_level" class="form-select" required>
                        <option value="Beginner" <?= ($edit_data && $edit_data['difficulty_level'] === 'Beginner') ? 'selected' : ''; ?>>Beginner</option>
                        <option value="Intermediate" <?= ($edit_data && $edit_data['difficulty_level'] === 'Intermediate') ? 'selected' : ''; ?>>Intermediate</option>
                        <option value="Advanced" <?= ($edit_data && $edit_data['difficulty_level'] === 'Advanced') ? 'selected' : ''; ?>>Advanced</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi Kurikulum &amp; Kompetensi</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Jelaskan fokus materi dan target kemampuan yang dipelajari..."><?= $edit_data ? htmlspecialchars($edit_data['description']) : ''; ?></textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn-pill btn-pill-primary">
                    <?= $edit_data ? 'Simpan Perubahan' : 'Terbitkan Jalur Karir'; ?>
                </button>
                <?php if ($edit_data): ?>
                    <a href="index.php?page=tracks" class="btn-pill btn-pill-dark">Batal</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- 3. Grid Visual Data (Pola Alternating Cards: Putih Aksen Navy vs Solid Navy) -->
<?php if (empty($trackList)): ?>
    <div class="card-custom p-5 text-center text-muted">
        <p class="mb-2">Belum ada data jalur karir yang terdaftar.</p>
        <small>Klik tombol <strong>+ Tambah Jalur Karir</strong> di atas untuk membuat entitas baru.</small>
    </div>
<?php else: ?>
    <div class="track-grid">
        <?php foreach ($trackList as $index => $item): ?>
            <?php 
            // Penentuan layout: Baris bergantian (Genap = Kartu A Putih, Ganjil = Kartu B Solid Navy)
            $isAlternate = ($index % 2 === 1);
            ?>

            <?php if (!$isAlternate): ?>
                <!-- Kartu Tipe A: White Background + Sudut Geometris Navy #253C6D -->
                <div class="track-card-a">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge-pill-rose"><?= htmlspecialchars($item['difficulty_level']); ?></span>
                        </div>
                        <h3 class="track-title"><?= htmlspecialchars($item['track_name']); ?></h3>
                        <p class="track-desc"><?= htmlspecialchars($item['description']); ?></p>
                        
                        <div class="pt-2 border-top">
                            <div class="track-info-item">
                                <strong>Prospek:</strong> <?= htmlspecialchars($item['career_prospect']); ?>
                            </div>
                            <div class="track-info-item">
                                <strong>Gaji:</strong> <?= htmlspecialchars($item['estimated_salary']); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi Edit & Hapus (Menggunakan Javascript Confirm) -->
                    <div class="d-flex gap-2 mt-4 pt-3 border-top">
                        <a href="index.php?page=tracks&action=edit&id=<?= $item['id']; ?>" class="btn-pill btn-pill-dark btn-pill-sm">
                            Edit
                        </a>
                        <a href="index.php?page=tracks&action=delete&id=<?= $item['id']; ?>" class="btn-pill btn-pill-outline btn-pill-sm" onclick="return confirm('Hapus jalur karir ini? Kursus terkait akan ikut terhapus via CASCADE.')">
                            Hapus
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <!-- Kartu Tipe B: Solid Navy (#253C6D) untuk Sorotan Utama -->
                <div class="track-card-b">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge-pill-blush"><?= htmlspecialchars($item['difficulty_level']); ?></span>
                        </div>
                        <h3 class="track-title"><?= htmlspecialchars($item['track_name']); ?></h3>
                        <p class="track-desc"><?= htmlspecialchars($item['description']); ?></p>
                        
                        <div class="pt-2 border-top" style="border-color: rgba(255,255,255,0.2) !important;">
                            <div class="track-info-item">
                                <strong>Prospek:</strong> <?= htmlspecialchars($item['career_prospect']); ?>
                            </div>
                            <div class="track-info-item">
                                <strong>Gaji:</strong> <?= htmlspecialchars($item['estimated_salary']); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi Pill Putih & Transparan pada Kartu Solid Navy -->
                    <div class="d-flex gap-2 mt-4 pt-3 border-top" style="border-color: rgba(255,255,255,0.2) !important;">
                        <a href="index.php?page=tracks&action=edit&id=<?= $item['id']; ?>" class="btn-pill btn-pill-white btn-pill-sm">
                            Edit
                        </a>
                        <a href="index.php?page=tracks&action=delete&id=<?= $item['id']; ?>" class="btn-pill btn-pill-sm" style="background: rgba(255,255,255,0.2) !important; color: #FFFFFF !important;" onclick="return confirm('Hapus jalur karir ini? Kursus terkait akan ikut terhapus via CASCADE.')">
                            Hapus
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
