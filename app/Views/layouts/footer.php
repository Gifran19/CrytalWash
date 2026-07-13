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
    <!-- Login Modal -->
    <div id="loginModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300">
        <div id="modalContent" class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md p-8 relative m-4 border border-gray-100 dark:border-gray-700">
            <button id="closeModalBtn" class="absolute top-4 right-4 p-2 text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <h2 class="text-2xl font-bold text-center text-gray-800 dark:text-white mb-6">Login</h2>

            <form action="index.php?action=admin_login" method="POST">
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Username</label>
                    <input type="text" id="username" name="username" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-white rounded-xl focus:ring-olive-500 focus:border-olive-500 outline-none transition" placeholder="Masukkan username" required>
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
                    <input type="password" id="password" name="password" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-white rounded-xl focus:ring-olive-500 focus:border-olive-500 outline-none transition" placeholder="••••••••" required>
                </div>

                <button type="submit" class="w-full bg-olive-700 hover:bg-olive-800 text-white font-semibold py-3 px-4 rounded-xl transition duration-300 shadow-md hover:shadow-lg hover:-translate-y-0.5">
                    <?= trans('nav_login') ?? 'Masuk' ?>
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginModal = document.getElementById('loginModal');
            const openLoginBtn = document.getElementById('openLoginBtn');
            const openLoginMobileBtn = document.getElementById('openLoginMobileBtn');
            const closeModalBtn = document.getElementById('closeModalBtn');

            if (loginModal && closeModalBtn) {
                const openModal = (e) => {
                    e.preventDefault();
                    loginModal.classList.remove('hidden');
                };

                if (openLoginBtn) {
                    openLoginBtn.addEventListener('click', openModal);
                }
                if (openLoginMobileBtn) {
                    openLoginMobileBtn.addEventListener('click', openModal);
                }

                closeModalBtn.addEventListener('click', () => {
                    loginModal.classList.add('hidden');
                });

                loginModal.addEventListener('click', (e) => {
                    if (e.target === loginModal) {
                        loginModal.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</body>
</html>
