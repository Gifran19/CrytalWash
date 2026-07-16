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

    // =========================================================
    // Estimasi durasi berdasarkan nama layanan aktual di database
    // Sesuai ketentuan:
    //   Motor Standar (kecil) → 20 menit
    //   Motor Besar           → 32 menit
    //   Mobil Standar (kecil) → 45 menit
    //   Mobil Besar           → 60 menit
    // =========================================================
    $nama_layanan_session = $order['layanan'] ?? '';
    $tipe_kendaraan       = strtolower($order['tipe'] ?? 'mobil');
    $is_motor             = (strpos($tipe_kendaraan, 'motor') !== false
                          || strpos($tipe_kendaraan, 'motorcycle') !== false);

    $service_durations = [
        // Motor
        'Cuci Motor Standar' => 20,   // motor kecil
        'Cuci Motor Besar'   => 32,   // motor besar
        // Mobil
        'Cuci Mobil Standar' => 45,   // mobil kecil
        'Cuci Mobil Besar'   => 60,   // mobil besar
    ];

    // Fallback: gunakan rata-rata sesuai jenis kendaraan jika layanan tidak dikenali
    $default_estimasi = $is_motor ? 26 : 52;
    $estimasi = $service_durations[$nama_layanan_session] ?? $default_estimasi;

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

        // 3. INSERT Antrian (nomor otomatis per hari, TERPISAH per jenis kendaraan)
        // Hitung nomor antrian hanya untuk jenis kendaraan yang sama (Motor atau Mobil)
        $jenis_kendaraan_filter = $is_motor ? 'Motor' : 'Mobil';
        $stmt = $conn->prepare("
            SELECT COALESCE(MAX(a.nomor_antrian), 0) + 1 as next_nomor
            FROM antrian a
            JOIN booking b ON a.id_booking = b.id_booking
            JOIN kendaraan k ON b.id_kendaraan = k.id_kendaraan
            WHERE b.tanggal = :tanggal
              AND k.jenis = :jenis
        ");
        $stmt->execute([
            'tanggal' => $order['tanggal'] ?? date('Y-m-d'),
            'jenis'   => $jenis_kendaraan_filter,
        ]);
        $next_nomor = $stmt->fetch()['next_nomor'];

        $stmt = $conn->prepare("INSERT INTO antrian (nomor_antrian, status, id_booking)
            VALUES (:nomor, 'menunggu', :id_booking)");
        $stmt->execute([
            'nomor'      => $next_nomor,
            'id_booking' => $id_booking,
        ]);

        // Prefix antrian: M- untuk Motor, C- untuk Mobil
        $antrian_prefix = $is_motor ? 'M' : 'C';

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
        $_SESSION['order']['id_booking']        = $id_booking;
        $_SESSION['order']['nomor_antrian']     = $next_nomor;
        $_SESSION['order']['antrian_prefix']    = $antrian_prefix;         // 'M' atau 'C'
        $_SESSION['order']['antrian_display']   = $antrian_prefix . '-' . str_pad($next_nomor, 2, '0', STR_PAD_LEFT); // e.g. M-01
        $_SESSION['order']['payment_status']    = $payment_status;
        $_SESSION['order']['payment_method']    = $method;
        $_SESSION['order']['transaction_id']    = $transaction_prefix . '-' . strtoupper(uniqid());
        $_SESSION['order']['estimasi_waktu']    = $estimasi;

        // Redirect ke halaman selanjutnya
        header("Location: index.php?page=finish");
        exit();

    } catch (PDOException $e) {
        $conn->rollBack();
        die("Gagal memproses pembayaran: " . $e->getMessage());
    }
}
?>
