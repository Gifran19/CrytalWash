<?php include BASE_PATH . '/app/Views/layouts/header.php'; ?>

<!-- Hero Section (About) -->
<section id="about" class="py-28 bg-gradient-to-b from-olive-50 dark:from-slate-900 to-white dark:to-slate-900 transition-colors duration-300">
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

<!-- Highlight Features Bar -->
<section class="py-8 bg-gray-50/50 dark:bg-slate-800/40 border-y border-gray-100 dark:border-gray-800 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div style="display: flex; flex-wrap: wrap; justify-content: center; align-items: center; gap: 16px 32px;" class="text-sm font-semibold text-gray-600 dark:text-gray-300">
            <div style="display: flex; align-items: center; gap: 8px;">
                <svg class="w-5 h-5 text-olive-600 dark:text-olive-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px; flex-shrink: 0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span><?= trans('hl_clean') ?></span>
            </div>
            <div style="width: 6px; height: 6px; background-color: #cbd5e1;" class="rounded-full hidden md:block"></div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <svg class="w-5 h-5 text-olive-600 dark:text-olive-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px; flex-shrink: 0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span><?= trans('hl_fast') ?></span>
            </div>
            <div style="width: 6px; height: 6px; background-color: #cbd5e1;" class="rounded-full hidden md:block"></div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <svg class="w-5 h-5 text-olive-600 dark:text-olive-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px; flex-shrink: 0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span><?= trans('hl_queue') ?></span>
            </div>
            <div style="width: 6px; height: 6px; background-color: #cbd5e1;" class="rounded-full hidden md:block"></div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <svg class="w-5 h-5 text-olive-600 dark:text-olive-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px; flex-shrink: 0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                <span><?= trans('hl_payment') ?></span>
            </div>
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
                        <svg class="w-8 h-8 text-olive-600 mr-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width:32px;height:32px;">
                            <circle cx="5" cy="16" r="3" />
                            <circle cx="19" cy="16" r="3" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 16h14M12 14h2M5.5 12.5L10 6h3l1.5 2.5L19 12M9 13.5L12 9.5m0 0l2 4"></path>
                        </svg>
                        <?= trans('srv_motor_header') ?>
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
                        <?= trans('srv_motor_std_desc') ?>
                    </p>
                    <div class="bg-gray-50 dark:bg-gray-800 p-5 rounded-xl border border-gray-100 dark:border-gray-700">
                        <span class="block text-sm font-bold text-olive-600 dark:text-olive-500 mb-2"><?= trans('srv_example_vehicle') ?></span>
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
                        <?= trans('srv_motor_big_desc') ?>
                    </p>
                    <div class="bg-gray-50 dark:bg-gray-800 p-5 rounded-xl border border-gray-100 dark:border-gray-700">
                        <span class="block text-sm font-bold text-olive-600 dark:text-olive-500 mb-2"><?= trans('srv_example_vehicle') ?></span>
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
                        <svg class="w-8 h-8 text-olive-600 mr-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width:32px;height:32px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 18h14M5 18a2 2 0 002-2v-4M5 18a2 2 0 01-2-2v-4a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M19 18a2 2 0 002-2v-4M5 10l1.5-4.5h11L19 10m-14 0h14"></path>
                            <circle cx="7" cy="14" r="1" />
                            <circle cx="17" cy="14" r="1" />
                        </svg>
                        <?= trans('srv_mobil_header') ?>
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
                        <?= trans('srv_mobil_std_desc') ?>
                    </p>
                    <div class="bg-gray-50 dark:bg-gray-800 p-5 rounded-xl border border-gray-100 dark:border-gray-700">
                        <span class="block text-sm font-bold text-olive-600 dark:text-olive-500 mb-2"><?= trans('srv_example_vehicle') ?></span>
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
                        <?= trans('srv_mobil_big_desc') ?>
                    </p>
                    <div class="bg-gray-50 dark:bg-gray-800 p-5 rounded-xl border border-gray-100 dark:border-gray-700">
                        <span class="block text-sm font-bold text-olive-600 dark:text-olive-500 mb-2"><?= trans('srv_example_vehicle') ?></span>
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

<!-- CTA Section (Contact Us) -->
<section id="contact" class="py-32 bg-white dark:bg-slate-900 text-center transition-colors duration-300">
    <div class="max-w-3xl mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-serif font-bold mb-4"><?= trans('cta_title') ?></h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mb-10"><?= trans('cta_desc') ?></p>
        <a href="index.php?page=checkout" class="btn-primary"><?= trans('cta_btn') ?></a>
    </div>
</section>

<?php include BASE_PATH . '/app/Views/layouts/footer.php'; ?>
