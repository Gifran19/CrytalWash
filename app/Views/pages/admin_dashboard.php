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

$chart_monthly_labels = json_encode(array_map(function($r) {
    $dt = DateTime::createFromFormat('Y-m', $r['bulan']);
    return $dt ? $dt->format('M Y') : $r['bulan'];
}, $monthly_revenue));
$chart_monthly_values = json_encode(array_map(fn($r) => (int)$r['total'], $monthly_revenue));

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
        width: 200px; min-width: 200px;
        background: #f0f0ea; border-right: 1px solid #d4d4c8;
        display: flex; flex-direction: column; padding: 1.5rem 1rem;
    }
    .admin-sidebar .brand {
        font-family: 'Playfair Display', serif;
        font-size: 1.4rem; font-weight: 700; color: #1a1a1a;
        margin-bottom: 2rem; padding-left: 0.5rem;
    }
    .admin-sidebar nav { display: flex; flex-direction: column; gap: 0.6rem; }
    .sidebar-link {
        display: block; padding: 0.65rem 1.2rem;
        border-radius: 0.5rem; border: 1.5px solid #a3b18a;
        background: #c8d4a9; color: #2d3a1a;
        font-family: 'Inter', sans-serif; font-size: 0.85rem; font-weight: 600;
        text-decoration: none; text-align: center;
        transition: all 0.2s;
    }
    .sidebar-link:hover { background: #b0c090; }
    .sidebar-link.active {
        background: #5a6c3e; color: #fff; border-color: #4a5a32;
    }
    .sidebar-bottom { margin-top: auto; padding-top: 1rem; }
    .sidebar-user {
        font-family: 'Inter', sans-serif; font-size: 0.75rem;
        color: #777; margin-bottom: 0.5rem; padding-left: 0.5rem;
    }
    .sidebar-logout {
        display: block; padding: 0.5rem 1rem; border-radius: 0.4rem;
        background: transparent; border: 1px solid #c4c4b8;
        color: #888; font-size: 0.8rem; text-decoration: none;
        text-align: center; transition: all 0.2s;
    }
    .sidebar-logout:hover { background: #e8e8e0; color: #555; }

    /* Main Content */
    .admin-main {
        flex: 1; background: #f9f9f6; padding: 2rem;
        overflow-y: auto;
    }
    .admin-card {
        background: #fff; border: 1px solid #ddd; border-radius: 1rem;
        padding: 1.5rem 2rem; margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }
    .card-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 1rem;
    }
    .card-header h3 {
        font-family: 'Playfair Display', serif; font-size: 1.15rem;
        font-weight: 700; color: #1a1a1a; margin: 0;
    }
    .date-picker {
        padding: 0.4rem 0.8rem; border: 1px solid #bbb; border-radius: 0.4rem;
        font-family: 'Inter', sans-serif; font-size: 0.8rem;
        background: #f5f5ef; cursor: pointer;
    }
    .chart-area {
        width: 100%; min-height: 220px; background: #5a6c3e;
        border-radius: 0.75rem; display: flex; align-items: center;
        justify-content: center; position: relative; overflow: hidden;
    }
    .chart-area canvas { position: relative; z-index: 1; }

    /* Table */
    .admin-table {
        width: 100%; border-collapse: collapse;
        font-family: 'Inter', sans-serif; font-size: 0.82rem;
    }
    .admin-table th {
        background: #5a6c3e; color: #fff; padding: 0.7rem 1rem;
        text-align: left; font-weight: 600; white-space: nowrap;
    }
    .admin-table th:first-child { border-radius: 0.5rem 0 0 0; }
    .admin-table th:last-child { border-radius: 0 0.5rem 0 0; }
    .admin-table td {
        padding: 0.65rem 1rem; border-bottom: 1px solid #eee; color: #333;
    }
    .admin-table tr:hover td { background: #f7f7f2; }
    .badge {
        display: inline-block; padding: 0.2rem 0.6rem; border-radius: 1rem;
        font-size: 0.7rem; font-weight: 600; text-transform: capitalize;
    }
    .badge-pending { background: #fef3c7; color: #92400e; }
    .badge-progress { background: #dbeafe; color: #1e40af; }
    .badge-done { background: #d1fae5; color: #065f46; }
    .badge-paid { background: #d1fae5; color: #065f46; }
    .badge-unpaid { background: #fee2e2; color: #991b1b; }
    .empty-state {
        text-align: center; padding: 3rem; color: #aaa;
        font-family: 'Inter', sans-serif; font-size: 0.9rem;
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
</style>

<div class="admin-layout">
    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <div class="brand">CrystalWash</div>
        <nav>
            <a href="index.php?page=admin_dashboard&section=beranda" class="sidebar-link <?= $current_section === 'beranda' ? 'active' : '' ?>">Beranda</a>
            <a href="index.php?page=admin_dashboard&section=laporan" class="sidebar-link <?= $current_section === 'laporan' ? 'active' : '' ?>">Laporan</a>
            <a href="index.php?page=admin_dashboard&section=layanan" class="sidebar-link <?= $current_section === 'layanan' ? 'active' : '' ?>">Layanan</a>
            <a href="index.php?page=admin_dashboard&section=ulasan" class="sidebar-link <?= $current_section === 'ulasan' ? 'active' : '' ?>">Ulasan</a>
        </nav>
        <div class="sidebar-bottom">
            <div class="sidebar-user">Halo, <?= htmlspecialchars($admin_nama) ?></div>
            <a href="index.php?action=admin_logout" class="sidebar-logout">Logout</a>
        </div>
    </aside>

    <!-- Main -->
    <div class="admin-main">

        <?php if ($current_section === 'beranda'): ?>
        <!-- ===== BERANDA DASHBOARD ===== -->
        <style>
            .stat-cards { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.5rem; }
            .stat-card {
                background: #fff; border: 1px solid #e5e7eb; border-radius: 0.75rem;
                padding: 1.25rem 1.5rem; display: flex; flex-direction: column;
                transition: all 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            }
            .stat-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); transform: translateY(-2px); }
            .stat-card .stat-label { font-size: 0.75rem; font-weight: 600; color: #888; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.4rem; }
            .stat-card .stat-value { font-size: 1.6rem; font-weight: 800; color: #1a1a1a; }
            .stat-card .stat-icon { width: 36px; height: 36px; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; margin-bottom: 0.75rem; }
            .stat-card .stat-icon.orange { background: #fff7ed; color: #ea580c; }
            .stat-card .stat-icon.blue { background: #eff6ff; color: #2563eb; }
            .stat-card .stat-icon.green { background: #f0fdf4; color: #16a34a; }
            .stat-card .stat-icon.purple { background: #faf5ff; color: #9333ea; }

            .tab-nav { display: flex; gap: 0; border-bottom: 2px solid #e5e7eb; margin-bottom: 1.25rem; }
            .tab-btn {
                padding: 0.7rem 1.5rem; font-size: 0.85rem; font-weight: 600;
                background: none; border: none; border-bottom: 2px solid transparent;
                color: #888; cursor: pointer; transition: all 0.2s; margin-bottom: -2px;
                font-family: 'Inter', sans-serif; display: inline-flex; align-items: center; gap: 0.5rem;
            }
            .tab-btn:hover { color: #555; }
            .tab-btn.active { color: #5a6c3e; border-bottom-color: #5a6c3e; }
            .tab-btn .tab-count {
                font-size: 0.7rem; padding: 0.15rem 0.5rem; border-radius: 999px;
                font-weight: 700; line-height: 1.4;
            }
            .tab-btn.active .tab-count { background: #5a6c3e; color: #fff; }
            .tab-btn:not(.active) .tab-count { background: #f0f0f0; color: #888; }

            .tab-panel { display: none; }
            .tab-panel.active { display: block; }

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
                <div class="stat-label">Menunggu</div>
                <div class="stat-value"><?= $count_menunggu ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon blue">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div class="stat-label">Diproses</div>
                <div class="stat-value"><?= $count_diproses ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="stat-label">Selesai</div>
                <div class="stat-value"><?= $count_selesai ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="stat-label">Pendapatan Hari Ini</div>
                <div class="stat-value" style="font-size:1.2rem;">Rp <?= number_format($total_pendapatan_hari, 0, ',', '.') ?></div>
            </div>
        </div>

        <!-- Booking By Status -->
        <div class="admin-card">
            <div class="card-header"><h3>Daftar Booking</h3></div>
            <div class="tab-nav">
                <button class="tab-btn active" onclick="switchTab(event, 'tab-menunggu')">Menunggu <span class="tab-count"><?= $count_menunggu ?></span></button>
                <button class="tab-btn" onclick="switchTab(event, 'tab-diproses')">Diproses <span class="tab-count"><?= $count_diproses ?></span></button>
                <button class="tab-btn" onclick="switchTab(event, 'tab-selesai')">Selesai <span class="tab-count"><?= $count_selesai ?></span></button>
            </div>

            <?php
            function renderBookingTable($list, $status_label, $badge_class, $status_key) {
                if (empty($list)) {
                    echo '<div class="empty-state">Tidak ada booking ' . htmlspecialchars($status_label) . '.</div>';
                    return;
                }
                $show_action = ($status_key === 'pending' || $status_key === 'in_progress');
                echo '<div style="overflow-x:auto;"><table class="admin-table"><thead><tr>';
                echo '<th>#</th><th>Tanggal</th><th>Pelanggan</th><th>Kendaraan</th><th>Layanan</th><th>Antrian</th><th>Status</th>';
                if ($show_action) echo '<th style="min-width:200px;">Aksi</th>';
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
                            echo '<form method="POST" action="index.php?action=admin_update_status" style="display:inline;margin:0;" onsubmit="return confirm(\'Mulai proses booking ini?\');">';
                            echo '<input type="hidden" name="id_booking" value="' . $bk['id_booking'] . '">';
                            echo '<input type="hidden" name="new_status" value="in_progress">';
                            echo '<button type="submit" style="background:#2563eb;color:#fff;border:none;padding:0.35rem 0.75rem;border-radius:0.375rem;font-size:0.75rem;font-weight:600;cursor:pointer;white-space:nowrap;">▶ Mulai Proses</button>';
                            echo '</form>';
                        } elseif ($status_key === 'in_progress') {
                            // Diproses: cek metode pembayaran
                            $metode = strtolower($bk['pay_metode'] ?? '');
                            $is_cod = ($metode === 'tunai' || $metode === 'cod');
                            $is_paid = (($bk['pay_status'] ?? '') === 'paid');

                            if ($is_cod && !$is_paid) {
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
                                echo '<button type="button" onclick="openPaymentModal(' . $btnData . ')" style="background:#5a6c3e;color:#fff;border:none;padding:0.35rem 0.75rem;border-radius:0.375rem;font-size:0.75rem;font-weight:600;cursor:pointer;white-space:nowrap;margin-right:0.5rem;">💰 Bayar & Selesai</button>';
                            } else {
                                // E-Wallet/Kartu atau sudah lunas → tombol Tandai Selesai
                                echo '<form method="POST" action="index.php?action=admin_update_status" style="display:inline;margin:0;" onsubmit="return confirm(\'Tandai booking ini selesai?\');">';
                                echo '<input type="hidden" name="id_booking" value="' . $bk['id_booking'] . '">';
                                echo '<input type="hidden" name="new_status" value="completed">';
                                echo '<button type="submit" style="background:#16a34a;color:#fff;border:none;padding:0.35rem 0.75rem;border-radius:0.375rem;font-size:0.75rem;font-weight:600;cursor:pointer;white-space:nowrap;">✓ Tandai Selesai</button>';
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
            <div class="card-header"><h3>Trend Pendapatan Per Bulan</h3></div>
            <div class="chart-area" style="min-height:280px;">
                <canvas id="chartRevenue"></canvas>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
        <script>
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
            <div class="card-header"><h3>Daftar Booking</h3></div>
            <?php if (empty($bookings)): ?>
                <div class="empty-state">Belum ada data booking.</div>
            <?php else: ?>
            <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead><tr>
                    <th>#</th><th>Tanggal</th><th>Pelanggan</th><th>Kendaraan</th><th>Layanan</th><th>Antrian</th><th>Status</th>
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
                    <td><span class="badge <?= $bk['status'] === 'pending' ? 'badge-pending' : ($bk['status'] === 'in_progress' ? 'badge-progress' : 'badge-done') ?>"><?= $bk['status'] === 'pending' ? 'Menunggu' : ($bk['status'] === 'in_progress' ? 'Diproses' : 'Selesai') ?></span></td>
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
            <div class="card-header"><h3>Daftar Transaksi</h3></div>
            <?php if (empty($transaksi_list)): ?>
                <div class="empty-state">Belum ada data transaksi.</div>
            <?php else: ?>
            <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead><tr>
                    <th>#</th><th>Tanggal</th><th>Pelanggan</th><th>Plat Nomor Kendaraan</th><th>Layanan</th><th>Metode Pembayaran</th><th>Total Harga</th><th>Status</th><th>Aksi</th>
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
                    <td><span class="badge <?= ($tr['pay_status'] ?? '') === 'paid' ? 'badge-paid' : 'badge-unpaid' ?>"><?= ($tr['pay_status'] ?? '') === 'paid' ? 'Lunas' : 'Belum Lunas' ?></span></td>
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
                            <button type="button" onclick="openPaymentModal(<?= $btnData ?>)" class="px-3 py-1 rounded text-xs font-semibold" style="background:#5a6c3e;color:#fff;border:none;cursor:pointer;">Bayar</button>
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
                <h3>Laporan Harian</h3>
                <button onclick="window.print()" class="px-4 py-2 text-sm font-medium rounded print-hide" style="background:#5a6c3e;color:#fff;border:none;cursor:pointer;display:inline-flex;align-items:center;">
                    <svg style="width:16px;height:16px;margin-right:6px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak Laporan
                </button>
            </div>
            <?php if (empty($laporan)): ?>
                <div class="empty-state">Belum ada data laporan.</div>
            <?php else: ?>
            <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead><tr>
                    <th>Tanggal</th><th>Total Booking</th><th>Total Pendapatan</th>
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
        <div class="bg-white border border-gray-200 rounded-md shadow-sm mb-6 overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-200 px-6 py-4">
                <h3 class="font-bold text-gray-800 text-base m-0">Data Jenis Cucian</h3>
            </div>
            
            <div class="p-6">
                <!-- Tambah Data Button -->
                <button onclick="document.getElementById('modal-add').classList.add('show')" class="px-4 py-2 rounded text-sm font-medium mb-6 inline-flex items-center transition-colors shadow-sm" style="background-color: #28a745; color: white; border: 1px solid #218838;">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Data
                </button>

                <?php if (empty($layanan_list)): ?>
                    <div class="empty-state py-8 text-center text-gray-500">Belum ada data layanan.</div>
                <?php else: ?>
                <div class="overflow-x-auto border border-gray-200 rounded-sm">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="px-6 py-4 font-bold text-gray-800 w-16 border-r border-gray-200">No.</th>
                                <th class="px-6 py-4 font-bold text-gray-800 border-r border-gray-200">Jenis Cucian</th>
                                <th class="px-6 py-4 font-bold text-gray-800 w-48 border-r border-gray-200">Biaya</th>
                                <th class="px-6 py-4 font-bold text-gray-800 text-center w-64">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($layanan_list as $i => $l): ?>
                            <tr class="border-b border-gray-200 <?= $i % 2 === 0 ? 'bg-gray-50' : 'bg-white' ?>">
                                <td class="px-6 py-4 text-gray-700 border-r border-gray-200"><?= $i + 1 ?></td>
                                <td class="px-6 py-4 text-gray-700 border-r border-gray-200"><?= htmlspecialchars($l['nama_layanan']) ?></td>
                                <td class="px-6 py-4 text-gray-700 border-r border-gray-200">Rp <?= number_format($l['harga'], 0, ',', '.') ?></td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center space-x-3">
                                        <!-- Edit Button -->
                                        <button onclick="openEditModal(<?= $l['id_layanan'] ?>, '<?= htmlspecialchars(addslashes($l['nama_layanan'])) ?>', <?= $l['harga'] ?>, '<?= htmlspecialchars(addslashes($l['jenis_kendaraan'] ?? 'Car')) ?>')" class="px-4 py-1.5 rounded text-sm inline-flex items-center transition-colors shadow-sm" style="background-color: #ffc107; color: white; border: 1px solid #e0a800;">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            Edit
                                        </button>
                                        <!-- Delete Button (Triggers Custom Modal) -->
                                        <button type="button" onclick="openDeleteModal(<?= $l['id_layanan'] ?>)" class="px-4 py-1.5 rounded text-sm inline-flex items-center transition-colors shadow-sm" style="background-color: #dc3545; color: white; border: 1px solid #bd2130;">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            Hapus
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
                    <h3 class="font-bold text-gray-800 text-base m-0">Tambah Jenis Cucian</h3>
                    <button type="button" onclick="document.getElementById('modal-add').classList.remove('show')" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form action="index.php?action=admin_manage_layanan" method="POST" class="p-6">
                    <input type="hidden" name="manage_action" value="add">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Cucian</label>
                        <input type="text" name="nama_layanan" required class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-1 focus:ring-olive-500 focus:border-olive-500 outline-none">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Kendaraan</label>
                        <select name="jenis_kendaraan" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-1 focus:ring-olive-500 focus:border-olive-500 outline-none">
                            <option value="Car">Mobil</option>
                            <option value="Motorcycle">Motor</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Biaya (Rp)</label>
                        <input type="number" name="harga" required min="0" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-1 focus:ring-olive-500 focus:border-olive-500 outline-none">
                    </div>
                    <div class="flex justify-end space-x-3 pt-2">
                        <button type="button" onclick="document.getElementById('modal-add').classList.remove('show')" class="px-4 py-2 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50 transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-olive-700 text-white rounded text-sm hover:bg-olive-800 transition-colors">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Modal -->
        <div id="modal-edit" class="payment-modal-overlay">
            <div class="payment-modal-content" style="max-width: 450px;">
                <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 flex justify-between items-center" style="border-radius: 8px 8px 0 0;">
                    <h3 class="font-bold text-gray-800 text-base m-0">Edit Jenis Cucian</h3>
                    <button type="button" onclick="document.getElementById('modal-edit').classList.remove('show')" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form action="index.php?action=admin_manage_layanan" method="POST" class="p-6">
                    <input type="hidden" name="manage_action" value="edit">
                    <input type="hidden" name="id_layanan" id="edit_id">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Cucian</label>
                        <input type="text" name="nama_layanan" id="edit_nama" required class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-1 focus:ring-olive-500 focus:border-olive-500 outline-none">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Kendaraan</label>
                        <select name="jenis_kendaraan" id="edit_jenis" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-1 focus:ring-olive-500 focus:border-olive-500 outline-none">
                            <option value="Car">Mobil</option>
                            <option value="Motorcycle">Motor</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Biaya (Rp)</label>
                        <input type="number" name="harga" id="edit_harga" required min="0" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-1 focus:ring-olive-500 focus:border-olive-500 outline-none">
                    </div>
                    <div class="flex justify-end space-x-3 pt-2">
                        <button type="button" onclick="document.getElementById('modal-edit').classList.remove('show')" class="px-4 py-2 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50 transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-olive-700 text-white rounded text-sm hover:bg-olive-800 transition-colors">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div id="modal-delete" class="payment-modal-overlay">
            <div class="payment-modal-content" style="max-width: 350px;">
                <div class="p-8 text-center">
                    <svg class="mx-auto mb-4 text-red-500 w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <h3 class="mb-6 text-lg font-normal text-gray-700">Yakin ingin menghapus layanan ini?</h3>
                    <form action="index.php?action=admin_manage_layanan" method="POST" class="flex justify-center space-x-3">
                        <input type="hidden" name="manage_action" value="delete">
                        <input type="hidden" name="id_layanan" id="delete_id">
                        <button type="button" onclick="document.getElementById('modal-delete').classList.remove('show')" class="text-gray-500 bg-white hover:bg-gray-100 border border-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">Batal</button>
                        <button type="submit" class="text-white bg-red-600 hover:bg-red-800 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors border border-transparent">Ya, Hapus</button>
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
            <div class="card-header"><h3>Daftar Ulasan & Feedback</h3></div>
            <?php if (empty($ulasan_list)): ?>
                <div class="empty-state">Belum ada ulasan dari pelanggan.</div>
            <?php else: ?>
            <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead><tr>
                    <th>Tanggal</th><th>Pelanggan</th><th>Layanan</th><th>Rating</th><th>Komentar</th>
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
    border-radius: 8px;
    width: 100%;
    max-width: 600px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
    max-height: 90vh;
}
.payment-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #f0f0f0;
}
.payment-modal-header h3 {
    margin: 0;
    font-size: 1.1rem;
    color: #333;
    font-weight: 700;
}
.payment-modal-close {
    background: none; border: none; font-size: 1.5rem; color: #999; cursor: pointer; line-height: 1;
}
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
    padding: 0.5rem 0.75rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 0.9rem;
    color: #333;
    box-sizing: border-box;
    font-family: inherit;
}
.payment-field-group input:focus {
    outline: none;
    border-color: #a3b18a;
}
.payment-field-group input[readonly] {
    background-color: #f5f5f5;
    color: #666;
    border-color: #e0e0e0;
}
.payment-modal-footer {
    padding: 0 1.5rem 1.5rem;
}
.btn-simpan {
    display: inline-block;
    padding: 0.5rem 1.5rem;
    background-color: #28a745;
    color: #fff;
    border: none;
    border-radius: 4px;
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s;
}
.btn-simpan:hover { background-color: #218838; }
</style>

<div id="modal-payment" class="payment-modal-overlay">
    <div class="payment-modal-content">
        <div class="payment-modal-header">
            <h3>Data Pembayaran</h3>
            <button type="button" class="payment-modal-close" onclick="document.getElementById('modal-payment').classList.remove('show')">&times;</button>
        </div>
        <form action="index.php?action=admin_pay_transaction" method="POST" style="margin:0; display:flex; flex-direction:column; min-height:0;">
            <div class="payment-modal-body">
                <input type="hidden" name="id_booking" id="pay_id_booking">
                
                <div class="payment-field-group">
                    <label>No. Antrian</label>
                    <input type="text" id="pay_antrian" readonly>
                </div>
                
                <div class="payment-field-group">
                    <label>Nama Pelanggan</label>
                    <input type="text" id="pay_nama" readonly>
                </div>
                
                <div class="payment-field-group">
                    <label>No. Plat</label>
                    <input type="text" id="pay_plat" readonly>
                </div>
                
                <div class="payment-field-group">
                    <label>Tipe Kendaraan</label>
                    <input type="text" id="pay_jenis" readonly>
                </div>
                
                <div class="payment-field-group">
                    <label>Jenis Cucian</label>
                    <input type="text" id="pay_layanan" readonly>
                </div>
                
                <div class="payment-field-group">
                    <label>No. Nota</label>
                    <input type="text" id="pay_nota" readonly>
                </div>
                
                <div class="payment-field-group">
                    <label>Tanggal Pembayaran</label>
                    <input type="text" id="pay_tanggal" readonly>
                </div>
                
                <div class="payment-field-group">
                    <label>Total Biaya</label>
                    <input type="number" id="pay_total" readonly>
                </div>
                
                <div class="payment-field-group">
                    <label>Uang Yang Dibayarkan</label>
                    <input type="number" id="pay_uang" name="uang_dibayarkan" required oninput="calculateChange()">
                </div>
                
                <div class="payment-field-group">
                    <label>Kembalian</label>
                    <input type="text" id="pay_kembalian" readonly>
                </div>
            </div>
            
            <div class="payment-modal-footer">
                <button type="submit" class="btn-simpan">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openPaymentModal(data) {
    document.getElementById('pay_id_booking').value = data.id_booking;
    document.getElementById('pay_antrian').value = data.nomor_antrian;
    document.getElementById('pay_nama').value = data.nama;
    document.getElementById('pay_plat').value = data.no_plat;
    document.getElementById('pay_jenis').value = data.jenis;
    document.getElementById('pay_layanan').value = data.nama_layanan;
    document.getElementById('pay_nota').value = data.no_nota;
    document.getElementById('pay_tanggal').value = data.tanggal;
    document.getElementById('pay_total').value = data.total;
    document.getElementById('pay_uang').value = '';
    document.getElementById('pay_kembalian').value = '';
    
    document.getElementById('modal-payment').classList.add('show');
}

function calculateChange() {
    const total = parseInt(document.getElementById('pay_total').value) || 0;
    const paid = parseInt(document.getElementById('pay_uang').value) || 0;
    
    if (paid >= total) {
        document.getElementById('pay_kembalian').value = paid - total;
    } else {
        document.getElementById('pay_kembalian').value = '';
    }
}
</script>

<?php include BASE_PATH . '/app/Views/layouts/footer.php'; ?>
