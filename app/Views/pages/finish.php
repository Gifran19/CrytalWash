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
    <div class="absolute inset-0 bg-white/70"></div> <!-- Make overlay lighter -->
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
    <div class="bg-white rounded-[1.5rem] border border-gray-400 p-10 md:p-14 shadow-sm">
        <h2 class="font-sans text-3xl md:text-4xl font-bold text-black mb-10 mt-4">Layanan Selesai !</h2>
        
        <p class="text-[15px] font-serif text-black mb-5">Terimakasih telah menggunakan layanan kami.</p>
        <p class="text-[15px] font-serif text-black mb-14">Kami tunggu pesanan Anda berikutnya.</p>
        
        <!-- Buttons -->
        <div class="flex flex-col sm:flex-row gap-5 max-w-lg mt-8">
            <a href="index.php?page=home" class="w-full sm:w-1/2 bg-olive-700 text-white font-bold py-3.5 rounded-2xl hover:bg-olive-800 transition-colors text-center shadow-sm">
                Home
            </a>
            <a href="index.php?page=invoice" class="w-full sm:w-1/2 bg-white border border-olive-700 text-olive-700 font-bold py-3.5 rounded-2xl hover:bg-olive-50 transition-colors shadow-sm flex items-center justify-center">
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
