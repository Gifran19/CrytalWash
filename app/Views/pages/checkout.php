<?php
$hide_navbar = true; // Sembunyikan navbar global
include BASE_PATH . '/app/Views/layouts/header.php';

// Cek step saat ini (default step 1)
$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
?>

<!-- Booking Top Navigation -->
<div class="bg-[#f5f5f5] border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center h-auto md:h-24 py-4 md:py-0">
            <!-- Logo -->
            <div class="flex-shrink-0 mb-4 md:mb-0">
                <a href="index.php?page=home" class="font-serif text-4xl md:text-5xl font-bold text-gray-900 tracking-tight">Booking</a>
            </div>
            
            <!-- Stepper Pills -->
            <div class="flex space-x-2 md:space-x-4 overflow-x-auto pb-2 md:pb-0 w-full md:w-auto justify-start md:justify-end">
                <div class="whitespace-nowrap px-6 md:px-8 py-2 rounded-full text-xs md:text-sm font-semibold border transition-colors <?= $step == 1 ? 'bg-olive-700 text-white border-olive-700' : 'bg-transparent text-gray-800 border-gray-400' ?>">Data</div>
                <div class="whitespace-nowrap px-6 md:px-8 py-2 rounded-full text-xs md:text-sm font-semibold border transition-colors <?= $step == 2 ? 'bg-olive-700 text-white border-olive-700' : 'bg-transparent text-gray-800 border-gray-400' ?>">Kendaraan</div>
                <div class="whitespace-nowrap px-6 md:px-8 py-2 rounded-full text-xs md:text-sm font-semibold border transition-colors <?= $step == 3 ? 'bg-olive-700 text-white border-olive-700' : 'bg-transparent text-gray-800 border-gray-400' ?>">Layanan</div>
                <div class="whitespace-nowrap px-6 md:px-8 py-2 rounded-full text-xs md:text-sm font-semibold border transition-colors <?= $step == 4 ? 'bg-olive-700 text-white border-olive-700' : 'bg-transparent text-gray-800 border-gray-400' ?>">Pembayaran</div>
            </div>
        </div>
    </div>
</div>

<!-- Main Split Layout -->
<div class="flex flex-col md:flex-row min-h-[calc(100vh-96px)]">
    <!-- Bagian Form (Kiri) -->
    <div class="w-full md:w-1/2 bg-white flex flex-col px-6 py-12 md:px-12 lg:px-20 border-r border-gray-200">
        <div class="flex-grow max-w-lg w-full mx-auto">
            <?php 
            // Panggil tampilan berdasarkan step
            if ($step == 1) include BASE_PATH . '/app/Views/pages/booking_step1.php';
            elseif ($step == 2) include BASE_PATH . '/app/Views/pages/booking_step2.php';
            elseif ($step == 3) include BASE_PATH . '/app/Views/pages/booking_step3.php';
            elseif ($step == 4) include BASE_PATH . '/app/Views/pages/payment.php';
            ?>
        </div>
    </div>

    <!-- Bagian Gambar / Summary (Kanan) -->
    <div class="w-full md:w-1/2 flex flex-col">
        <?php if ($step == 4): 
            $order_summary = $_SESSION['order'] ?? [];
        ?>
            <!-- Booking Summary Card -->
            <div class="bg-gray-50 flex-grow flex items-center justify-center p-8">
                <div class="bg-white rounded-2xl p-10 w-full max-w-md shadow-2xl">
                    <h3 class="font-serif text-3xl font-bold text-gray-900 border-b border-gray-100 pb-6 mb-6">Ringkasan Booking</h3>
                    
                    <div class="flex justify-between items-center mb-4 text-sm">
                        <span class="text-gray-500">Nama:</span>
                        <strong class="text-gray-900"><?= htmlspecialchars($order_summary['nama'] ?? 'N/A') ?></strong>
                    </div>
                    
                    <div class="flex justify-between items-center mb-4 text-sm">
                        <span class="text-gray-500">Kendaraan:</span>
                        <strong class="text-gray-900 text-right"><?= htmlspecialchars($order_summary['tipe'] ?? 'N/A') ?><br><span class="text-xs text-gray-400">(<?= htmlspecialchars($order_summary['plat'] ?? 'N/A') ?>)</span></strong>
                    </div>
                    
                    <div class="flex justify-between items-center mb-4 text-sm">
                        <span class="text-gray-500">Layanan:</span>
                        <strong class="text-gray-900"><?= htmlspecialchars($order_summary['layanan'] ?? 'N/A') ?></strong>
                    </div>
                    
                    <hr class="my-6 border-dashed border-gray-300">
                    
                    <div class="flex justify-between items-center text-lg font-bold">
                        <span class="text-gray-700">Total Pembayaran:</span>
                        <strong class="text-olive-700">Rp <?= number_format($order_summary['total_price'] ?? 0, 0, ',', '.') ?></strong>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="h-[60%] w-full relative">
                <!-- Using benefit_wash.png instead because it's higher quality based on previous tasks -->
                <img src="assets/img/benefit_wash.png" alt="Premium Car Wash" class="absolute inset-0 w-full h-full object-cover">
            </div>
            <div class="h-[40%] w-full bg-white flex flex-col items-center justify-center p-8">
                <h2 class="font-serif text-4xl md:text-5xl font-bold text-gray-900 text-center">Mobil Bersih, Hidup Bahagia</h2>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include BASE_PATH . '/app/Views/layouts/footer.php'; ?>
