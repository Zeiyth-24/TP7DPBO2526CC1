<?php
/**
 * Class Course (Model / Business Logic Entitas 2: Program Kursus IT)
 * Tugas Praktikum DPBO Modul 8 - Universitas Pendidikan Indonesia
 *
 * Kelas ini mengelola data kursus/bootcamp yang terikat ke tabel `tracks` melalui
 * foreign key `track_id`. Seluruh transaksi database menggunakan PDO Prepared Statements.
 */

class Course {
    private ?PDO $db;
    private string $table = "courses";

    /**
     * Dependency Injection: Mengaitkan koneksi database PDO dari router index.php
     */
    public function __construct(?PDO $dbConnection) {
        $this->db = $dbConnection;
    }

    /**
     * Menyimpan data kursus baru
     * Parameter track_id mengikat kursus ke jalur karir tertentu (Foreign Key)
     */
    public function create(int $track_id, string $course_name, string $tech_stack, int $duration_weeks, string $mentor_name, string $portfolio_project): bool {
        if (!$this->db) {
            return false;
        }

        $sql = "INSERT INTO {$this->table} (track_id, course_name, tech_stack, duration_weeks, mentor_name, portfolio_project) 
                VALUES (:track_id, :course_name, :tech_stack, :duration_weeks, :mentor_name, :portfolio_project)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':track_id' => $track_id,
            ':course_name' => $course_name,
            ':tech_stack' => $tech_stack,
            ':duration_weeks' => $duration_weeks,
            ':mentor_name' => $mentor_name,
            ':portfolio_project' => $portfolio_project
        ]);
    }

    /**
     * Mengambil seluruh data kursus beserta informasi jalur karir induknya
     * Menggunakan INNER JOIN ke tabel `tracks` agar nama jalur karir dapat langsung ditampilkan
     */
    public function getAll(): array {
        if (!$this->db) {
            return [];
        }

        $sql = "SELECT c.*, t.track_name, t.difficulty_level 
                FROM {$this->table} c 
                JOIN tracks t ON c.track_id = t.id 
                ORDER BY c.id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Mengambil data satu kursus spesifik berdasarkan id (digunakan untuk form mode edit)
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
     * Memperbarui informasi kursus yang sudah ada berdasarkan primary key (id)
     */
    public function update(int $id, int $track_id, string $course_name, string $tech_stack, int $duration_weeks, string $mentor_name, string $portfolio_project): bool {
        if (!$this->db) {
            return false;
        }

        $sql = "UPDATE {$this->table} 
                SET track_id = :track_id, course_name = :course_name, tech_stack = :tech_stack, 
                    duration_weeks = :duration_weeks, mentor_name = :mentor_name, 
                    portfolio_project = :portfolio_project 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':track_id' => $track_id,
            ':course_name' => $course_name,
            ':tech_stack' => $tech_stack,
            ':duration_weeks' => $duration_weeks,
            ':mentor_name' => $mentor_name,
            ':portfolio_project' => $portfolio_project
        ]);
    }

    /**
     * Menghapus record kursus berdasarkan id
     * Catatan Relasi: Siswa (enrollments) yang mengambil kursus ini akan terhapus otomatis
     * berkat aturan foreign key ON DELETE CASCADE.
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
