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

=============================================================
TAHAP 6: PRODUCTION DEPLOYMENT (RAILWAY)
-------------------------------------------------------------
Proyek ini siap dideploy ke Railway menggunakan Docker. Berikut adalah langkah-langkahnya:

1. Buat Service Baru di Railway:
   - Hubungkan repositori GitHub Anda ke Railway.
   - Railway akan mendeteksi `Dockerfile` secara otomatis dan melakukan build.

2. Konfigurasi Database Migration di Production:
   - Jalankan perintah migrasi skema database Supabase menggunakan command line:
     `npx prisma migrate deploy`
     (Perintah ini akan menerapkan seluruh migrasi pending tanpa merusak data production).

3. Konfigurasi Environment Variables di Railway:
   - Masuk ke tab **Variables** di service Anda pada Railway.
   - Tambahkan variabel berikut:
     * `DATABASE_URL`: URL koneksi database PostgreSQL (Supabase) Anda dengan pooler (contoh: `postgresql://postgres...:[YOUR-PASSWORD]@aws-1-ap-southeast-1.pooler.supabase.com:6543/postgres?pgbouncer=true`).
     * `APP_ENV`: `production` (agar error database sensitif tidak ditampilkan ke publik).
     * `PORT`: `80` (default port untuk Railway).
     * `MIDTRANS_SERVER_KEY`: Server Key dari Dashboard Midtrans.
     * `MIDTRANS_CLIENT_KEY`: Client Key dari Dashboard Midtrans.
     * `MIDTRANS_IS_PRODUCTION`: `false` (Sandbox) atau `true` (Production).

4. Konfigurasi Health Check di Railway:
   - Anda perlu mengonfigurasi jalur pemantauan kesehatan aplikasi secara manual melalui Railway Service Settings dengan mengarahkan Health Check Path ke `/health.php`.

5. Selesai!


=============================================================
TAHAP 7: KONFIGURASI MIDTRANS (SANDBOX & PRODUCTION)
-------------------------------------------------------------
Untuk menggunakan pembayaran online (QRIS):

1. Ambil Server Key dan Client Key Anda dari Dashboard Midtrans (pilih Sandbox untuk pengujian awal).
2. PENTING: Jangan pernah membagikan atau memasukkan key asli Anda ke file `.env.example`. Hanya masukkan ke file `.env` lokal Anda yang di-ignore oleh git:
   ```env
   MIDTRANS_SERVER_KEY="Isi_dengan_server_key_anda"
   MIDTRANS_CLIENT_KEY="Isi_dengan_client_key_anda"
   MIDTRANS_IS_PRODUCTION=false
   ```
3. Di server production (seperti Railway), tambahkan ketiga variabel lingkungan di atas pada tab **Variables**.
4. PENTING: Karena Sandbox Server Key sebelumnya sempat tercatat di riwayat repositori, pastikan Anda melakukan **rotasi (regenerate) Server Key** baru di Dashboard Midtrans demi keamanan!
5. Status Integrasi QRIS: Saat ini alur QRIS berjalan pada mode Sandbox. Sebelum berpindah ke mode production (transaksi nyata), Anda harus mengonfigurasi Webhook URL resmi di dashboard Midtrans yang mengarah ke endpoint konfirmasi status pembayaran aplikasi Anda untuk menjamin pembaruan status otomatis tanpa interaksi manual dari tombol pengguna.
