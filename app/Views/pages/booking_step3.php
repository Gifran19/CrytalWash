<?php
// Koneksi database untuk mengambil daftar layanan
include BASE_PATH . '/app/Config/database.php';

// Mengambil tipe kendaraan dari session untuk referensi
$tipe_kendaraan = isset($_SESSION['order']['tipe']) ? $_SESSION['order']['tipe'] : 'Car';

// Load layanan dari database berdasarkan tipe kendaraan
$stmt = $conn->prepare("SELECT id_layanan, nama_layanan, harga FROM layanan WHERE jenis_kendaraan = :tipe ORDER BY harga ASC");
$stmt->execute(['tipe' => $tipe_kendaraan]);
$services = $stmt->fetchAll();
?>

<div class="mb-8">
    <h2 class="font-serif text-4xl font-bold text-olive-700 dark:text-olive-400 tracking-tight">Pesan Cucian Anda</h2>
    <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm">Pilih layanan perawatan terbaik untuk kendaraan Anda.</p>
</div>

<form action="index.php?action=auth_booking" method="POST" class="space-y-8">
    <input type="hidden" name="next_step" value="4">
    
    <div>
        <label class="text-xs uppercase font-semibold text-gray-600 dark:text-gray-400 tracking-wider mb-4 block">Pilihan Layanan</label>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <?php foreach ($services as $s) : ?>
                <label class="relative block cursor-pointer group h-full">
                    <input type="radio" name="layanan" value="<?= htmlspecialchars($s['nama_layanan']) ?>" required class="peer sr-only">
                    
                    <div class="rounded-2xl border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 transition-all duration-500 peer-checked:border-2 peer-checked:border-olive-700 dark:peer-checked:border-olive-500 peer-checked:bg-olive-50 dark:peer-checked:bg-gray-800/80 peer-checked:shadow-xl peer-checked:scale-[1.03] shadow-sm hover:border-olive-200 hover:shadow-md relative h-full flex flex-col justify-between min-h-[140px]">
                        
                        <!-- Checkmark for selected state -->
                        <div class="absolute top-4 right-4 w-6 h-6 bg-olive-700 dark:bg-olive-500 rounded-full text-white items-center justify-center hidden peer-checked:flex shadow-sm transform scale-0 peer-checked:scale-100 transition-transform duration-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        
                        <!-- Top Row: Title -->
                        <div class="flex justify-between items-start mb-4 pr-6">
                            <span class="block font-bold text-gray-800 dark:text-gray-100 text-lg group-hover:text-olive-700 dark:group-hover:text-olive-400 transition-colors"><?= htmlspecialchars($s['nama_layanan']) ?></span>
                        </div>
                        
                        <!-- Bottom Row: Price -->
                        <div class="flex justify-between items-end mt-auto border-t border-gray-100 dark:border-gray-700 peer-checked:border-olive-200 dark:peer-checked:border-olive-500/50 pt-4">
                            <span class="block font-bold text-olive-700 dark:text-olive-400 text-xl tracking-tight">Rp <?= number_format($s['harga'], 0, ',', '.') ?></span>
                        </div>
                    </div>
                </label>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Buttons -->
    <div class="flex space-x-4 pt-6 mt-8 border-t border-gray-100 dark:border-gray-700">
        <button type="button" onclick="window.history.back()" class="w-1/3 px-6 py-4 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 rounded-xl font-bold text-sm hover:border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-300">
            Kembali
        </button>
        <button type="submit" class="w-2/3 px-6 py-4 bg-olive-700 text-white rounded-xl font-bold text-lg hover:bg-olive-800 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300">
            Lanjut
        </button>
    </div>
</form>
