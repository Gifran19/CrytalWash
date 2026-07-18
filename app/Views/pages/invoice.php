<?php
$hide_navbar = true; // Sembunyikan navbar global
require_once BASE_PATH . '/app/Config/database.php';

// Ambil data dari session atau URL (dari DB berdasarkan ID)
$order = $_SESSION['order'] ?? [];
$id_booking = $_GET['id'] ?? ($order['id_booking'] ?? 'INV-' . strtoupper(uniqid()));
$date = date('d.m.Y');

// Coba ambil dari database jika id_booking valid
$booking_data = [];
$query_id = $_GET['id'] ?? ($order['id_booking'] ?? null);

if ($query_id && is_numeric($query_id)) {
    $stmt = $conn->prepare("SELECT 
            b.*, l.nama_layanan, l.harga, k.no_plat, k.jenis,
            p.nama, p.email, p.no_hp,
            pay.metode AS db_payment_method, pay.status AS db_payment_status
        FROM booking b
        JOIN layanan l ON b.id_layanan = l.id_layanan
        JOIN kendaraan k ON b.id_kendaraan = k.id_kendaraan
        JOIN pelanggan p ON b.id_pelanggan = p.id_pelanggan
        LEFT JOIN pembayaran pay ON b.id_booking = pay.id_booking
        WHERE b.id_booking = :id_booking");
    $stmt->execute(['id_booking' => (int)$query_id]);
    $booking_data = $stmt->fetch();
}

$name = $booking_data['nama'] ?? ($order['nama'] ?? 'Guest');
$email = $booking_data['email'] ?? ($order['email'] ?? '-');
$whatsapp = $booking_data['no_hp'] ?? ($order['whatsapp'] ?? '-');
$service = $booking_data['nama_layanan'] ?? ($order['layanan'] ?? 'Quick Wash');
$service_date = $booking_data['tanggal'] ?? date('Y-m-d');
$duration = $booking_data['estimasi_waktu'] ?? 30;
$payment_method = $booking_data['db_payment_method'] ?? ($order['payment_method'] ?? 'COD');
$price = $booking_data['harga'] ?? ($order['total_price'] ?? 0);
$total = $price;

// Tentukan apakah pembayaran sudah lunas
$is_paid = false;
if ((isset($booking_data['db_payment_status']) && strtolower($booking_data['db_payment_status']) === 'paid') ||
    (isset($order['payment_status']) && strtolower($order['payment_status']) === 'paid')) {
    $is_paid = true;
} elseif (strcasecmp($payment_method, 'QRIS') === 0 || strcasecmp($payment_method, 'Ewallet') === 0 || strcasecmp($payment_method, 'Credit') === 0) {
    $is_paid = true;
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - <?= htmlspecialchars($id_booking) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/accessibility.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; }
            .print-shadow-none { box-shadow: none !important; }
        }
    </style>
</head>
<body class="bg-gray-100 font-sans min-h-screen flex items-center justify-center p-4 md:p-8">

    <div class="bg-white w-full max-w-3xl rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.08)] overflow-hidden print-shadow-none relative pb-24">
        
        <!-- Top Header Bar -->
        <div class="bg-olive-700 px-10 py-6 flex justify-between items-center">
            <h1 class="font-serif text-2xl md:text-3xl font-bold text-white tracking-tight">CrystalWash</h1>
            <!-- Action buttons (Hidden in print) -->
            <div class="space-x-3 no-print">
                <button onclick="window.print()" class="bg-white/20 hover:bg-white/30 text-white p-2 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                </button>
            </div>
        </div>

        <!-- Invoice Title & Meta -->
        <div class="text-center pt-12 pb-8 px-10">
            <h2 class="text-5xl font-light tracking-widest text-gray-900 mb-6">INVOICE</h2>
            <p class="text-sm text-gray-500 mb-1">No: <span class="font-semibold text-gray-900"><?= htmlspecialchars($id_booking) ?></span></p>
            <p class="text-sm text-gray-500">Invoice date: <span class="font-semibold text-gray-900"><?= $date ?></span></p>
        </div>

        <div class="px-8 md:px-12 space-y-6">
            
            <!-- INFORMASI PELANGGAN -->
            <div class="border border-gray-100 rounded-2xl p-8 shadow-sm hover:shadow-md transition-all duration-300">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="bg-olive-50 p-2.5 rounded-xl">
                        <svg class="w-6 h-6 text-olive-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 tracking-wide text-sm">INFORMASI PELANGGAN</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                    <div>
                        <p class="text-gray-500 text-xs mb-1">Name</p>
                        <p class="font-semibold text-gray-900"><?= htmlspecialchars($name) ?></p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs mb-1">Email</p>
                        <p class="font-semibold text-gray-900"><?= htmlspecialchars($email) ?></p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs mb-1">No. HP</p>
                        <p class="font-semibold text-gray-900"><?= htmlspecialchars($whatsapp) ?></p>
                    </div>
                </div>
            </div>

            <!-- DETAIL LAYANAN -->
            <div class="border border-gray-100 rounded-2xl p-8 shadow-sm hover:shadow-md transition-all duration-300">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="bg-olive-50 p-2.5 rounded-xl">
                        <svg class="w-6 h-6 text-olive-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 tracking-wide text-sm">DETAIL LAYANAN</h3>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-gray-50 rounded-xl px-4 py-3">
                        <p class="text-gray-400 text-[10px] uppercase font-bold mb-1">Layanan</p>
                        <p class="font-semibold text-gray-900 text-sm"><?= htmlspecialchars($service) ?></p>
                    </div>
                    <div class="bg-gray-50 rounded-xl px-4 py-3">
                        <p class="text-gray-400 text-[10px] uppercase font-bold mb-1">Tanggal</p>
                        <p class="font-semibold text-gray-900 text-sm"><?= date('d M Y', strtotime($service_date)) ?></p>
                    </div>
                    <div class="bg-gray-50 rounded-xl px-4 py-3">
                        <p class="text-gray-400 text-[10px] uppercase font-bold mb-1">Estimasi Durasi</p>
                        <p class="font-semibold text-gray-900 text-sm"><?= $duration ?> Menit</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl px-4 py-3">
                        <p class="text-gray-400 text-[10px] uppercase font-bold mb-1">Status Kendaraan</p>
                        <p class="font-semibold text-olive-700 text-sm">Selesai</p>
                    </div>
                </div>
            </div>

            <!-- DETAIL PEMBAYARAN -->
            <div class="border border-gray-100 rounded-2xl p-8 shadow-sm hover:shadow-md transition-all duration-300">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="bg-olive-50 p-2.5 rounded-xl">
                        <svg class="w-6 h-6 text-olive-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 tracking-wide text-sm">DETAIL PEMBAYARAN</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-xl px-4 py-3 flex justify-between items-center">
                        <span class="text-gray-500 text-xs font-medium">Metode Pembayaran</span>
                        <span class="font-bold text-gray-900 text-sm"><?= htmlspecialchars($payment_method) ?></span>
                    </div>
                    <div class="bg-gray-50 rounded-xl px-4 py-3 flex justify-between items-center">
                        <span class="text-gray-500 text-xs font-medium">Status Pembayaran</span>
                        <?php if ($is_paid): ?>
                            <span class="font-bold text-green-600 text-sm px-2 py-1 bg-green-100 rounded text-[10px] uppercase">Lunas</span>
                        <?php else: ?>
                            <span class="font-bold text-red-600 text-sm px-2 py-1 bg-red-100 rounded text-[10px] uppercase">Belum Lunas</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- RINCIAN BIAYA -->
            <div class="border border-olive-100 bg-gradient-to-br from-white to-olive-50/50 rounded-2xl p-8 relative overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                <div class="absolute right-0 top-0 w-40 h-40 bg-olive-100 rounded-full -mr-16 -mt-16 opacity-40"></div>
                
                <div class="flex items-center space-x-4 mb-6 relative z-10">
                    <div class="bg-white p-2.5 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-olive-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 tracking-wide text-sm">RINCIAN BIAYA</h3>
                </div>
                
                <div class="space-y-3 relative z-10">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Harga Layanan</span>
                        <span class="font-medium text-gray-900">Rp <?= number_format($price, 0, ',', '.') ?></span>
                    </div>
                    
                    <hr class="border-gray-200 my-4">
                    
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-gray-900 text-base">TOTAL PEMBAYARAN</span>
                        <span class="font-bold text-olive-700 text-2xl">Rp <?= number_format($total, 0, ',', '.') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aesthetic Footer Wave & Back Button -->
        <div class="absolute bottom-0 left-0 w-full h-24 bg-olive-700 rounded-tr-[5rem] flex items-center justify-end px-10 no-print">
            <a href="index.php?page=home" class="bg-white text-olive-800 font-bold px-8 py-3 rounded-full hover:-translate-y-0.5 transition-all duration-300 shadow-md hover:shadow-lg text-sm">
                Kembali
            </a>
        </div>
        
        <!-- Background shape to complete the wave illusion -->
        <div class="absolute bottom-0 left-0 w-1/2 h-24 bg-white z-[-1]"></div>
    </div>
</body>
</html>
