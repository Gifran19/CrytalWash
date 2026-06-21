    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-b from-gray-50 dark:from-slate-900 to-gray-100 dark:to-slate-800 border-t border-gray-200 dark:border-gray-800 mt-auto transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex justify-center md:justify-start space-x-6 md:order-2">
                    <a href="index.php?page=home#about" class="text-xs text-gray-500 dark:text-gray-400 hover:text-olive-700 dark:hover:text-olive-400 uppercase tracking-widest font-semibold transition-all duration-300"><?= trans('nav_about') ?></a>
                    <a href="index.php?page=home#benefit" class="text-xs text-gray-500 dark:text-gray-400 hover:text-olive-700 dark:hover:text-olive-400 uppercase tracking-widest font-semibold transition-all duration-300"><?= trans('nav_benefit') ?></a>
                    <a href="index.php?page=home#service" class="text-xs text-gray-500 dark:text-gray-400 hover:text-olive-700 dark:hover:text-olive-400 uppercase tracking-widest font-semibold transition-all duration-300"><?= trans('nav_service') ?></a>
                    <a href="index.php?page=home#contact" class="text-xs text-gray-500 dark:text-gray-400 hover:text-olive-700 dark:hover:text-olive-400 uppercase tracking-widest font-semibold transition-all duration-300"><?= trans('nav_contact') ?></a>
                </div>
                <div class="mt-8 md:mt-0 md:order-1 flex justify-between w-full md:w-auto">
                    <p class="text-xs text-gray-400 dark:text-gray-500">
                        &copy; 2026 CrystalWash. <?= trans('footer_copyright') ?>
                    </p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 ml-4 md:ml-8">
                        <?= trans('footer_project') ?>
                    </p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
