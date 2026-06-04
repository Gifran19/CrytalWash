<?php
// app/Controllers/admin_pay_transaction.php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php?page=login');
    exit;
}

require_once BASE_PATH . '/app/Config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_booking = $_POST['id_booking'] ?? null;
    if ($id_booking) {
        try {
            // Mark payment as paid
            $stmt = $conn->prepare("UPDATE pembayaran SET status = 'paid' WHERE id_booking = :id_booking");
            $stmt->execute(['id_booking' => $id_booking]);

            // Also mark booking as completed
            $stmt = $conn->prepare("UPDATE booking SET status = 'completed' WHERE id_booking = :id_booking");
            $stmt->execute(['id_booking' => $id_booking]);

            // Also mark antrian as selesai
            $stmt = $conn->prepare("UPDATE antrian SET status = 'selesai' WHERE id_booking = :id_booking");
            $stmt->execute(['id_booking' => $id_booking]);
        } catch (PDOException $e) {
            // handle error if needed
        }
    }
}

header('Location: index.php?page=admin_dashboard&section=beranda');
exit;
