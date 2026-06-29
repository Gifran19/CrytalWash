-- ==============================================================================
-- SQL Script: Hapus Data Uji Coba (Dummy Data) Secara Aman
-- ==============================================================================
-- Perintah ini akan menghapus semua data transaksional dari aplikasi (pelanggan,
-- booking, kendaraan, antrian, pembayaran, transaksi, invoice, feedback)
-- dan mereset ID auto-increment (serial) kembali ke 1.
-- 
-- PENTING: Tabel master seperti "layanan" dan "kasir" TIDAK disertakan di sini
-- agar daftar harga dan akun login admin tetap tersedia.
-- ==============================================================================

TRUNCATE TABLE 
    antrian, 
    pembayaran, 
    transaksi, 
    invoice, 
    feedback, 
    booking, 
    kendaraan, 
    pelanggan 
RESTART IDENTITY CASCADE;
