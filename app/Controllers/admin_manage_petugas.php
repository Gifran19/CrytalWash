<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php?page=login');
    exit;
}

require_once BASE_PATH . '/app/Config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['manage_action'] ?? '';

    if ($action === 'add') {
        $nama = $_POST['nama'] ?? '';
        $no_hp = $_POST['no_hp'] ?? '';
        $status = $_POST['status'] ?? 'aktif';

        if ($nama) {
            try {
                $stmt = $conn->prepare("INSERT INTO petugas (nama, no_hp, status) VALUES (:nama, :no_hp, :status)");
                $stmt->execute(['nama' => $nama, 'no_hp' => $no_hp, 'status' => $status]);
                $_SESSION['sweetalert_success'] = 'Data petugas berhasil ditambahkan!';
            } catch (PDOException $e) {
                $_SESSION['sweetalert_error'] = 'Gagal menambahkan petugas.';
            }
        }
    } elseif ($action === 'edit') {
        $id_petugas = $_POST['id_petugas'] ?? '';
        $nama = $_POST['nama'] ?? '';
        $no_hp = $_POST['no_hp'] ?? '';
        $status = $_POST['status'] ?? 'aktif';

        if ($id_petugas && $nama) {
            try {
                $stmt = $conn->prepare("UPDATE petugas SET nama = :nama, no_hp = :no_hp, status = :status WHERE id_petugas = :id");
                $stmt->execute(['nama' => $nama, 'no_hp' => $no_hp, 'status' => $status, 'id' => $id_petugas]);
                $_SESSION['sweetalert_success'] = 'Data petugas berhasil diperbarui!';
            } catch (PDOException $e) {
                $_SESSION['sweetalert_error'] = 'Gagal memperbarui petugas.';
            }
        }
    } elseif ($action === 'delete') {
        $id_petugas = $_POST['id_petugas'] ?? '';

        if ($id_petugas) {
            try {
                $stmt = $conn->prepare("DELETE FROM petugas WHERE id_petugas = :id");
                $stmt->execute(['id' => $id_petugas]);
                $_SESSION['sweetalert_success'] = 'Data petugas berhasil dihapus!';
            } catch (PDOException $e) {
                $_SESSION['sweetalert_error'] = 'Gagal menghapus petugas. Mungkin sedang digunakan di transaksi.';
            }
        }
    }
}

header('Location: index.php?page=admin_dashboard&section=petugas');
exit;
