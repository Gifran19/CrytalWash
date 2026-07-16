<!-- 
  [PENTING] 
  Proyek ini menggunakan Tailwind CSS via NPM. 
  Pastikan Anda menjalankan perintah berikut di terminal (command line) untuk mengkompilasi file CSS:
  npx tailwindcss -i ./src/css/input.css -o ./public/assets/css/style.css --watch
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
    <!-- Aksesibilitas: penyesuaian font untuk keterbacaan usia 25-60 tahun -->
    <link rel="stylesheet" href="assets/css/accessibility.css?v=<?= time() ?>">
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
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="index.php?page=home" class="font-serif text-2xl sm:text-3xl font-bold text-olive-700 dark:text-olive-400 tracking-tight">CrystalWash</a>
                </div>
                
                <!-- Center Links -->
                <ul class="hidden md:flex items-center gap-6">
                    <li><a href="index.php?page=home#about" class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-olive-700 dark:hover:text-olive-400 transition-all duration-300"><?= trans('nav_about') ?></a></li>
                    <li><a href="index.php?page=home#benefit" class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-olive-700 dark:hover:text-olive-400 transition-all duration-300"><?= trans('nav_benefit') ?></a></li>
                    <li><a href="index.php?page=home#service" class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-olive-700 dark:hover:text-olive-400 transition-all duration-300"><?= trans('nav_service') ?></a></li>
                    <li><a href="index.php?page=home#contact" class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-olive-700 dark:hover:text-olive-400 transition-all duration-300"><?= trans('nav_contact') ?></a></li>
                </ul>

                <!-- Right Controls -->
                <div class="flex items-center gap-2 sm:gap-4">
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
                        <div class="px-2 text-gray-400 dark:text-gray-500 hidden sm:block">
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
                    
                    <button id="openLoginBtn" class="hidden md:inline-flex items-center justify-center px-6 py-2 border border-transparent rounded-full shadow-sm text-sm font-medium text-white bg-olive-700 hover:bg-olive-800 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md cursor-pointer">
                        <?= trans('nav_login') ?>
                    </button>
                    
                    <!-- Hamburger Menu Button (Mobile) -->
                    <button id="mobileMenuToggleBtn" class="inline-flex md:hidden items-center justify-center p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-300 text-gray-500 dark:text-gray-400" aria-label="Toggle Mobile Menu">
                        <svg id="hamburgerIcon" class="w-6 h-6 block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <svg id="closeIcon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Dropdown Menu -->
        <div id="mobileMenu" class="hidden md:hidden border-t border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 transition-all duration-300">
            <div class="px-4 pt-2 pb-6 space-y-3 shadow-inner">
                <a href="index.php?page=home#about" class="block px-3 py-2 rounded-xl text-base font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-olive-700 dark:hover:text-olive-400 transition-colors"><?= trans('nav_about') ?></a>
                <a href="index.php?page=home#benefit" class="block px-3 py-2 rounded-xl text-base font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-olive-700 dark:hover:text-olive-400 transition-colors"><?= trans('nav_benefit') ?></a>
                <a href="index.php?page=home#service" class="block px-3 py-2 rounded-xl text-base font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-olive-700 dark:hover:text-olive-400 transition-colors"><?= trans('nav_service') ?></a>
                <a href="index.php?page=home#contact" class="block px-3 py-2 rounded-xl text-base font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-olive-700 dark:hover:text-olive-400 transition-colors"><?= trans('nav_contact') ?></a>
                
                <div class="border-t border-gray-100 dark:border-gray-800 my-2 pt-2"></div>
                
                <button id="openLoginMobileBtn" class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-full shadow-sm text-base font-medium text-white bg-olive-700 hover:bg-olive-800 transition-all duration-300 cursor-pointer">
                    <?= trans('nav_login') ?>
                </button>
            </div>
        </div>
    </nav>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleBtn = document.getElementById('mobileMenuToggleBtn');
            const mobileMenu = document.getElementById('mobileMenu');
            const hamburgerIcon = document.getElementById('hamburgerIcon');
            const closeIcon = document.getElementById('closeIcon');
            
            if (toggleBtn && mobileMenu) {
                toggleBtn.addEventListener('click', () => {
                    const isHidden = mobileMenu.classList.contains('hidden');
                    if (isHidden) {
                        mobileMenu.classList.remove('hidden');
                        hamburgerIcon.classList.add('hidden');
                        closeIcon.classList.remove('hidden');
                    } else {
                        mobileMenu.classList.add('hidden');
                        hamburgerIcon.classList.remove('hidden');
                        closeIcon.classList.add('hidden');
                    }
                });
                
                // Close mobile menu when clicking on links
                const links = mobileMenu.querySelectorAll('a, button');
                links.forEach(link => {
                    link.addEventListener('click', () => {
                        mobileMenu.classList.add('hidden');
                        hamburgerIcon.classList.remove('hidden');
                        closeIcon.classList.add('hidden');
                    });
                });
            }
        });
    </script>
    <?php endif; ?>

    <!-- ============================================ -->
    <!-- Floating Live Queue Widget                  -->
    <!-- Fixed pojok kanan bawah, bisa expand/collapse -->
    <!-- ============================================ -->
    <div id="fq-widget" style="
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 9999;
        font-family: 'Inter', sans-serif;
    ">
        <!-- Collapsed Pill -->
        <button id="fq-toggle" onclick="fqToggle()" class="fq-pill-btn" style="
            display: flex;
            align-items: center;
            gap: 8px;
            background: white;
            border: 1.5px solid #d1d5db;
            border-radius: 9999px;
            padding: 8px 16px 8px 12px;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(0,0,0,0.10), 0 1px 4px rgba(0,0,0,0.06);
            transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
            color: #374151;
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
        " onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 28px rgba(0,0,0,0.14)';this.style.borderColor='#4d7c0f';"
           onmouseout="this.style.transform='';this.style.boxShadow='0 4px 20px rgba(0,0,0,0.10), 0 1px 4px rgba(0,0,0,0.06)';this.style.borderColor='#d1d5db';">
            <!-- Live dot -->
            <span style="position:relative;display:flex;width:9px;height:9px;flex-shrink:0;">
                <span style="
                    position:absolute;inset:0;border-radius:50%;
                    background:#ef4444;opacity:0.55;
                    animation: fqPing 1.5s cubic-bezier(0,0,0.2,1) infinite;
                "></span>
                <span style="position:relative;width:9px;height:9px;border-radius:50%;background:#ef4444;display:block;"></span>
            </span>
            <!-- Icon -->
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#4d7c0f;flex-shrink:0;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span style="color:#374151;font-weight:600;">Antrian</span>
            <!-- Badge jumlah -->
            <span id="fq-pill-count" style="
                background: #f0fdf4;
                border: 1px solid #bbf7d0;
                border-radius: 9999px;
                padding: 1px 8px;
                font-size: 12px;
                font-weight: 700;
                color: #15803d;
                min-width: 26px;
                text-align: center;
            ">—</span>
            <!-- Chevron -->
            <svg id="fq-chevron" width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="color:#9ca3af;transition:transform 0.3s;flex-shrink:0;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
            </svg>
        </button>

        <!-- Expanded Panel -->
        <div id="fq-panel" style="
            display: none;
            position: absolute;
            bottom: calc(100% + 10px);
            right: 0;
            width: 288px;
            background: white;
            border: 1.5px solid #e5e7eb;
            border-radius: 20px;
            padding: 18px;
            box-shadow: 0 16px 48px rgba(0,0,0,0.12), 0 4px 12px rgba(0,0,0,0.07);
            transform-origin: bottom right;
            animation: fqSlideIn 0.25s cubic-bezier(0.34,1.56,0.64,1);
        ">
            <!-- Header panel -->
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;padding-bottom:12px;border-bottom:1px solid #f3f4f6;">
                <div style="display:flex;align-items:center;gap:7px;">
                    <span style="position:relative;display:flex;width:7px;height:7px;">
                        <span style="position:absolute;inset:0;border-radius:50%;background:#ef4444;opacity:0.6;animation:fqPing 1.5s cubic-bezier(0,0,0.2,1) infinite;"></span>
                        <span style="position:relative;width:7px;height:7px;border-radius:50%;background:#ef4444;display:block;"></span>
                    </span>
                    <span style="font-size:10px;font-weight:800;color:#ef4444;letter-spacing:0.18em;text-transform:uppercase;">Live</span>
                    <span style="width:1px;height:11px;background:#e5e7eb;display:inline-block;"></span>
                    <span style="font-size:13px;font-weight:700;color:#111827;">Antrian Hari Ini</span>
                </div>
                <span style="font-size:10px;color:#9ca3af;font-weight:500;">
                    <span id="fq-updated">--:--</span>
                </span>
            </div>

            <!-- Stats grid -->
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:12px;">
                <!-- Menunggu -->
                <div class="fq-stat-card" style="background:#fffbeb;border:1px solid #fde68a;border-radius:12px;padding:10px 6px;text-align:center;">
                    <div class="fq-stat-num" style="font-size:24px;font-weight:900;color:#92400e;line-height:1;" id="fq-menunggu">—</div>
                    <div class="fq-stat-lbl" style="font-size:9px;font-weight:700;color:#d97706;text-transform:uppercase;letter-spacing:0.08em;margin-top:3px;">Menunggu</div>
                </div>
                <!-- Diproses -->
                <div class="fq-stat-card" style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;padding:10px 6px;text-align:center;">
                    <div class="fq-stat-num" style="font-size:24px;font-weight:900;color:#1e40af;line-height:1;" id="fq-diproses">—</div>
                    <div class="fq-stat-lbl" style="font-size:9px;font-weight:700;color:#2563eb;text-transform:uppercase;letter-spacing:0.08em;margin-top:3px;">Diproses</div>
                </div>
                <!-- Estimasi -->
                <div class="fq-stat-card" style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:10px 6px;text-align:center;">
                    <div class="fq-stat-num" style="font-size:24px;font-weight:900;color:#14532d;line-height:1;">~<span id="fq-estimasi">—</span><span style="font-size:10px;color:#6b7280;font-weight:600;">m</span></div>
                    <div class="fq-stat-lbl" style="font-size:9px;font-weight:700;color:#16a34a;text-transform:uppercase;letter-spacing:0.08em;margin-top:3px;">Est. Tunggu</div>
                </div>
            </div>

            <!-- Status message -->
            <div id="fq-msg" style="
                font-size:11.5px;
                color:#6b7280;
                text-align:center;
                margin-bottom:12px;
                min-height:17px;
                line-height:1.5;
                background:#f9fafb;
                border-radius:8px;
                padding:6px 8px;
            ">Memuat data antrian...</div>

            <!-- CTA Button -->
            <a href="index.php?page=checkout" style="
                display:block;
                text-align:center;
                background: linear-gradient(135deg, #4d7c0f, #3f6212);
                color: white;
                font-size: 12px;
                font-weight: 700;
                padding: 9px 0;
                border-radius: 9999px;
                text-decoration: none;
                letter-spacing: 0.03em;
                transition: opacity 0.2s, transform 0.2s;
                box-shadow: 0 2px 8px rgba(77,124,15,0.35);
            " onmouseover="this.style.opacity='0.88';this.style.transform='translateY(-1px)';"
               onmouseout="this.style.opacity='1';this.style.transform='';">
                🚗 Booking Sekarang
            </a>
        </div>
    </div>

    <!-- Animations + Queue Logic -->
    <style>
        @keyframes fqPing {
            75%, 100% { transform: scale(2); opacity: 0; }
        }
        @keyframes fqSlideIn {
            from { opacity: 0; transform: scale(0.85) translateY(8px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
    </style>
    <script>
    (function() {
        var _fqOpen = false;

        // ── Tema: light & dark ──────────────────────────────────────────
        var THEME = {
            light: {
                pillBg:         'white',
                pillBorder:     '#d1d5db',
                pillColor:      '#374151',
                pillShadow:     '0 4px 20px rgba(0,0,0,0.10), 0 1px 4px rgba(0,0,0,0.06)',
                iconColor:      '#4d7c0f',
                badgeBg:        '#f0fdf4',
                badgeBorder:    '#bbf7d0',
                badgeColor:     '#15803d',
                chevronColor:   '#9ca3af',
                panelBg:        'white',
                panelBorder:    '#e5e7eb',
                panelShadow:    '0 16px 48px rgba(0,0,0,0.12), 0 4px 12px rgba(0,0,0,0.07)',
                headerSep:      '#e5e7eb',
                titleColor:     '#111827',
                timeColor:      '#9ca3af',
                dividerColor:   '#f3f4f6',
                // stat cards
                card1Bg: '#fffbeb', card1Border: '#fde68a', card1Num: '#92400e', card1Label: '#d97706',
                card2Bg: '#eff6ff', card2Border: '#bfdbfe', card2Num: '#1e40af', card2Label: '#2563eb',
                card3Bg: '#f0fdf4', card3Border: '#bbf7d0', card3Num: '#14532d', card3Label: '#16a34a',
                msgBg:   '#f9fafb', msgColor: '#6b7280',
            },
            dark: {
                pillBg:         '#1e293b',
                pillBorder:     'rgba(255,255,255,0.12)',
                pillColor:      '#e2e8f0',
                pillShadow:     '0 4px 20px rgba(0,0,0,0.40), 0 1px 6px rgba(0,0,0,0.25)',
                iconColor:      '#86efac',
                badgeBg:        'rgba(134,239,172,0.15)',
                badgeBorder:    'rgba(134,239,172,0.30)',
                badgeColor:     '#86efac',
                chevronColor:   '#475569',
                panelBg:        '#1e293b',
                panelBorder:    'rgba(255,255,255,0.10)',
                panelShadow:    '0 20px 60px rgba(0,0,0,0.50)',
                headerSep:      'rgba(255,255,255,0.08)',
                titleColor:     '#f1f5f9',
                timeColor:      '#475569',
                dividerColor:   'rgba(255,255,255,0.06)',
                // stat cards
                card1Bg: 'rgba(251,191,36,0.10)', card1Border: 'rgba(251,191,36,0.20)', card1Num: '#fbbf24', card1Label: '#f59e0b',
                card2Bg: 'rgba(96,165,250,0.10)',  card2Border: 'rgba(96,165,250,0.20)',  card2Num: '#93c5fd', card2Label: '#60a5fa',
                card3Bg: 'rgba(134,239,172,0.10)', card3Border: 'rgba(134,239,172,0.20)', card3Num: '#86efac', card3Label: '#4ade80',
                msgBg:   'rgba(255,255,255,0.05)', msgColor: '#94a3b8',
            }
        };

        function isDark() {
            return document.documentElement.classList.contains('dark');
        }

        function applyFqTheme() {
            var t = isDark() ? THEME.dark : THEME.light;

            // — Pill button —
            var pill = document.getElementById('fq-toggle');
            if (pill) {
                pill.style.background   = t.pillBg;
                pill.style.border       = '1.5px solid ' + t.pillBorder;
                pill.style.color        = t.pillColor;
                pill.style.boxShadow    = t.pillShadow;
            }

            // — Icon antrian —
            var icon = pill ? pill.querySelector('svg') : null;
            if (icon) icon.style.color = t.iconColor;

            // — Badge jumlah —
            var badge = document.getElementById('fq-pill-count');
            if (badge) {
                badge.style.background  = t.badgeBg;
                badge.style.border      = '1px solid ' + t.badgeBorder;
                badge.style.color       = t.badgeColor;
            }

            // — Chevron —
            var chev = document.getElementById('fq-chevron');
            if (chev) chev.style.color = t.chevronColor;

            // — Panel —
            var panel = document.getElementById('fq-panel');
            if (panel) {
                panel.style.background  = t.panelBg;
                panel.style.border      = '1.5px solid ' + t.panelBorder;
                panel.style.boxShadow   = t.panelShadow;
            }

            // — Panel header divider —
            var hdr = panel ? panel.querySelector('div:first-child') : null;
            if (hdr) hdr.style.borderBottom = '1px solid ' + t.dividerColor;

            // — Live separator & title —
            var sep = hdr ? hdr.querySelector('span:nth-child(3)') : null;
            if (sep) sep.style.background = t.headerSep;
            var title = hdr ? hdr.querySelector('span:nth-child(4)') : null;
            if (title) title.style.color = t.titleColor;
            var timeEl = hdr ? hdr.querySelector('#fq-updated') : null;
            if (timeEl && timeEl.parentElement) timeEl.parentElement.style.color = t.timeColor;

            // — Stat cards —
            var cards = panel ? panel.querySelectorAll('.fq-stat-card') : [];
            var cfg = [
                {bg: t.card1Bg, border: t.card1Border, numColor: t.card1Num, lblColor: t.card1Label},
                {bg: t.card2Bg, border: t.card2Border, numColor: t.card2Num, lblColor: t.card2Label},
                {bg: t.card3Bg, border: t.card3Border, numColor: t.card3Num, lblColor: t.card3Label},
            ];
            cards.forEach(function(card, i) {
                if (!cfg[i]) return;
                card.style.background = cfg[i].bg;
                card.style.border     = '1px solid ' + cfg[i].border;
                var num = card.querySelector('.fq-stat-num');
                var lbl = card.querySelector('.fq-stat-lbl');
                if (num) num.style.color = cfg[i].numColor;
                if (lbl) lbl.style.color = cfg[i].lblColor;
            });

            // — Status message —
            var msg = document.getElementById('fq-msg');
            if (msg) {
                msg.style.background = t.msgBg;
                msg.style.color      = t.msgColor;
            }
        }

        // Observe perubahan class 'dark' di <html>
        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(m) {
                if (m.attributeName === 'class') applyFqTheme();
            });
        });
        observer.observe(document.documentElement, { attributes: true });

        // ── Toggle panel ──────────────────────────────────────────────
        window.fqToggle = function() {
            _fqOpen = !_fqOpen;
            var panel   = document.getElementById('fq-panel');
            var chevron = document.getElementById('fq-chevron');
            if (_fqOpen) {
                panel.style.display = 'block';
                chevron.style.transform = 'rotate(180deg)';
            } else {
                panel.style.display = 'none';
                chevron.style.transform = '';
            }
        };

        // ── Count-up animation ────────────────────────────────────────
        function animCount(el, to) {
            if (!el) return;
            var from = parseInt(el.textContent) || 0;
            to = parseInt(to) || 0;
            var dur = 500, t0 = performance.now();
            (function tick(now) {
                var p = Math.min((now - t0) / dur, 1);
                var e = 1 - Math.pow(1 - p, 3);
                el.textContent = Math.round(from + (to - from) * e);
                if (p < 1) requestAnimationFrame(tick);
            })(performance.now());
        }

        // ── Fetch data antrian ────────────────────────────────────────
        function fetchQueue() {
            fetch('index.php?action=get_queue_status')
                .then(function(r) { return r.json(); })
                .then(function(d) {
                    if (!d.success) return;

                    var pillEl = document.getElementById('fq-pill-count');
                    if (pillEl) pillEl.textContent = d.total_aktif;

                    animCount(document.getElementById('fq-menunggu'), d.menunggu);
                    animCount(document.getElementById('fq-diproses'), d.diproses);
                    animCount(document.getElementById('fq-estimasi'), d.estimasi_menit);

                    var upd = document.getElementById('fq-updated');
                    if (upd) upd.textContent = d.updated_at;

                    var msg = document.getElementById('fq-msg');
                    if (msg) {
                        if (d.total_aktif === 0)      msg.textContent = '✨ Antrian kosong — waktu terbaik booking!';
                        else if (d.total_aktif <= 2)  msg.textContent = '🟢 Antrian sedikit, giliran cepat tiba!';
                        else if (d.total_aktif <= 5)  msg.textContent = '🟡 Antrian ramai, segera amankan posisi.';
                        else                          msg.textContent = '🔴 Antrian penuh, coba waktu lain.';
                    }
                })
                .catch(function() {
                    var msg = document.getElementById('fq-msg');
                    if (msg) msg.textContent = '⚠️ Gagal memuat data antrian.';
                });
        }

        // ── Close panel jika klik di luar ────────────────────────────
        document.addEventListener('click', function(e) {
            if (_fqOpen && !document.getElementById('fq-widget').contains(e.target)) {
                _fqOpen = false;
                document.getElementById('fq-panel').style.display = 'none';
                document.getElementById('fq-chevron').style.transform = '';
            }
        });

        // Apply tema saat pertama load, lalu fetch data
        document.addEventListener('DOMContentLoaded', function() {
            applyFqTheme();
        });
        // Backup: apply segera (untuk kasus script jalan setelah DOM ready)
        if (document.readyState !== 'loading') applyFqTheme();

        fetchQueue();
        setInterval(fetchQueue, 30000);
    })();
    </script>

    <!-- Main Content Wrapper -->
    <main class="flex-grow">
