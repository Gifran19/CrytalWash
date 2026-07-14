<?php
// Deprecated page - redirecting to home with login modal active
$error = $_GET['error'] ?? null;
$success = $_GET['success'] ?? null;
$query = 'show_login=true';
if ($error) {
    $query .= '&error=' . urlencode($error);
}
if ($success) {
    $query .= '&success=' . urlencode($success);
}
header('Location: index.php?page=home&' . $query);
exit;
?>
<?php include BASE_PATH . '/app/Views/layouts/header.php'; ?>

<div class="min-h-screen flex items-center justify-center bg-olive-50 dark:bg-slate-900 p-4 transition-colors duration-300">
    <div class="flex w-full max-w-4xl bg-white dark:bg-gray-800 rounded-3xl shadow-2xl overflow-hidden border border-transparent dark:border-gray-700">
        
        <!-- Left: Form -->
        <div class="w-full md:w-1/2 p-10 md:p-14 flex flex-col justify-center">
            <div class="mb-8">
                <h1 class="font-serif text-3xl lg:text-4xl font-bold text-olive-700 dark:text-olive-400 mb-2"><?= trans('admin_login_welcome') ?> CrystalWash</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400"><?= trans('admin_login_desc') ?></p>
            </div>

            <?php if ($error === 'invalid'): ?>
                <div class="bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl text-sm mb-6 border border-red-200 dark:border-red-800 flex items-start">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <?= trans('admin_login_err_invalid') ?>
                </div>
            <?php elseif ($error === 'empty'): ?>
                <div class="bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl text-sm mb-6 border border-red-200 dark:border-red-800 flex items-start">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <?= trans('admin_login_err_empty') ?>
                </div>
            <?php endif; ?>

            <?php if ($success === 'logout'): ?>
                <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 p-4 rounded-xl flex items-center gap-2 mb-6 text-sm">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <?= trans('admin_login_success_out') ?>
                </div>
            <?php endif; ?>

            <form action="index.php?action=admin_login" method="POST" id="loginForm">
                <div class="mb-4">
                    <label for="username" class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide block mb-1"><?= trans('admin_login_username') ?></label>
                    <input type="text" id="username" name="username" autocomplete="username" required
                        class="w-full border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 dark:text-white rounded-xl p-4 mt-2 focus:bg-white dark:focus:bg-gray-600 focus:ring-2 focus:ring-olive-400 focus:outline-none transition-all">
                </div>

                <div class="mb-6">
                    <label for="password" class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide block mb-1"><?= trans('admin_login_password') ?></label>
                    <input type="password" id="password" name="password" autocomplete="current-password" required
                        class="w-full border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 dark:text-white rounded-xl p-4 mt-2 focus:bg-white dark:focus:bg-gray-600 focus:ring-2 focus:ring-olive-400 focus:outline-none transition-all">
                </div>

                <button type="submit" class="w-full bg-olive-700 text-white font-bold text-lg py-4 rounded-xl hover:bg-olive-800 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300"><?= trans('admin_login_btn') ?></button>
            </form>
        </div>

        <!-- Right: Image -->
        <div class="hidden md:block w-1/2 relative">
            <img src="assets/img/login_car_wash.png" alt="Car Wash" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-black/10 to-transparent"></div>
        </div>

    </div>
</div>

<?php include BASE_PATH . '/app/Views/layouts/footer.php'; ?>
