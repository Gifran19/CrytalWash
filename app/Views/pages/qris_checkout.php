<?php
$hide_navbar = true; // Sembunyikan navbar global
include BASE_PATH . '/app/Views/layouts/header.php';

$order = $_SESSION['order'] ?? [];
if (empty($order)) {
    header("Location: index.php?page=home");
    exit;
}
$total = $order['total_price'] ?? 0;
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

<div class="min-h-screen flex items-center justify-center bg-olive-50 p-4 md:p-8">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl p-8 md:p-10 text-center animate-fade-in-down border border-gray-100">
        <div class="mb-6">
            <div class="w-16 h-16 bg-olive-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-olive-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            </div>
            <h1 class="font-serif text-3xl font-bold text-olive-800 mb-2">Pembayaran QRIS</h1>
            <p class="text-sm text-gray-500">Scan QR Code di bawah menggunakan M-Banking atau E-Wallet Anda.</p>
        </div>

        <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl p-6 mb-8 inline-block shadow-sm">
            <div class="bg-white p-3 rounded-xl shadow-sm mb-4">
                <!-- Gunakan gambar dummy QRIS yang sudah ada -->
                <img src="assets/img/qris-dummy.png" alt="QRIS Code" class="w-48 h-48 mx-auto object-contain">
            </div>
            <p class="text-xs uppercase font-semibold text-gray-500 tracking-wider mb-1">Total Pembayaran</p>
            <p class="text-2xl font-bold text-olive-700">Rp <?= number_format($total, 0, ',', '.') ?></p>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-left flex items-start gap-3 mb-8">
            <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="text-xs text-blue-800 leading-relaxed">
                <strong>Instruksi:</strong> Setelah melakukan pembayaran, silakan klik tombol di bawah dan tunjukkan bukti transfer kepada kasir kami untuk memproses pesanan Anda.
            </p>
        </div>

        <a href="index.php?action=process_order" class="block w-full bg-olive-700 text-white font-bold text-lg py-4 rounded-xl hover:bg-olive-800 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300">
            Saya Sudah Membayar
        </a>
        
        <a href="index.php?page=checkout&step=4" class="block w-full mt-4 text-sm font-semibold text-gray-500 hover:text-olive-700 transition-colors">
            Pilih metode pembayaran lain
        </a>
    </div>
</div>

<?php include BASE_PATH . '/app/Views/layouts/footer.php'; ?>
