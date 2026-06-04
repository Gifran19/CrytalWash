<?php

require_once BASE_PATH . '/app/Config/database.php';
require_once BASE_PATH . '/app/Helpers/functions.php';

/**
 * CrystalWash - Submit Review
 * Menyimpan feedback/ulasan ke database dan menyelesaikan proses booking
 */

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_booking = $_SESSION['order']['id_booking'] ?? null;
    $rating     = intval($_POST['rating'] ?? 0);
    $komentar   = cleanInput($_POST['komentar'] ?? '');

    $is_skip = isset($_POST['skip']);

    // Validasi rating hanya jika tidak diskip
    if (!$is_skip && ($rating < 1 || $rating > 5)) {
        $rating = 5; // Default jika tidak valid
    }

    if ($id_booking) {
        try {
            $conn->beginTransaction();

            if (!$is_skip) {
                // 1. INSERT Feedback
                $stmt = $conn->prepare("INSERT INTO feedback (rating, komentar, id_booking)
                    VALUES (:rating, :komentar, :id_booking)");
                $stmt->execute([
                    'rating'     => $rating,
                    'komentar'   => $komentar,
                    'id_booking' => $id_booking,
                ]);
            }

            // Status is now handled exclusively by the Admin Dashboard.
            // Customers reviewing does not automatically mark it completed.

            $conn->commit();
        } catch (PDOException $e) {
            $conn->rollBack();
            // Jika sudah ada feedback (duplicate), abaikan error dan lanjut
            if (strpos($e->getMessage(), 'unique') === false) {
                die("Gagal menyimpan ulasan: " . $e->getMessage());
            }
        }
    }

    // Arahkan ke halaman selesai
    header("Location: index.php?page=finish");
    exit();
}
?>
