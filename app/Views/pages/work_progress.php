<?php
$hide_navbar = true; // Sembunyikan navbar global
require_once BASE_PATH . '/app/Config/database.php';

// Ambil data dari session
$order = $_SESSION['order'] ?? [];
$id_booking = $order['id_booking'] ?? null;

// Simpan waktu mulai pengerjaan di session agar tidak berubah saat di-refresh
if (!isset($_SESSION['order']['start_time'])) {
    $_SESSION['order']['start_time'] = time();
}

// Ambil data dari database jika ada
if ($id_booking) {
    $stmt = $conn->prepare("SELECT 
            b.estimasi_waktu, b.tanggal, b.status,
            l.nama_layanan,
            k.no_plat,
            a.nomor_antrian, a.status AS antrian_status
        FROM booking b
        JOIN layanan l ON b.id_layanan = l.id_layanan
        JOIN kendaraan k ON b.id_kendaraan = k.id_kendaraan
        LEFT JOIN antrian a ON b.id_booking = a.id_booking
        WHERE b.id_booking = :id_booking");
    $stmt->execute(['id_booking' => $id_booking]);
    $booking_data = $stmt->fetch();

    // Status update is now handled exclusively by the Admin Dashboard
    // so we don't automatically update it here anymore.
}

// Data untuk ditampilkan
$selected_service = $booking_data['nama_layanan'] ?? ($order['layanan'] ?? 'Quick Wash');
$work_duration    = $booking_data['estimasi_waktu'] ?? ($order['estimasi_waktu'] ?? 15);
$name             = $order['nama'] ?? 'Guest';
$date             = $booking_data['tanggal'] ?? date('Y-m-d');
$email            = $order['email'] ?? '-';
$vehicle          = $order['tipe'] ?? 'Car';
$total            = $order['total_price'] ?? 0;

// Hitung perkiraan waktu selesai
$start_time = $_SESSION['order']['start_time'];
$estimated_finish_time = $start_time + ($work_duration * 60);
$formatted_finish_time = date('H:i', $estimated_finish_time) . ' WIB';
?>
<?php include BASE_PATH . '/app/Views/layouts/header.php'; ?>

<!-- Banner Section -->
<div class="relative w-full h-[250px] flex items-center justify-center overflow-hidden">
    <img src="assets/img/benefit_wash.png" alt="Progress" class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0 bg-white/70"></div> <!-- Make overlay lighter so text is very clear -->
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
                <label class="block text-xs font-serif text-black mb-2">Nama</label>
                <div class="w-full px-5 py-3 border border-olive-400 rounded-full text-sm text-gray-700 bg-white">
                    <?= htmlspecialchars($name) ?>
                </div>
            </div>
            <!-- Vehicle -->
            <div>
                <label class="block text-xs font-serif text-black mb-2">Kendaraan</label>
                <div class="w-full px-5 py-3 border border-olive-400 rounded-full text-sm text-gray-700 bg-white">
                    <?= htmlspecialchars($vehicle) ?>
                </div>
            </div>
            <!-- Date -->
            <div>
                <label class="block text-xs font-serif text-black mb-2">Tanggal</label>
                <div class="w-full px-5 py-3 border border-olive-400 rounded-full text-sm text-gray-700 bg-white">
                    <?= date('d M Y', strtotime($date)) ?>
                </div>
            </div>
            <!-- Service -->
            <div>
                <label class="block text-xs font-serif text-black mb-2">Layanan</label>
                <div class="w-full px-5 py-3 border border-olive-400 rounded-full text-sm text-gray-700 bg-white">
                    <?= htmlspecialchars($selected_service) ?>
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
        </div>
    </div>

    <!-- Estimasi Waktu Card -->
    <div class="bg-white rounded-[1.5rem] border border-gray-400 p-8 md:p-10 flex flex-col md:flex-row items-start md:items-center space-y-6 md:space-y-0 md:space-x-8 shadow-sm">
        <!-- Clock Icon -->
        <div class="flex-shrink-0 mx-auto md:mx-0">
            <svg class="w-24 h-24 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <circle cx="12" cy="12" r="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M12 6v6l4 2" stroke-linecap="round" stroke-linejoin="round" />
                <!-- Decorative dots -->
                <circle cx="12" cy="4" r="0.75" fill="currentColor" stroke="none" />
                <circle cx="12" cy="20" r="0.75" fill="currentColor" stroke="none" />
                <circle cx="4" cy="12" r="0.75" fill="currentColor" stroke="none" />
                <circle cx="20" cy="12" r="0.75" fill="currentColor" stroke="none" />
                <circle cx="6.5" cy="6.5" r="0.75" fill="currentColor" stroke="none" />
                <circle cx="17.5" cy="6.5" r="0.75" fill="currentColor" stroke="none" />
                <circle cx="6.5" cy="17.5" r="0.75" fill="currentColor" stroke="none" />
                <circle cx="17.5" cy="17.5" r="0.75" fill="currentColor" stroke="none" />
            </svg>
        </div>
        
        <!-- Text Content -->
        <div class="text-center md:text-left w-full">
            <h3 class="font-sans text-lg text-black mb-1">Estimasi Waktu</h3>
            <div class="text-3xl font-bold text-black mb-3">+- <?= $work_duration ?> Menit</div>
            
            <div class="bg-olive-50 border border-olive-200 rounded-xl p-4 mb-3 inline-block w-full md:w-auto">
                <p class="text-sm text-gray-700 font-serif">
                    Perkiraan kendaraan siap diambil pada:
                </p>
                <p class="text-xl font-bold text-olive-700 mt-1">
                    <?= $formatted_finish_time ?>
                </p>
            </div>
            
            <p class="text-gray-500 text-xs mt-2 italic">Kami akan segera menghubungi anda jika ada perubahan jadwal.</p>
        </div>
    </div>

    <!-- For navigation testing -->
    <div class="mt-8 text-right">
        <a href="index.php?page=review" class="inline-block px-8 py-3 border border-olive-700 text-olive-700 rounded-full font-bold hover:bg-olive-50 transition-colors">
            Lanjut ke Ulasan
        </a>
    </div>

</div>

<?php include BASE_PATH . '/app/Views/layouts/footer.php'; ?>
