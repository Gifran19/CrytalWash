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
INSERT INTO layanan (nama_layanan, harga, jenis_kendaraan) 
VALUES 
    ('Cuci Reguler Mobil', 50000, 'Car'),
    ('Cuci Premium Mobil', 80000, 'Car'),
    ('Cuci Reguler Motor', 20000, 'Motorcycle'),
    ('Cuci Premium Motor', 35000, 'Motorcycle')
ON CONFLICT DO NOTHING;
