<?php

require_once BASE_PATH . '/app/Config/database.php';
require_once BASE_PATH . '/app/Helpers/functions.php';

/**
 * CrystalWash - Payment Gateway
 * Memproses pembayaran dan membuat semua record terkait:
 * booking, pembayaran, antrian, transaksi, invoice
 */

$is_post = $_SERVER['REQUEST_METHOD'] == 'POST';
$is_confirm_qris = isset($_GET['confirm_qris']) && $_GET['confirm_qris'] == 1;

if ($is_post || $is_confirm_qris) {
    if ($is_post) {
        $method = cleanInput($_POST['payment_method'] ?? '');
    } else {
        $method = 'QRIS';
    }
    
    $order      = $_SESSION['order'] ?? [];
    $total      = $order['total_price'] ?? 0;

    // Validasi data session
    if (empty($order['id_pelanggan']) || empty($order['id_kendaraan']) || empty($order['id_layanan'])) {
        header("Location: index.php?page=checkout&step=1&error=incomplete_data");
        exit();
    }

    // Validasi input metode
    if (!in_array($method, ['Cash', 'QRIS'])) {
        header("Location: index.php?page=checkout&step=4&error=invalid_method");
        exit();
    }

    if ($method === 'QRIS' && !$is_confirm_qris) {
        $_SESSION['order']['payment_method'] = 'QRIS';
        header("Location: index.php?page=qris_checkout");
        exit();
    }

    // Tentukan status pembayaran berdasarkan metode
    $payment_status = 'unpaid';
    $transaction_prefix = ($method === 'QRIS') ? 'QRS' : 'CSH';

    // Jika booking sudah dibuat sebelumnya (user kembali dari halaman QRIS ke pilihan pembayaran),
    // perbarui metode pembayaran saja daripada menduplikat pesanan baru.
    $id_booking = $_SESSION['order']['id_booking'] ?? null;
    if ($id_booking) {
        try {
            $conn->beginTransaction();
            // Update Pembayaran
            $stmt = $conn->prepare("UPDATE pembayaran SET metode = :metode WHERE id_booking = :id_booking");
            $stmt->execute(['metode' => $method, 'id_booking' => $id_booking]);
            $conn->commit();

            $_SESSION['order']['payment_method']  = $method;
            $_SESSION['order']['transaction_id']  = $transaction_prefix . '-' . strtoupper(uniqid());

            if ($method === 'QRIS') {
                header("Location: index.php?page=qris_checkout&id_booking=" . $id_booking);
            } else {
                header("Location: index.php?page=finish");
            }
            exit();
        } catch (PDOException $e) {
            $conn->rollBack();
            die("Gagal memperbarui metode pembayaran: " . $e->getMessage());
        }
    }

    // Definisikan durasi estimasi berdasarkan layanan
    $service_durations = [
        'Quick Wash'      => 30,
        'Full Wash'       => 60,
        'Interior Detail' => 90,
        'Engine Wash'     => 45,
    ];
    $estimasi = $service_durations[$order['layanan']] ?? 45;

    // =========================================================
    // DATABASE TRANSACTION: Atomic insert semua record
    // =========================================================
    try {
        $conn->beginTransaction();

        // 1. INSERT Booking
        $stmt = $conn->prepare("INSERT INTO booking (tanggal, status, jenis_booking, estimasi_waktu, id_pelanggan, id_kendaraan, id_layanan)
            VALUES (:tanggal, 'pending', 'online', :estimasi, :id_pelanggan, :id_kendaraan, :id_layanan)");
        $stmt->execute([
            'tanggal'       => $order['tanggal'] ?? date('Y-m-d'),
            'estimasi'      => $estimasi,
            'id_pelanggan'  => $order['id_pelanggan'],
            'id_kendaraan'  => $order['id_kendaraan'],
            'id_layanan'    => $order['id_layanan'],
        ]);
        $id_booking = $conn->lastInsertId();

        // 2. INSERT Pembayaran
        $stmt = $conn->prepare("INSERT INTO pembayaran (metode, status, total, id_booking)
            VALUES (:metode, :status, :total, :id_booking)");
        $stmt->execute([
            'metode'     => $method,
            'status'     => $payment_status,
            'total'      => $total,
            'id_booking' => $id_booking,
        ]);

        // 3. INSERT Antrian (nomor otomatis per hari)
        $stmt = $conn->prepare("SELECT COALESCE(MAX(nomor_antrian), 0) + 1 as next_nomor FROM antrian a
            JOIN booking b ON a.id_booking = b.id_booking
            WHERE b.tanggal = :tanggal");
        $stmt->execute(['tanggal' => $order['tanggal'] ?? date('Y-m-d')]);
        $next_nomor = $stmt->fetch()['next_nomor'];

        $stmt = $conn->prepare("INSERT INTO antrian (nomor_antrian, status, id_booking)
            VALUES (:nomor, 'menunggu', :id_booking)");
        $stmt->execute([
            'nomor'      => $next_nomor,
            'id_booking' => $id_booking,
        ]);

        // 4. INSERT Transaksi
        $stmt = $conn->prepare("INSERT INTO transaksi (total, id_booking)
            VALUES (:total, :id_booking)");
        $stmt->execute([
            'total'      => $total,
            'id_booking' => $id_booking,
        ]);

        // 5. INSERT Invoice
        $stmt = $conn->prepare("INSERT INTO invoice (total, id_booking)
            VALUES (:total, :id_booking)");
        $stmt->execute([
            'total'      => $total,
            'id_booking' => $id_booking,
        ]);

        $conn->commit();

        // Simpan data ke session untuk halaman berikutnya
        $_SESSION['order']['id_booking']      = $id_booking;
        $_SESSION['order']['nomor_antrian']   = $next_nomor;
        $_SESSION['order']['payment_status']  = $payment_status;
        $_SESSION['order']['payment_method']  = $method;
        $_SESSION['order']['transaction_id']  = $transaction_prefix . '-' . strtoupper(uniqid());
        $_SESSION['order']['estimasi_waktu']  = $estimasi;

        // Redirect ke halaman selanjutnya
        header("Location: index.php?page=finish");
        exit();

    } catch (PDOException $e) {
        $conn->rollBack();
        die("Gagal memproses pembayaran: " . $e->getMessage());
    }
}
?>
