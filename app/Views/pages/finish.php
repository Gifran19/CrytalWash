<?php
$hide_navbar = true; // Sembunyikan navbar global
// Bersihkan session setelah selesai (opsional)
// session_unset();
// session_destroy();
?>
<?php include BASE_PATH . '/app/Views/layouts/header.php'; ?>

<!-- Banner Section -->
<div class="relative w-full h-[250px] flex items-center justify-center overflow-hidden">
    <img src="assets/img/benefit_wash.png" alt="Finished" class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0 bg-olive-900/60 mix-blend-multiply"></div>
    <h1 class="relative z-10 text-5xl md:text-6xl text-white font-serif font-bold tracking-tight">Booking Berhasil</h1>
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
                <div class="w-14 h-14 rounded-full bg-olive-700 border-2 border-olive-700 flex items-center justify-center z-10"></div>
                <span class="mt-3 text-[13px] font-bold text-black text-center leading-tight">Ulasan</span>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-14 h-14 rounded-full bg-olive-700 border-2 border-olive-700 flex items-center justify-center z-10"></div>
                <span class="mt-3 text-[13px] font-bold text-black text-center leading-tight">Selesai</span>
            </div>
        </div>
    </div>

    <!-- Success Message Card -->
    <div class="bg-white rounded-2xl border border-gray-100 p-12 md:p-16 shadow-xl text-center max-w-2xl mx-auto">
        <h2 class="font-serif text-3xl md:text-4xl font-bold text-gray-900 mb-6">Layanan Selesai !</h2>
        
        <p class="text-base font-sans text-gray-600 mb-2">Terima kasih telah menggunakan layanan kami.</p>
        <p class="text-base font-sans text-gray-600 mb-12">Kami tunggu kedatangan Anda berikutnya.</p>
        
        <!-- Buttons -->
        <div class="flex flex-col sm:flex-row gap-5 max-w-lg mx-auto mt-8">
            <a href="index.php?page=home" class="w-full sm:w-1/2 btn-primary text-lg">
                Home
            </a>
            <a href="index.php?page=invoice" class="w-full sm:w-1/2 btn-secondary text-lg">
                Cetak Invoice
            </a>
        </div>
    </div>
</div>

<style>
/* Hide unnecessary elements when printing the invoice */
@media print {
    body * {
        visibility: hidden;
    }
    .bg-white.rounded-\[1\.5rem\].border {
        visibility: visible;
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        border: none;
    }
    .bg-white.rounded-\[1\.5rem\].border * {
        visibility: visible;
    }
    button, a, .relative.mb-16 {
        display: none !important;
    }
}
</style>

<?php include BASE_PATH . '/app/Views/layouts/footer.php'; ?>
