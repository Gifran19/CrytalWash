<?php
// app/Controllers/admin_pay_transaction.php
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php?page=home&show_login=true');
    exit;
}

require_once BASE_PATH . '/app/Config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $id_booking = $_POST['id_booking'] ?? null;
    $total_tagihan = isset($_POST['total_tagihan']) ? (int)$_POST['total_tagihan'] : 0;
    $uang_dibayar = isset($_POST['uang_dibayarkan']) ? (int)$_POST['uang_dibayarkan'] : 0;

    if ($id_booking) {
        if ($uang_dibayar < $total_tagihan) {
            $_SESSION['sweetalert_error'] = 'Transaksi Gagal: Nominal uang yang dibayarkan kurang dari total tagihan!';
            header('Location: index.php?page=admin_dashboard&section=beranda');
            exit;
        }

        try {
            $conn->beginTransaction();

            // Mark payment as paid
            $stmt = $conn->prepare("UPDATE pembayaran SET status = 'paid' WHERE id_booking = :id_booking");
            $stmt->execute(['id_booking' => $id_booking]);

            // Also mark booking as completed
            $stmt = $conn->prepare("UPDATE booking SET status = 'completed' WHERE id_booking = :id_booking");
            $stmt->execute(['id_booking' => $id_booking]);

            // Also mark antrian as selesai
            $stmt = $conn->prepare("UPDATE antrian SET status = 'selesai' WHERE id_booking = :id_booking");
            $stmt->execute(['id_booking' => $id_booking]);

            $conn->commit();
        } catch (PDOException $e) {
            $conn->rollBack();
            // handle error if needed
        }
    }
}

header('Location: index.php?page=admin_dashboard&section=beranda');
exit;
