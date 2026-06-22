<?php
$hide_navbar = true;
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php?page=login');
    exit;
}
require_once BASE_PATH . '/app/Config/database.php';

$admin_nama = $_SESSION['admin_nama'] ?? 'Admin';
$current_section = $_GET['section'] ?? 'beranda';
$filter_date = $_GET['date'] ?? date('Y-m-d');

// ======= Fetch data for BERANDA =======
$beranda_menunggu = [];
$beranda_diproses = [];
$beranda_selesai = [];
$count_menunggu = 0;
$count_diproses = 0;
$count_selesai = 0;
$total_pendapatan_hari = 0;
$monthly_revenue = [];

if ($current_section === 'beranda') {
    try {
        // Bookings grouped by status — include payment info
        $stmt = $conn->prepare("SELECT b.id_booking, b.tanggal, b.status,
                p.nama, k.jenis, k.no_plat, l.nama_layanan, l.harga,
                a.nomor_antrian,
                py.metode as pay_metode, py.status as pay_status,
                t.id_transaksi, t.total as trx_total
            FROM booking b
            JOIN pelanggan p ON b.id_pelanggan = p.id_pelanggan
            JOIN kendaraan k ON b.id_kendaraan = k.id_kendaraan
            JOIN layanan l ON b.id_layanan = l.id_layanan
            LEFT JOIN antrian a ON b.id_booking = a.id_booking
            LEFT JOIN pembayaran py ON b.id_booking = py.id_booking
            LEFT JOIN transaksi t ON b.id_booking = t.id_booking
            ORDER BY b.created_at DESC LIMIT 100");
        $stmt->execute();
        $all_bookings = $stmt->fetchAll();

        foreach ($all_bookings as $bk) {
            if ($bk['status'] === 'pending') { $beranda_menunggu[] = $bk; }
            elseif ($bk['status'] === 'in_progress') { $beranda_diproses[] = $bk; }
            else { $beranda_selesai[] = $bk; }
        }
        $count_menunggu = count($beranda_menunggu);
        $count_diproses = count($beranda_diproses);
        $count_selesai = count($beranda_selesai);

        // Today's revenue
        $stmt = $conn->prepare("SELECT COALESCE(SUM(t.total), 0) as total FROM transaksi t WHERE DATE(t.tanggal) = :tgl");
        $stmt->execute(['tgl' => date('Y-m-d')]);
        $total_pendapatan_hari = (int)($stmt->fetch()['total'] ?? 0);

        // Monthly revenue trend (last 6 months)
        $stmt = $conn->query("SELECT DATE_FORMAT(t.tanggal, '%Y-%m') as bulan, SUM(t.total) as total
            FROM transaksi t
            WHERE t.tanggal >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
            GROUP BY DATE_FORMAT(t.tanggal, '%Y-%m')
            ORDER BY bulan ASC");
        $monthly_revenue = $stmt->fetchAll();
    } catch (PDOException $e) {
        // silently fail
    }
}

// Generate last 6 months to ensure chart always has data points
$last_6_months = [];
for ($i = 5; $i >= 0; $i--) {
    $last_6_months[date('Y-m', strtotime("-$i months"))] = 0;
}

// Fill with actual data if available
foreach ($monthly_revenue as $row) {
    if (isset($last_6_months[$row['bulan']])) {
        $last_6_months[$row['bulan']] = (int)$row['total'];
    }
}

$chart_monthly_labels_array = [];
$chart_monthly_values_array = [];
foreach ($last_6_months as $bulan => $total) {
    $dt = DateTime::createFromFormat('Y-m', $bulan);
    $chart_monthly_labels_array[] = $dt ? $dt->format('M Y') : $bulan;
    $chart_monthly_values_array[] = $total;
}

$chart_monthly_labels = json_encode($chart_monthly_labels_array);
$chart_monthly_values = json_encode($chart_monthly_values_array);

// ======= Fetch data for BOOKING section =======
$bookings = [];
if ($current_section === 'booking') {
    try {
        $stmt = $conn->prepare("SELECT b.id_booking, b.tanggal, b.status, b.estimasi_waktu,
                p.nama, p.email, k.jenis, k.no_plat, l.nama_layanan, l.harga,
                a.nomor_antrian, a.status as antrian_status
            FROM booking b
            JOIN pelanggan p ON b.id_pelanggan = p.id_pelanggan
            JOIN kendaraan k ON b.id_kendaraan = k.id_kendaraan
            JOIN layanan l ON b.id_layanan = l.id_layanan
            LEFT JOIN antrian a ON b.id_booking = a.id_booking
            ORDER BY b.created_at DESC LIMIT 50");
        $stmt->execute();
        $bookings = $stmt->fetchAll();
    } catch (PDOException $e) {}
}

// Transaksi list
$transaksi_list = [];
if ($current_section === 'transaksi') {
    try {
        $stmt = $conn->prepare("SELECT t.id_transaksi, t.tanggal, t.total,
                p.nama, k.no_plat, k.jenis, l.nama_layanan, py.metode, py.status as pay_status, b.id_booking, a.nomor_antrian
            FROM transaksi t
            JOIN booking b ON t.id_booking = b.id_booking
            JOIN pelanggan p ON b.id_pelanggan = p.id_pelanggan
            JOIN kendaraan k ON b.id_kendaraan = k.id_kendaraan
            JOIN layanan l ON b.id_layanan = l.id_layanan
            LEFT JOIN pembayaran py ON b.id_booking = py.id_booking
            LEFT JOIN antrian a ON b.id_booking = a.id_booking
            ORDER BY t.tanggal DESC LIMIT 50");
        $stmt->execute();
        $transaksi_list = $stmt->fetchAll();
    } catch (PDOException $e) {}
}

// Laporan summary
$laporan = [];
if ($current_section === 'laporan') {
    try {
        $stmt = $conn->query("SELECT DATE(b.tanggal) as tgl, COUNT(*) as total_booking,
                SUM(py.total) as total_pendapatan
            FROM booking b
            LEFT JOIN pembayaran py ON b.id_booking = py.id_booking
            GROUP BY DATE(b.tanggal)
            ORDER BY tgl DESC LIMIT 30");
        $laporan = $stmt->fetchAll();
    } catch (PDOException $e) {}
}

// Layanan management
$layanan_list = [];
if ($current_section === 'layanan') {
    try {
        $stmt = $conn->query("SELECT id_layanan, nama_layanan, harga, jenis_kendaraan FROM layanan ORDER BY jenis_kendaraan ASC, harga ASC");
        $layanan_list = $stmt->fetchAll();
    } catch (PDOException $e) {}
}

// Ulasan/Feedback
$ulasan_list = [];
if ($current_section === 'ulasan') {
    try {
        $stmt = $conn->query("SELECT f.id_feedback, f.rating, f.komentar, f.tanggal,
                p.nama, l.nama_layanan
            FROM feedback f
            JOIN booking b ON f.id_booking = b.id_booking
            JOIN pelanggan p ON b.id_pelanggan = p.id_pelanggan
            JOIN layanan l ON b.id_layanan = l.id_layanan
            ORDER BY f.tanggal DESC LIMIT 50");
        $ulasan_list = $stmt->fetchAll();
    } catch (PDOException $e) {}
}

?>
<?php include BASE_PATH . '/app/Views/layouts/header.php'; ?>

<style>
    .admin-layout { display: flex; min-height: 100vh; }

    /* Sidebar */
    .admin-sidebar {
        width: 250px; min-width: 250px;
        background: #f8faf5; border-right: 1px solid #e5e7eb;
        display: flex; flex-direction: column; padding: 2rem 1.5rem;
    }
    .admin-sidebar .brand {
        font-family: 'Playfair Display', serif;
        font-size: 1.75rem; font-weight: 700; color: #1a1a1a;
        margin-bottom: 2.5rem; padding-left: 0.5rem;
    }
    .admin-sidebar nav { display: flex; flex-direction: column; gap: 0.5rem; }
    .sidebar-link {
        display: block; padding: 0.75rem 1rem;
        border-radius: 0.75rem; border: 1px solid transparent;
        color: #4b5563;
        font-family: 'Inter', sans-serif; font-size: 0.9rem; font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .sidebar-link:hover { background: #edf2e1; color: #4b5320; }
    .sidebar-link.active {
        background: #4b5320; color: #fff;
        box-shadow: 0 4px 6px -1px rgba(75, 83, 32, 0.2);
    }
    .sidebar-bottom { margin-top: auto; padding-top: 1rem; }
    .sidebar-user {
        font-family: 'Inter', sans-serif; font-size: 0.8rem; font-weight: 500;
        color: #6b7280; margin-bottom: 0.75rem; padding-left: 0.5rem;
    }
    .sidebar-logout {
        display: block; padding: 0.6rem 1rem; border-radius: 0.75rem;
        background: transparent; border: 1px solid #e5e7eb;
        color: #6b7280; font-size: 0.85rem; text-decoration: none; font-weight: 500;
        text-align: center; transition: all 0.2s;
    }
    .sidebar-logout:hover { background: #fee2e2; color: #ef4444; border-color: #fca5a5; }

    /* Main Content */
    .admin-main {
        flex: 1; background: #fafafa; padding: 2.5rem;
        overflow-y: auto;
    }
    .admin-card {
        background: #fff; border: 1px solid #f3f4f6; border-radius: 1.5rem;
        padding: 1.75rem 2rem; margin-bottom: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03), 0 2px 4px -1px rgba(0,0,0,0.02);
    }
    .card-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 1.5rem;
    }
    .card-header h3 {
        font-family: 'Playfair Display', serif; font-size: 1.4rem;
        font-weight: 700; color: #1a1a1a; margin: 0;
    }
    .chart-area {
        width: 100%; min-height: 250px; background: #fafafa; border: 1px solid #f3f4f6;
        border-radius: 1rem; display: flex; align-items: center;
        justify-content: center; position: relative; overflow: hidden; padding: 1rem;
    }
    .chart-area canvas { position: relative; z-index: 1; }

    /* Table */
    .admin-table {
        width: 100%; border-collapse: collapse;
        font-family: 'Inter', sans-serif; font-size: 0.85rem;
    }
    .admin-table th {
        background: #f8faf5; color: #4b5563; padding: 1rem 1.25rem;
        text-align: left; font-weight: 600; white-space: nowrap; border-bottom: 2px solid #e5e7eb;
    }
    .admin-table th:first-child { border-radius: 0.75rem 0 0 0; }
    .admin-table th:last-child { border-radius: 0 0.75rem 0 0; }
    .admin-table td {
        padding: 1rem 1.25rem; border-bottom: 1px solid #f3f4f6; color: #374151; font-weight: 500;
    }
    .admin-table tr:hover td { background: #fafafa; }
    .admin-table tr:last-child td { border-bottom: none; }
    .badge {
        display: inline-block; padding: 0.25rem 0.75rem; border-radius: 9999px;
        font-size: 0.75rem; font-weight: 600; text-transform: capitalize;
    }
    .badge-pending { background: #fef3c7; color: #92400e; }
    .badge-progress { background: #e0e7ff; color: #3730a3; }
    .badge-done { background: #dcfce7; color: #166534; }
    .badge-paid { background: #dcfce7; color: #166534; }
    .badge-unpaid { background: #fee2e2; color: #991b1b; }
    .empty-state {
        text-align: center; padding: 4rem 2rem; color: #9ca3af;
        font-family: 'Inter', sans-serif; font-size: 0.95rem;
    }

    @media (max-width: 768px) {
        .admin-layout { flex-direction: column; }
        .admin-sidebar { width: 100%; min-width: 100%; flex-direction: row; flex-wrap: wrap; padding: 1rem; gap: 0.5rem; }
        .admin-sidebar .brand { width: 100%; margin-bottom: 0.5rem; }
        .admin-sidebar nav { flex-direction: row; flex-wrap: wrap; gap: 0.4rem; }
        .sidebar-bottom { margin-top: 0; padding-top: 0; }
    }

    @media print {
        body * { visibility: hidden; }
        #print-area, #print-area * { visibility: visible; }
        #print-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 0;
            box-shadow: none;
            border: none;
        }
        .print-hide { display: none !important; }
        .admin-sidebar { display: none !important; }
        /* Reset table styles for print */
        .admin-table { width: 100%; border-collapse: collapse; border: 1px solid #ddd; }
        .admin-table th, .admin-table td { border: 1px solid #ddd; padding: 8px; }
        .admin-table th { background-color: #f2f2f2 !important; color: #000 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .card-header h3 { margin-bottom: 1rem; text-align: center; width: 100%; }
    }

    /* --- Dark Mode Overrides --- */
    .dark .admin-sidebar { background: #1e293b; border-color: #334155; }
    .dark .admin-sidebar .brand { color: #f8fafc; }
    .dark .sidebar-link { color: #cbd5e1; }
    .dark .sidebar-link:hover { background: #334155; color: #a3b18a; }
    .dark .sidebar-link.active { background: #4b5320; color: #fff; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.5); }
    .dark .sidebar-user { color: #94a3b8; }
    .dark .sidebar-logout { border-color: #475569; color: #cbd5e1; }
    .dark .sidebar-logout:hover { background: #7f1d1d; color: #fca5a5; border-color: #b91c1c; }
    
    .dark .admin-main { background: #0f172a; }
    .dark .admin-card { background: #1e293b; border-color: #334155; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.5); }
    .dark .card-header h3 { color: #f8fafc; }
    .dark .chart-area { background: #1e293b; border-color: #334155; }
    
    .dark .admin-table th { background: #334155; color: #f8fafc; border-color: #475569; }
    .dark .admin-table td { border-color: #334155; color: #cbd5e1; }
    .dark .admin-table tr:hover td { background: #0f172a; }
    .dark .empty-state { color: #64748b; }

    .dark .stat-card { background: #1e293b; border-color: #334155; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.5); }
    .dark .stat-card .stat-label { color: #94a3b8; }
    .dark .stat-card .stat-value { color: #f8fafc; }
    .dark .stat-card .stat-icon.orange { background: #7c2d12; color: #fdba74; }
    .dark .stat-card .stat-icon.blue { background: #312e81; color: #a5b4fc; }
    .dark .stat-card .stat-icon.green { background: #14532d; color: #86efac; }
    .dark .stat-card .stat-icon.purple { background: #581c87; color: #d8b4fe; }

    .dark .tab-nav { border-color: #334155; }
    .dark .tab-btn { color: #94a3b8; }
    .dark .tab-btn:hover { color: #f8fafc; }
    .dark .tab-btn.active { color: #a3b18a; border-color: #a3b18a; }
    .dark .tab-btn:not(.active) .tab-count { background: #334155; color: #cbd5e1; }

    .dark .badge-pending { background: #78350f; color: #fde68a; }
    .dark .badge-progress { background: #312e81; color: #c7d2fe; }
    .dark .badge-done, .dark .badge-paid { background: #14532d; color: #bbf7d0; }
    .dark .badge-unpaid { background: #7f1d1d; color: #fecaca; }
</style>

<div class="admin-layout">
    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <div class="flex items-center justify-between mb-8">
            <div class="brand" style="margin-bottom:0; padding-left:0;">CrystalWash</div>
        </div>
        <nav>
            <a href="index.php?page=admin_dashboard&section=beranda" class="sidebar-link <?= $current_section === 'beranda' ? 'active' : '' ?>"><?= trans('admin_nav_dashboard') ?></a>
            <a href="index.php?page=admin_dashboard&section=laporan" class="sidebar-link <?= $current_section === 'laporan' ? 'active' : '' ?>"><?= trans('admin_nav_reports') ?></a>
            <a href="index.php?page=admin_dashboard&section=layanan" class="sidebar-link <?= $current_section === 'layanan' ? 'active' : '' ?>"><?= trans('admin_nav_services') ?></a>
            <a href="index.php?page=admin_dashboard&section=ulasan" class="sidebar-link <?= $current_section === 'ulasan' ? 'active' : '' ?>"><?= trans('admin_nav_reviews') ?></a>
        </nav>
    </aside>

    <!-- Main -->
    <div class="admin-main">
        <!-- Topbar -->
        <header class="flex justify-between items-center mb-8 pb-4 border-b border-gray-200 dark:border-gray-800 bg-transparent">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 font-serif capitalize">
                    <?php
                        $section_title = 'Dashboard';
                        if ($current_section === 'beranda') $section_title = trans('admin_nav_dashboard');
                        if ($current_section === 'laporan') $section_title = trans('admin_nav_reports');
                        if ($current_section === 'layanan') $section_title = trans('admin_nav_services');
                        if ($current_section === 'ulasan') $section_title = trans('admin_nav_reviews');
                        if ($current_section === 'transaksi') $section_title = trans('admin_sec_transaction_list');
                        if ($current_section === 'booking') $section_title = trans('admin_sec_booking_list');
                        echo htmlspecialchars($section_title);
                    ?>
                </h2>
            </div>
            <div class="flex items-center gap-6">
                <!-- Language Switcher -->
                <?php
                    $current_lang = $_SESSION['lang'] ?? 'id';
                    $page_url = $_SERVER['REQUEST_URI'];
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
                    <!-- Sun icon -->
                    <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <!-- Moon icon -->
                    <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                </button>

                <!-- User Profile & Logout -->
                <div class="flex items-center gap-3 border-l border-gray-200 dark:border-gray-700 pl-6">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-olive-100 flex items-center justify-center text-olive-700 font-bold text-sm">
                            <?= strtoupper(substr($admin_nama, 0, 1)) ?>
                        </div>
                        <span class="text-sm text-gray-700 dark:text-gray-200 font-medium hidden sm:inline-block"><?= trans('admin_hello') ?>, <?= htmlspecialchars($admin_nama) ?></span>
                    </div>
                    <a href="index.php?action=admin_logout" class="text-sm font-semibold text-red-500 hover:text-red-700 transition-colors ml-2 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span class="hidden sm:inline-block"><?= trans('admin_logout') ?></span>
                    </a>
                </div>
            </div>
        </header>

        <?php if ($current_section === 'beranda'): ?>
        <!-- ===== BERANDA DASHBOARD ===== -->
        <style>
            .stat-cards { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 2rem; }
            .stat-card {
                background: #fff; border: 1px solid #f3f4f6; border-radius: 1.5rem;
                padding: 1.5rem; display: flex; flex-direction: column;
                transition: all 0.3s ease; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03);
            }
            .stat-card:hover { box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); transform: translateY(-4px); }
            .stat-card .stat-label { font-size: 0.8rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; font-family: 'Inter', sans-serif; }
            .stat-card .stat-value { font-size: 2rem; font-weight: 800; color: #111827; font-family: 'Inter', sans-serif; }
            .stat-card .stat-icon { width: 48px; height: 48px; border-radius: 1rem; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; }
            .stat-card .stat-icon.orange { background: #ffedd5; color: #ea580c; }
            .stat-card .stat-icon.blue { background: #e0e7ff; color: #4f46e5; }
            .stat-card .stat-icon.green { background: #dcfce7; color: #16a34a; }
            .stat-card .stat-icon.purple { background: #f3e8ff; color: #9333ea; }

            .tab-nav { display: flex; gap: 0; border-bottom: 2px solid #f3f4f6; margin-bottom: 1.5rem; }
            .tab-btn {
                padding: 1rem 1.5rem; font-size: 0.95rem; font-weight: 600;
                background: none; border: none; border-bottom: 2px solid transparent;
                color: #6b7280; cursor: pointer; transition: all 0.3s ease; margin-bottom: -2px;
                font-family: 'Inter', sans-serif; display: inline-flex; align-items: center; gap: 0.5rem;
            }
            .tab-btn:hover { color: #374151; }
            .tab-btn.active { color: #4b5320; border-bottom-color: #4b5320; }
            .tab-btn .tab-count {
                font-size: 0.75rem; padding: 0.2rem 0.6rem; border-radius: 9999px;
                font-weight: 700; line-height: 1.4; transition: all 0.3s ease;
            }
            .tab-btn.active .tab-count { background: #4b5320; color: #fff; }
            .tab-btn:not(.active) .tab-count { background: #f3f4f6; color: #6b7280; }

            .tab-panel { display: none; }
            .tab-panel.active { display: block; animation: fadeIn 0.3s ease; }
            @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }

            @media (max-width: 768px) {
                .stat-cards { grid-template-columns: repeat(2, 1fr); }
            }
            @media (max-width: 480px) {
                .stat-cards { grid-template-columns: 1fr; }
            }
        </style>

        <!-- Stat Cards -->
        <div class="stat-cards">
            <div class="stat-card">
                <div class="stat-icon orange">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="stat-label"><?= trans('admin_stat_waiting') ?></div>
                <div class="stat-value"><?= $count_menunggu ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon blue">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div class="stat-label"><?= trans('admin_stat_processing') ?></div>
                <div class="stat-value"><?= $count_diproses ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="stat-label"><?= trans('admin_stat_completed') ?></div>
                <div class="stat-value"><?= $count_selesai ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="stat-label"><?= trans('admin_stat_revenue') ?></div>
                <div class="stat-value" style="font-size:1.2rem;">Rp <?= number_format($total_pendapatan_hari, 0, ',', '.') ?></div>
            </div>
        </div>

        <!-- Booking By Status -->
        <div class="admin-card">
            <div class="card-header"><h3><?= trans('admin_sec_booking_list') ?></h3></div>
            <div class="tab-nav">
                <button class="tab-btn active" onclick="switchTab(event, 'tab-menunggu')"><?= trans('admin_tab_waiting') ?> <span class="tab-count"><?= $count_menunggu ?></span></button>
                <button class="tab-btn" onclick="switchTab(event, 'tab-diproses')"><?= trans('admin_tab_processing') ?> <span class="tab-count"><?= $count_diproses ?></span></button>
                <button class="tab-btn" onclick="switchTab(event, 'tab-selesai')"><?= trans('admin_tab_completed') ?> <span class="tab-count"><?= $count_selesai ?></span></button>
            </div>

            <?php
            function renderBookingTable($list, $status_label, $badge_class, $status_key) {
                if (empty($list)) {
                    echo '<div class="empty-state">' . trans('admin_empty_booking_status') . ' ' . htmlspecialchars($status_label) . '.</div>';
                    return;
                }
                $show_action = ($status_key === 'pending' || $status_key === 'in_progress');
                echo '<div style="overflow-x:auto;"><table class="admin-table"><thead><tr>';
                echo '<th>' . trans('admin_col_no') . '</th><th>' . trans('admin_col_date') . '</th><th>' . trans('admin_col_customer') . '</th><th>' . trans('admin_col_vehicle') . '</th><th>' . trans('admin_col_service') . '</th><th>' . trans('admin_col_queue') . '</th><th>' . trans('admin_col_status') . '</th>';
                if ($show_action) echo '<th style="min-width:200px;">' . trans('admin_col_action') . '</th>';
                echo '</tr></thead><tbody>';
                foreach ($list as $i => $bk) {
                    echo '<tr>';
                    echo '<td>' . ($i + 1) . '</td>';
                    echo '<td>' . date('d M Y', strtotime($bk['tanggal'])) . '</td>';
                    echo '<td>' . htmlspecialchars($bk['nama']) . '</td>';
                    echo '<td>' . htmlspecialchars($bk['jenis']) . ' (' . htmlspecialchars($bk['no_plat']) . ')</td>';
                    echo '<td>' . htmlspecialchars($bk['nama_layanan']) . '</td>';
                    echo '<td>' . ($bk['nomor_antrian'] ?? '-') . '</td>';
                    echo '<td><span class="badge ' . $badge_class . '">' . htmlspecialchars($status_label) . '</span></td>';

                    if ($show_action) {
                        echo '<td>';
                        if ($status_key === 'pending') {
                            // Menunggu: hanya tombol Mulai Proses
                            echo '<form method="POST" action="index.php?action=admin_update_status" style="display:inline;margin:0;" onsubmit="confirmMulaiProses(event, this);">';
                            echo '<input type="hidden" name="id_booking" value="' . $bk['id_booking'] . '">';
                            echo '<input type="hidden" name="new_status" value="in_progress">';
                            echo '<button type="submit" style="background:#4f46e5;color:#fff;border:none;padding:0.5rem 1rem;border-radius:0.5rem;font-size:0.8rem;font-weight:600;cursor:pointer;white-space:nowrap;transition:background 0.2s;" onmouseover="this.style.background=\'#4338ca\'" onmouseout="this.style.background=\'#4f46e5\'">▶ ' . trans('admin_action_start') . '</button>';
                            echo '</form>';
                        } elseif ($status_key === 'in_progress') {
                            // Diproses: cek metode pembayaran
                            $metode = $bk['pay_metode'] ?? '';
                            $is_cod = (stripos($metode, 'cash') !== false || stripos($metode, 'tunai') !== false || stripos($metode, 'cod') !== false);
                            $is_unpaid = (($bk['pay_status'] ?? '') === 'unpaid' || ($bk['pay_status'] ?? '') === 'pending');

                            if ($is_cod && $is_unpaid) {
                                // COD/Tunai dan belum lunas → tombol Bayar (buka modal, sekaligus selesai)
                                $btnData = htmlspecialchars(json_encode([
                                    'id_booking' => $bk['id_booking'],
                                    'nomor_antrian' => ($bk['nomor_antrian'] ? date('Y-m-d', strtotime($bk['tanggal'])) . '/' . $bk['nomor_antrian'] : '-'),
                                    'nama' => $bk['nama'],
                                    'no_plat' => $bk['no_plat'],
                                    'jenis' => $bk['jenis'],
                                    'nama_layanan' => $bk['nama_layanan'],
                                    'no_nota' => $bk['id_transaksi'] ?? '-',
                                    'tanggal' => date('d/m/Y', strtotime($bk['tanggal'])),
                                    'total' => $bk['trx_total'] ?? $bk['harga']
                                ]));
                                echo '<button type="button" onclick="openPaymentModal(' . $btnData . ')" style="background:#4b5320;color:#fff;border:none;padding:0.5rem 1rem;border-radius:0.5rem;font-size:0.8rem;font-weight:600;cursor:pointer;white-space:nowrap;margin-right:0.5rem;transition:background 0.2s;" onmouseover="this.style.background=\'#3a4218\'" onmouseout="this.style.background=\'#4b5320\'">💰 ' . trans('admin_action_pay_finish') . '</button>';
                            } else {
                                // E-Wallet/Kartu atau sudah lunas → tombol Tandai Selesai
                                echo '<form method="POST" action="index.php?action=admin_update_status" style="display:inline;margin:0;" onsubmit="confirmSelesaiProses(event, this);">';
                                echo '<input type="hidden" name="id_booking" value="' . $bk['id_booking'] . '">';
                                echo '<input type="hidden" name="new_status" value="completed">';
                                echo '<button type="submit" style="background:#16a34a;color:#fff;border:none;padding:0.5rem 1rem;border-radius:0.5rem;font-size:0.8rem;font-weight:600;cursor:pointer;white-space:nowrap;transition:background 0.2s;" onmouseover="this.style.background=\'#15803d\'" onmouseout="this.style.background=\'#16a34a\'">✓ ' . trans('admin_action_mark_finish') . '</button>';
                                echo '</form>';
                            }
                        }
                        echo '</td>';
                    }
                    echo '</tr>';
                }
                echo '</tbody></table></div>';
            }
            ?>

            <div id="tab-menunggu" class="tab-panel active">
                <?php renderBookingTable($beranda_menunggu, 'Menunggu', 'badge-pending', 'pending'); ?>
            </div>
            <div id="tab-diproses" class="tab-panel">
                <?php renderBookingTable($beranda_diproses, 'Diproses', 'badge-progress', 'in_progress'); ?>
            </div>
            <div id="tab-selesai" class="tab-panel">
                <?php renderBookingTable($beranda_selesai, 'Selesai', 'badge-done', 'completed'); ?>
            </div>
        </div>

        <!-- Monthly Revenue Trend -->
        <div class="admin-card">
            <div class="card-header"><h3><?= trans('admin_sec_revenue_trend') ?></h3></div>
            <div class="chart-area" style="min-height:280px;">
                <canvas id="chartRevenue"></canvas>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <style>
            .swal2-confirm, .swal2-cancel {
                color: #ffffff !important;
                font-weight: bold !important;
                letter-spacing: 0.5px;
                padding: 0.6rem 1.5rem !important;
                border-radius: 0.5rem !important;
            }
            .swal2-title {
                color: #1a1a1a !important;
            }
            .swal2-html-container {
                color: #4b5563 !important;
            }
        </style>
        <?php if (isset($_SESSION['sweetalert_success'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Berhasil!',
                    text: "<?= addslashes($_SESSION['sweetalert_success']) ?>",
                    icon: 'success',
                    confirmButtonColor: '#4b5320',
                    confirmButtonText: 'Tutup',
                    timer: 3000,
                    timerProgressBar: true
                });
            });
        </script>
        <?php unset($_SESSION['sweetalert_success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['sweetalert_error'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Gagal!',
                    text: "<?= addslashes($_SESSION['sweetalert_error']) ?>",
                    icon: 'error',
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: 'Tutup'
                });
            });
        </script>
        <?php unset($_SESSION['sweetalert_error']); ?>
        <?php endif; ?>

        <script>
        function confirmMulaiProses(e, form) {
            e.preventDefault();
            Swal.fire({
                title: 'Mulai Proses Booking?',
                text: "Pastikan kendaraan sudah siap untuk dicuci.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#9ca3af',
                confirmButtonText: 'Ya, Mulai!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'shadow-md hover:shadow-lg transition-all',
                    cancelButton: 'shadow-sm hover:shadow-md transition-all'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        function confirmSelesaiProses(e, form) {
            e.preventDefault();
            Swal.fire({
                title: 'Tandai Selesai?',
                text: "Pastikan proses cuci kendaraan telah selesai sepenuhnya.",
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#9ca3af',
                confirmButtonText: 'Ya, Selesai!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'shadow-md hover:shadow-lg transition-all',
                    cancelButton: 'shadow-sm hover:shadow-md transition-all'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
        function switchTab(e, tabId) {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
            e.currentTarget.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('chartRevenue');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?= $chart_monthly_labels ?>,
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: <?= $chart_monthly_values ?>,
                        borderColor: '#5a6c3e',
                        backgroundColor: 'rgba(163, 177, 138, 0.2)',
                        borderWidth: 2.5,
                        pointBackgroundColor: '#5a6c3e',
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { labels: { color: '#fff', font: { weight: 600 } } }
                    },
                    scales: {
                        x: { ticks: { color: '#ddd', font: { size: 11 } }, grid: { color: 'rgba(255,255,255,0.08)' } },
                        y: { ticks: { color: '#ddd', callback: function(v) { return 'Rp ' + v.toLocaleString('id-ID'); } }, grid: { color: 'rgba(255,255,255,0.08)' } }
                    }
                }
            });
        });
        </script>

        <?php elseif ($current_section === 'booking'): ?>
        <!-- ===== BOOKING ===== -->
        <div class="admin-card">
            <div class="card-header"><h3><?= trans('admin_sec_booking_list') ?></h3></div>
            <?php if (empty($bookings)): ?>
                <div class="empty-state"><?= trans('admin_empty_booking') ?></div>
            <?php else: ?>
            <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead><tr>
                    <th><?= trans('admin_col_no') ?></th><th><?= trans('admin_col_date') ?></th><th><?= trans('admin_col_customer') ?></th><th><?= trans('admin_col_vehicle') ?></th><th><?= trans('admin_col_service') ?></th><th><?= trans('admin_col_queue') ?></th><th><?= trans('admin_col_status') ?></th>
                </tr></thead>
                <tbody>
                <?php foreach ($bookings as $i => $bk): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= date('d M Y', strtotime($bk['tanggal'])) ?></td>
                    <td><?= htmlspecialchars($bk['nama']) ?></td>
                    <td><?= htmlspecialchars($bk['jenis']) ?> (<?= htmlspecialchars($bk['no_plat']) ?>)</td>
                    <td><?= htmlspecialchars($bk['nama_layanan']) ?></td>
                    <td><?= $bk['nomor_antrian'] ?? '-' ?></td>
                    <td><span class="badge <?= $bk['status'] === 'pending' ? 'badge-pending' : ($bk['status'] === 'in_progress' ? 'badge-progress' : 'badge-done') ?>"><?= $bk['status'] === 'pending' ? trans('admin_tab_waiting') : ($bk['status'] === 'in_progress' ? trans('admin_tab_processing') : trans('admin_tab_completed')) ?></span></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
            <?php endif; ?>
        </div>

        <?php elseif ($current_section === 'transaksi'): ?>
        <!-- ===== TRANSAKSI ===== -->
        <div class="admin-card">
            <div class="card-header"><h3><?= trans('admin_sec_transaction_list') ?></h3></div>
            <?php if (empty($transaksi_list)): ?>
                <div class="empty-state"><?= trans('admin_empty_transaction') ?></div>
            <?php else: ?>
            <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead><tr>
                    <th><?= trans('admin_col_no') ?></th><th><?= trans('admin_col_date') ?></th><th><?= trans('admin_col_customer') ?></th><th><?= trans('admin_col_plate') ?></th><th><?= trans('admin_col_service') ?></th><th><?= trans('admin_col_pay_method') ?></th><th><?= trans('admin_col_total_price') ?></th><th><?= trans('admin_col_status') ?></th><th><?= trans('admin_col_action') ?></th>
                </tr></thead>
                <tbody>
                <?php foreach ($transaksi_list as $i => $tr): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= date('d M Y H:i', strtotime($tr['tanggal'])) ?></td>
                    <td><?= htmlspecialchars($tr['nama']) ?></td>
                    <td><?= htmlspecialchars($tr['no_plat'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($tr['nama_layanan']) ?></td>
                    <td><?= htmlspecialchars($tr['metode'] ?? '-') ?></td>
                    <td>Rp <?= number_format($tr['total'], 0, ',', '.') ?></td>
                    <td><span class="badge <?= ($tr['pay_status'] ?? '') === 'paid' ? 'badge-paid' : 'badge-unpaid' ?>"><?= ($tr['pay_status'] ?? '') === 'paid' ? trans('admin_badge_paid') : trans('admin_badge_unpaid') ?></span></td>
                    <td>
                        <?php if ((strtolower($tr['metode'] ?? '') === 'tunai' || strtolower($tr['metode'] ?? '') === 'cod') && ($tr['pay_status'] ?? '') !== 'paid'): ?>
                            <?php
                                $btnData = htmlspecialchars(json_encode([
                                    'id_booking' => $tr['id_booking'],
                                    'nomor_antrian' => ($tr['nomor_antrian'] ? date('Y-m-d', strtotime($tr['tanggal'])) . '/' . $tr['nomor_antrian'] : '-'),
                                    'nama' => $tr['nama'],
                                    'no_plat' => $tr['no_plat'],
                                    'jenis' => $tr['jenis'],
                                    'nama_layanan' => $tr['nama_layanan'],
                                    'no_nota' => $tr['id_transaksi'],
                                    'tanggal' => date('d/m/Y', strtotime($tr['tanggal'])),
                                    'total' => $tr['total']
                                ]));
                            ?>
                            <button type="button" onclick="openPaymentModal(<?= $btnData ?>)" class="px-3 py-1 rounded text-xs font-semibold" style="background:#5a6c3e;color:#fff;border:none;cursor:pointer;"><?= trans('admin_action_pay') ?></button>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
            <?php endif; ?>
        </div>

        <?php elseif ($current_section === 'laporan'): ?>
        <!-- ===== LAPORAN ===== -->
        <div class="admin-card" id="print-area">
            <div class="card-header">
                <h3><?= trans('admin_sec_report_daily') ?></h3>
                <button onclick="window.print()" class="px-4 py-2 text-sm font-medium rounded print-hide" style="background:#5a6c3e;color:#fff;border:none;cursor:pointer;display:inline-flex;align-items:center;">
                    <svg style="width:16px;height:16px;margin-right:6px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    <?= trans('admin_btn_print') ?>
                </button>
            </div>
            <?php if (empty($laporan)): ?>
                <div class="empty-state"><?= trans('admin_empty_report') ?></div>
            <?php else: ?>
            <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead><tr>
                    <th><?= trans('admin_col_date') ?></th><th><?= trans('admin_col_total_booking') ?></th><th><?= trans('admin_col_total_revenue') ?></th>
                </tr></thead>
                <tbody>
                <?php foreach ($laporan as $lp): ?>
                <tr>
                    <td><?= date('d M Y', strtotime($lp['tgl'])) ?></td>
                    <td><?= $lp['total_booking'] ?></td>
                    <td>Rp <?= number_format($lp['total_pendapatan'] ?? 0, 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
            <?php endif; ?>
        </div>
        
        <?php elseif ($current_section === 'layanan'): ?>
        <!-- ===== LAYANAN ===== -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-sm mb-6 overflow-hidden">
            <div class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                <h3 class="font-bold text-gray-800 dark:text-gray-100 text-base m-0"><?= trans('admin_sec_service_data') ?></h3>
            </div>
            
            <div class="p-6">
                <!-- Tambah Data Button -->
                <button onclick="document.getElementById('modal-add').classList.add('show')" class="px-5 py-2.5 rounded-xl text-sm font-semibold mb-6 inline-flex items-center transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5" style="background-color: #4b5320; color: white; border: none;">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <?= trans('admin_btn_add_service') ?>
                </button>

                <?php if (empty($layanan_list)): ?>
                    <div class="empty-state py-8 text-center text-gray-500 dark:text-gray-400"><?= trans('admin_empty_service') ?></div>
                <?php else: ?>
                <div class="overflow-x-auto rounded-sm">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th><?= trans('admin_col_no') ?>.</th>
                                <th><?= trans('admin_mod_label_service') ?></th>
                                <th><?= trans('admin_mod_label_cost') ?></th>
                                <th class="text-center w-64"><?= trans('admin_col_action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($layanan_list as $i => $l): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($l['nama_layanan']) ?></td>
                                <td>Rp <?= number_format($l['harga'], 0, ',', '.') ?></td>
                                <td>
                                    <div class="flex justify-center space-x-3">
                                        <!-- Edit Button -->
                                        <button onclick="openEditModal(<?= $l['id_layanan'] ?>, '<?= htmlspecialchars(addslashes($l['nama_layanan'])) ?>', <?= $l['harga'] ?>, '<?= htmlspecialchars(addslashes($l['jenis_kendaraan'] ?? 'Car')) ?>')" class="px-4 py-1.5 rounded text-sm inline-flex items-center transition-colors shadow-sm" style="background-color: #ffc107; color: white; border: 1px solid #e0a800;">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            <?= trans('admin_action_edit') ?>
                                        </button>
                                        <!-- Delete Button (Triggers Custom Modal) -->
                                        <button type="button" onclick="openDeleteModal(<?= $l['id_layanan'] ?>)" class="px-4 py-1.5 rounded text-sm inline-flex items-center transition-colors shadow-sm" style="background-color: #dc3545; color: white; border: 1px solid #bd2130;">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            <?= trans('admin_action_delete') ?>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Add Modal -->
        <div id="modal-add" class="payment-modal-overlay">
            <div class="payment-modal-content" style="max-width: 450px;">
                <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 flex justify-between items-center" style="border-radius: 8px 8px 0 0;">
                    <h3 class="font-bold text-gray-800 text-base m-0"><?= trans('admin_mod_add_title') ?></h3>
                    <button type="button" onclick="document.getElementById('modal-add').classList.remove('show')" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form action="index.php?action=admin_manage_layanan" method="POST" class="p-6">
                    <input type="hidden" name="manage_action" value="add">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1"><?= trans('admin_mod_label_service') ?></label>
                        <input type="text" name="nama_layanan" required class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-1 focus:ring-olive-500 focus:border-olive-500 outline-none">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1"><?= trans('admin_mod_label_type') ?></label>
                        <select name="jenis_kendaraan" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-1 focus:ring-olive-500 focus:border-olive-500 outline-none">
                            <option value="Car">Mobil</option>
                            <option value="Motorcycle">Motor</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1"><?= trans('admin_mod_label_cost') ?></label>
                        <input type="number" name="harga" required min="0" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-1 focus:ring-olive-500 focus:border-olive-500 outline-none">
                    </div>
                    <div class="flex justify-end space-x-3 pt-2">
                        <button type="button" onclick="document.getElementById('modal-add').classList.remove('show')" class="px-4 py-2 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50 transition-colors"><?= trans('admin_mod_btn_cancel') ?></button>
                        <button type="submit" class="px-4 py-2 bg-olive-700 text-white rounded text-sm hover:bg-olive-800 transition-colors"><?= trans('admin_mod_btn_save') ?></button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Modal -->
        <div id="modal-edit" class="payment-modal-overlay">
            <div class="payment-modal-content" style="max-width: 450px;">
                <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 flex justify-between items-center" style="border-radius: 8px 8px 0 0;">
                    <h3 class="font-bold text-gray-800 text-base m-0"><?= trans('admin_mod_edit_title') ?></h3>
                    <button type="button" onclick="document.getElementById('modal-edit').classList.remove('show')" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form action="index.php?action=admin_manage_layanan" method="POST" class="p-6">
                    <input type="hidden" name="manage_action" value="edit">
                    <input type="hidden" name="id_layanan" id="edit_id">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1"><?= trans('admin_mod_label_service') ?></label>
                        <input type="text" name="nama_layanan" id="edit_nama" required class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-1 focus:ring-olive-500 focus:border-olive-500 outline-none">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1"><?= trans('admin_mod_label_type') ?></label>
                        <select name="jenis_kendaraan" id="edit_jenis" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-1 focus:ring-olive-500 focus:border-olive-500 outline-none">
                            <option value="Car">Mobil</option>
                            <option value="Motorcycle">Motor</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1"><?= trans('admin_mod_label_cost') ?></label>
                        <input type="number" name="harga" id="edit_harga" required min="0" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-1 focus:ring-olive-500 focus:border-olive-500 outline-none">
                    </div>
                    <div class="flex justify-end space-x-3 pt-2">
                        <button type="button" onclick="document.getElementById('modal-edit').classList.remove('show')" class="px-4 py-2 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50 transition-colors"><?= trans('admin_mod_btn_cancel') ?></button>
                        <button type="submit" class="px-4 py-2 bg-olive-700 text-white rounded text-sm hover:bg-olive-800 transition-colors"><?= trans('admin_mod_btn_save_changes') ?></button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div id="modal-delete" class="payment-modal-overlay">
            <div class="payment-modal-content" style="max-width: 350px;">
                <div class="p-8 text-center">
                    <svg class="mx-auto mb-4 text-red-500 w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <h3 class="mb-6 text-lg font-normal text-gray-700"><?= trans('admin_mod_del_title') ?></h3>
                    <form action="index.php?action=admin_manage_layanan" method="POST" class="flex justify-center space-x-3">
                        <input type="hidden" name="manage_action" value="delete">
                        <input type="hidden" name="id_layanan" id="delete_id">
                        <button type="button" onclick="document.getElementById('modal-delete').classList.remove('show')" class="text-gray-500 bg-white hover:bg-gray-100 border border-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors"><?= trans('admin_mod_btn_cancel') ?></button>
                        <button type="submit" class="text-white bg-red-600 hover:bg-red-800 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors border border-transparent"><?= trans('admin_mod_btn_yes_del') ?></button>
                    </form>
                </div>
            </div>
        </div>

        <script>
        function openEditModal(id, nama, harga, jenis) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_harga').value = harga;
            document.getElementById('edit_jenis').value = jenis;
            document.getElementById('modal-edit').classList.add('show');
        }

        function openDeleteModal(id) {
            document.getElementById('delete_id').value = id;
            document.getElementById('modal-delete').classList.add('show');
        }
        </script>

        <?php elseif ($current_section === 'ulasan'): ?>
        <!-- ===== ULASAN ===== -->
        <div class="admin-card">
            <div class="card-header"><h3><?= trans('admin_sec_review_list') ?></h3></div>
            <?php if (empty($ulasan_list)): ?>
                <div class="empty-state"><?= trans('admin_empty_review') ?></div>
            <?php else: ?>
            <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead><tr>
                    <th><?= trans('admin_col_date') ?></th><th><?= trans('admin_col_customer') ?></th><th><?= trans('admin_col_service') ?></th><th><?= trans('admin_col_rating') ?></th><th><?= trans('admin_col_comment') ?></th>
                </tr></thead>
                <tbody>
                <?php foreach ($ulasan_list as $u): ?>
                <tr>
                    <td class="whitespace-nowrap"><?= date('d M Y', strtotime($u['tanggal'])) ?></td>
                    <td class="whitespace-nowrap font-medium"><?= htmlspecialchars($u['nama']) ?></td>
                    <td class="whitespace-nowrap"><?= htmlspecialchars($u['nama_layanan']) ?></td>
                    <td class="whitespace-nowrap">
                        <div class="flex items-center text-yellow-400">
                            <?php for ($i=0; $i < $u['rating']; $i++): ?>
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <?php endfor; ?>
                        </div>
                    </td>
                    <td class="max-w-md break-words text-sm"><?= htmlspecialchars($u['komentar']) ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    </div>
</div>

<!-- Payment Modal -->
<style>
/* Custom Modal Overlay styles */
.payment-modal-overlay {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: rgba(0, 0, 0, 0.4);
    backdrop-filter: blur(4px);
    z-index: 9999;
    display: none; /* hidden by default */
    align-items: center;
    justify-content: center;
    padding: 1rem;
}
.payment-modal-overlay.show {
    display: flex;
}
.payment-modal-content {
    background-color: #fff;
    border-radius: 1.5rem;
    width: 100%;
    max-width: 600px;
    box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
    display: flex;
    flex-direction: column;
    max-height: 90vh;
    border: 1px solid #f3f4f6;
}
.payment-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #f3f4f6;
}
.payment-modal-header h3 {
    margin: 0;
    font-size: 1.25rem;
    color: #111827;
    font-weight: 700;
    font-family: 'Playfair Display', serif;
}
.payment-modal-close {
    background: #f3f4f6; border: none; font-size: 1.25rem; color: #6b7280; cursor: pointer; line-height: 1; border-radius: 9999px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; transition: all 0.2s;
}
.payment-modal-close:hover { background: #e5e7eb; color: #374151; }
.payment-modal-body {
    padding: 1.5rem;
    overflow-y: auto;
}
.payment-field-group {
    margin-bottom: 1rem;
}
.payment-field-group label {
    display: block;
    font-size: 0.85rem;
    color: #555;
    margin-bottom: 0.3rem;
    font-weight: 600;
}
.payment-field-group input {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.75rem;
    font-size: 0.9rem;
    color: #111827;
    box-sizing: border-box;
    font-family: inherit;
    transition: all 0.2s;
    background-color: #fff;
}
.payment-field-group input:focus {
    outline: none;
    border-color: #4b5320;
    box-shadow: 0 0 0 3px rgba(75, 83, 32, 0.1);
}
.payment-field-group input[readonly] {
    background-color: #f9fafb;
    color: #6b7280;
    border-color: #f3f4f6;
}
.payment-modal-footer {
    padding: 0 2rem 2rem;
}
.btn-simpan {
    display: block;
    width: 100%;
    padding: 0.875rem 1.5rem;
    background-color: #4b5320;
    color: #fff;
    border: none;
    border-radius: 0.75rem;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: 0 4px 6px -1px rgba(75, 83, 32, 0.2);
}
.btn-simpan:hover { background-color: #3a4218; transform: translateY(-1px); }
</style>

<div id="modal-payment" class="payment-modal-overlay">
    <div class="payment-modal-content">
        <div class="payment-modal-header">
            <h3><?= trans('admin_mod_pay_title') ?></h3>
            <button type="button" class="payment-modal-close" onclick="document.getElementById('modal-payment').classList.remove('show')">&times;</button>
        </div>
        <form action="index.php?action=admin_pay_transaction" method="POST" style="margin:0; display:flex; flex-direction:column; min-height:0;">
            <div class="payment-modal-body p-6">
                <input type="hidden" name="id_booking" id="pay_id_booking">
                <input type="hidden" id="pay_total" name="total_tagihan">
                
                <!-- Kotak Ringkasan Transaksi -->
                <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-5 mb-6 shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex justify-between items-center mb-4">
                        <span id="pay_plat_display" class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider bg-white dark:bg-gray-900 px-3 py-1 rounded-md border border-gray-200 dark:border-gray-600"></span>
                        <span id="pay_layanan_display" class="text-sm font-medium text-gray-600 dark:text-gray-400 text-right"></span>
                    </div>
                    
                    <div class="border-b border-dashed border-gray-300 dark:border-gray-600 mb-4"></div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Tagihan</span>
                        <span id="pay_total_display" class="text-3xl font-bold text-olive-700 dark:text-olive-400"></span>
                    </div>
                </div>
                
                <!-- Input Uang -->
                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-2">Amount Paid (Uang Dibayar)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 font-bold text-lg">Rp</span>
                            <input type="number" id="pay_uang" name="uang_dibayarkan" required oninput="calculateChange()"
                                class="w-full p-4 pl-12 text-xl font-bold border-2 border-gray-300 dark:border-gray-600 focus:border-olive-500 dark:focus:border-olive-500 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 outline-none transition-colors shadow-sm">
                        </div>
                        <div id="pay_error" style="color: #dc2626; font-size: 0.75rem; margin-top: 0.25rem; display: none;">Uang yang dibayarkan kurang dari total tagihan!</div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-2">Change (Kembalian)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-red-500 font-bold text-lg">Rp</span>
                            <input type="text" id="pay_kembalian" readonly
                                class="w-full p-4 pl-12 text-xl border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-gray-100 dark:bg-gray-800 text-red-500 font-bold outline-none cursor-not-allowed">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="payment-modal-footer">
                <button type="submit" id="pay_submit_btn" class="btn-simpan"><?= trans('admin_mod_btn_save') ?></button>
            </div>
        </form>
    </div>
</div>

<script>
function openPaymentModal(data) {
    document.getElementById('pay_id_booking').value = data.id_booking;
    
    document.getElementById('pay_plat_display').textContent = data.no_plat;
    document.getElementById('pay_layanan_display').textContent = data.nama_layanan;
    
    document.getElementById('pay_total').value = data.total;
    document.getElementById('pay_total_display').textContent = 'Rp ' + parseInt(data.total).toLocaleString('id-ID');

    document.getElementById('pay_uang').value = '';
    document.getElementById('pay_kembalian').value = '';
    document.getElementById('pay_submit_btn').disabled = true;
    document.getElementById('pay_submit_btn').style.opacity = '0.5';
    document.getElementById('pay_error').style.display = 'none';
    
    document.getElementById('modal-payment').classList.add('show');
}

function calculateChange() {
    const total = parseInt(document.getElementById('pay_total').value) || 0;
    const paid = parseInt(document.getElementById('pay_uang').value) || 0;
    const submitBtn = document.getElementById('pay_submit_btn');
    const errorMsg = document.getElementById('pay_error');
    
    if (paid >= total && total > 0) {
        document.getElementById('pay_kembalian').value = (paid - total).toLocaleString('id-ID');
        submitBtn.disabled = false;
        submitBtn.style.opacity = '1';
        errorMsg.style.display = 'none';
    } else {
        document.getElementById('pay_kembalian').value = '';
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.5';
        if (paid > 0) {
            errorMsg.style.display = 'block';
        } else {
            errorMsg.style.display = 'none';
        }
    }
}

document.querySelector('form[action="index.php?action=admin_pay_transaction"]').addEventListener('submit', function(e) {
    const total = parseInt(document.getElementById('pay_total').value) || 0;
    const paid = parseInt(document.getElementById('pay_uang').value) || 0;
    if (paid < total) {
        e.preventDefault();
        alert('Transaksi Gagal: Nominal uang yang dibayarkan kurang dari total tagihan!');
    }
});
</script>

<?php include BASE_PATH . '/app/Views/layouts/footer.php'; ?>
