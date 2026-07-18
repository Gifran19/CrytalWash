<?php

require_once BASE_PATH . '/app/Config/database.php';
require_once BASE_PATH . '/app/Helpers/functions.php';

/**
 * CrystalWash - Submit Review
 * Menyimpan feedback/ulasan ke database dan mengarahkan kembali ke Beranda
 */

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    verify_csrf();
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
                // INSERT Feedback ke database
                $stmt = $conn->prepare("INSERT INTO feedback (rating, komentar, id_booking)
                    VALUES (:rating, :komentar, :id_booking)");
                $stmt->execute([
                    'rating'     => $rating,
                    'komentar'   => $komentar,
                    'id_booking' => $id_booking,
                ]);
            }

            // Status booking ditangani oleh Admin Dashboard saja.
            // Customer submit ulasan tidak otomatis mengubah status.

            $conn->commit();
        } catch (PDOException $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            // Jika sudah ada feedback (duplicate key), abaikan error dan lanjut
            if (strpos($e->getMessage(), 'unique') === false) {
                error_log("Gagal menyimpan ulasan: " . $e->getMessage());
                header("Location: index.php?page=home&review=error");
                exit();
            }
        }
    }

    // Setelah kirim ulasan, kembali ke Beranda (BUKAN invoice)
    header("Location: index.php?page=home&review=sent");
    exit();
}
?>
