<?php
$hide_navbar = true; // Sembunyikan navbar global
// Ambil data ID dari session
$id_booking = $_SESSION['order']['id_booking'] ?? null;
?>
<?php include BASE_PATH . '/app/Views/layouts/header.php'; ?>

<!-- Banner Section -->
<div class="relative w-full h-[250px] flex items-center justify-center overflow-hidden">
    <img src="assets/img/benefit_wash.png" alt="Progress" class="absolute inset-0 w-full h-full object-cover">
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
                <div class="w-14 h-14 rounded-full bg-white border border-olive-700 flex items-center justify-center z-10"></div>
                <span class="mt-3 text-[13px] font-bold text-black text-center leading-tight">Selesai</span>
            </div>
        </div>
    </div>

    <!-- Review Form Card -->
    <div class="bg-white rounded-[1.5rem] border border-gray-400 p-8 md:p-10 shadow-sm">
        <h2 class="font-sans text-xl font-bold text-black mb-3">Bagaimana Pengalaman Booking Anda?</h2>
        <hr class="border-gray-400 mb-6">
        <p class="text-xs font-serif text-black mb-8">Bantu kami untuk terus meningkatkan kualitas layanan.</p>

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
                    <label for="star5" class="text-[3.5rem] leading-none text-[#d1d5db] cursor-pointer peer-hover:text-[#b4b8bf] peer-checked:text-[#b4b8bf] hover:text-[#b4b8bf] transition-colors">★</label>

                    <input type="radio" id="star4" name="rating" value="4" class="peer hidden">
                    <label for="star4" class="text-[3.5rem] leading-none text-[#d1d5db] cursor-pointer peer-hover:text-[#b4b8bf] peer-checked:text-[#b4b8bf] hover:text-[#b4b8bf] transition-colors">★</label>

                    <input type="radio" id="star3" name="rating" value="3" class="peer hidden">
                    <label for="star3" class="text-[3.5rem] leading-none text-[#d1d5db] cursor-pointer peer-hover:text-[#b4b8bf] peer-checked:text-[#b4b8bf] hover:text-[#b4b8bf] transition-colors">★</label>

                    <input type="radio" id="star2" name="rating" value="2" class="peer hidden">
                    <label for="star2" class="text-[3.5rem] leading-none text-[#d1d5db] cursor-pointer peer-hover:text-[#b4b8bf] peer-checked:text-[#b4b8bf] hover:text-[#b4b8bf] transition-colors">★</label>

                    <input type="radio" id="star1" name="rating" value="1" class="peer hidden">
                    <label for="star1" class="text-[3.5rem] leading-none text-[#d1d5db] cursor-pointer peer-hover:text-[#b4b8bf] peer-checked:text-[#b4b8bf] hover:text-[#b4b8bf] transition-colors">★</label>
                </div>
            </div>

            <!-- Comment Box -->
            <div class="mb-8">
                <textarea name="komentar" id="komentar" rows="3" placeholder="Tulis saran atau masukan anda di sini." class="w-full px-5 py-4 border border-gray-400 rounded-xl focus:ring-2 focus:ring-olive-700 focus:border-olive-700 transition-colors resize-none text-sm font-serif text-gray-700 placeholder-gray-400"></textarea>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" <?= !$id_booking ? 'disabled' : '' ?> class="w-full bg-olive-700 text-white font-bold py-3.5 rounded-xl hover:bg-olive-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed shadow-md text-lg">
                    Kirim
                </button>
                <div class="text-center mt-3">
                    <button type="submit" name="skip" value="1" class="text-[10px] text-white hover:text-gray-300 bg-transparent border-none cursor-pointer">
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
    color: #b4b8bf; /* slightly darker gray */
}
</style>

<?php include BASE_PATH . '/app/Views/layouts/footer.php'; ?>
