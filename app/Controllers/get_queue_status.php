<?php
// app/Controllers/get_queue_status.php
// API endpoint publik (tanpa login) untuk menampilkan status antrian hari ini.
// Mengembalikan data terpisah untuk antrian Mobil dan Motor.

header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');

require_once BASE_PATH . '/app/Config/database.php';

try {
    // Helper: hitung antrian berdasarkan status dan jenis kendaraan
    function getQueueCount($conn, $status_array, $jenis, $tanggal) {
        $placeholders = implode(',', array_fill(0, count($status_array), '?'));
        $stmt = $conn->prepare("
            SELECT COUNT(*) as total
            FROM antrian a
            JOIN booking b ON a.id_booking = b.id_booking
            JOIN kendaraan k ON b.id_kendaraan = k.id_kendaraan
            WHERE a.status IN ($placeholders)
              AND b.tanggal = ?
              AND k.jenis = ?
        ");
        $stmt->execute(array_merge($status_array, [$tanggal, $jenis]));
        return (int) $stmt->fetchColumn();
    }

    function getNextNomor($conn, $jenis, $tanggal) {
        $stmt = $conn->prepare("
            SELECT COALESCE(MAX(a.nomor_antrian), 0) + 1 as next_nomor
            FROM antrian a
            JOIN booking b ON a.id_booking = b.id_booking
            JOIN kendaraan k ON b.id_kendaraan = k.id_kendaraan
            WHERE b.tanggal = ?
              AND k.jenis = ?
        ");
        $stmt->execute([$tanggal, $jenis]);
        return (int) $stmt->fetchColumn();
    }

    function getEstimasiMenit($conn, $jenis, $tanggal) {
        $stmt = $conn->prepare("
            SELECT COALESCE(SUM(b.estimasi_waktu), 0) as total_menit
            FROM antrian a
            JOIN booking b ON a.id_booking = b.id_booking
            JOIN kendaraan k ON b.id_kendaraan = k.id_kendaraan
            WHERE a.status IN ('menunggu', 'proses')
              AND b.tanggal = ?
              AND k.jenis = ?
        ");
        $stmt->execute([$tanggal, $jenis]);
        return (int) $stmt->fetchColumn();
    }

    $tanggal = date('Y-m-d');

    // ===== ANTRIAN MOTOR =====
    $motor_menunggu  = getQueueCount($conn, ['menunggu'], 'Motor', $tanggal);
    $motor_diproses  = getQueueCount($conn, ['proses'], 'Motor', $tanggal);
    $motor_estimasi  = getEstimasiMenit($conn, 'Motor', $tanggal);
    $motor_next      = getNextNomor($conn, 'Motor', $tanggal);

    // ===== ANTRIAN MOBIL =====
    $mobil_menunggu  = getQueueCount($conn, ['menunggu'], 'Mobil', $tanggal);
    $mobil_diproses  = getQueueCount($conn, ['proses'], 'Mobil', $tanggal);
    $mobil_estimasi  = getEstimasiMenit($conn, 'Mobil', $tanggal);
    $mobil_next      = getNextNomor($conn, 'Mobil', $tanggal);

    // ===== TOTAL SEMUA =====
    $total_menunggu = $motor_menunggu + $mobil_menunggu;
    $total_diproses = $motor_diproses + $mobil_diproses;

    echo json_encode([
        'success'    => true,
        'updated_at' => date('H:i'),

        // Data gabungan (backward compatible)
        'menunggu'        => $total_menunggu,
        'diproses'        => $total_diproses,
        'total_aktif'     => $total_menunggu + $total_diproses,
        'estimasi_menit'  => max($motor_estimasi, $mobil_estimasi),
        'antrian_ke'      => $motor_next, // deprecated, gunakan per-jenis

        // Data antrian Motor
        'motor' => [
            'menunggu'       => $motor_menunggu,
            'diproses'       => $motor_diproses,
            'total_aktif'    => $motor_menunggu + $motor_diproses,
            'estimasi_menit' => $motor_estimasi,
            'antrian_ke'     => $motor_next,
            'antrian_display'=> 'M-' . str_pad($motor_next, 2, '0', STR_PAD_LEFT),
        ],

        // Data antrian Mobil
        'mobil' => [
            'menunggu'       => $mobil_menunggu,
            'diproses'       => $mobil_diproses,
            'total_aktif'    => $mobil_menunggu + $mobil_diproses,
            'estimasi_menit' => $mobil_estimasi,
            'antrian_ke'     => $mobil_next,
            'antrian_display'=> 'C-' . str_pad($mobil_next, 2, '0', STR_PAD_LEFT),
        ],
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Gagal mengambil data antrian.',
    ]);
}
