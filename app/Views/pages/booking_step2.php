<?php
// Koneksi database
include BASE_PATH . '/app/Config/database.php'; 

date_default_timezone_set('Asia/Jakarta');
$hari_ini = date('Y-m-d');

// 1. HITUNG ANTREAN OTOMATIS DARI DATABASE
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM antrian a
    JOIN booking b ON a.id_booking = b.id_booking
    WHERE b.tanggal = :tanggal
    AND a.status IN ('menunggu', 'proses')");
$stmt->execute(['tanggal' => $hari_ini]);
$data = $stmt->fetch();

$jumlah_antrean = $data['total'] ?? 0; 
$jumlah_petugas = 2; // Bisa dibuat dinamis dari tabel petugas
$durasi_cuci = 30;    

// 2. HITUNG ESTIMASI
$estimasi_menit = ceil(max($jumlah_antrean, 1) / $jumlah_petugas) * $durasi_cuci;
$saran_jam = date('H:i', strtotime("+$estimasi_menit minutes"));
?>

<?php
$error = $_GET['error'] ?? null;
if ($error === 'duplicate_plat'): ?>
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm mb-4">
        Kendaraan dengan plat nomor tersebut sudah terdaftar di sistem.
    </div>
<?php elseif ($error === 'empty_plat'): ?>
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm mb-4">
        Nomor plat kendaraan wajib diisi.
    </div>
<?php endif; ?>

<h2 class="font-serif text-4xl font-bold text-gray-900 mb-8">Pesan Cucian Anda</h2>

<form action="index.php?action=auth_booking" method="POST" class="space-y-6">
    <input type="hidden" name="next_step" value="3">
    
    <!-- Vehicle Type -->
    <div>
        <label class="block text-sm font-serif text-gray-900 mb-2">Tipe Kendaraan</label>
        <div class="flex space-x-4">
            <label class="relative w-1/2 cursor-pointer">
                <input type="radio" name="tipe" value="Car" checked class="peer sr-only">
                <div class="w-full px-5 py-3 border border-olive-400 rounded-2xl flex items-center transition-colors peer-checked:border-olive-700 peer-checked:ring-1 peer-checked:ring-olive-700 bg-white">
                    <div class="w-4 h-4 rounded-full border border-gray-400 mr-3 flex items-center justify-center peer-checked:border-olive-700">
                        <!-- Dot is visible when checked via CSS below -->
                        <div class="w-2.5 h-2.5 rounded-full bg-olive-700 hidden peer-checked:block"></div>
                    </div>
                    <span class="text-sm font-serif text-gray-900">Mobil</span>
                </div>
            </label>
            <label class="relative w-1/2 cursor-pointer">
                <input type="radio" name="tipe" value="Motorcycle" class="peer sr-only">
                <div class="w-full px-5 py-3 border border-olive-400 rounded-2xl flex items-center transition-colors peer-checked:border-olive-700 peer-checked:ring-1 peer-checked:ring-olive-700 bg-white">
                    <div class="w-4 h-4 rounded-full border border-gray-400 mr-3 flex items-center justify-center peer-checked:border-olive-700">
                        <div class="w-2.5 h-2.5 rounded-full bg-olive-700 hidden peer-checked:block"></div>
                    </div>
                    <span class="text-sm font-serif text-gray-900">Motor</span>
                </div>
            </label>
        </div>
    </div>
    
    <!-- Plat Number -->
    <div>
        <label class="block text-sm font-serif text-gray-900 mb-2">Nomor Plat</label>
        <input type="text" name="plat" placeholder="masukkan nomor plat anda" required
            class="w-full px-5 py-3 border border-olive-400 rounded-2xl focus:ring-2 focus:ring-olive-700 focus:border-olive-700 transition-colors text-sm text-gray-600 placeholder-gray-400 bg-white uppercase">
    </div>
    
    <!-- Date & Time (Antrian) -->
    <div>
        <label class="block text-sm font-serif text-gray-900 mb-2">Tanggal & Waktu</label>
        <div class="flex items-stretch border border-olive-400 rounded-2xl bg-white overflow-hidden focus-within:ring-2 focus-within:ring-olive-700 focus-within:border-olive-700 transition-colors">
            <!-- Date Picker -->
            <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" required
                class="w-1/2 px-5 py-3 border-none focus:ring-0 text-sm font-serif text-gray-600 bg-transparent">
            
            <!-- Vertical Divider -->
            <div class="w-px bg-gray-300 my-2"></div>
            
            <!-- Antrian Status -->
            <div class="w-1/2 px-5 py-3 flex items-center justify-center bg-transparent">
                <span class="text-sm font-serif text-gray-900">Antrian: <?= $jumlah_antrean ?></span>
                <input type="hidden" name="jam" value="<?= $saran_jam ?>">
            </div>
        </div>
        <p class="text-xs text-gray-400 mt-2 ml-2">*Saran Waktu: <?= $saran_jam ?> WIB</p>
    </div>
    
    <!-- Buttons -->
    <div class="flex space-x-4 pt-4">
        <button type="button" onclick="window.history.back()" class="w-1/2 border border-olive-700 text-olive-700 font-serif text-lg py-3 rounded-full hover:bg-olive-50 transition-colors">
            Kembali
        </button>
        <button type="submit" class="w-1/2 bg-olive-700 text-white font-serif text-lg py-3 rounded-full hover:bg-olive-800 transition-colors shadow-md">
            Lanjut
        </button>
    </div>
</form>

<style>
/* CSS hack to make sibling dot show when radio checked */
input:checked ~ div > div > div {
    display: block;
}
</style>
