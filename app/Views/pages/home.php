<?php include BASE_PATH . '/app/Views/layouts/header.php'; ?>

<!-- Hero Section -->
<section class="py-28 bg-gradient-to-b from-olive-50 dark:from-slate-900 to-white dark:to-slate-900 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl sm:text-5xl md:text-7xl font-bold font-serif leading-tight mb-12">
            <?= trans('hero_title') ?>
        </h1>
        
        <div class="relative max-w-5xl mx-auto">
            <!-- Olive Accent Block behind image -->
            <div class="absolute inset-x-0 bottom-0 h-1/2 bg-olive-400 rounded-3xl -mx-4 md:-mx-8"></div>
            <!-- Main Hero Image -->
            <img src="assets/img/hero_main.png" alt="CrystalWash Vehicles" class="relative z-10 w-full rounded-2xl shadow-2xl object-cover h-[400px] md:h-[600px] border-4 border-white">
        </div>
    </div>
</section>

<!-- Marquee / Brands / Services List -->
<section class="py-10 border-b border-gray-100 dark:border-gray-800 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap justify-center gap-8 md:gap-16 text-sm text-gray-500 dark:text-gray-400 tracking-[0.2em] uppercase font-medium">
            <span>Quick Wash</span>
            <span>Full Wash</span>
            <span>Premium Wash</span>
            <span>Express Wash</span>
            <span>VIP Wash</span>
        </div>
    </div>
</section>

<!-- Benefit Section -->
<section id="benefit" class="py-24 bg-white dark:bg-slate-900 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mb-16">
            <h2 class="text-3xl md:text-4xl font-serif font-bold mb-4"><?= trans('benefit_title') ?></h2>
            <p class="text-gray-500 dark:text-gray-400 uppercase text-xs font-semibold tracking-widest"><?= trans('benefit_subtitle') ?></p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
            <div class="bg-olive-50 dark:bg-gray-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1 border border-transparent dark:border-gray-700">
                <h4 class="text-sm font-bold border-b border-gray-200 dark:border-gray-700 pb-2 mb-3"><?= trans('benefit_1_title') ?></h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed"><?= trans('benefit_1_desc') ?></p>
            </div>
            <div class="bg-olive-50 dark:bg-gray-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1 border border-transparent dark:border-gray-700">
                <h4 class="text-sm font-bold border-b border-gray-200 dark:border-gray-700 pb-2 mb-3"><?= trans('benefit_2_title') ?></h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed"><?= trans('benefit_2_desc') ?></p>
            </div>
            <div class="bg-olive-50 dark:bg-gray-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1 border border-transparent dark:border-gray-700">
                <h4 class="text-sm font-bold border-b border-gray-200 dark:border-gray-700 pb-2 mb-3"><?= trans('benefit_3_title') ?></h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed"><?= trans('benefit_3_desc') ?></p>
            </div>
            <div class="bg-olive-50 dark:bg-gray-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1 border border-transparent dark:border-gray-700">
                <h4 class="text-sm font-bold border-b border-gray-200 dark:border-gray-700 pb-2 mb-3"><?= trans('benefit_4_title') ?></h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed"><?= trans('benefit_4_desc') ?></p>
            </div>
        </div>

        <div class="w-full h-[400px] md:h-[500px] overflow-hidden rounded-3xl">
            <img src="assets/img/benefit_wash.png" alt="Car wash process" class="w-full h-full object-cover hover:scale-105 transition-transform duration-700">
        </div>
    </div>
</section>

<!-- Why Choose CrystalWash Section -->
<section id="service" class="py-24 bg-gray-50 dark:bg-slate-800 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-serif font-bold mb-6"><?= trans('service_title') ?></h2>
            <button class="bg-olive-100 dark:bg-olive-900 text-olive-800 dark:text-olive-200 text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider mb-12 border dark:border-olive-700">
                <?= trans('service_btn') ?>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">
            
            <!-- Kolom Kiri: Motor -->
            <div class="space-y-6">
                <div class="text-center mb-8">
                    <h3 class="flex items-center justify-center text-3xl font-serif font-bold text-gray-800 dark:text-gray-100">
                        <svg class="w-8 h-8 text-olive-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 17a2 2 0 100-4 2 2 0 000 4zM5 17a2 2 0 100-4 2 2 0 000 4z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 9l-3-3m0 0l-3 3m3-3v8"></path>
                        </svg>
                        Layanan Motor
                    </h3>
                    <div class="w-16 h-1 bg-olive-500 mx-auto mt-4 rounded-full"></div>
                </div>
                
                <!-- Motor Standar -->
                <div class="bg-white dark:bg-gray-900 p-8 rounded-2xl shadow-md border border-gray-100 dark:border-gray-800 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="flex justify-between items-center mb-4 border-b border-gray-100 dark:border-gray-800 pb-4">
                        <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100">Motor Standar</h4>
                        <span class="text-2xl font-black text-olive-600 dark:text-olive-500">Rp 15.000</span>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 mb-6 leading-relaxed">
                        Layanan cuci untuk kendaraan roda dua berdimensi kompak dengan kapasitas mesin kecil hingga menengah. Proses pengerjaan lebih cepat dan praktis, mencakup pembersihan bodi, velg, dan semir ban.
                    </p>
                    <div class="bg-gray-50 dark:bg-gray-800 p-5 rounded-xl border border-gray-100 dark:border-gray-700">
                        <span class="block text-sm font-bold text-olive-600 dark:text-olive-500 mb-2">CONTOH KENDARAAN:</span>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300 leading-snug">
                            Honda Beat, Scoopy, Vario 125, Supra, Yamaha Mio, Fazzio, Gear.
                        </p>
                    </div>
                </div>

                <!-- Motor Besar -->
                <div class="bg-white dark:bg-gray-900 p-8 rounded-2xl shadow-md border border-gray-100 dark:border-gray-800 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="flex justify-between items-center mb-4 border-b border-gray-100 dark:border-gray-800 pb-4">
                        <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100">Motor Besar</h4>
                        <span class="text-2xl font-black text-olive-600 dark:text-olive-500">Rp 20.000</span>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 mb-6 leading-relaxed">
                        Layanan khusus untuk motor maxi scooter, motor sport, atau motor dengan bodi lebar. Memerlukan ketelitian ekstra untuk menjangkau celah bodi yang lebih kompleks dan area mesin yang terbuka.
                    </p>
                    <div class="bg-gray-50 dark:bg-gray-800 p-5 rounded-xl border border-gray-100 dark:border-gray-700">
                        <span class="block text-sm font-bold text-olive-600 dark:text-olive-500 mb-2">CONTOH KENDARAAN:</span>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300 leading-snug">
                            Yamaha NMAX, Aerox, Honda PCX, ADV, CBR, Kawasaki Ninja, Vespa Matic.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Mobil -->
            <div class="space-y-6">
                <div class="text-center mb-8">
                    <h3 class="flex items-center justify-center text-3xl font-serif font-bold text-gray-800 dark:text-gray-100">
                        <svg class="w-8 h-8 text-olive-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M5 10V8a2 2 0 012-2h10a2 2 0 012 2v2m-3 8a2 2 0 11-4 0 2 2 0 014 0zm-10 0a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Layanan Mobil
                    </h3>
                    <div class="w-16 h-1 bg-olive-500 mx-auto mt-4 rounded-full"></div>
                </div>
                
                <!-- Mobil Standar -->
                <div class="bg-white dark:bg-gray-900 p-8 rounded-2xl shadow-md border border-gray-100 dark:border-gray-800 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="flex justify-between items-center mb-4 border-b border-gray-100 dark:border-gray-800 pb-4">
                        <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100">Mobil Standar</h4>
                        <span class="text-2xl font-black text-olive-600 dark:text-olive-500">Rp 45.000</span>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 mb-6 leading-relaxed">
                        Perawatan cuci luar-dalam untuk kendaraan roda empat berukuran kompak hingga menengah (City Car, Hatchback, Compact SUV, dan Low MPV). Cocok untuk pembersihan harian yang efisien.
                    </p>
                    <div class="bg-gray-50 dark:bg-gray-800 p-5 rounded-xl border border-gray-100 dark:border-gray-700">
                        <span class="block text-sm font-bold text-olive-600 dark:text-olive-500 mb-2">CONTOH KENDARAAN:</span>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300 leading-snug">
                            Honda Brio, Jazz, HR-V, Toyota Agya, Yaris, Avanza, Rush, Daihatsu Xenia.
                        </p>
                    </div>
                </div>

                <!-- Mobil Besar -->
                <div class="bg-white dark:bg-gray-900 p-8 rounded-2xl shadow-md border border-gray-100 dark:border-gray-800 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="flex justify-between items-center mb-4 border-b border-gray-100 dark:border-gray-800 pb-4">
                        <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100">Mobil Besar</h4>
                        <span class="text-2xl font-black text-olive-600 dark:text-olive-500">Rp 50.000</span>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 mb-6 leading-relaxed">
                        Penanganan cuci ekstra untuk kendaraan berdimensi luas, panjang, dan tinggi (Large SUV, Premium MPV, Double Cabin). Membutuhkan lebih banyak sampo, waktu pengerjaan, dan pembersihan kabin interior yang lebih luas.
                    </p>
                    <div class="bg-gray-50 dark:bg-gray-800 p-5 rounded-xl border border-gray-100 dark:border-gray-700">
                        <span class="block text-sm font-bold text-olive-600 dark:text-olive-500 mb-2">CONTOH KENDARAAN:</span>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300 leading-snug">
                            Toyota Fortuner, Innova (Reborn/Zenix), Alphard, Mitsubishi Pajero Sport, Hilux.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Bottom Image Showcase -->
<section class="py-12 bg-white dark:bg-slate-900 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="w-full h-[300px] md:h-[500px] overflow-hidden rounded-3xl shadow-xl">
            <img src="assets/img/showcase_moto.png" alt="Motorcycle foam wash" class="w-full h-full object-cover">
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-32 bg-white dark:bg-slate-900 text-center transition-colors duration-300">
    <div class="max-w-3xl mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-serif font-bold mb-4"><?= trans('cta_title') ?></h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mb-10"><?= trans('cta_desc') ?></p>
        <a href="index.php?page=checkout" class="btn-primary"><?= trans('cta_btn') ?></a>
    </div>
</section>

<?php include BASE_PATH . '/app/Views/layouts/footer.php'; ?>
