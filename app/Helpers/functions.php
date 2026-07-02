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
    if (strpos(strtolower($type), 'mobil') !== false || $type == 'Car') {
        return "<span class='badge-car'>🚗 Mobil</span>";
    } else {
        return "<span class='badge-motor'>🏍️ Motor</span>";
    }
}

/**
 * Mendapatkan teks translasi berdasarkan key
 *
 * @param string $key
 * @return string
 */
function trans($key) {
    // Pastikan session language tersedia, default 'id'
    $lang = $_SESSION['lang'] ?? 'id';
    
    // Cegah path traversal dengan membatasi bahasa yang diperbolehkan
    $allowed_langs = ['id', 'en'];
    if (!in_array($lang, $allowed_langs)) {
        $lang = 'id';
    }

    // Cache file bahasa dalam array statis agar tidak dibaca berkali-kali dalam satu request
    static $translations = [];
    
    if (!isset($translations[$lang])) {
        $file_path = BASE_PATH . "/app/Language/{$lang}.php";
        if (file_exists($file_path)) {
            $translations[$lang] = require $file_path;
        } else {
            $translations[$lang] = [];
        }
    }

    // Kembalikan teks translasi atau key aslinya jika tidak ditemukan
    return $translations[$lang][$key] ?? $key;
}
?>
