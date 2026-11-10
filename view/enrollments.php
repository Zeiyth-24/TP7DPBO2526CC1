<?php
/**
 * View Entitas 3: Talenta Siswa & Kesiapan Karir (Enrollments)
 * Tugas Praktikum DPBO Modul 8 - Universitas Pendidikan Indonesia
 *
 * Komponen ini menangani:
 * 1. Pengecekan parameter mode edit (?action=edit&id=...) untuk memuat data pendaftaran siswa lama.
 * 2. Form input/edit terhubung dengan dropdown relasi Foreign Key (`course_id` -> tabel `courses`).
 * 3. Tampilan data berbasis Clean Modern Table dengan container beradius 16px.
 * 4. Pemetaan visual status kesiapan kerja (Learning, Building Portfolio, Interview Ready, Hired)
 *    menggunakan match expression PHP 8.x untuk menetapkan kelas CSS badge yang sesuai.
 */

$edit_data = null;
// Ambil record pendaftaran siswa dari basis data jika request berada pada mode edit
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id']) && !empty($enrollmentObj)) {
    $edit_data = $enrollmentObj->getById(intval($_GET['id']));
}
?>

<!-- 1. Header Halaman & Tombol Toggle Form (+ Daftarkan / Tutup) -->
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h1 class="page-title">Talenta &amp; Kesiapan Karir (Enrollments)</h1>
        <p class="text-muted mb-0">Pemantauan progres belajar siswa, repositori portfolio, dan kesiapan interview kerja.</p>
    </div>
    <div>
        <!-- Tombol toggle form input talenta baru -->
        <button class="btn-pill btn-pill-primary" type="button" data-bs-toggle="collapse" data-bs-target="#formCollapseEnrollment" aria-expanded="<?= $edit_data ? 'true' : 'false'; ?>">
            <?= $edit_data ? 'Tutup Form' : '+ Daftarkan Talenta'; ?>
        </button>
    </div>
</div>

<!-- 2. Form CRUD Input / Edit (Bootstrap Collapse Container) -->
<div class="collapse <?= $edit_data ? 'show' : ''; ?> mb-4" id="formCollapseEnrollment">
    <div class="card-custom p-4">
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
            <h4 class="mb-0" style="color: var(--brand-red);">
                <?= $edit_data ? 'Edit Data Talenta' : 'Form Pendaftaran Siswa Baru'; ?>
            </h4>
            <span class="badge-pill-blush"><?= $edit_data ? 'Mode Perbarui' : 'Entitas 3'; ?></span>
        </div>

        <!-- Form mengirim data ke router index.php via metode HTTP POST -->
        <form action="index.php?page=enrollments" method="POST">
            <!-- Hidden inputs untuk tipe aksi ('create'/'update') dan primary key ID saat update -->
            <input type="hidden" name="action_type" value="<?= $edit_data ? 'update' : 'create'; ?>">
            <?php if ($edit_data): ?>
                <input type="hidden" name="id" value="<?= $edit_data['id']; ?>">
            <?php endif; ?>

            <div class="row">
                <!-- Dropdown Relasi Foreign Key: Mengaitkan talenta ke program kursus tertentu -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Pilih Program Kursus (Relasi Foreign Key)</label>
                    <select name="course_id" class="form-select" required>
                        <option value="">-- Pilih Program Kursus --</option>
                        <?php foreach (($allCourses ?? []) as $crs): ?>
                            <option value="<?= $crs['id']; ?>" <?= ($edit_data && $edit_data['course_id'] == $crs['id']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($crs['course_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Lengkap Siswa</label>
                    <input type="text" name="student_name" class="form-control" placeholder="Contoh: Farhan Pratama" required value="<?= $edit_data ? htmlspecialchars($edit_data['student_name']) : ''; ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Alamat Email</label>
                    <input type="email" name="email" class="form-control" placeholder="nama@email.com" required value="<?= $edit_data ? htmlspecialchars($edit_data['email']) : ''; ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Target Role Karir</label>
                    <input type="text" name="target_role" class="form-control" placeholder="Contoh: Junior Backend Developer" required value="<?= $edit_data ? htmlspecialchars($edit_data['target_role']) : ''; ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Status Kesiapan Kerja</label>
                    <select name="readiness_status" class="form-select" required>
                        <option value="Learning" <?= ($edit_data && $edit_data['readiness_status'] === 'Learning') ? 'selected' : ''; ?>>Learning</option>
                        <option value="Building Portfolio" <?= ($edit_data && $edit_data['readiness_status'] === 'Building Portfolio') ? 'selected' : ''; ?>>Building Portfolio</option>
                        <option value="Interview Ready" <?= ($edit_data && $edit_data['readiness_status'] === 'Interview Ready') ? 'selected' : ''; ?>>Interview Ready</option>
                        <option value="Hired" <?= ($edit_data && $edit_data['readiness_status'] === 'Hired') ? 'selected' : ''; ?>>Hired</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Tautan Repositori GitHub</label>
                <input type="url" name="github_url" class="form-control" placeholder="https://github.com/username/project" value="<?= $edit_data ? htmlspecialchars($edit_data['github_url']) : ''; ?>">
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn-pill btn-pill-primary">
                    <?= $edit_data ? 'Simpan Perubahan' : 'Daftarkan Siswa'; ?>
                </button>
                <?php if ($edit_data): ?>
                    <a href="index.php?page=enrollments" class="btn-pill btn-pill-dark">Batal</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- 3. Tabel Data Talenta (Modern Clean Table) -->
<?php if (empty($enrollmentList)): ?>
    <div class="card-custom p-5 text-center text-muted">
        <p class="mb-2">Belum ada talenta yang terdaftar.</p>
        <small>Silakan klik tombol <strong>+ Daftarkan Talenta</strong> untuk mendaftarkan siswa baru.</small>
    </div>
<?php else: ?>
    <div class="table-container-custom">
        <div class="table-responsive">
            <table class="table table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 60px;">No</th>
                        <th>Nama Siswa &amp; Kontak</th>
                        <th>Kursus IT &amp; Jalur</th>
                        <th>Target Role</th>
                        <th class="text-center" style="width: 170px;">Status Kesiapan</th>
                        <th class="text-center" style="width: 130px;">Portfolio</th>
                        <th class="text-center" style="width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($enrollmentList as $item): ?>
                        <tr>
                            <td class="text-center font-monospace text-muted"><?= $no++; ?></td>
                            <td>
                                <strong style="color: var(--text-main);"><?= htmlspecialchars($item['student_name']); ?></strong>
                                <div class="text-muted" style="font-size: 0.8rem;"><?= htmlspecialchars($item['email']); ?></div>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: var(--text-main);"><?= htmlspecialchars($item['course_name']); ?></div>
                                <div class="text-muted" style="font-size: 0.8rem;">Jalur: <?= htmlspecialchars($item['track_name']); ?></div>
                            </td>
                            <td>
                                <span style="font-weight: 700; color: var(--brand-red);"><?= htmlspecialchars($item['target_role']); ?></span>
                            </td>
                            <td class="text-center">
                                <?php
                                // Pemetaan status kesiapan ke kelas styling badge (PHP 8 match expression)
                                $badgeClass = match ($item['readiness_status']) {
                                    'Learning' => 'badge-pill-blush',
                                    'Building Portfolio' => 'badge-pill-rose',
                                    'Interview Ready' => 'badge-pill-red',
                                    'Hired' => 'badge-pill-dark',
                                    default => 'badge-pill-dark'
                                };
                                ?>
                                <span class="<?= $badgeClass; ?>">
                                    <?= htmlspecialchars($item['readiness_status']); ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if (!empty($item['github_url'])): ?>
                                    <a href="<?= htmlspecialchars($item['github_url']); ?>" target="_blank" class="btn-pill btn-pill-outline btn-pill-sm">
                                        GitHub
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted" style="font-size: 0.8rem;">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <!-- Tombol Aksi Edit & Hapus Record -->
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="index.php?page=enrollments&action=edit&id=<?= $item['id']; ?>" class="btn-pill btn-pill-dark btn-pill-sm">
                                        Edit
                                    </a>
                                    <a href="index.php?page=enrollments&action=delete&id=<?= $item['id']; ?>" class="btn-pill btn-pill-outline btn-pill-sm" onclick="return confirm('Hapus data pendaftaran siswa ini?')">
                                        Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>
