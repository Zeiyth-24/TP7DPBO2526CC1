<?php
/**
 * Class Enrollment (Model / Business Logic Entitas 3: Talenta Siswa & Kesiapan Karir)
 * Tugas Praktikum DPBO Modul 8 - Universitas Pendidikan Indonesia
 *
 * Kelas ini menangani pencatatan siswa yang terdaftar pada suatu kursus,
 * memonitor status kesiapan karir, dan menampung tautan portofolio/GitHub.
 * Menggunakan relasi berjenjang (enrollments -> courses -> tracks).
 */

class Enrollment {
    private ?PDO $db;
    private string $table = "enrollments";

    /**
     * Dependency Injection: Menghubungkan instance PDO
     */
    public function __construct(?PDO $dbConnection) {
        $this->db = $dbConnection;
    }

    /**
     * Menambahkan data pendaftaran siswa baru ke dalam kursus
     */
    public function create(int $course_id, string $student_name, string $email, string $target_role, string $readiness_status, ?string $github_url): bool {
        if (!$this->db) {
            return false;
        }

        $sql = "INSERT INTO {$this->table} (course_id, student_name, email, target_role, readiness_status, github_url) 
                VALUES (:course_id, :student_name, :email, :target_role, :readiness_status, :github_url)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':course_id' => $course_id,
            ':student_name' => $student_name,
            ':email' => $email,
            ':target_role' => $target_role,
            ':readiness_status' => $readiness_status,
            ':github_url' => $github_url
        ]);
    }

    /**
     * Mengambil data seluruh siswa beserta nama kursus dan jalur karir terkait
     * Menerapkan 2 level JOIN (enrollments JOIN courses JOIN tracks) untuk konsistensi relasi foreign key
     */
    public function getAll(): array {
        if (!$this->db) {
            return [];
        }

        $sql = "SELECT e.*, c.course_name, t.track_name 
                FROM {$this->table} e 
                JOIN courses c ON e.course_id = c.id 
                JOIN tracks t ON c.track_id = t.id 
                ORDER BY e.id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Mengambil data spesifik pendaftaran siswa berdasarkan id
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
     * Memperbarui informasi talenta siswa dan status kesiapan kerjanya
     */
    public function update(int $id, int $course_id, string $student_name, string $email, string $target_role, string $readiness_status, ?string $github_url): bool {
        if (!$this->db) {
            return false;
        }

        $sql = "UPDATE {$this->table} 
                SET course_id = :course_id, student_name = :student_name, email = :email, 
                    target_role = :target_role, readiness_status = :readiness_status, 
                    github_url = :github_url 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':course_id' => $course_id,
            ':student_name' => $student_name,
            ':email' => $email,
            ':target_role' => $target_role,
            ':readiness_status' => $readiness_status,
            ':github_url' => $github_url
        ]);
    }

    /**
     * Menghapus record pendaftaran siswa berdasarkan id
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
