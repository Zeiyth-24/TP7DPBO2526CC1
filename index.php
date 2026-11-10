<?php
/**
 * Entry Point & Front Controller (Router HTTP Request - Response)
 * Tugas Praktikum DPBO Modul 8 - Universitas Pendidikan Indonesia
 *
 * File ini mengontrol alur kerja utama aplikasi:
 * 1. Menginisialisasi koneksi basis data PDO (config/db.php).
 * 2. Menginstansiasi objek-objek class bisnis logika (Track, Course, Enrollment).
 * 3. Menangkap HTTP Request (POST untuk Create/Update, GET untuk Delete & Navigasi).
 * 4. Menerapkan pola Post-Redirect-Get (PRG) untuk mencegah submit ganda saat refresh.
 * 5. Merender tampilan antarmuka secara modular (header -> view entitas -> footer).
 */

require_once 'config/db.php';
require_once 'class/Track.php';
require_once 'class/Course.php';
require_once 'class/Enrollment.php';

// Inisialisasi koneksi database PDO
$database = new Database();
$db = $database->getConnection();
$dbError = $database->errorMessage;

// Instansiasi objek class dengan Dependency Injection instance PDO
$trackObj = new Track($db);
$courseObj = new Course($db);
$enrollmentObj = new Enrollment($db);

// Menentukan halaman aktif dari parameter query string (?page=...), default ke 'tracks'
$page = $_GET['page'] ?? 'tracks';

// =========================================================================
// 1. PENANGANAN HTTP POST (Operasi Create & Update)
// Pola Post-Redirect-Get (PRG) diterapkan di sini agar form tidak ter-submit ulang saat F5
// =========================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $db !== null) {
    $action_type = $_POST['action_type'] ?? '';

    // Aksi untuk Entitas 1: Jalur Karir (Tracks)
    if ($page === 'tracks') {
        $track_name = trim($_POST['track_name'] ?? '');
        $career_prospect = trim($_POST['career_prospect'] ?? '');
        $estimated_salary = trim($_POST['estimated_salary'] ?? '');
        $difficulty_level = trim($_POST['difficulty_level'] ?? 'Beginner');
        $description = trim($_POST['description'] ?? '');

        if ($action_type === 'create') {
            $trackObj->create($track_name, $career_prospect, $estimated_salary, $difficulty_level, $description);
            header("Location: index.php?page=tracks&msg=success");
            exit;
        } elseif ($action_type === 'update') {
            $id = intval($_POST['id'] ?? 0);
            $trackObj->update($id, $track_name, $career_prospect, $estimated_salary, $difficulty_level, $description);
            header("Location: index.php?page=tracks&msg=success");
            exit;
        }

    // Aksi untuk Entitas 2: Program Kursus (Courses)
    } elseif ($page === 'courses') {
        $track_id = intval($_POST['track_id'] ?? 0);
        $course_name = trim($_POST['course_name'] ?? '');
        $tech_stack = trim($_POST['tech_stack'] ?? '');
        $duration_weeks = intval($_POST['duration_weeks'] ?? 0);
        $mentor_name = trim($_POST['mentor_name'] ?? '');
        $portfolio_project = trim($_POST['portfolio_project'] ?? '');

        if ($action_type === 'create') {
            $courseObj->create($track_id, $course_name, $tech_stack, $duration_weeks, $mentor_name, $portfolio_project);
            header("Location: index.php?page=courses&msg=success");
            exit;
        } elseif ($action_type === 'update') {
            $id = intval($_POST['id'] ?? 0);
            $courseObj->update($id, $track_id, $course_name, $tech_stack, $duration_weeks, $mentor_name, $portfolio_project);
            header("Location: index.php?page=courses&msg=success");
            exit;
        }

    // Aksi untuk Entitas 3: Talenta Siswa (Enrollments)
    } elseif ($page === 'enrollments') {
        $course_id = intval($_POST['course_id'] ?? 0);
        $student_name = trim($_POST['student_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $target_role = trim($_POST['target_role'] ?? '');
        $readiness_status = trim($_POST['readiness_status'] ?? 'Learning');
        $github_url = trim($_POST['github_url'] ?? '');

        if ($action_type === 'create') {
            $enrollmentObj->create($course_id, $student_name, $email, $target_role, $readiness_status, $github_url);
            header("Location: index.php?page=enrollments&msg=success");
            exit;
        } elseif ($action_type === 'update') {
            $id = intval($_POST['id'] ?? 0);
            $enrollmentObj->update($id, $course_id, $student_name, $email, $target_role, $readiness_status, $github_url);
            header("Location: index.php?page=enrollments&msg=success");
            exit;
        }
    }
}

// =========================================================================
// 2. PENANGANAN HTTP GET (Operasi Delete)
// =========================================================================
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id']) && $db !== null) {
    $id = intval($_GET['id']);
    if ($page === 'tracks') {
        $trackObj->delete($id);
        header("Location: index.php?page=tracks&msg=deleted");
        exit;
    } elseif ($page === 'courses') {
        $courseObj->delete($id);
        header("Location: index.php?page=courses&msg=deleted");
        exit;
    } elseif ($page === 'enrollments') {
        $enrollmentObj->delete($id);
        header("Location: index.php?page=enrollments&msg=deleted");
        exit;
    }
}

// =========================================================================
// 3. RENDERING VIEW MODULAR (Siklus HTTP Response)
// =========================================================================
require_once 'view/header.php';

// Memuat view sesuai halaman yang dipilih dan menyiapkan data yang dibutuhkan view
if ($page === 'tracks') {
    $trackList = $trackObj->getAll();
    require_once 'view/tracks.php';
} elseif ($page === 'courses') {
    $courseList = $courseObj->getAll();
    $allTracks = $trackObj->getAll(); // Dibutuhkan untuk dropdown relasi track_id
    require_once 'view/courses.php';
} elseif ($page === 'enrollments') {
    $enrollmentList = $enrollmentObj->getAll();
    $allCourses = $courseObj->getAll(); // Dibutuhkan untuk dropdown relasi course_id
    require_once 'view/enrollments.php';
} else {
    echo '<div class="alert alert-warning">Halaman tidak ditemukan.</div>';
}

require_once 'view/footer.php';
