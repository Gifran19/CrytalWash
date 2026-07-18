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

            <!-- Error message container -->
            <div id="modalLoginError" class="hidden bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl text-sm mb-6 border border-red-200 dark:border-red-800 flex items-start">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <span id="modalLoginErrorText"></span>
            </div>

            <form id="modalLoginForm" action="index.php?action=admin_login" method="POST">
                <?php csrf_field(); ?>
                <div class="mb-4">
                    <label for="modalUsername" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Username</label>
                    <input type="text" id="modalUsername" name="username" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-white rounded-xl focus:ring-olive-500 focus:border-olive-500 outline-none transition" placeholder="Masukkan username" required>
                </div>

                <div class="mb-6">
                    <label for="modalPassword" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
                    <input type="password" id="modalPassword" name="password" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-white rounded-xl focus:ring-olive-500 focus:border-olive-500 outline-none transition" placeholder="••••••••" required>
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
            const modalLoginForm = document.getElementById('modalLoginForm');
            const modalLoginError = document.getElementById('modalLoginError');
            const modalLoginErrorText = document.getElementById('modalLoginErrorText');

            if (loginModal && closeModalBtn) {
                const openModal = (e) => {
                    e.preventDefault();
                    if (modalLoginError) {
                        modalLoginError.classList.add('hidden');
                    }
                    if (modalLoginForm) {
                        modalLoginForm.reset();
                    }
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

            if (modalLoginForm) {
                modalLoginForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    if (modalLoginError) {
                        modalLoginError.classList.add('hidden');
                    }
                    
                    const formData = new FormData(modalLoginForm);
                    
                    fetch(modalLoginForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            window.location.href = data.redirect || 'index.php?page=admin_dashboard';
                        } else {
                            if (modalLoginError && modalLoginErrorText) {
                                modalLoginErrorText.textContent = data.message || 'Login failed';
                                modalLoginError.classList.remove('hidden');
                            } else {
                                alert(data.message || 'Login failed');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error during login:', error);
                        if (modalLoginError && modalLoginErrorText) {
                            modalLoginErrorText.textContent = 'An error occurred. Please try again.';
                            modalLoginError.classList.remove('hidden');
                        }
                    });
                });
            }

            // Check if show_login is requested in the URL
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('show_login') === 'true') {
                if (loginModal) {
                    loginModal.classList.remove('hidden');
                }
                const errorType = urlParams.get('error');
                if (errorType && modalLoginError && modalLoginErrorText) {
                    let errMsg = '';
                    if (errorType === 'invalid') {
                        errMsg = <?= json_encode(trans('admin_login_err_invalid')) ?>;
                    } else if (errorType === 'empty') {
                        errMsg = <?= json_encode(trans('admin_login_err_empty')) ?>;
                    }
                    if (errMsg) {
                        modalLoginErrorText.textContent = errMsg;
                        modalLoginError.classList.remove('hidden');
                    }
                }
                
                // Clean URL query parameters so they don't persist on page refresh
                const cleanParams = new URLSearchParams(window.location.search);
                cleanParams.delete('show_login');
                cleanParams.delete('error');
                cleanParams.delete('success');
                const newSearch = cleanParams.toString();
                const newUrl = window.location.pathname + (newSearch ? '?' + newSearch : '') + window.location.hash;
                window.history.replaceState({}, document.title, newUrl);
            }
        });
    </script>
</body>
</html>
