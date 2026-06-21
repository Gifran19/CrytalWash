<!-- 
  [PENTING] 
  Proyek ini menggunakan Tailwind CSS via NPM. 
  Pastikan Anda menjalankan perintah berikut di terminal (command line) untuk mengkompilasi file CSS:
  npx tailwindcss -i ./src/css/input.css -o ./assets/css/style.css --watch
-->
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CrystalWash - Wash Smarter Not Longer</title>
    <meta name="description" content="CrystalWash - Premium Car Wash Service. Cuci Lebih Pintar Bukan Lebih Lama.">
    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?= time() ?>">
    <!-- Fonts: Playfair Display & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
        
        function toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
        }
    </script>
</head>
<body class="bg-white dark:bg-slate-900 text-dark dark:text-gray-100 font-sans flex flex-col min-h-screen transition-colors duration-300">

    <!-- Navbar -->
    <?php if (!isset($hide_navbar) || !$hide_navbar): ?>
    <nav class="sticky top-0 z-50 bg-white/90 dark:bg-gray-900/90 backdrop-blur-md border-b border-gray-100 dark:border-gray-800 shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="index.php?page=home" class="font-serif text-3xl font-bold text-olive-700 dark:text-olive-400 tracking-tight">CrystalWash</a>
                </div>
                
                <!-- Center Links -->
                <ul class="hidden md:flex items-center gap-6">
                    <li><a href="index.php?page=home#about" class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-olive-700 dark:hover:text-olive-400 transition-all duration-300"><?= trans('nav_about') ?></a></li>
                    <li><a href="index.php?page=home#benefit" class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-olive-700 dark:hover:text-olive-400 transition-all duration-300"><?= trans('nav_benefit') ?></a></li>
                    <li><a href="index.php?page=home#service" class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-olive-700 dark:hover:text-olive-400 transition-all duration-300"><?= trans('nav_service') ?></a></li>
                    <li><a href="index.php?page=home#contact" class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-olive-700 dark:hover:text-olive-400 transition-all duration-300"><?= trans('nav_contact') ?></a></li>
                </ul>

                <!-- Right Controls -->
                <div class="flex items-center gap-3 md:gap-4">
                    <!-- Language Switcher -->
                    <?php
                        $current_lang = $_SESSION['lang'] ?? 'id';
                        $page_url = $_SERVER['REQUEST_URI'];
                        // Build clean URLs for lang toggles
                        $url_base = preg_replace('/([&?])lang=[^&]+(&|$)/', '$1', $page_url);
                        $url_base = rtrim($url_base, '&?');
                        $url_id = $url_base . (strpos($url_base, '?') !== false ? '&' : '?') . 'lang=id';
                        $url_en = $url_base . (strpos($url_base, '?') !== false ? '&' : '?') . 'lang=en';
                    ?>
                    <div class="flex items-center gap-1 text-xs font-semibold border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 p-1 rounded-full shadow-sm">
                        <div class="px-2 text-gray-400 dark:text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <a href="<?= htmlspecialchars($url_id) ?>" class="px-3 py-1 rounded-full transition-colors duration-300 <?= $current_lang === 'id' ? 'bg-olive-700 text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-olive-700 dark:hover:text-olive-400' ?>">ID</a>
                        <a href="<?= htmlspecialchars($url_en) ?>" class="px-3 py-1 rounded-full transition-colors duration-300 <?= $current_lang === 'en' ? 'bg-olive-700 text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-olive-700 dark:hover:text-olive-400' ?>">EN</a>
                    </div>
                    
                    <!-- Dark Mode Toggle -->
                    <button onclick="toggleTheme()" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-300 text-gray-500 dark:text-gray-400" aria-label="Toggle Dark Mode">
                        <!-- Sun icon (shows in dark mode) -->
                        <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <!-- Moon icon (shows in light mode) -->
                        <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    </button>
                    
                    <a href="index.php?page=login" class="hidden md:inline-flex items-center justify-center px-6 py-2 border border-transparent rounded-full shadow-sm text-sm font-medium text-white bg-olive-700 hover:bg-olive-800 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md">
                        <?= trans('nav_login') ?>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <!-- Main Content Wrapper -->
    <main class="flex-grow">
