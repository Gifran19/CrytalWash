<?php
/**
 * CrystalWash - Admin Manage Layanan Controller
 * Handles CRUD operations for services (layanan)
 */

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php?page=home&show_login=true');
    exit;
}

require_once BASE_PATH . '/app/Config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['manage_action'] ?? '';

    try {
        if ($action === 'add') {
            $nama_layanan = trim($_POST['nama_layanan'] ?? '');
            $harga = intval($_POST['harga'] ?? 0);
            $jenis_kendaraan = $_POST['jenis_kendaraan'] ?? 'Car';

            if (!empty($nama_layanan) && $harga > 0) {
                $stmt = $conn->prepare("INSERT INTO layanan (nama_layanan, harga, jenis_kendaraan) VALUES (?, ?, ?)");
                $stmt->execute([$nama_layanan, $harga, $jenis_kendaraan]);
            }
        } 
        elseif ($action === 'edit') {
            $id_layanan = intval($_POST['id_layanan'] ?? 0);
            $nama_layanan = trim($_POST['nama_layanan'] ?? '');
            $harga = intval($_POST['harga'] ?? 0);
            $jenis_kendaraan = $_POST['jenis_kendaraan'] ?? 'Car';

            if ($id_layanan > 0 && !empty($nama_layanan) && $harga > 0) {
                $stmt = $conn->prepare("UPDATE layanan SET nama_layanan = ?, harga = ?, jenis_kendaraan = ? WHERE id_layanan = ?");
                $stmt->execute([$nama_layanan, $harga, $jenis_kendaraan, $id_layanan]);
            }
        }
        elseif ($action === 'delete') {
            $id_layanan = intval($_POST['id_layanan'] ?? 0);

            if ($id_layanan > 0) {
                // Check if used in booking first
                $check = $conn->prepare("SELECT COUNT(*) as count FROM booking WHERE id_layanan = ?");
                $check->execute([$id_layanan]);
                $used = $check->fetch()['count'];

                if ($used == 0) {
                    $stmt = $conn->prepare("DELETE FROM layanan WHERE id_layanan = ?");
                    $stmt->execute([$id_layanan]);
                    $_SESSION['sweetalert_success'] = trans('admin_mod_del_success') !== 'admin_mod_del_success' ? trans('admin_mod_del_success') : 'Data layanan berhasil dihapus.';
                } else {
                    $_SESSION['sweetalert_error'] = trans('admin_mod_del_error_in_use') !== 'admin_mod_del_error_in_use' ? trans('admin_mod_del_error_in_use') : 'Gagal menghapus! Layanan ini sedang digunakan pada riwayat pesanan/transaksi pelanggan.';
                }
            }
        }
    } catch (PDOException $e) {
        // Handle error silently or redirect with error
    }
}

header('Location: index.php?page=admin_dashboard&section=layanan');
exit;
?>
