<?php
/**
 * Konfigurasi & Inisialisasi Koneksi Database (PDO)
 * Tugas Praktikum DPBO Modul 8 - Universitas Pendidikan Indonesia
 *
 * Kelas Database ini bertanggung jawab mengelola koneksi ke server MySQL
 * menggunakan interface PDO (PHP Data Objects). Pendekatan OOP digunakan
 * agar kredensial dan objek koneksi terenkapsulasi dengan rapi.
 */

class Database {
    // Gunakan 127.0.0.1 (bukan 'localhost') untuk mencegah isu Unix Domain Socket di macOS / Linux
    private string $host = "127.0.0.1";
    private int $port = 3306;
    private string $db_name = "db_it_career";
    private string $username = "root";
    private string $password = "";

    // Properti koneksi dan penampung pesan error jika koneksi gagal
    public ?PDO $conn = null;
    public ?string $errorMessage = null;

    /**
     * Membuka koneksi database dengan opsi konfigurasi keamanan PDO
     * @return PDO|null Mengembalikan instance PDO aktif atau null bila gagal
     */
    public function getConnection(): ?PDO {
        $this->conn = null;
        $this->errorMessage = null;

        try {
            // Data Source Name (DSN) dengan format UTF-8 modern (utf8mb4)
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name};charset=utf8mb4";
            
            // Konfigurasi PDO:
            // 1. ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION agar error query memicu exception
            // 2. ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC agar hasil query berupa array asosiatif
            // 3. ATTR_EMULATE_PREPARES => false agar prepared statements dieksekusi murni di level DBMS
            $this->conn = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            // Tangkap exception dan simpan pesannya agar halaman tetap dapat menampilkan peringatan tanpa fatal error
            $this->errorMessage = $e->getMessage();
        }

        return $this->conn;
    }
}
