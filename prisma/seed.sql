-- ==============================================================================
-- SQL Script: Seed Data Master (Fail-Safe)
-- ==============================================================================
-- File ini digunakan untuk mengisi data awal pada tabel master (kasir dan layanan)
-- jika database sewaktu-waktu di-reset.
--
-- CARA EKSEKUSI DI SUPABASE:
-- 1. Buka Supabase Dashboard > Pilih Project Anda.
-- 2. Masuk ke menu "SQL Editor" di sidebar kiri.
-- 3. Buat query baru, copy seluruh isi file ini, lalu paste.
-- 4. Klik tombol "Run" (atau tekan Cmd/Ctrl + Enter).
-- ==============================================================================

-- 1. Insert Data Akun Kasir / Admin
-- Password default adalah 'admin123'
-- Hash ini di-generate menggunakan algoritma BCRYPT standar PHP (password_hash)
INSERT INTO kasir (username, password) 
VALUES (
    'admin', 
    '$2y$10$QO0R5l8/0b68aA/m.zF/JeeB2m1Yc2mGfB8A9x/Q8IqXgA/8uYjE2' -- Hash untuk 'admin123'
)
ON CONFLICT (username) DO NOTHING;

-- 2. Insert Data Layanan Standar
-- Memastikan kolom jenis_kendaraan tersedia di tabel layanan (mencegah error 42703 di Supabase)
ALTER TABLE layanan ADD COLUMN IF NOT EXISTS jenis_kendaraan VARCHAR(20) DEFAULT 'Car';

INSERT INTO layanan (nama_layanan, harga, jenis_kendaraan) 
VALUES 
    ('Toyota Avanza / Veloz / Rush', 50000, 'Mobil'),
    ('Honda Brio / Jazz / HRV', 50000, 'Mobil'),
    ('Daihatsu Xenia / Terios / Sigra', 45000, 'Mobil'),
    ('Mitsubishi Pajero / Toyota Fortuner', 70000, 'Mobil'),
    ('Toyota Alphard / Premium MPV', 90000, 'Mobil'),
    ('Honda Beat / Vario / Scoopy', 20000, 'Motor'),
    ('Yamaha NMAX / Aerox / PCX', 25000, 'Motor'),
    ('Motor Sport / Vespa / 250cc+', 35000, 'Motor')
ON CONFLICT DO NOTHING;
