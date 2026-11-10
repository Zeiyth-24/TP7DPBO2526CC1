<?php
/**
 * Class Track (Model / Business Logic Entitas 1: Jalur Karir IT)
 * Tugas Praktikum DPBO Modul 8 - Universitas Pendidikan Indonesia
 *
 * Kelas ini mengimplementasikan konsep OOP untuk mengenkapsulasi seluruh operasi
 * CRUD pada tabel `tracks`. Seluruh query dieksekusi dengan PDO Prepared Statements.
 */

class Track {
    private ?PDO $db;
    private string $table = "tracks";

    /**
     * Dependency Injection: Menerima instance PDO aktif dari luar
     */
    public function __construct(?PDO $dbConnection) {
        $this->db = $dbConnection;
    }

    /**
     * Menyimpan data jalur karir baru ke tabel `tracks`
     * Menggunakan named parameter bindings (:param) untuk mencegah celah SQL Injection
     */
    public function create(string $track_name, string $career_prospect, string $estimated_salary, string $difficulty_level, string $description): bool {
        if (!$this->db) {
            return false;
        }

        $sql = "INSERT INTO {$this->table} (track_name, career_prospect, estimated_salary, difficulty_level, description) 
                VALUES (:track_name, :career_prospect, :estimated_salary, :difficulty_level, :description)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':track_name' => $track_name,
            ':career_prospect' => $career_prospect,
            ':estimated_salary' => $estimated_salary,
            ':difficulty_level' => $difficulty_level,
            ':description' => $description
        ]);
    }

    /**
     * Mengambil seluruh data jalur karir secara berurutan berdasarkan id
     */
    public function getAll(): array {
        if (!$this->db) {
            return [];
        }

        $sql = "SELECT * FROM {$this->table} ORDER BY id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Mengambil detail satu record jalur karir berdasarkan primary key (id)
     */
    public function getById(int $id): array|false {
        if (!$this->db) {
            return false;
        }

        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Memperbarui record jalur karir yang sudah ada berdasarkan primary key (id)
     */
    public function update(int $id, string $track_name, string $career_prospect, string $estimated_salary, string $difficulty_level, string $description): bool {
        if (!$this->db) {
            return false;
        }

        $sql = "UPDATE {$this->table} 
                SET track_name = :track_name, career_prospect = :career_prospect, 
                    estimated_salary = :estimated_salary, difficulty_level = :difficulty_level, 
                    description = :description 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':track_name' => $track_name,
            ':career_prospect' => $career_prospect,
            ':estimated_salary' => $estimated_salary,
            ':difficulty_level' => $difficulty_level,
            ':description' => $description
        ]);
    }

    /**
     * Menghapus record jalur karir berdasarkan id
     * Catatan Relasi: Kursus dan pendaftaran terkait akan ikut terhapus secara otomatis
     * melalui aturan database ON DELETE CASCADE pada foreign key.
     */
    public function delete(int $id): bool {
        if (!$this->db) {
            return false;
        }

        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
