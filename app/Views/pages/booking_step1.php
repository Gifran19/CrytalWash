<div class="mb-8">
    <h2 class="font-serif text-4xl font-bold text-olive-700 dark:text-olive-400 tracking-tight"><?= trans('booking_step1_title') ?></h2>
    <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm"><?= trans('booking_step1_subtitle') ?></p>
</div>

<form action="index.php?action=auth_booking" method="POST" class="flex flex-col gap-5">
    <?php csrf_field(); ?>
    <input type="hidden" name="next_step" value="2">
    
    <div class="group">
        <label class="text-xs uppercase font-semibold text-gray-600 dark:text-gray-400 tracking-wider mb-2 block group-focus-within:text-olive-700 dark:group-focus-within:text-olive-400 transition-colors"><?= trans('label_fullname') ?></label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400 dark:text-gray-500 group-focus-within:text-olive-600 dark:group-focus-within:text-olive-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <input type="text" name="nama" placeholder="<?= trans('placeholder_fullname') ?>" required
                class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl py-4 pl-12 pr-4 text-gray-800 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-olive-400 focus:border-olive-700 dark:focus:ring-olive-500 focus:outline-none transition-all duration-300">
        </div>
    </div>
    
    <div class="group">
        <label class="text-xs uppercase font-semibold text-gray-600 dark:text-gray-400 tracking-wider mb-2 block group-focus-within:text-olive-700 dark:group-focus-within:text-olive-400 transition-colors"><?= trans('label_whatsapp') ?></label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400 dark:text-gray-500 group-focus-within:text-olive-600 dark:group-focus-within:text-olive-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
            </div>
            <input type="tel" name="whatsapp" placeholder="<?= trans('placeholder_whatsapp') ?>" required
                pattern="[0-9]+" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl py-4 pl-12 pr-4 text-gray-800 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-olive-400 focus:border-olive-700 dark:focus:ring-olive-500 focus:outline-none transition-all duration-300">
        </div>
    </div>
    
    <div class="group">
        <label class="text-xs uppercase font-semibold text-gray-600 dark:text-gray-400 tracking-wider mb-2 block group-focus-within:text-olive-700 dark:group-focus-within:text-olive-400 transition-colors"><?= trans('label_email') ?></label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400 dark:text-gray-500 group-focus-within:text-olive-600 dark:group-focus-within:text-olive-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <input type="email" name="email" placeholder="<?= trans('placeholder_email') ?>"
                class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl py-4 pl-12 pr-4 text-gray-800 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-olive-400 focus:border-olive-700 dark:focus:ring-olive-500 focus:outline-none transition-all duration-300">
        </div>
    </div>
    
    <div class="flex space-x-4 pt-6 mt-2 border-t border-gray-100 dark:border-gray-700">
        <button type="button" onclick="window.history.back()" class="w-1/3 px-3 py-3 sm:px-6 sm:py-4 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 rounded-xl font-bold text-xs sm:text-sm hover:border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-300">
            <?= trans('btn_back') ?>
        </button>
        <button type="submit" class="w-2/3 px-4 py-3 sm:px-6 sm:py-4 bg-olive-700 text-white rounded-xl font-bold text-sm sm:text-lg hover:bg-olive-800 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300">
            <?= trans('btn_next') ?>
        </button>
    </div>
</form>
