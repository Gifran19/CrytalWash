<?php

require_once BASE_PATH . '/app/Config/database.php';
require_once BASE_PATH . '/app/Helpers/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $next_step = $_POST['next_step'];

    // =========================================================
    // STEP 1 → 2: Simpan data pelanggan
    // =========================================================
    if ($next_step == 2) {
        $nama  = cleanInput($_POST['nama']);
        $no_hp = cleanInput($_POST['whatsapp']);
        $email = cleanInput($_POST['email']);

        // Cek apakah pelanggan sudah ada (berdasarkan email)
        $stmt = $conn->prepare("SELECT id_pelanggan FROM pelanggan WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $existing = $stmt->fetch();

        if ($existing) {
            // Update data pelanggan yang sudah ada
            $id_pelanggan = $existing['id_pelanggan'];
            $stmt = $conn->prepare("UPDATE pelanggan SET nama = :nama, no_hp = :no_hp WHERE id_pelanggan = :id");
            $stmt->execute(['nama' => $nama, 'no_hp' => $no_hp, 'id' => $id_pelanggan]);
        } else {
            // Insert pelanggan baru
            $stmt = $conn->prepare("INSERT INTO pelanggan (nama, no_hp, email) VALUES (:nama, :no_hp, :email)");
            $stmt->execute(['nama' => $nama, 'no_hp' => $no_hp, 'email' => $email]);
            $id_pelanggan = $conn->lastInsertId();
        }

        // Simpan ke session
        $_SESSION['order']['id_pelanggan'] = $id_pelanggan;
        $_SESSION['order']['nama']  = $nama;
        $_SESSION['order']['whatsapp'] = $no_hp;
        $_SESSION['order']['email'] = $email;
    }

    // =========================================================
    // STEP 2 → 3: Simpan data kendaraan
    // =========================================================
    if ($next_step == 3) {
        $plat    = cleanInput($_POST['plat']);
        // Normalisasi: hapus semua spasi dan ubah ke huruf kapital
        $plat    = strtoupper(preg_replace('/\s+/', '', $plat));
        $tipe    = cleanInput($_POST['tipe']);
        $tanggal = cleanInput($_POST['tanggal']);
        $jam     = cleanInput($_POST['jam'] ?? date('H:i'));
        $id_pelanggan = $_SESSION['order']['id_pelanggan'] ?? null;

        if (empty($plat)) {
            header("Location: index.php?page=checkout&step=2&error=empty_plat");
            exit();
        }

        if ($id_pelanggan) {
            // Cek apakah kendaraan sudah terdaftar untuk pelanggan ini
            $stmt = $conn->prepare("SELECT id_kendaraan FROM kendaraan WHERE no_plat = :plat AND id_pelanggan = :id_pelanggan LIMIT 1");
            $stmt->execute(['plat' => $plat, 'id_pelanggan' => $id_pelanggan]);
            $existing_vehicle = $stmt->fetch();

            if ($existing_vehicle) {
                $id_kendaraan = $existing_vehicle['id_kendaraan'];
                // Update jenis jika berubah
                $stmt = $conn->prepare("UPDATE kendaraan SET jenis = :jenis WHERE id_kendaraan = :id");
                $stmt->execute(['jenis' => $tipe, 'id' => $id_kendaraan]);
            } else {
                // Insert kendaraan baru dengan penanganan duplikasi
                try {
                    $stmt = $conn->prepare("INSERT INTO kendaraan (jenis, no_plat, id_pelanggan) VALUES (:jenis, :plat, :id_pelanggan)");
                    $stmt->execute(['jenis' => $tipe, 'plat' => $plat, 'id_pelanggan' => $id_pelanggan]);
                    $id_kendaraan = $conn->lastInsertId();
                } catch (PDOException $e) {
                    // Pelanggaran unique constraint (PostgreSQL error code 23505)
                    if ($e->getCode() == '23505') {
                        header("Location: index.php?page=checkout&step=2&error=duplicate_plat");
                        exit();
                    }
                    throw $e; // Re-throw jika error lain
                }
            }

            $_SESSION['order']['id_kendaraan'] = $id_kendaraan;
        }

        // Simpan ke session
        $_SESSION['order']['plat']    = $plat;
        $_SESSION['order']['tipe']    = $tipe;
        $_SESSION['order']['tanggal'] = $tanggal;
        $_SESSION['order']['jam']     = $jam;
    }

    // =========================================================
    // STEP 3 → 4: Simpan data layanan dan hitung harga
    // =========================================================
    if ($next_step == 4 && isset($_POST['layanan'])) {
        $nama_layanan = cleanInput($_POST['layanan']);

        // Lookup harga dari database (bukan hardcode) untuk keamanan
        $stmt = $conn->prepare("SELECT id_layanan, harga FROM layanan WHERE nama_layanan = :nama LIMIT 1");
        $stmt->execute(['nama' => $nama_layanan]);
        $layanan_data = $stmt->fetch();

        if ($layanan_data) {
            $_SESSION['order']['id_layanan']   = $layanan_data['id_layanan'];
            $_SESSION['order']['total_price']  = $layanan_data['harga'];
            $_SESSION['order']['layanan']      = $nama_layanan;
        }
    }

    // Simpan data POST lainnya ke session (yang belum disimpan)
    foreach ($_POST as $key => $value) {
        if (!isset($_SESSION['order'][$key])) {
            $_SESSION['order'][$key] = htmlspecialchars($value);
        }
    }

    // Arahkan ke step berikutnya
    header("Location: index.php?page=checkout&step=" . $next_step);
    exit();
}
?>
