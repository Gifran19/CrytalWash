<!-- 
  [PENTING] 
  Proyek ini menggunakan Tailwind CSS via NPM. 
  Pastikan Anda menjalankan perintah berikut di terminal (command line) untuk mengkompilasi file CSS:
  npx tailwindcss -i ./src/css/input.css -o ./public/assets/css/style.css --watch
-->
<?php
$hide_navbar = true; // Sembunyikan navbar global
include BASE_PATH . '/app/Views/layouts/header.php';

// Cek step saat ini (default step 1)
$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
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

<!-- Booking Top Navigation -->
<div class="bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 sticky top-0 z-50 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center py-4">
            <!-- Logo -->
            <div class="flex-shrink-0 mb-6 md:mb-0">
                <a href="index.php?page=home" class="font-serif text-3xl md:text-4xl font-bold text-olive-700 dark:text-olive-500 tracking-tight">CrystalWash</a>
            </div>
            
            <!-- Modern Stepper -->
            <div class="w-full md:w-1/2 max-w-lg px-4 md:px-0 mb-4 pb-6 md:mb-0 md:pb-0">
                <div class="relative flex justify-between items-center w-full">
                    <!-- Background Line -->
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-gray-100 dark:bg-gray-800 rounded-full z-0 transition-colors duration-300"></div>
                    <!-- Active Progress Line -->
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-olive-700 rounded-full z-0 transition-all duration-700 ease-in-out" style="width: <?= ($step - 1) * 50 ?>%"></div>
                    
                    <?php
                    $steps = ['Data', 'Layanan', 'Bayar'];
                    foreach ($steps as $index => $name):
                        $stepNum = $index + 1;
                        $isActive = $step == $stepNum;
                        $isCompleted = $step > $stepNum;
                        
                        $circleClass = 'w-8 h-8 rounded-full border-2 flex items-center justify-center z-10 font-bold text-xs transition-all duration-500 ';
                        $textClass = 'absolute -bottom-6 left-1/2 -translate-x-1/2 text-[10px] uppercase tracking-widest font-semibold transition-all duration-500 ';
                        
                        if ($isActive) {
                            $circleClass .= 'bg-olive-700 border-olive-700 text-white shadow-md scale-110';
                            $textClass .= 'text-olive-700';
                        } elseif ($isCompleted) {
                            $circleClass .= 'bg-olive-700 border-olive-700 text-white';
                            $textClass .= 'text-olive-700';
                        } else {
                            $circleClass .= 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-400 dark:text-gray-500';
                            $textClass .= 'text-gray-400 dark:text-gray-500';
                        }
                    ?>
                        <div class="relative flex flex-col items-center">
                            <div class="<?= $circleClass ?>">
                                <?php if ($isCompleted): ?>
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                <?php else: ?>
                                    <?= $stepNum ?>
                                <?php endif; ?>
                            </div>
                            <span class="<?= $textClass ?>"><?= $name ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Split Layout -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-0 h-full min-h-[calc(100vh-85px)]">
    <!-- Bagian Form (Kiri) - Super soft olive background with card -->
    <div class="bg-olive-50/40 dark:bg-gray-900 flex flex-col px-6 py-12 md:px-12 lg:px-20 relative transition-colors duration-300">
        <div class="flex-grow max-w-xl w-full mx-auto bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 md:p-12 animate-fade-in-down transition-colors duration-300">
            <?php 
            // Panggil tampilan berdasarkan step
            if ($step == 1) include BASE_PATH . '/app/Views/pages/booking_step1.php';
            elseif ($step == 2) include BASE_PATH . '/app/Views/pages/booking_step2.php';
            elseif ($step == 3) include BASE_PATH . '/app/Views/pages/payment.php';
            ?>
        </div>
    </div>

    <!-- Bagian Gambar / Summary (Kanan) -->
    <div class="<?= $step == 3 ? 'flex' : 'hidden md:flex' ?> flex-col bg-white dark:bg-gray-900 relative overflow-hidden min-h-[350px] md:min-h-0 md:h-full transition-colors duration-300">
        <?php if ($step == 3): 
            $order_summary = $_SESSION['order'] ?? [];
        ?>
            <!-- Booking Summary Card -->
            <div class="flex items-center justify-center p-8 bg-olive-50/20 dark:bg-gray-900 h-full w-full absolute inset-0 transition-colors duration-300">
                <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 w-full max-w-sm shadow-md transition-all duration-500">
                    <h3 class="font-bold text-gray-500 dark:text-gray-400 text-sm uppercase tracking-wider mb-5">Ringkasan Booking</h3>
                    
                    <div class="space-y-3 mb-5">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Layanan</span>
                            <strong class="text-gray-900 dark:text-gray-100 font-semibold"><?= htmlspecialchars($order_summary['layanan'] ?? '-') ?></strong>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Nomor Plat</span>
                            <strong class="text-gray-900 dark:text-gray-100 font-mono tracking-wider bg-white dark:bg-gray-900 px-2 py-0.5 rounded border border-gray-200 dark:border-gray-700"><?= htmlspecialchars($order_summary['plat'] ?? '-') ?></strong>
                        </div>
                    </div>
                    
                    <div class="border-b border-dashed border-gray-300 dark:border-gray-600 mb-5"></div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-300 font-semibold">Total Bayar</span>
                        <strong class="text-2xl font-bold text-olive-700 dark:text-olive-400">Rp <?= number_format($order_summary['total_price'] ?? 0, 0, ',', '.') ?></strong>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="absolute inset-0 w-full h-full">
                <!-- Using benefit_wash.png instead because it's higher quality based on previous tasks -->
                <img src="assets/img/benefit_wash.png" alt="Premium Car Wash" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-olive-900/40 to-transparent mix-blend-multiply"></div>
            </div>
            <div class="absolute bottom-0 left-0 w-full p-12 text-white z-10 text-center">
                <h2 class="font-serif text-4xl md:text-5xl font-bold mb-4 drop-shadow-md">Kemewahan untuk<br>Kendaraan Anda</h2>
                <p class="text-olive-100 font-medium tracking-wide drop-shadow text-lg">Layanan eksklusif setara spa otomotif.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include BASE_PATH . '/app/Views/layouts/footer.php'; ?>
