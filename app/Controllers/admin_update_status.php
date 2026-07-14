<?php
// app/Controllers/admin_update_status.php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php?page=home&show_login=true');
    exit;
}

require_once BASE_PATH . '/app/Config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_booking = $_POST['id_booking'] ?? null;
    $new_status = $_POST['new_status'] ?? null;

    $valid_statuses = ['in_progress', 'completed'];

    if ($id_booking && $new_status && in_array($new_status, $valid_statuses)) {
        try {
            // Update booking status
            $stmt = $conn->prepare("UPDATE booking SET status = :status WHERE id_booking = :id_booking");
            $stmt->execute([
                'status' => $new_status,
                'id_booking' => $id_booking
            ]);

            // Also update antrian status accordingly
            if ($new_status === 'in_progress') {
                $stmt = $conn->prepare("UPDATE antrian SET status = 'proses' WHERE id_booking = :id_booking");
                $stmt->execute(['id_booking' => $id_booking]);


                $_SESSION['sweetalert_success'] = 'Proses booking berhasil dimulai!';
            } elseif ($new_status === 'completed') {
                $stmt = $conn->prepare("UPDATE antrian SET status = 'selesai' WHERE id_booking = :id_booking");
                $stmt->execute(['id_booking' => $id_booking]);
                
                // Automatically mark payment as paid upon booking completion
                $stmt = $conn->prepare("UPDATE pembayaran SET status = 'paid' WHERE id_booking = :id_booking");
                $stmt->execute(['id_booking' => $id_booking]);

                $_SESSION['sweetalert_success'] = 'Booking berhasil diselesaikan!';
            }
        } catch (PDOException $e) {
            $_SESSION['sweetalert_error'] = 'Terjadi kesalahan pada sistem.';
        }
    }
}

header('Location: index.php?page=admin_dashboard&section=beranda');
exit;
