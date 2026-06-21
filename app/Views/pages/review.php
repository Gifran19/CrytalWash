<?php
$hide_navbar = true; // Sembunyikan navbar global
// Ambil data ID dari session
$id_booking = $_SESSION['order']['id_booking'] ?? null;
?>
<?php include BASE_PATH . '/app/Views/layouts/header.php'; ?>

<!-- Banner Section -->
<div class="relative w-full h-[250px] flex items-center justify-center overflow-hidden">
    <img src="assets/img/benefit_wash.png" alt="Progress" class="absolute inset-0 w-full h-full object-cover">
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
                <div class="w-14 h-14 rounded-full bg-white border border-olive-700 flex items-center justify-center z-10"></div>
                <span class="mt-3 text-[13px] font-bold text-black text-center leading-tight">Selesai</span>
            </div>
        </div>
    </div>

    <!-- Review Form Card -->
    <div class="bg-white rounded-2xl border border-gray-100 p-10 md:p-14 shadow-xl max-w-2xl mx-auto">
        <h2 class="font-serif text-2xl font-bold text-gray-900 mb-2">Bagaimana Pengalaman Booking Anda?</h2>
        <p class="text-sm font-sans text-gray-500 mb-8">Bantu kami untuk terus meningkatkan kualitas layanan.</p>
        <hr class="border-gray-100 mb-8">

        <form action="index.php?action=submit_review" method="POST">
            <!-- Peringatan jika belum ada ID Booking -->
            <?php if (!$id_booking): ?>
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg text-sm text-center mb-6">
                    ID Booking tidak ditemukan. Anda tidak dapat mengirim ulasan.
                </div>
            <?php else: ?>
                <input type="hidden" name="id_booking" value="<?= htmlspecialchars($id_booking) ?>">
            <?php endif; ?>

            <!-- Rating Stars -->
            <div class="flex flex-col items-center mb-8">
                <div class="flex flex-row-reverse justify-center group gap-6">
                    <input type="radio" id="star5" name="rating" value="5" class="peer hidden">
                    <label for="star5" class="text-[3.5rem] leading-none text-gray-200 cursor-pointer peer-hover:text-olive-500 peer-checked:text-olive-500 hover:text-olive-500 transition-colors duration-300">★</label>

                    <input type="radio" id="star4" name="rating" value="4" class="peer hidden">
                    <label for="star4" class="text-[3.5rem] leading-none text-gray-200 cursor-pointer peer-hover:text-olive-500 peer-checked:text-olive-500 hover:text-olive-500 transition-colors duration-300">★</label>

                    <input type="radio" id="star3" name="rating" value="3" class="peer hidden">
                    <label for="star3" class="text-[3.5rem] leading-none text-gray-200 cursor-pointer peer-hover:text-olive-500 peer-checked:text-olive-500 hover:text-olive-500 transition-colors duration-300">★</label>

                    <input type="radio" id="star2" name="rating" value="2" class="peer hidden">
                    <label for="star2" class="text-[3.5rem] leading-none text-gray-200 cursor-pointer peer-hover:text-olive-500 peer-checked:text-olive-500 hover:text-olive-500 transition-colors duration-300">★</label>

                    <input type="radio" id="star1" name="rating" value="1" class="peer hidden">
                    <label for="star1" class="text-[3.5rem] leading-none text-gray-200 cursor-pointer peer-hover:text-olive-500 peer-checked:text-olive-500 hover:text-olive-500 transition-colors duration-300">★</label>
                </div>
            </div>

            <!-- Comment Box -->
            <div class="mb-10">
                <textarea name="komentar" id="komentar" rows="4" placeholder="Tulis saran atau masukan anda di sini." class="form-input resize-none"></textarea>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" <?= !$id_booking ? 'disabled' : '' ?> class="w-full btn-primary text-lg py-4 disabled:opacity-50 disabled:cursor-not-allowed">
                    Kirim Ulasan
                </button>
                <div class="text-center mt-6">
                    <button type="submit" name="skip" value="1" class="text-sm font-medium text-gray-400 hover:text-gray-600 bg-transparent border-none cursor-pointer transition-colors duration-300">
                        Lewati
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
/* Memastikan efek bintang menyala dari kiri ke kanan saat hover bekerja dengan flex-row-reverse */
label:hover ~ label {
    color: #839665; /* olive-500 */
}
</style>

<?php include BASE_PATH . '/app/Views/layouts/footer.php'; ?>
