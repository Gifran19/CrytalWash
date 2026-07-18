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

        // Cek apakah pelanggan sudah ada (berdasarkan email dan nama)
        $stmt = $conn->prepare("SELECT id_pelanggan FROM pelanggan WHERE email = :email AND nama = :nama LIMIT 1");
        $stmt->execute(['email' => $email, 'nama' => $nama]);
        $existing = $stmt->fetch();

        if ($existing) {
            // Update data pelanggan yang sudah ada (hanya no_hp yang mungkin berubah)
            $id_pelanggan = $existing['id_pelanggan'];
            $stmt = $conn->prepare("UPDATE pelanggan SET no_hp = :no_hp WHERE id_pelanggan = :id");
            $stmt->execute(['no_hp' => $no_hp, 'id' => $id_pelanggan]);
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

        if (strlen($plat) > 20) {
            header("Location: index.php?page=checkout&step=2&error=long_plat");
            exit();
        }

        // Validasi format plat nomor Indonesia: 1-2 Huruf, 1-4 Angka, 0-3 Huruf (tanpa spasi karena sudah di-clean)
        if (!preg_match('/^[A-Z]{1,2}[0-9]{1,4}[A-Z]{0,3}$/', $plat)) {
            header("Location: index.php?page=checkout&step=2&error=invalid_plat");
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
        
        // Lookup harga dari database berdasarkan nama layanan (aman & tidak percaya harga dari browser)
        $layanan_name = cleanInput($_POST['layanan'] ?? '');
        $stmt = $conn->prepare("SELECT id_layanan, harga, nama_layanan FROM layanan WHERE nama_layanan = :layanan LIMIT 1");
        $stmt->execute(['layanan' => $layanan_name]);
        $layanan_data = $stmt->fetch();

        if ($layanan_data) {
            $_SESSION['order']['id_layanan']   = $layanan_data['id_layanan'];
            $_SESSION['order']['total_price']  = $layanan_data['harga'];
            $_SESSION['order']['layanan']      = $layanan_data['nama_layanan'];
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
