<?php

require_once BASE_PATH . '/app/Config/database.php';

// Ambil id_booking dari session
$id_booking = $_SESSION['order']['id_booking'] ?? null;
$order = $_SESSION['order'] ?? [];

// Jika ada id_booking, ambil data lengkap dari database
if ($id_booking) {
    $stmt = $conn->prepare("SELECT 
            b.id_booking, b.tanggal, b.status, b.estimasi_waktu,
            p.nama, p.email, p.no_hp,
            k.jenis AS tipe, k.no_plat AS plat,
            l.nama_layanan AS layanan, l.harga,
            py.metode AS payment_method, py.status AS payment_status, py.total,
            a.nomor_antrian,
            i.id_invoice
        FROM booking b
        JOIN pelanggan p ON b.id_pelanggan = p.id_pelanggan
        JOIN kendaraan k ON b.id_kendaraan = k.id_kendaraan
        JOIN layanan l ON b.id_layanan = l.id_layanan
        LEFT JOIN pembayaran py ON b.id_booking = py.id_booking
        LEFT JOIN antrian a ON b.id_booking = a.id_booking
        LEFT JOIN invoice i ON b.id_booking = i.id_booking
        WHERE b.id_booking = :id_booking");
    $stmt->execute(['id_booking' => $id_booking]);
    $booking = $stmt->fetch();
} else {
    $booking = null;
}

// Fallback ke session jika tidak ada data dari DB
$nama     = $booking['nama'] ?? ($order['nama'] ?? '-');
$tipe     = $booking['tipe'] ?? ($order['tipe'] ?? '-');
$plat     = $booking['plat'] ?? ($order['plat'] ?? '-');
$tanggal  = $booking['tanggal'] ?? ($order['tanggal'] ?? date('Y-m-d'));
$layanan  = $booking['layanan'] ?? ($order['layanan'] ?? '-');
$email    = $booking['email'] ?? ($order['email'] ?? '-');
$total    = $booking['total'] ?? ($order['total_price'] ?? 0);
$nomor_antrian = $booking['nomor_antrian'] ?? ($order['nomor_antrian'] ?? '-');
?>
<?php $hide_navbar = true; ?>
<?php include BASE_PATH . '/app/Views/layouts/header.php'; ?>

<!-- Banner Section -->
<div class="relative w-full h-[250px] flex items-center justify-center overflow-hidden">
    <img src="assets/img/benefit_wash.png" alt="Progress" class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0 bg-white/70"></div>
    <h1 class="relative z-10 text-5xl md:text-6xl text-black font-sans font-semibold tracking-tight">Booking Berhasil</h1>
</div>

<div class="max-w-3xl mx-auto px-4 py-16">
    
    <!-- 3-Step Progress Tracker -->
    <div class="relative mb-16 max-w-[280px] mx-auto px-2">
        <!-- Connecting Line -->
        <div class="absolute top-7 left-8 right-8 h-[1px] bg-olive-700 z-0"></div>
        
        <div class="relative z-10 flex justify-between items-start">
            <div class="flex flex-col items-center">
                <div class="w-14 h-14 rounded-full bg-olive-700 border-2 border-olive-700 flex items-center justify-center z-10"></div>
                <span class="mt-3 text-[13px] font-bold text-black text-center leading-tight">Booking<br>Berhasil</span>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-14 h-14 rounded-full bg-white border border-olive-700 flex items-center justify-center z-10"></div>
                <span class="mt-3 text-[13px] font-bold text-black text-center leading-tight">Ulasan</span>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-14 h-14 rounded-full bg-white border border-olive-700 flex items-center justify-center z-10"></div>
                <span class="mt-3 text-[13px] font-bold text-black text-center leading-tight">Selesai</span>
            </div>
        </div>
    </div>

    <!-- Detail Booking Card -->
    <div class="bg-white rounded-[1.5rem] border border-gray-400 p-8 md:p-10 mb-8 shadow-sm">
        <h2 class="font-sans text-xl font-bold text-black mb-3">Detail Booking</h2>
        <hr class="border-gray-400 mb-8">
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-12 gap-y-6">
            <!-- Name -->
            <div>
                <label class="block text-xs font-serif text-black mb-2">Name</label>
                <div class="w-full px-5 py-3 border border-olive-400 rounded-full text-sm text-gray-700 bg-white">
                    <?= htmlspecialchars($nama) ?>
                </div>
            </div>
            <!-- Vehicle -->
            <div>
                <label class="block text-xs font-serif text-black mb-2">Vehicle</label>
                <div class="w-full px-5 py-3 border border-olive-400 rounded-full text-sm text-gray-700 bg-white">
                    <?= htmlspecialchars($tipe) ?> <?= !empty($plat) ? '('.htmlspecialchars($plat).')' : '' ?>
                </div>
            </div>
            <!-- Date -->
            <div>
                <label class="block text-xs font-serif text-black mb-2">Date</label>
                <div class="w-full px-5 py-3 border border-olive-400 rounded-full text-sm text-gray-700 bg-white">
                    <?= date('d M Y', strtotime($tanggal)) ?>
                </div>
            </div>
            <!-- Service -->
            <div>
                <label class="block text-xs font-serif text-black mb-2">Service</label>
                <div class="w-full px-5 py-3 border border-olive-400 rounded-full text-sm text-gray-700 bg-white">
                    <?= htmlspecialchars($layanan) ?>
                </div>
            </div>
            <!-- Email -->
            <div>
                <label class="block text-xs font-serif text-black mb-2">Email</label>
                <div class="w-full px-5 py-3 border border-olive-400 rounded-full text-sm text-gray-700 bg-white">
                    <?= htmlspecialchars($email) ?>
                </div>
            </div>
            <!-- Total -->
            <div>
                <label class="block text-xs font-serif text-black mb-2">Total</label>
                <div class="w-full px-5 py-3 border border-olive-400 rounded-full text-sm text-gray-700 bg-white">
                    Rp <?= number_format($total, 0, ',', '.') ?>
                </div>
            </div>
            <!-- Nomor Antrian -->
            <div class="sm:col-span-2">
                <label class="block text-xs font-serif text-black mb-2">Nomor Antrian</label>
                <div class="w-full px-5 py-3 border border-olive-400 rounded-full text-lg font-bold text-olive-700 bg-white">
                    #<?= htmlspecialchars($nomor_antrian) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Tombol ke Halaman Proses -->
    <div class="mt-8 text-right">
        <a href="index.php?page=work_progress" class="inline-block px-8 py-3 bg-olive-700 text-white rounded-full font-bold hover:bg-olive-800 transition-colors">
            Lihat Proses Pengerjaan &rarr;
        </a>
    </div>

</div>

<?php include BASE_PATH . '/app/Views/layouts/footer.php'; ?>
