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
    <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl text-sm mb-6 shadow-sm flex items-start">
        <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <span>Kendaraan dengan plat nomor tersebut sudah terdaftar di sistem.</span>
    </div>
<?php elseif ($error === 'empty_plat'): ?>
    <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl text-sm mb-6 shadow-sm flex items-start">
        <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <span>Nomor plat kendaraan wajib diisi.</span>
    </div>
<?php endif; ?>

<div class="mb-8">
    <h2 class="font-serif text-4xl font-bold text-olive-700 dark:text-olive-400 tracking-tight">Pesan Cucian Anda</h2>
    <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm">Beritahu kami detail kendaraan Anda.</p>
</div>

<form action="index.php?action=auth_booking" method="POST" class="space-y-6">
    <input type="hidden" name="next_step" value="3">
    
    <!-- Vehicle Type -->
    <div>
        <label class="text-xs uppercase font-semibold text-gray-600 dark:text-gray-400 tracking-wider mb-3 block">Tipe Kendaraan</label>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Mobil Kecil -->
            <label class="relative cursor-pointer group">
                <input type="radio" name="tipe" value="Mobil Kecil" checked class="peer sr-only">
                <div class="h-full p-4 border border-gray-200 dark:border-gray-700 rounded-xl flex flex-col items-center justify-center transition-all duration-300 peer-checked:border-olive-700 dark:peer-checked:border-olive-500 peer-checked:ring-2 peer-checked:ring-olive-400 peer-checked:bg-olive-50 dark:peer-checked:bg-gray-800/80 bg-white dark:bg-gray-800 shadow-sm group-hover:shadow-md group-hover:-translate-y-0.5">
                    <div class="w-5 h-5 rounded-full border-2 border-gray-300 dark:border-gray-600 mb-2 flex items-center justify-center peer-checked:border-olive-700 dark:peer-checked:border-olive-500">
                        <div class="w-2.5 h-2.5 rounded-full bg-olive-700 dark:bg-olive-500 hidden peer-checked:block"></div>
                    </div>
                    <span class="text-sm font-semibold text-gray-800 dark:text-gray-200 text-center">Mobil Kecil</span>
                </div>
            </label>
            <!-- Mobil Sedang -->
            <label class="relative cursor-pointer group">
                <input type="radio" name="tipe" value="Mobil Sedang" class="peer sr-only">
                <div class="h-full p-4 border border-gray-200 dark:border-gray-700 rounded-xl flex flex-col items-center justify-center transition-all duration-300 peer-checked:border-olive-700 dark:peer-checked:border-olive-500 peer-checked:ring-2 peer-checked:ring-olive-400 peer-checked:bg-olive-50 dark:peer-checked:bg-gray-800/80 bg-white dark:bg-gray-800 shadow-sm group-hover:shadow-md group-hover:-translate-y-0.5">
                    <div class="w-5 h-5 rounded-full border-2 border-gray-300 dark:border-gray-600 mb-2 flex items-center justify-center peer-checked:border-olive-700 dark:peer-checked:border-olive-500">
                        <div class="w-2.5 h-2.5 rounded-full bg-olive-700 dark:bg-olive-500 hidden peer-checked:block"></div>
                    </div>
                    <span class="text-sm font-semibold text-gray-800 dark:text-gray-200 text-center">Mobil Sedang</span>
                </div>
            </label>
            <!-- Mobil Besar -->
            <label class="relative cursor-pointer group">
                <input type="radio" name="tipe" value="Mobil Besar" class="peer sr-only">
                <div class="h-full p-4 border border-gray-200 dark:border-gray-700 rounded-xl flex flex-col items-center justify-center transition-all duration-300 peer-checked:border-olive-700 dark:peer-checked:border-olive-500 peer-checked:ring-2 peer-checked:ring-olive-400 peer-checked:bg-olive-50 dark:peer-checked:bg-gray-800/80 bg-white dark:bg-gray-800 shadow-sm group-hover:shadow-md group-hover:-translate-y-0.5">
                    <div class="w-5 h-5 rounded-full border-2 border-gray-300 dark:border-gray-600 mb-2 flex items-center justify-center peer-checked:border-olive-700 dark:peer-checked:border-olive-500">
                        <div class="w-2.5 h-2.5 rounded-full bg-olive-700 dark:bg-olive-500 hidden peer-checked:block"></div>
                    </div>
                    <span class="text-sm font-semibold text-gray-800 dark:text-gray-200 text-center">Mobil Besar</span>
                </div>
            </label>
            <!-- Motor -->
            <label class="relative cursor-pointer group">
                <input type="radio" name="tipe" value="Motor" class="peer sr-only">
                <div class="h-full p-4 border border-gray-200 dark:border-gray-700 rounded-xl flex flex-col items-center justify-center transition-all duration-300 peer-checked:border-olive-700 dark:peer-checked:border-olive-500 peer-checked:ring-2 peer-checked:ring-olive-400 peer-checked:bg-olive-50 dark:peer-checked:bg-gray-800/80 bg-white dark:bg-gray-800 shadow-sm group-hover:shadow-md group-hover:-translate-y-0.5">
                    <div class="w-5 h-5 rounded-full border-2 border-gray-300 dark:border-gray-600 mb-2 flex items-center justify-center peer-checked:border-olive-700 dark:peer-checked:border-olive-500">
                        <div class="w-2.5 h-2.5 rounded-full bg-olive-700 dark:bg-olive-500 hidden peer-checked:block"></div>
                    </div>
                    <span class="text-sm font-semibold text-gray-800 dark:text-gray-200 text-center">Motor</span>
                </div>
            </label>
        </div>
    </div>
    
    <!-- Plat Number -->
    <div class="group">
        <label class="text-xs uppercase font-semibold text-gray-600 dark:text-gray-400 tracking-wider mb-2 block group-focus-within:text-olive-700 dark:group-focus-within:text-olive-400 transition-colors">Nomor Plat</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400 dark:text-gray-500 group-focus-within:text-olive-600 dark:group-focus-within:text-olive-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
            </div>
            <input type="text" name="plat" placeholder="Contoh: B 1234 ABC" required
                class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl py-4 pl-12 pr-4 text-gray-800 dark:text-gray-100 uppercase placeholder:normal-case placeholder:text-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-olive-400 focus:border-olive-700 dark:focus:ring-olive-500 focus:outline-none transition-all duration-300">
        </div>
    </div>
    
    <!-- Date & Time (Antrian) -->
    <div class="group">
        <label class="text-xs uppercase font-semibold text-gray-600 dark:text-gray-400 tracking-wider mb-2 block group-focus-within:text-olive-700 dark:group-focus-within:text-olive-400 transition-colors">Tanggal & Waktu</label>
        <div class="flex items-stretch border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 overflow-hidden focus-within:ring-2 focus-within:ring-olive-400 focus-within:border-olive-700 dark:focus-within:border-olive-500 transition-all duration-300 shadow-sm relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                <svg class="h-5 w-5 text-gray-400 dark:text-gray-500 group-focus-within:text-olive-600 dark:group-focus-within:text-olive-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <!-- Date Picker -->
            <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" required
                class="w-1/2 py-4 pl-12 pr-4 border-none focus:ring-0 text-sm font-semibold text-gray-800 dark:text-gray-100 bg-transparent outline-none [color-scheme:light] dark:[color-scheme:dark]">
            
            <!-- Vertical Divider -->
            <div class="w-px bg-gray-200 dark:bg-gray-700 my-3"></div>
            
            <!-- Antrian Status -->
            <div class="w-1/2 p-4 flex items-center justify-center bg-gray-50/50 dark:bg-gray-900/50">
                <div class="flex items-center text-sm font-semibold text-gray-800 dark:text-gray-200">
                    <svg class="w-4 h-4 mr-2 text-olive-600 dark:text-olive-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Antrean: <span class="ml-1 text-olive-700 dark:text-olive-400 bg-olive-100 dark:bg-gray-800 px-2 py-0.5 rounded-md"><?= $jumlah_antrean ?></span>
                </div>
                <input type="hidden" name="jam" value="<?= $saran_jam ?>">
            </div>
        </div>
        <div class="flex items-center gap-3 bg-olive-50 dark:bg-gray-800 border border-olive-200 dark:border-gray-700 p-4 rounded-xl mt-6 shadow-sm">
            <svg class="w-6 h-6 flex-shrink-0 text-olive-700 dark:text-olive-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-sm text-gray-700 dark:text-gray-300">
                Estimasi dilayani pada <span class="font-bold text-olive-700 dark:text-olive-400 ml-1 bg-white dark:bg-gray-900 border border-olive-100 dark:border-gray-700 px-2 py-1 rounded-md shadow-sm"><?= $saran_jam ?> WIB</span>
            </p>
        </div>
    </div>
    
    <!-- Buttons -->
    <div class="flex space-x-4 pt-6 mt-2 border-t border-gray-100 dark:border-gray-700">
        <button type="button" onclick="window.history.back()" class="w-1/3 px-6 py-4 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 rounded-xl font-bold text-sm hover:border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-300">
            Kembali
        </button>
        <button type="submit" class="w-2/3 px-6 py-4 bg-olive-700 text-white rounded-xl font-bold text-lg hover:bg-olive-800 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300">
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
