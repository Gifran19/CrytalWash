=============================================================
TUTORIAL: CARA MENYAMBUNGKAN PROJECT LOCALHOST (XAMPP) 
KE DATABASE SUPABASE MILIK SENDIRI
=============================================================

TAHAP 1: MENGAKTIFKAN POSTGRESQL DI XAMPP
-------------------------------------------------------------
1. Buka aplikasi XAMPP Control Panel.
2. Pastikan Apache dalam kondisi mati (klik tombol Stop jika sedang Start).
3. Di baris Apache, klik tombol "Config", lalu pilih "PHP (php.ini)".
4. File Notepad akan terbuka. Tekan Ctrl + F lalu cari kata: pdo_pgsql
5. Anda akan menemukan baris berikut:
   ;extension=pdo_pgsql
   ;extension=pgsql

6. Hapus tanda titik koma (;) di paling depan kedua baris tersebut, sehingga menjadi:
   extension=pdo_pgsql
   extension=pgsql

7. Simpan file (Ctrl + S) lalu tutup Notepad.
8. Klik tombol "Start" lagi pada Apache di XAMPP Anda.


TAHAP 2: MEMBUAT & MENDAPATKAN KODE DATABASE DI SUPABASE
-------------------------------------------------------------
1. Buka browser dan pergi ke situs: https://supabase.com
2. Login dan klik tombol "New Project", lalu pilih nama organisasi Anda.
3. Isi data proyek (Name: CrystalWash DB, Password: Bikin yang kuat, Region: Singapore).
4. Klik "Create new project" dan tunggu loading selesai.
5. Setelah masuk ke halaman utama proyek Anda, klik tombol "Connect" yang ada di bagian atas dasbor.
6. Akan muncul jendela popup "Connect to your project".
7. Pada pilihan tipe koneksi, klik menu "ORM".
8. Lalu di bagian bawahnya, pilih "Prisma".
9. Scroll ke bawah ke bagian Langkah 2 ("Configure ORM").
10. Di sana ada tab ".env" (atau ".env.local"). Copy kedua baris kode yang diberikan. 
    Bentuknya akan terlihat seperti ini:

    DATABASE_URL="postgresql://postgres...:[YOUR-PASSWORD]@aws-0..."
    DIRECT_URL="postgresql://postgres...:[YOUR-PASSWORD]@aws-0..."


TAHAP 3: MENYAMBUNGKAN PROJECT DI KOMPUTER
-------------------------------------------------------------
1. Buka folder project Anda di VS Code (C:\xampp\htdocs\CrytalWash).
2. Cari file bernama ".env". Jika belum ada, buat file baru beri nama tepat ".env".
3. Paste kedua baris kode (DATABASE_URL & DIRECT_URL) yang tadi dicopy dari Supabase ke dalam file tersebut.
4. PENTING: Ganti tulisan [YOUR-PASSWORD] dengan password database yang Anda buat di Langkah 3 (Tahap 2).
   (Hapus juga tanda kurung sikunya, contoh: ...postgres.xxx:Rahasia123!@aws...)
5. Save file tersebut.


TAHAP 4: MEMBUAT TABEL OTOMATIS DENGAN PRISMA
-------------------------------------------------------------
(Catatan: Anda tidak perlu menjalankan 'npm install prisma' lagi karena di project ini sudah terpasang)

1. Buka Terminal di VS Code (tekan tombol Ctrl + `) 
2. Pastikan tulisan di terminal menunjuk ke folder C:\xampp\htdocs\CrytalWash.
3. Ketik perintah berikut lalu tekan Enter:

   npx prisma db push

   (Perintah ini akan membaca struktur tabel di komputer Anda dan otomatis membuatnya di Supabase).


TAHAP 5: MENGISI DATA AWAL (SEEDING)
-------------------------------------------------------------
1. Di VS Code, buka file: prisma/seed.sql
2. Copy semua tulisan di dalam file tersebut (Ctrl + A, lalu Ctrl + C).
3. Buka kembali web Supabase Anda. Di menu sebelah kiri dasbor, klik "SQL Editor".
4. Klik "New query".
5. Paste semua kode SQL yang tadi dicopy ke kotak teks yang besar.
6. Klik tombol "Run" di kanan bawah (atau tekan Ctrl + Enter).
7. Jika muncul pesan "Success", artinya data mobil & motor lengkap sudah masuk!

Selesai! Sekarang website CrystalWash di komputer Anda sepenuhnya berjalan mandiri menggunakan database Supabase Anda sendiri.
