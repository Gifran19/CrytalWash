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

<h2 class="font-serif text-4xl font-bold text-gray-900 mb-6">Pesan Cucian Anda</h2>

<form action="index.php?action=auth_booking" method="POST" class="space-y-6">
    <input type="hidden" name="next_step" value="4">
    
    <div>
        <label class="block text-sm font-serif text-gray-900 mb-4">Layanan</label>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <?php foreach ($services as $s) : ?>
                <label class="relative block cursor-pointer group">
                    <input type="radio" name="layanan" value="<?= htmlspecialchars($s['nama_layanan']) ?>" required class="peer sr-only">
                    
                    <div class="rounded-2xl border border-olive-400 bg-white p-5 transition-colors peer-checked:border-olive-700 peer-checked:ring-1 peer-checked:ring-olive-700 relative h-full flex flex-col justify-between min-h-[100px]">
                        
                        <!-- Top Row: Title & Radio Dot -->
                        <div class="flex justify-between items-start mb-4">
                            <span class="block font-serif text-gray-900 text-sm md:text-base"><?= htmlspecialchars($s['nama_layanan']) ?></span>
                            <!-- Radio dot -->
                            <div class="w-4 h-4 rounded-full border border-gray-400 flex items-center justify-center peer-checked:border-olive-700">
                                <div class="w-2.5 h-2.5 rounded-full bg-olive-700 hidden peer-checked:block"></div>
                            </div>
                        </div>
                        
                        <!-- Bottom Row: Duration & Price -->
                        <div class="flex justify-between items-end mt-auto">
                            <!-- Dummy duration for UI sake, as it's not in DB yet -->
                            <span class="block text-gray-900 font-serif text-sm md:text-base">Rp <?= number_format($s['harga'], 0, ',', '.') ?></span>
                        </div>
                    </div>
                </label>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Buttons -->
    <div class="flex space-x-4 pt-4 mt-8">
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
input:checked ~ div > div > div > div {
    display: block;
}
</style>
