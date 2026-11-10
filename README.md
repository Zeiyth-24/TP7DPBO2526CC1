# Platform Kursus & Persiapan Karir IT (TechCareer Academy)
> **Tugas Praktikum Modul 8 - Desain & Pemrograman Berorientasi Objek (DPBO)**  
> **Format Repository:** `TP7DPBO2526CC1`  
> **Program Studi:** Ilmu Komputer, Universitas Pendidikan Indonesia

---

## Janji
Saya Zufar Ahmad Maulidy dengan NIM 2400285 mengerjakan Tugas Praktikum 7 (Modul 8) dalam mata kuliah Desain dan Pemrograman Berorientasi Objek untuk keberkahan-Nya maka saya tidak melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin.

---

## 1. Tema & Deskripsi Aplikasi
Aplikasi ini bertema **Platform Kursus & Persiapan Karir IT (TechCareer Academy)**. Website ini dirancang khusus untuk menjembatani mahasiswa dan calon talenta digital agar siap kerja di industri teknologi. 

Aplikasi ini mengelola 3 entitas terintegrasi:
1. **Jalur Karir IT (`tracks`)**: Menentukan domain profesi (seperti DevOps, Backend, Frontend, Data Science, AI, Cyber Security), estimasi gaji industri, tingkat kesulitan, dan deskripsi kurikulum.
2. **Program Kursus / Bootcamp (`courses`)**: Program pelatihan intensif berbasis proyek portfolio industri. Memiliki relasi ke tabel `tracks`.
3. **Talenta & Kesiapan Karir (`enrollments`)**: Data pembelajar IT, target role pekerjaan, repositori GitHub, serta status kesiapan kerja (*Learning*, *Building Portfolio*, *Interview Ready*, hingga *Hired*). Memiliki relasi ke tabel `courses`.

---

## 2. Struktur Database & Relasi (3 Entitas)
Database yang digunakan adalah `db_it_career` dengan relasi berjenjang (Foreign Key ON DELETE CASCADE):

```
+-----------------------------------+
|              tracks               |
+-----------------------------------+
| PK | id                           |
|    | track_name                   |
|    | career_prospect              |
|    | estimated_salary             |
|    | difficulty_level             |
|    | description                  |
+-----------------------------------+
                 | 1
                 |
                 | N
+-----------------------------------+
|              courses              |
+-----------------------------------+
| PK | id                           |
| FK | track_id  -------------------+ (Menghubungkan kursus ke jalur karir)
|    | course_name                  |
|    | tech_stack                   |
|    | duration_weeks               |
|    | mentor_name                  |
|    | portfolio_project            |
+-----------------------------------+
                 | 1
                 |
                 | N
+-----------------------------------+
|            enrollments            |
+-----------------------------------+
| PK | id                           |
| FK | course_id -------------------+ (Menghubungkan talenta ke kursus yang diambil)
|    | student_name                 |
|    | email                        |
|    | target_role                  |
|    | readiness_status             |
|    | github_url                   |
+-----------------------------------+
```

### Keamanan Database:
- **100% Prepared Statements**: Seluruh proses transaksi SQL (INSERT, SELECT, UPDATE, DELETE) pada class `Track`, `Course`, dan `Enrollment` menggunakan metode `$stmt = $this->db->prepare(...)` dan `$stmt->execute(...)` menggunakan driver PDO.
- Tidak ada query mentah langsung (`db->query()` mentah ditiadakan sesuai ketentuan modul).

---

## 3. Struktur Direktori Proyek
Struktur proyek mengikuti standar arsitektur OOP Modul 8:

```text
TP7DPBO2526C../
├── class/
│   ├── Track.php          # Class OOP entitas Jalur Karir (CRUD Prepared Statements)
│   ├── Course.php         # Class OOP entitas Kursus (CRUD + JOIN Track)
│   └── Enrollment.php     # Class OOP entitas Siswa/Talenta (CRUD + JOIN Course & Track)
├── config/
│   └── db.php             # Koneksi PDO (127.0.0.1:3306) & penanganan error
├── database/
│   └── db_it_career.sql   # Skema tabel (CASCADE) & data awal realistis
├── font/                  # Berkas tipografi lokal (Futura, Helvetica, Open Sans)
├── view/
│   ├── header.php         # Banner identitas praktikan, Bootstrap 5 & navbar
│   ├── footer.php         # Footer informasi & script Bootstrap bundle
│   ├── tracks.php         # Tabel utama & form collapse Jalur Karir
│   ├── courses.php        # Tabel utama & form collapse Kursus IT
│   └── enrollments.php    # Tabel utama & form collapse Talenta & Kesiapan
├── index.php              # Router & Controller utama
├── style.css              # Tipografi lokal & aturan visual (kelipatan 4 & 8)
└── README.md              # Dokumentasi lengkap proyek
```

---

## 4. Sistem Desain Antarmuka (Palet Navy #253C6D, Royal Blue #263997, Sky Blue #4CA2D9 & White #FFFFFF)
Aplikasi ini mengadopsi sistem desain antarmuka modern yang terinspirasi dari **Course-Net** dan keselarasan identitas resmi **UPI FPMIPA**, berfokus pada estetika visual, tata letak proporsional, geometri tegas, dan palet warna profesional:
- **Token Warna & Tema**:
  - `--brand-navy: #253C6D;` (Navy blue utama untuk tombol aksi, aksen aktif, kartu sorotan, dan border)
  - `--brand-navy-dark: #1D3057;` (Navy pekat untuk thumbnail banner kursus dan state hover)
  - `--brand-blue: #263997;` (Warna pendukung: Royal Blue FPMIPA untuk badge aktif & aksen sudut)
  - `--brand-sky: #4CA2D9;` (Warna pendukung: Cerulean Sky Blue FPMIPA untuk badge soft, divider, & border)
  - `--bg-page: #FFFFFF;` (Warna latar belakang halaman: Putih Bersih)
  - `--btn-dark: #0F172A;` (Charcoal gelap untuk tombol aksi sekunder)
  - `--text-main: #0F172A;` & `--text-muted: #546478;`
  - `--border-subtle: #E2E8F0;` & `--bg-card: #FFFFFF;`
- **Rebranding Navbar & Integrasi Logo UPI FPMIPA**:
  - Menyertakan logo resmi **UPI FPMIPA** (`assets/img/logo-upi-fpmipa.png`) dengan tinggi 40px proporsional.
  - Garis pembatas vertikal tipis beraksen sky blue (`border-right: 1.5px solid #4CA2D9; height: 30px; margin: 0 14px;`).
  - Teks brand **ITCareer**: "IT" berwarna hitam pekat (`#0F172A`) dan "Career" berwarna navy blue (`#253C6D`).
- **Tipografi**:
  - Menggunakan font modern **Plus Jakarta Sans** (Google Fonts) dengan bobot tebal (700-800) dan line-height rapat (1.2).
- **Komponen Geometris & Micro-styling**:
  - **Pill Badges**: `border-radius: 9999px`, teks navy solid (`#253C6D`), royal blue (`#263997`), cerulean sky (`#4CA2D9`), atau dark (`#0F172A`).
  - **Tombol Full-Pill**: `border-radius: 9999px` tanpa sudut tajam dengan efek transisi hover halus.
  - **Form Controls**: `border-radius: 12px`, border 1.5px dengan ring fokus navy halus (`rgba(37, 60, 109, 0.15)`).
  - **Card & Container**: `border-radius: 20px` dengan soft shadow dan border beraksen netral halus (`#E2E8F0`).
- **Arsitektur Layout Kartu**:
  - **Entitas 1 (Jalur Karir)**: Pola *Alternating Cards* (Kartu Tipe A: Putih dengan sudut geometris Navy `#253C6D`; Kartu Tipe B: Solid Navy `#253C6D` dengan sudut aksen sky blue `#4CA2D9` dan tombol pill putih).
  - **Entitas 2 (Kursus IT)**: Pola *Thumbnail Banner* gradasi navy (`#253C6D` ke `#1D3057`) dengan ornamen geometris royal blue (`#263997`) dan sky blue (`#4CA2D9`).
  - **Entitas 3 (Talenta Siswa)**: *Clean Modern Table* dengan header berlatar bersih (`#F8FAFC`), border sky blue (`#4CA2D9`), dan status pill badge.
  - **Integrasi Form CRUD**: Menggunakan container collapse di atas grid/tabel yang dibuka melalui tombol pill navy `+ Tambah Data` di pojok kanan header.

---

## 5. Alur Kerja Kode (Flow Aplikasi)
Sesuai dengan HTTP Request-Response cycle pada Modul 8:
1. **Client Request**: Browser mengakses `index.php?page=courses`.
2. **Router Controller**: `index.php` memanggil koneksi `Database::getConnection()` dari `config/db.php`, kemudian menginisialisasi objek class terkait (`$courseObj = new Course($db)`).
3. **OOP Data Execution**:
   - Untuk operasi **Read**: Objek class memanggil method `getAll()` yang mengeksekusi PDO Prepared Statement dan mengembalikan array data hasil fetch.
   - Untuk operasi **Create/Update/Delete**: Data dari `$_POST` atau `$_GET` diproses oleh method class terkait dengan binding parameter yang aman dari SQL Injection.
4. **View Response**: `index.php` menyertakan `view/header.php`, view halaman spesifik (`view/courses.php`), dan `view/footer.php` untuk menampilkan UI kepada pengguna.

---

## 6. Cara Instalasi & Menjalankan Proyek

### Menggunakan Laragon / macOS Localhost (PHP Built-in Server):
1. Pastikan service **MySQL** sudah berjalan di host `127.0.0.1` port `3306`.
2. Import file database `database/db_it_career.sql` via phpMyAdmin atau terminal:
   ```bash
   mysql -u root -p < database/db_it_career.sql
   ```
3. Jalankan server lokal dari dalam folder proyek:
   ```bash
   php -S 127.0.0.1:8000
   ```
4. Buka browser dan akses: `http://127.0.0.1:8000`

---

## 7. Panduan 5 Langkah Pesan Git Commit Bertahap
Agar riwayat repository GitHub tampak natural dan menunjukkan proses pengerjaan bertahap:

1. **Commit 1 (Struktur Database & Koneksi)**
   ```bash
   git add config/db.php database/db_it_career.sql
   git commit -m "feat(database): inisialisasi skema tabel db_it_career dan konfigurasi koneksi PDO"
   ```

2. **Commit 2 (Model OOP & Prepared Statements)**
   ```bash
   git add class/Track.php class/Course.php class/Enrollment.php
   git commit -m "feat(class): implementasi class model Track, Course, dan Enrollment dengan PDO prepared statements"
   ```

3. **Commit 3 (Controller & HTTP Request Routing)**
   ```bash
   git add index.php
   git commit -m "feat(controller): setup router index.php untuk penanganan request POST dan GET action"
   ```

4. **Commit 4 (Antarmuka GUI & Desain Branding UPI FPMIPA)**
   ```bash
   git add style.css assets/ view/header.php view/footer.php view/tracks.php view/courses.php view/enrollments.php
   git commit -m "feat(view): implementasi tema Merah Bold & Hitam Elegan, logo UPI FPMIPA, dan layout kartu modern"
   ```

5. **Commit 5 (Dokumentasi & Finalisasi)**
   ```bash
   git add README.md
   git commit -m "docs: lengkapi dokumentasi proyek, alur sistem, dan panduan pengujian Modul 8"
   ```

---

## 8. Panduan & Dokumentasi Video Pengujian CRUD (Screen Record)
Video demonstrasi telah direkam dalam kualitas **Full HD 1080p (60 FPS)** dan disertakan di dalam repository:
- **File Video Rekaman:** [`Recording TP7.mp4`](Recording%20TP7.mp4)

### Alur Skenario Pengujian pada Video:
1. **Tabel 1 (Jalur Karir / Tracks)**:
   - Klik tombol **+ Tambah Jalur Karir** untuk membuka form input.
   - Tambah track baru (*Mobile Flutter Engineering*).
   - Tampilkan data yang berhasil masuk ke card.
   - Klik tombol **Edit** pada track tersebut (ubah rentang gaji).
   - Klik tombol **Hapus** pada track percobaan dengan konfirmasi dialog.
2. **Tabel 2 (Kursus / Courses)**:
   - Tambah kursus baru dengan memilih dropdown Track yang sudah ada.
   - Tampilkan bahwa nama track berhasil ter-JOIN di tabel kursus.
   - Edit detail durasi pelatihan.
   - Hapus kursus percobaan (konfirmasi CASCADE).
3. **Tabel 3 (Talenta / Enrollments)**:
   - Daftarkan siswa baru dengan memilih kursus dari dropdown.
   - Ubah status kesiapan kerja (dari *Building Portfolio* menjadi *Interview Ready*).
   - Tampilkan perubahan pill badge status dan link portfolio GitHub aktif.
   - Hapus data pendaftar percobaan.
4. **Pemeriksaan Database MySQL (phpMyAdmin)**:
   - Menampilkan struktur database `db_it_career` (3 tabel).
   - Menampilkan struktur kolom tabel `courses`.
   - Menampilkan Relation View Foreign Key `fk_courses_track` dan `fk_enrollments_course` dengan aturan `ON DELETE CASCADE`.
   - Menampilkan visual ERD schema pada menu **phpMyAdmin Designer**.
