<?php
require_once __DIR__ . '/../app/Config/database.php';

try {
    $stmt = $conn->query("SELECT t.id_transaksi, t.tanggal, t.total, py.status as pay_status 
                          FROM transaksi t 
                          LEFT JOIN pembayaran py ON t.id_booking = py.id_booking");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($data);

    echo "\nMonthly Revenue Query:\n";
    $stmt2 = $conn->query("SELECT DATE_FORMAT(t.tanggal, '%Y-%m') as bulan, SUM(t.total) as total
            FROM transaksi t
            WHERE t.tanggal >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
            GROUP BY DATE_FORMAT(t.tanggal, '%Y-%m')
            ORDER BY bulan ASC");
    $data2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    print_r($data2);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
