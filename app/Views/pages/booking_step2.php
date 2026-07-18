<?php
// Koneksi database
include BASE_PATH . '/app/Config/database.php'; 

date_default_timezone_set('Asia/Jakarta');
$hari_ini = date('Y-m-d');

// 1. HITUNG ANTREAN OTOMATIS DARI DATABASE — TERPISAH PER JENIS KENDARAAN
// Antrian Motor
$stmt_motor = $conn->prepare("SELECT COUNT(*) as total FROM antrian a
    JOIN booking b ON a.id_booking = b.id_booking
    JOIN kendaraan k ON b.id_kendaraan = k.id_kendaraan
    WHERE b.tanggal = :tanggal
    AND a.status IN ('menunggu', 'proses')
    AND k.jenis = 'Motor'");
$stmt_motor->execute(['tanggal' => $hari_ini]);
$jumlah_antrean_motor = (int)($stmt_motor->fetch()['total'] ?? 0);
$next_motor = $jumlah_antrean_motor + 1;

// Antrian Mobil
$stmt_mobil = $conn->prepare("SELECT COUNT(*) as total FROM antrian a
    JOIN booking b ON a.id_booking = b.id_booking
    JOIN kendaraan k ON b.id_kendaraan = k.id_kendaraan
    WHERE b.tanggal = :tanggal
    AND a.status IN ('menunggu', 'proses')
    AND k.jenis = 'Mobil'");
$stmt_mobil->execute(['tanggal' => $hari_ini]);
$jumlah_antrean_mobil = (int)($stmt_mobil->fetch()['total'] ?? 0);
$next_mobil = $jumlah_antrean_mobil + 1;

$jumlah_petugas = 2;
// Estimasi per jenis kendaraan — berdasarkan rata-rata durasi layanan aktual:
// Motor: (Cuci Motor Standar 20 menit + Cuci Motor Besar 32 menit) / 2 = 26 menit
// Mobil: (Cuci Mobil Standar 45 menit + Cuci Mobil Besar 60 menit) / 2 = 52 menit
$avg_motor = 26;
$avg_mobil = 52;
$estimasi_menit_motor = ceil(max($jumlah_antrean_motor, 1) / $jumlah_petugas) * $avg_motor;
$estimasi_menit_mobil = ceil(max($jumlah_antrean_mobil, 1) / $jumlah_petugas) * $avg_mobil;
$saran_jam_motor = date('H:i', strtotime("+$estimasi_menit_motor minutes"));
$saran_jam_mobil = date('H:i', strtotime("+$estimasi_menit_mobil minutes"));

// 2. AMBIL DATA LAYANAN DARI DATABASE
$stmt_lay = $conn->query("SELECT id_layanan, nama_layanan, harga, jenis_kendaraan FROM layanan ORDER BY harga ASC");
$all_services = $stmt_lay->fetchAll();

$services_mobil = [];
$services_motor = [];
foreach ($all_services as $s) {
    $jenis = strtolower($s['jenis_kendaraan']);
    if (strpos($jenis, 'motor') !== false || strpos($jenis, 'motorcycle') !== false) {
        $services_motor[] = $s;
    } else {
        $services_mobil[] = $s;
    }
}
?>

<?php
$error = $_GET['error'] ?? null;
if ($error === 'duplicate_plat'): ?>
    <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl text-sm mb-6 shadow-sm flex items-start">
        <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <span>Kendaraan dengan plat nomor tersebut sudah terdaftar di sistem.</span>
    </div>
<?php elseif ($error === 'empty_plat'): ?>
    <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl text-sm mb-6 shadow-sm flex items-start">
        <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <span>Nomor plat kendaraan wajib diisi.</span>
    </div>
<?php elseif ($error === 'long_plat'): ?>
    <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl text-sm mb-6 shadow-sm flex items-start">
        <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <span>Nomor plat kendaraan tidak boleh lebih dari 20 karakter.</span>
    </div>
<?php elseif ($error === 'invalid_plat'): ?>
    <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl text-sm mb-6 shadow-sm flex items-start">
        <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <span>Format nomor plat tidak valid. Contoh format yang benar: B 1234 ABC</span>
    </div>
<?php endif; ?>

<div class="mb-8">
    <h2 class="font-serif text-4xl font-bold text-olive-700 dark:text-olive-400 tracking-tight"><?= trans('bk_step2_title') ?></h2>
    <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm"><?= trans('bk_step2_subtitle') ?></p>
</div>

<form action="index.php?action=auth_booking" method="POST" class="space-y-6">
    <?php csrf_field(); ?>
    <input type="hidden" name="next_step" value="3">
    
    <!-- Vehicle Type: Mobil vs Motor -->
    <div>
        <label class="text-xs uppercase font-semibold text-gray-600 dark:text-gray-400 tracking-wider mb-3 block"><?= trans('bk_step2_choose_vehicle') ?></label>
        <div class="grid grid-cols-2 gap-4">
            <!-- Mobil -->
            <label class="relative cursor-pointer group">
                <input type="radio" name="tipe" value="Mobil" <?= ($_SESSION['order']['tipe'] ?? 'Mobil') === 'Mobil' ? 'checked' : '' ?> onchange="updateDropdown('Mobil')" class="peer sr-only">
                <div class="h-full p-5 border border-gray-200 dark:border-gray-700 rounded-2xl flex flex-col items-center justify-center transition-all duration-300 peer-checked:border-olive-700 dark:peer-checked:border-olive-500 peer-checked:ring-2 peer-checked:ring-olive-400 peer-checked:bg-olive-50 dark:peer-checked:bg-gray-800/80 bg-white dark:bg-gray-800 shadow-sm group-hover:shadow-md group-hover:-translate-y-0.5">
                    <svg class="w-8 h-8 mb-2 text-gray-600 dark:text-gray-400 peer-checked:text-olive-700 dark:peer-checked:text-olive-400 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 18h14M5 18a2 2 0 002-2v-4M5 18a2 2 0 01-2-2v-4a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M19 18a2 2 0 002-2v-4M5 10l1.5-4.5h11L19 10m-14 0h14"></path>
                        <circle cx="7" cy="14" r="1" />
                        <circle cx="17" cy="14" r="1" />
                    </svg>
                    <span class="text-base font-bold text-gray-800 dark:text-gray-200 text-center"><?= trans('bk_step2_car') ?></span>
                </div>
            </label>
            <!-- Motor -->
            <label class="relative cursor-pointer group">
                <input type="radio" name="tipe" value="Motor" <?= ($_SESSION['order']['tipe'] ?? '') === 'Motor' ? 'checked' : '' ?> onchange="updateDropdown('Motor')" class="peer sr-only">
                <div class="h-full p-5 border border-gray-200 dark:border-gray-700 rounded-2xl flex flex-col items-center justify-center transition-all duration-300 peer-checked:border-olive-700 dark:peer-checked:border-olive-500 peer-checked:ring-2 peer-checked:ring-olive-400 peer-checked:bg-olive-50 dark:peer-checked:bg-gray-800/80 bg-white dark:bg-gray-800 shadow-sm group-hover:shadow-md group-hover:-translate-y-0.5">
                    <svg class="w-8 h-8 mb-2 text-gray-600 dark:text-gray-400 peer-checked:text-olive-700 dark:peer-checked:text-olive-400 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="5" cy="16" r="3" />
                        <circle cx="19" cy="16" r="3" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 16h14M12 14h2M5.5 12.5L10 6h3l1.5 2.5L19 12M9 13.5L12 9.5m0 0l2 4"></path>
                    </svg>
                    <span class="text-base font-bold text-gray-800 dark:text-gray-200 text-center"><?= trans('bk_step2_motor') ?></span>
                </div>
            </label>
        </div>
    </div>
    
    <!-- Layanan Dropdown -->
    <div class="group">
        <label class="text-xs uppercase font-semibold text-gray-600 dark:text-gray-400 tracking-wider mb-2 block"><?= trans('bk_step2_choose_service') ?></label>
        <div class="relative">
            <select name="layanan" id="select_layanan" onchange="updatePrice()" required
                class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl py-4 px-4 text-gray-800 dark:text-gray-100 font-medium focus:ring-2 focus:ring-olive-400 focus:border-olive-700 dark:focus:ring-olive-500 focus:outline-none transition-all duration-300 shadow-sm appearance-none cursor-pointer">
                <!-- Diisi via JavaScript -->
            </select>
            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
        </div>
    </div>

    <!-- Display Harga Premium -->
    <div class="bg-olive-700 dark:bg-gray-800 border border-transparent dark:border-gray-700 text-white dark:text-gray-100 rounded-2xl p-6 shadow-xl dark:shadow-none flex items-center justify-between transition-all duration-500 transform hover:scale-[1.01]">
        <div>
            <span class="text-xs text-gray-200 dark:text-gray-400 uppercase tracking-widest font-bold block mb-1"><?= trans('bk_step2_total_price') ?></span>
            <span id="display_layanan_name" class="text-sm text-white dark:text-gray-200 font-semibold block mb-1">-</span>
        </div>
        <div class="text-right">
            <span id="display_harga" class="text-3xl font-bold tracking-tight text-white dark:text-olive-400">Rp 0</span>
        </div>
    </div>

    <!-- Plat Number -->
    <div class="group">
        <label class="text-xs uppercase font-semibold text-gray-600 dark:text-gray-400 tracking-wider mb-2 block group-focus-within:text-olive-700 dark:group-focus-within:text-olive-400 transition-colors"><?= trans('bk_step2_plate') ?></label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400 dark:text-gray-500 group-focus-within:text-olive-600 dark:group-focus-within:text-olive-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
            </div>
            <input type="text" name="plat" value="<?= htmlspecialchars($_SESSION['order']['plat'] ?? '') ?>" placeholder="<?= trans('bk_step2_plate_placeholder') ?>" required maxlength="20" pattern="[A-Za-z]{1,2}\s?[0-9]{1,4}\s?[A-Za-z]{0,3}" title="<?= trans('bk_step2_plate_title') ?>"
                class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl py-4 pl-12 pr-4 text-gray-800 dark:text-gray-100 uppercase placeholder:normal-case placeholder:text-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-olive-400 focus:border-olive-700 dark:focus:ring-olive-500 focus:outline-none transition-all duration-300">
        </div>
    </div>
    
    <!-- Date & Time (Antrian) -->
    <div class="group">
        <label class="text-xs uppercase font-semibold text-gray-600 dark:text-gray-400 tracking-wider mb-2 block group-focus-within:text-olive-700 dark:group-focus-within:text-olive-400 transition-colors"><?= trans('bk_step2_datetime') ?></label>
        <div class="flex flex-col sm:flex-row sm:items-stretch border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 overflow-hidden focus-within:ring-2 focus-within:ring-olive-400 focus-within:border-olive-700 dark:focus-within:border-olive-500 transition-all duration-300 shadow-sm">
            <!-- Date Picker Wrapper -->
            <div class="relative w-full sm:w-1/2">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500 group-focus-within:text-olive-600 dark:group-focus-within:text-olive-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <!-- Date Picker -->
                <input type="date" name="tanggal" value="<?= htmlspecialchars($_SESSION['order']['tanggal'] ?? date('Y-m-d')) ?>" required
                    class="w-full py-4 pl-12 pr-4 border-none focus:ring-0 text-sm font-semibold text-gray-800 dark:text-gray-100 bg-transparent outline-none [color-scheme:light] dark:[color-scheme:dark]">
            </div>
            
            <!-- Divider (Horizontal on mobile, Vertical on sm+) -->
            <div class="h-px w-full bg-gray-200 dark:bg-gray-700 sm:hidden"></div>
            <div class="hidden sm:block w-px bg-gray-200 dark:bg-gray-700 my-3"></div>
            
            <!-- Antrian Status — Terpisah Motor & Mobil -->
            <div class="w-full sm:w-1/2 p-4 flex items-center justify-center bg-gray-50/50 dark:bg-gray-900/50">
                <!-- Antrian Mobil (tampil default karena Mobil di-check awal) -->
                <div id="antrian-mobil-badge" class="flex items-center gap-2">
                    <div class="flex items-center gap-1.5 text-sm font-semibold text-gray-800 dark:text-gray-200">
                        <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M5 10V8a2 2 0 012-2h10a2 2 0 012 2v2m-3 8a2 2 0 11-4 0 2 2 0 014 0zm-10 0a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Mobil:</span>
                        <span class="text-olive-700 dark:text-olive-400 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 px-2 py-0.5 rounded-md font-bold text-xs">
                            C-<?= str_pad($next_mobil, 2, '0', STR_PAD_LEFT) ?>
                        </span>
                    </div>
                </div>
                <!-- Antrian Motor (tersembunyi awal) -->
                <div id="antrian-motor-badge" class="flex items-center gap-2 hidden">
                    <div class="flex items-center gap-1.5 text-sm font-semibold text-gray-800 dark:text-gray-200">
                        <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                        </svg>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Motor:</span>
                        <span class="text-olive-700 dark:text-olive-400 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 px-2 py-0.5 rounded-md font-bold text-xs">
                            M-<?= str_pad($next_motor, 2, '0', STR_PAD_LEFT) ?>
                        </span>
                    </div>
                </div>
                <input type="hidden" name="jam" id="jam-hidden" value="<?= $saran_jam_mobil ?>">
            </div>
        </div>
        <!-- Estimasi Waktu — berubah sesuai jenis kendaraan -->
        <div id="estimasi-mobil" class="flex items-center gap-3 bg-blue-50 dark:bg-gray-800 border border-blue-200 dark:border-gray-700 p-4 rounded-xl mt-6 shadow-sm">
            <svg class="w-6 h-6 flex-shrink-0 text-blue-500 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-sm text-gray-700 dark:text-gray-300">
                <span class="font-semibold text-blue-600 dark:text-blue-400"><?= $jumlah_antrean_mobil ?> mobil</span> antri &bull;
                Estimasi dilayani pada <span class="font-bold text-olive-700 dark:text-olive-400 ml-1 bg-white dark:bg-gray-900 border border-olive-100 dark:border-gray-700 px-2 py-1 rounded-md shadow-sm"><?= $saran_jam_mobil ?> WIB</span>
            </p>
        </div>
        <div id="estimasi-motor" class="hidden flex items-center gap-3 bg-green-50 dark:bg-gray-800 border border-green-200 dark:border-gray-700 p-4 rounded-xl mt-6 shadow-sm">
            <svg class="w-6 h-6 flex-shrink-0 text-green-500 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-sm text-gray-700 dark:text-gray-300">
                <span class="font-semibold text-green-600 dark:text-green-400"><?= $jumlah_antrean_motor ?> motor</span> antri &bull;
                Estimasi dilayani pada <span class="font-bold text-olive-700 dark:text-olive-400 ml-1 bg-white dark:bg-gray-900 border border-olive-100 dark:border-gray-700 px-2 py-1 rounded-md shadow-sm"><?= $saran_jam_motor ?> WIB</span>
            </p>
        </div>
    </div>
    
    <!-- Buttons -->
    <div class="flex space-x-4 pt-6 mt-2 border-t border-gray-100 dark:border-gray-700">
        <button type="button" onclick="window.history.back()" class="w-1/3 px-6 py-4 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 rounded-xl font-bold text-sm hover:border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-300">
            Kembali
        </button>
        <button type="submit" class="w-2/3 px-6 py-4 bg-olive-700 text-white rounded-xl font-bold text-lg hover:bg-olive-800 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300">
            Lanjut
        </button>
    </div>
</form>

<script>
const servicesMobil = <?= json_encode($services_mobil) ?>;
const servicesMotor = <?= json_encode($services_motor) ?>;
const oldLayanan = <?= json_encode($_SESSION['order']['layanan'] ?? '') ?>;

// Data antrian per jenis dari server
const antrianData = {
    Mobil: {
        jumlah: <?= $jumlah_antrean_mobil ?>,
        next:   <?= $next_mobil ?>,
        display: 'C-<?= str_pad($next_mobil, 2, '0', STR_PAD_LEFT) ?>',
        estimasi: '<?= $saran_jam_mobil ?> WIB',
        jam: '<?= $saran_jam_mobil ?>',
    },
    Motor: {
        jumlah: <?= $jumlah_antrean_motor ?>,
        next:   <?= $next_motor ?>,
        display: 'M-<?= str_pad($next_motor, 2, '0', STR_PAD_LEFT) ?>',
        estimasi: '<?= $saran_jam_motor ?> WIB',
        jam: '<?= $saran_jam_motor ?>',
    }
};

function updateDropdown(tipe) {
    const select = document.getElementById('select_layanan');
    select.innerHTML = '';
    
    const list = (tipe === 'Mobil') ? servicesMobil : servicesMotor;
    
    list.forEach(s => {
        const opt = document.createElement('option');
        opt.value = s.nama_layanan;
        opt.setAttribute('data-harga', s.harga);
        opt.textContent = s.nama_layanan;
        if (s.nama_layanan === oldLayanan) {
            opt.selected = true;
        }
        select.appendChild(opt);
    });
    
    // Tambahkan opsi 'Model Lainnya' di paling bawah
    const optLainnya = document.createElement('option');
    const hargaLainnya = (tipe === 'Mobil') ? 90000 : 35000;
    const valueLainnya = (tipe === 'Mobil') ? 'Lainnya (Mobil)' : 'Lainnya (Motor)';
    optLainnya.value = valueLainnya;
    optLainnya.setAttribute('data-harga', hargaLainnya);
    optLainnya.textContent = 'Model Lainnya / Cek di Lokasi';
    if (valueLainnya === oldLayanan) {
        optLainnya.selected = true;
    }
    select.appendChild(optLainnya);
    
    // Update antrian badge & estimasi sesuai jenis kendaraan
    updateAntrianDisplay(tipe);
    updatePrice();
}

function updateAntrianDisplay(tipe) {
    const badgeMobil  = document.getElementById('antrian-mobil-badge');
    const badgeMotor  = document.getElementById('antrian-motor-badge');
    const estimasiMobil = document.getElementById('estimasi-mobil');
    const estimasiMotor = document.getElementById('estimasi-motor');
    const jamHidden     = document.getElementById('jam-hidden');

    if (tipe === 'Motor') {
        badgeMobil.classList.add('hidden');
        badgeMotor.classList.remove('hidden');
        estimasiMobil.classList.add('hidden');
        estimasiMotor.classList.remove('hidden');
        jamHidden.value = antrianData.Motor.jam;
    } else {
        badgeMotor.classList.add('hidden');
        badgeMobil.classList.remove('hidden');
        estimasiMotor.classList.add('hidden');
        estimasiMobil.classList.remove('hidden');
        jamHidden.value = antrianData.Mobil.jam;
    }
}

function updatePrice() {
    const select = document.getElementById('select_layanan');
    const selectedOpt = select.options[select.selectedIndex];
    if (selectedOpt) {
        const harga = selectedOpt.getAttribute('data-harga');
        const nama = selectedOpt.value;
        
        document.getElementById('display_harga').textContent = 'Rp ' + parseInt(harga).toLocaleString('id-ID');
        document.getElementById('display_layanan_name').textContent = selectedOpt.textContent.split(' - ')[0];
    }
}

// Inisialisasi awal saat halaman dimuat
document.addEventListener('DOMContentLoaded', () => {
    const selectedTipe = document.querySelector('input[name="tipe"]:checked')?.value || 'Mobil';
    updateDropdown(selectedTipe);
});
</script>
