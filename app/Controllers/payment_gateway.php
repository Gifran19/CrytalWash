<?php

require_once BASE_PATH . '/app/Config/database.php';
require_once BASE_PATH . '/app/Helpers/functions.php';

$is_post = $_SERVER['REQUEST_METHOD'] == 'POST';

if ($is_post) {
    verify_csrf();

    $confirm_qris = isset($_POST['confirm_qris']) && $_POST['confirm_qris'] == '1';

    if ($confirm_qris) {
        // CASE A: User confirms QRIS payment
        $order = $_SESSION['order'] ?? [];
        $id_booking = $order['id_booking'] ?? null;
        $midtrans_order_id = $order['midtrans_order_id'] ?? null;

        if (!$id_booking || !$midtrans_order_id) {
            header("Location: index.php?page=home");
            exit();
        }

        $status_check = MidtransService::checkPaymentStatus($midtrans_order_id);

        if ($status_check['success'] && in_array($status_check['transaction_status'], ['settlement', 'capture'])) {
            // Mark as paid
            try {
                $conn->beginTransaction();

                $stmt = $conn->prepare("UPDATE pembayaran
                    SET status = 'paid', midtrans_status = :m_status, paid_at = NOW(), updated_at = NOW()
                    WHERE id_booking = :id_booking");
                $stmt->execute([
                    'm_status' => $status_check['transaction_status'],
                    'id_booking' => $id_booking
                ]);

                $conn->commit();

                $_SESSION['order']['payment_status'] = 'paid';
                header("Location: index.php?page=finish");
                exit();
            } catch (PDOException $e) {
                if ($conn->inTransaction()) {
                    $conn->rollBack();
                }
                error_log("Gagal memperbarui status pembayaran QRIS ke paid: " . $e->getMessage());
                $_SESSION['qris_error'] = "Terjadi kesalahan sistem saat memproses konfirmasi pembayaran.";
                header("Location: index.php?page=qris_checkout&error=sys_error");
                exit();
            }
        } else {
            // Transaction status is still pending or not paid
            $m_status = $status_check['transaction_status'] ?? 'pending';
            try {
                $stmt = $conn->prepare("UPDATE pembayaran
                    SET midtrans_status = :m_status, updated_at = NOW()
                    WHERE id_booking = :id_booking");
                $stmt->execute([
                    'm_status' => $m_status,
                    'id_booking' => $id_booking
                ]);
            } catch (PDOException $e) {
                error_log("Gagal menyimpan status pending QRIS: " . $e->getMessage());
            }

            $user_msg = "Pembayaran belum terdeteksi. Silakan selesaikan pembayaran di aplikasi e-wallet Anda.";
            if (isset($status_check['message']) && $status_check['success'] === false) {
                $user_msg = $status_check['message'];
            }
            $_SESSION['qris_error'] = $user_msg;
            header("Location: index.php?page=qris_checkout&error=not_paid");
            exit();
        }
    } else {
        // CASE B: User submits payment method from Step 3 (Cash or QRIS)
        $method = cleanInput($_POST['payment_method'] ?? '');
        $order  = $_SESSION['order'] ?? [];
        $total  = $order['total_price'] ?? 0;

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

        // Tentukan status pembayaran awal berdasarkan metode
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
                    // Check if we already have midtrans_order_id in DB, if not generate it
                    $stmt = $conn->prepare("SELECT midtrans_order_id FROM pembayaran WHERE id_booking = :id_booking LIMIT 1");
                    $stmt->execute(['id_booking' => $id_booking]);
                    $row = $stmt->fetch();
                    if (!empty($row['midtrans_order_id'])) {
                        // Already generated before, redirect directly
                        $_SESSION['order']['midtrans_order_id'] = $row['midtrans_order_id'];
                        header("Location: index.php?page=qris_checkout");
                        exit();
                    }

                    // Not generated yet, generate now
                    $charge = MidtransService::createQrisCharge($id_booking, $total);
                    if (!$charge['success']) {
                        throw new Exception($charge['message']);
                    }

                    $conn->beginTransaction();
                    $stmt = $conn->prepare("UPDATE pembayaran
                        SET midtrans_order_id = :midtrans_order_id, midtrans_status = 'pending', updated_at = NOW()
                        WHERE id_booking = :id_booking");
                    $stmt->execute([
                        'midtrans_order_id' => $charge['order_id'],
                        'id_booking' => $id_booking
                    ]);
                    $conn->commit();

                    $_SESSION['order']['qr_url'] = $charge['qr_url'];
                    $_SESSION['order']['midtrans_order_id'] = $charge['order_id'];
                    header("Location: index.php?page=qris_checkout");
                } else {
                    header("Location: index.php?page=finish");
                }
                exit();
            } catch (Exception $e) {
                if ($conn->inTransaction()) {
                    $conn->rollBack();
                }
                error_log("Gagal memperbarui metode pembayaran: " . $e->getMessage());
                $_SESSION['payment_error'] = $e->getMessage();
                header("Location: index.php?page=checkout&step=3&error=process_error");
                exit();
            }
        }

        // Estimasi durasi berdasarkan nama layanan aktual di database
        $nama_layanan_session = $order['layanan'] ?? '';
        $tipe_kendaraan       = strtolower($order['tipe'] ?? 'mobil');
        $is_motor             = (strpos($tipe_kendaraan, 'motor') !== false
                              || strpos($tipe_kendaraan, 'motorcycle') !== false);

        $service_durations = [
            'Cuci Motor Standar' => 20,
            'Cuci Motor Besar'   => 32,
            'Cuci Mobil Standar' => 45,
            'Cuci Mobil Besar'   => 60,
        ];

        $default_estimasi = $is_motor ? 26 : 52;
        $estimasi = $service_durations[$nama_layanan_session] ?? $default_estimasi;

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

            $midtrans_order_id = null;
            $qr_url = null;

            if ($method === 'QRIS') {
                // Call Midtrans
                $charge = MidtransService::createQrisCharge($id_booking, $total);
                if (!$charge['success']) {
                    throw new Exception($charge['message']);
                }
                $midtrans_order_id = $charge['order_id'];
                $qr_url = $charge['qr_url'];
            }

            // 2. INSERT Pembayaran
            $stmt = $conn->prepare("INSERT INTO pembayaran (metode, status, total, id_booking, midtrans_order_id, midtrans_status)
                VALUES (:metode, :status, :total, :id_booking, :midtrans_order_id, :midtrans_status)");
            $stmt->execute([
                'metode'            => $method,
                'status'            => $payment_status,
                'total'             => $total,
                'id_booking'        => $id_booking,
                'midtrans_order_id' => $midtrans_order_id,
                'midtrans_status'   => ($method === 'QRIS') ? 'pending' : null
            ]);

            // 3. INSERT Antrian
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

            $_SESSION['order']['id_booking']        = $id_booking;
            $_SESSION['order']['nomor_antrian']     = $next_nomor;
            $_SESSION['order']['antrian_prefix']    = $antrian_prefix;
            $_SESSION['order']['antrian_display']   = $antrian_prefix . '-' . str_pad($next_nomor, 2, '0', STR_PAD_LEFT);
            $_SESSION['order']['payment_status']    = $payment_status;
            $_SESSION['order']['payment_method']    = $method;
            $_SESSION['order']['transaction_id']    = $transaction_prefix . '-' . strtoupper(uniqid());
            $_SESSION['order']['estimasi_waktu']    = $estimasi;

            if ($method === 'QRIS') {
                $_SESSION['order']['qr_url']            = $qr_url;
                $_SESSION['order']['midtrans_order_id']  = $midtrans_order_id;
                header("Location: index.php?page=qris_checkout");
            } else {
                header("Location: index.php?page=finish");
            }
            exit();

        } catch (Exception $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            error_log("Gagal memproses pembayaran: " . $e->getMessage());
            $_SESSION['payment_error'] = $e->getMessage();
            header("Location: index.php?page=checkout&step=3&error=process_error");
            exit();
        }
    }
} else {
    header("Location: index.php?page=home");
    exit();
}
