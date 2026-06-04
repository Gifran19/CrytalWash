<?php
/**
 * Fungsi untuk mengubah angka menjadi format Rupiah
 * Contoh: 100000 -> Rp 100.000
 */
function formatRupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

/**
 * Fungsi untuk membersihkan input agar aman dari XSS
 */
function cleanInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

/**
 * Fungsi untuk mendapatkan badge status kendaraan (Opsional)
 */
function getVehicleBadge($type) {
    if ($type == 'Car') {
        return "<span class='badge-car'>🚗 Car</span>";
    } else {
        return "<span class='badge-motor'>🏍️ Motorcycle</span>";
    }
}
?>
