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
    '$2y$10$ChvzsP7ipONnUpPUC5Kx0.3.MWhcqaiKOj55IBHX4mRAZywScoFfu' -- Hash untuk 'admin123'
)
ON CONFLICT (username) DO NOTHING;

-- 2. Insert Data Layanan Standar
-- Memastikan kolom jenis_kendaraan tersedia di tabel layanan (mencegah error 42703 di Supabase)
ALTER TABLE layanan ADD COLUMN IF NOT EXISTS jenis_kendaraan VARCHAR(20) DEFAULT 'Car';

-- Hapus data layanan lama sebelum disisipkan ulang untuk mencegah duplikasi
DELETE FROM layanan WHERE nama_layanan IN (
    'Cuci Mobil Standar', 'Cuci Mobil Besar', 'Cuci Motor', 'Cuci Motor Besar', 'Lainnya (Mobil)', 'Lainnya (Motor)'
);

INSERT INTO layanan (nama_layanan, harga, jenis_kendaraan) 
VALUES 
    ('Cuci Mobil Standar', 45000, 'Mobil'),
    ('Cuci Mobil Besar', 50000, 'Mobil'),
    ('Cuci Motor', 15000, 'Motor'),
    ('Cuci Motor Besar', 20000, 'Motor'),
    ('Lainnya (Mobil)', 90000, 'Mobil'),
    ('Lainnya (Motor)', 35000, 'Motor')
ON CONFLICT DO NOTHING;
