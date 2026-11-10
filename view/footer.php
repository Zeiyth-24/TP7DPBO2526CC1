<?php
/**
 * View Footer Global
 * Tugas Praktikum DPBO Modul 8 - Universitas Pendidikan Indonesia
 *
 * Komponen ini memuat:
 * 1. Tag penutup untuk elemen <main class="container">.
 * 2. Informasi footer akademik (Nama Mata Kuliah, Program Studi, & Universitas).
 * 3. Pemanggilan Bootstrap 5 JavaScript Bundle via CDN untuk interaktivitas komponen (collapse, dropdown, alerts).
 */
?>
    </main>

    <!-- Footer Akademik -->
    <footer class="text-center">
        <div class="container">
            <p class="mb-1"><strong>Praktikum DPBO Modul 8</strong> - Program Studi Ilmu Komputer, Universitas Pendidikan Indonesia</p>
            <small class="text-muted">Aplikasi Pengelolaan Karir &amp; Kursus IT berbasis PHP Native OOP dan PDO Prepared Statements.</small>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle CDN (Diperlukan untuk fungsionalitas Collapse Box & Alert Dismiss) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
