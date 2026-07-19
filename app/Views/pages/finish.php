<?php
$hide_navbar = true; // Sembunyikan navbar global
require_once BASE_PATH . '/app/Config/database.php';

// Ambil data dari session
$order = $_SESSION['order'] ?? [];
$id_booking = $order['id_booking'] ?? null;

// Ambil data dari database jika ada
$booking_data = [];
if ($id_booking) {
    $stmt = $conn->prepare("SELECT 
            b.estimasi_waktu, b.tanggal, b.status,
            l.nama_layanan,
            k.no_plat, k.jenis AS tipe_kendaraan,
            a.nomor_antrian, a.status AS antrian_status,
            p.total AS total_harga,
            pl.nama, pl.email
        FROM booking b
        JOIN layanan l ON b.id_layanan = l.id_layanan
        JOIN kendaraan k ON b.id_kendaraan = k.id_kendaraan
        LEFT JOIN antrian a ON b.id_booking = a.id_booking
        LEFT JOIN pembayaran p ON b.id_booking = p.id_booking
        LEFT JOIN pelanggan pl ON b.id_pelanggan = pl.id_pelanggan
        WHERE b.id_booking = :id_booking");
    $stmt->execute(['id_booking' => $id_booking]);
    $booking_data = $stmt->fetch() ?: [];
}

// Pastikan booking valid dan ada di database
if (!$id_booking || empty($booking_data)) {
    header("Location: index.php?page=home");
    exit();
}

// Data untuk ditampilkan
$selected_service = $booking_data['nama_layanan'];
$work_duration    = $booking_data['estimasi_waktu'];
$name             = $booking_data['nama'] ?? 'Guest';
$date             = $booking_data['tanggal'];
$email            = $booking_data['email'] ?? '-';
$vehicle          = $booking_data['tipe_kendaraan'] ?? 'Mobil';
$total            = $booking_data['total_harga'] ?? 0;

// Hitung nomor antrian
$queue_number     = $booking_data['nomor_antrian'] ?? null;
$is_motor_bk      = (strpos(strtolower($vehicle), 'motor') !== false || strpos(strtolower($vehicle), 'motorcycle') !== false);
$prefix_bk        = $is_motor_bk ? 'M' : 'C';
$queue_display    = $queue_number ? $prefix_bk . '-' . str_pad($queue_number, 2, '0', STR_PAD_LEFT) : '-';

// Hitung perkiraan waktu selesai
$start_time = $_SESSION['order']['start_time'] ?? time();
$estimated_finish_time = $start_time + ($work_duration * 60);
$formatted_finish_time = date('H:i', $estimated_finish_time) . ' WIB';

?>
<?php include BASE_PATH . '/app/Views/layouts/header.php'; ?>

<!-- Container Utama dengan Dukungan Light/Dark Mode -->
<div class="bg-olive-50/40 dark:bg-slate-900 flex-grow flex items-center justify-center py-12 px-4 md:p-8 w-full min-h-screen transition-colors duration-300">
    <!-- Kontainer Utama (Grid 2 Kolom pada md, 1 Kolom pada mobile) -->
    <div class="max-w-5xl w-full grid grid-cols-1 md:grid-cols-2 gap-6 items-stretch">

        <!-- ===== KOLOM KIRI: Detail & Estimasi ===== -->
        <div class="flex flex-col gap-6">
            
            <!-- Card 1: Detail Booking -->
            <div class="bg-white dark:bg-gray-800 border border-transparent dark:border-gray-700 rounded-xl shadow-lg p-6 sm:p-8 flex-grow transition-colors duration-300">
                <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-700 pb-4 mb-6">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100"><?= trans('fin_detail_title') ?></h2>
                    <a href="index.php?page=invoice" class="text-xs font-bold bg-olive-100 dark:bg-olive-900/40 text-olive-700 dark:text-olive-300 px-3 py-1.5 rounded-lg hover:bg-olive-200 dark:hover:bg-olive-900/60 transition-colors"><?= trans('fin_btn_invoice') ?></a>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Data 1 -->
                    <div>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider mb-1.5"><?= trans('fin_label_name') ?></p>
                        <div class="bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-700 rounded-md p-3 text-sm font-medium text-gray-800 dark:text-gray-100"><?= htmlspecialchars($name) ?></div>
                    </div>
                    <!-- Data 2 -->
                    <div>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider mb-1.5"><?= trans('fin_label_vehicle') ?></p>
                        <div class="bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-700 rounded-md p-3 text-sm font-medium text-gray-800 dark:text-gray-100"><?= htmlspecialchars($vehicle) ?></div>
                    </div>
                    <!-- Data 3 -->
                    <div>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider mb-1.5"><?= trans('fin_label_date') ?></p>
                        <div class="bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-700 rounded-md p-3 text-sm font-medium text-gray-800 dark:text-gray-100"><?= date('d M Y', strtotime($date)) ?></div>
                    </div>
                    <!-- Data 4 -->
                    <div>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider mb-1.5"><?= trans('fin_label_service') ?></p>
                        <div class="bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-700 rounded-md p-3 text-sm font-medium text-gray-800 dark:text-gray-100"><?= htmlspecialchars($selected_service) ?></div>
                    </div>
                    <!-- Data 5 -->
                    <div class="sm:col-span-2">
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider mb-1.5"><?= trans('fin_label_email') ?></p>
                        <div class="bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-700 rounded-md p-3 text-sm font-medium text-gray-800 dark:text-gray-100"><?= htmlspecialchars($email) ?></div>
                    </div>
                    <!-- Data 6 -->
                    <div class="sm:col-span-2">
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider mb-1.5"><?= trans('fin_label_total') ?></p>
                        <div class="bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-700 rounded-md p-3 text-lg font-bold text-[#4a5d23] dark:text-olive-400">Rp <?= number_format($total, 0, ',', '.') ?></div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Nomor Antrian & Estimasi Waktu -->
            <div class="bg-white dark:bg-gray-800 border border-transparent dark:border-gray-700 rounded-xl shadow-lg p-6 sm:p-8 flex flex-col items-center text-center transition-colors duration-300">
                <!-- Tampilan Nomor Antrian -->
                <div class="mb-6 w-full pb-6 border-b border-gray-100 dark:border-gray-700">
                    <span class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider block mb-2">Nomor Antrian Anda</span>
                    <?php
                    $badge_bg = $is_motor_bk ? 'bg-green-50 dark:bg-green-950/30' : 'bg-blue-50 dark:bg-blue-950/30';
                    $badge_border = $is_motor_bk ? 'border-green-200 dark:border-green-800' : 'border-blue-200 dark:border-blue-800';
                    $badge_text = $is_motor_bk ? 'text-green-700 dark:text-green-400' : 'text-blue-700 dark:text-blue-400';
                    ?>
                    <span class="inline-block <?= $badge_bg ?> border <?= $badge_border ?> <?= $badge_text ?> px-8 py-3 rounded-2xl font-bold text-4xl font-mono shadow-sm tracking-wide">
                        <?= $queue_display ?>
                    </span>
                </div>

                <!-- Ikon Jam Hijau Zaitun -->
                <div class="w-14 h-14 bg-[#4a5d23]/10 dark:bg-olive-900/30 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-[#4a5d23] dark:text-olive-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-3"><?= sprintf(trans('fin_est_time'), $work_duration) ?></h3>
                
                <!-- Kotak Hijau Muda -->
                <div class="bg-[#4a5d23]/10 dark:bg-olive-900/20 text-[#4a5d23] dark:text-olive-400 px-5 py-3 rounded-lg text-sm font-medium w-full mt-1 border border-[#4a5d23]/20 dark:border-olive-900/30">
                    <?= trans('fin_est_ready') ?> <br class="sm:hidden"><strong class="text-base ml-1"><?= $formatted_finish_time ?></strong>
                </div>
            </div>

        </div>

        <!-- ===== KOLOM KANAN: Form Ulasan ===== -->
        <div class="bg-white dark:bg-gray-800 border border-transparent dark:border-gray-700 rounded-xl shadow-lg p-6 sm:p-8 flex flex-col h-full transition-colors duration-300">
            <div class="text-center mb-8 mt-4">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-gray-100 mb-3"><?= trans('fin_rev_title') ?></h2>
                <p class="text-sm text-gray-500 dark:text-gray-400"><?= trans('fin_rev_subtitle') ?></p>
            </div>

            <form action="index.php?action=submit_review" method="POST" class="flex-grow flex flex-col">
                <?php csrf_field(); ?>
                <?php if ($id_booking): ?>
                    <input type="hidden" name="id_booking" value="<?= htmlspecialchars($id_booking) ?>">
                <?php else: ?>
                    <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg text-sm text-center mb-6">
                        <?= trans('fin_err_id') ?>
                    </div>
                <?php endif; ?>

                <input type="hidden" name="rating" id="rating-input" value="0" required>

                <!-- Bintang Interaktif -->
                <div class="flex justify-center gap-2 mb-8" id="star-container">
                    <!-- Data-value digunakan untuk identifikasi bintang via JavaScript -->
                    <svg class="star w-12 h-12 text-gray-300 dark:text-gray-600 cursor-pointer transition-colors duration-200 hover:scale-110 transform" data-value="1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="star w-12 h-12 text-gray-300 dark:text-gray-600 cursor-pointer transition-colors duration-200 hover:scale-110 transform" data-value="2" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="star w-12 h-12 text-gray-300 dark:text-gray-600 cursor-pointer transition-colors duration-200 hover:scale-110 transform" data-value="3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="star w-12 h-12 text-gray-300 dark:text-gray-600 cursor-pointer transition-colors duration-200 hover:scale-110 transform" data-value="4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="star w-12 h-12 text-gray-300 dark:text-gray-600 cursor-pointer transition-colors duration-200 hover:scale-110 transform" data-value="5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                </div>

                <!-- Form Saran -->
                <div class="flex-grow flex flex-col justify-between">
                    <div class="mb-6">
                        <textarea name="komentar" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-4 text-sm focus:ring-2 focus:ring-[#4a5d23] focus:border-transparent outline-none resize-none placeholder:text-gray-400 dark:placeholder:text-gray-500" rows="5" placeholder="<?= trans('fin_rev_placeholder') ?>"></textarea>
                    </div>

                    <!-- Bagian Tombol -->
                    <div class="space-y-3">
                        <!-- Tombol Kirim Ulasan -->
                        <button type="submit" <?= !$id_booking ? 'disabled' : '' ?> class="w-full bg-olive-700 hover:bg-olive-800 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold py-3.5 px-4 rounded-xl transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5">
                            <?= trans('fin_rev_submit') ?>
                        </button>
                        <!-- Tombol Kembali ke Home (Lewati) -->
                        <a href="index.php?page=home" class="w-full flex items-center justify-center bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 text-gray-700 font-bold py-3.5 px-4 rounded-xl transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5 mt-3">
                            <?= trans('fin_rev_skip') ?>
                        </a>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- Script JavaScript untuk Interaksi Bintang -->
<script>
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('rating-input');
    let selectedValue = 0; // Menyimpan nilai rating yang dipilih (diklik)

    stars.forEach(star => {
        // Ketika kursor menyorot bintang (Hover)
        star.addEventListener('mouseover', function() {
            const hoverValue = this.getAttribute('data-value');
            highlightStars(hoverValue);
        });

        // Ketika kursor keluar dari bintang
        star.addEventListener('mouseout', function() {
            highlightStars(selectedValue);
        });

        // Ketika bintang diklik
        star.addEventListener('click', function() {
            selectedValue = this.getAttribute('data-value');
            ratingInput.value = selectedValue; // Set ke input hidden
            highlightStars(selectedValue);
        });
    });

    // Fungsi untuk mewarnai bintang
    function highlightStars(value) {
        stars.forEach(s => {
            if (parseInt(s.getAttribute('data-value')) <= parseInt(value)) {
                s.classList.remove('text-gray-300', 'dark:text-gray-600');
                s.classList.add('text-yellow-400'); // Berubah jadi kuning
            } else {
                s.classList.remove('text-yellow-400');
                s.classList.add('text-gray-300', 'dark:text-gray-600'); // Tetap abu-abu
            }
        });
    }
</script>

<?php include BASE_PATH . '/app/Views/layouts/footer.php'; ?>
