<?php include BASE_PATH . '/app/Views/layouts/header.php'; ?>

<!-- Hero Section -->
<section class="py-28 bg-gradient-to-b from-olive-50 dark:from-slate-900 to-white dark:to-slate-900 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-5xl md:text-7xl font-bold font-serif leading-tight mb-12">
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
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-16">
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

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Cuci Dasar -->
            <div class="bg-white dark:bg-gray-900 p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <h3 class="text-lg font-bold text-center mb-6 text-gray-900 dark:text-gray-100"><?= trans('srv_1_title') ?></h3>
                <ul class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> <?= trans('srv_1_l1') ?></li>
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> <?= trans('srv_1_l2') ?></li>
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> <?= trans('srv_1_l3') ?></li>
                </ul>
            </div>
            <!-- Cuci Penuh -->
            <div class="bg-white dark:bg-gray-900 p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <h3 class="text-lg font-bold text-center mb-6 text-gray-900 dark:text-gray-100"><?= trans('srv_2_title') ?></h3>
                <ul class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> <?= trans('srv_2_l1') ?></li>
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> <?= trans('srv_2_l2') ?></li>
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> <?= trans('srv_2_l3') ?></li>
                </ul>
            </div>
            <!-- Detail Interior -->
            <div class="bg-white dark:bg-gray-900 p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <h3 class="text-lg font-bold text-center mb-6 text-gray-900 dark:text-gray-100"><?= trans('srv_3_title') ?></h3>
                <ul class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> <?= trans('srv_3_l1') ?></li>
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> <?= trans('srv_3_l2') ?></li>
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> <?= trans('srv_3_l3') ?></li>
                </ul>
            </div>
            <!-- Detail Mesin -->
            <div class="bg-white dark:bg-gray-900 p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <h3 class="text-lg font-bold text-center mb-6 text-gray-900 dark:text-gray-100"><?= trans('srv_4_title') ?></h3>
                <ul class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> <?= trans('srv_4_l1') ?></li>
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> <?= trans('srv_4_l2') ?></li>
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> <?= trans('srv_4_l3') ?></li>
                </ul>
            </div>
            <!-- Cuci Kilat -->
            <div class="bg-white dark:bg-gray-900 p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <h3 class="text-lg font-bold text-center mb-6 text-gray-900 dark:text-gray-100"><?= trans('srv_5_title') ?></h3>
                <ul class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> <?= trans('srv_5_l1') ?></li>
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> <?= trans('srv_5_l2') ?></li>
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> <?= trans('srv_5_l3') ?></li>
                </ul>
            </div>
            <!-- Cuci Ramah Lingkungan -->
            <div class="bg-white dark:bg-gray-900 p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <h3 class="text-lg font-bold text-center mb-6 text-gray-900 dark:text-gray-100"><?= trans('srv_6_title') ?></h3>
                <ul class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> <?= trans('srv_6_l1') ?></li>
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> <?= trans('srv_6_l2') ?></li>
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> <?= trans('srv_6_l3') ?></li>
                </ul>
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
