<?php
// Validasi data session dilakukan SEBELUM menyertakan header.php
$order = $_SESSION['order'] ?? [];
$id_booking = $order['id_booking'] ?? null;
$qr_url = $order['qr_url'] ?? null;
$midtrans_order_id = $order['midtrans_order_id'] ?? null;

if (empty($order) || !$id_booking) {
    header("Location: index.php?page=home");
    exit;
}
$total = $order['total_price'] ?? 0;

$hide_navbar = true; // Sembunyikan navbar global
include BASE_PATH . '/app/Views/layouts/header.php';

// Ambil error dari session jika ada
$qris_error = $_SESSION['qris_error'] ?? null;
unset($_SESSION['qris_error']);
?>

<style>
@keyframes fadeInDown {
  from { opacity: 0; transform: translateY(-15px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in-down {
  animation: fadeInDown 0.5s ease-out forwards;
}
</style>

<div class="min-h-screen flex items-center justify-center bg-olive-50 dark:bg-slate-900 p-4 md:p-8 transition-colors duration-300">
    <div class="bg-white dark:bg-gray-800 w-full max-w-md rounded-3xl shadow-2xl p-8 md:p-10 text-center animate-fade-in-down border border-gray-100 dark:border-gray-700 transition-colors duration-300">
        <div class="mb-6">
            <div class="w-16 h-16 bg-olive-100 dark:bg-olive-900/30 rounded-full flex items-center justify-center mx-auto mb-4 transition-colors duration-300">
                <svg class="w-8 h-8 text-olive-700 dark:text-olive-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            </div>
            <h1 class="font-serif text-3xl font-bold text-olive-800 dark:text-olive-400 mb-2 transition-colors duration-300">Pembayaran QRIS</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 transition-colors duration-300">Scan QR Code di bawah menggunakan M-Banking atau E-Wallet Anda.</p>
        </div>

        <?php if (!empty($qris_error)): ?>
            <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 p-4 rounded-xl mb-6 text-sm text-left">
                <strong>Status Pembayaran:</strong> <?= htmlspecialchars($qris_error) ?>
            </div>
        <?php endif; ?>

        <?php if (empty($qr_url)): ?>
            <!-- Menampilkan info bahwa QRIS tidak tersedia, tanpa QR code dummy -->
            <div class="bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-300 p-6 rounded-2xl mb-8 text-center text-sm">
                <svg class="w-12 h-12 text-amber-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <p class="font-semibold mb-1">Layanan QRIS Belum Tersedia</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Hubungi admin atau silakan pilih metode pembayaran lain.</p>
            </div>
        <?php else: ?>
            <div class="bg-gray-50 dark:bg-gray-700/50 border-2 border-dashed border-gray-200 dark:border-gray-600 rounded-2xl p-6 mb-8 inline-block shadow-sm transition-colors duration-300">
                <div class="bg-white p-3 rounded-xl shadow-sm mb-4">
                    <img src="<?= htmlspecialchars($qr_url) ?>" alt="QRIS Code" class="w-48 h-48 mx-auto object-contain">
                </div>
                <p class="text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 tracking-wider mb-1 transition-colors duration-300">Total Pembayaran</p>
                <p class="text-2xl font-bold text-olive-700 dark:text-olive-400 transition-colors duration-300">Rp <?= number_format($total, 0, ',', '.') ?></p>
            </div>

            <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-xl p-4 text-left flex items-start gap-3 mb-8 transition-colors duration-300">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-xs text-blue-800 dark:text-blue-300 leading-relaxed transition-colors duration-300">
                    <strong>Simulasi Sandbox:</strong> Untuk mensimulasikan pembayaran, buka <a href="https://simulator.sandbox.midtrans.com/qris/index" target="_blank" class="font-bold underline text-blue-600 hover:text-blue-800">Simulator QRIS Midtrans</a>, lalu klik kanan QR code, salin alamat gambar, paste link QRIS di simulator.
                </p>
            </div>

            <form action="index.php?action=payment_gateway" method="POST" class="w-full">
                <?php csrf_field(); ?>
                <input type="hidden" name="confirm_qris" value="1">
                <button type="submit" class="block w-full bg-olive-700 text-white font-bold text-lg py-4 rounded-xl hover:bg-olive-800 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300">
                    Saya Sudah Membayar
                </button>
            </form>
        <?php endif; ?>
        
        <a href="index.php?page=checkout&step=3" class="block w-full mt-4 text-sm font-semibold text-gray-500 dark:text-gray-400 hover:text-olive-700 dark:hover:text-olive-400 transition-colors duration-300">
            Pilih metode pembayaran lain
        </a>
    </div>
</div>

<?php include BASE_PATH . '/app/Views/layouts/footer.php'; ?>
