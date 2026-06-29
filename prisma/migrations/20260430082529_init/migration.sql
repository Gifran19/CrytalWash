-- CreateTable
CREATE TABLE "pelanggan" (
    "id_pelanggan" SERIAL NOT NULL,
    "nama" VARCHAR(100) NOT NULL,
    "no_hp" VARCHAR(20) NOT NULL,
    "email" VARCHAR(100) NOT NULL,
    "created_at" TIMESTAMPTZ(6) NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT "pelanggan_pkey" PRIMARY KEY ("id_pelanggan")
);

-- CreateTable
CREATE TABLE "kasir" (
    "id_kasir" SERIAL NOT NULL,
    "username" VARCHAR(50) NOT NULL,
    "password" VARCHAR(255) NOT NULL,
    "created_at" TIMESTAMPTZ(6) NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT "kasir_pkey" PRIMARY KEY ("id_kasir")
);

-- CreateTable
CREATE TABLE "kendaraan" (
    "id_kendaraan" SERIAL NOT NULL,
    "jenis" VARCHAR(20) NOT NULL,
    "no_plat" VARCHAR(20) NOT NULL,
    "id_pelanggan" INTEGER NOT NULL,

    CONSTRAINT "kendaraan_pkey" PRIMARY KEY ("id_kendaraan")
);

-- CreateTable
CREATE TABLE "layanan" (
    "id_layanan" SERIAL NOT NULL,
    "nama_layanan" VARCHAR(50) NOT NULL,
    "harga" INTEGER NOT NULL,

    CONSTRAINT "layanan_pkey" PRIMARY KEY ("id_layanan")
);

-- CreateTable
CREATE TABLE "booking" (
    "id_booking" SERIAL NOT NULL,
    "tanggal" DATE NOT NULL,
    "status" VARCHAR(20) NOT NULL DEFAULT 'pending',
    "jenis_booking" VARCHAR(30) NOT NULL DEFAULT 'online',
    "estimasi_waktu" INTEGER NOT NULL,
    "id_pelanggan" INTEGER NOT NULL,
    "id_kendaraan" INTEGER NOT NULL,
    "id_layanan" INTEGER NOT NULL,
    "created_at" TIMESTAMPTZ(6) NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT "booking_pkey" PRIMARY KEY ("id_booking")
);

-- CreateTable
CREATE TABLE "antrian" (
    "id_antrian" SERIAL NOT NULL,
    "nomor_antrian" INTEGER NOT NULL,
    "status" VARCHAR(20) NOT NULL DEFAULT 'menunggu',
    "id_booking" INTEGER NOT NULL,

    CONSTRAINT "antrian_pkey" PRIMARY KEY ("id_antrian")
);

-- CreateTable
CREATE TABLE "pembayaran" (
    "id_pembayaran" SERIAL NOT NULL,
    "metode" VARCHAR(20) NOT NULL,
    "status" VARCHAR(20) NOT NULL DEFAULT 'unpaid',
    "total" INTEGER NOT NULL,
    "id_booking" INTEGER NOT NULL,
    "created_at" TIMESTAMPTZ(6) NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT "pembayaran_pkey" PRIMARY KEY ("id_pembayaran")
);

-- CreateTable
CREATE TABLE "invoice" (
    "id_invoice" SERIAL NOT NULL,
    "tanggal" TIMESTAMPTZ(6) NOT NULL DEFAULT CURRENT_TIMESTAMP,
    "total" INTEGER NOT NULL,
    "id_booking" INTEGER NOT NULL,

    CONSTRAINT "invoice_pkey" PRIMARY KEY ("id_invoice")
);

-- CreateTable
CREATE TABLE "transaksi" (
    "id_transaksi" SERIAL NOT NULL,
    "tanggal" TIMESTAMPTZ(6) NOT NULL DEFAULT CURRENT_TIMESTAMP,
    "total" INTEGER NOT NULL,
    "id_booking" INTEGER NOT NULL,
    "id_kasir" INTEGER,

    CONSTRAINT "transaksi_pkey" PRIMARY KEY ("id_transaksi")
);

-- CreateTable
CREATE TABLE "feedback" (
    "id_feedback" SERIAL NOT NULL,
    "rating" INTEGER NOT NULL,
    "komentar" TEXT,
    "tanggal" TIMESTAMPTZ(6) NOT NULL DEFAULT CURRENT_TIMESTAMP,
    "id_booking" INTEGER NOT NULL,

    CONSTRAINT "feedback_pkey" PRIMARY KEY ("id_feedback")
);

-- CreateIndex
CREATE UNIQUE INDEX "kasir_username_key" ON "kasir"("username");

-- CreateIndex
CREATE UNIQUE INDEX "antrian_id_booking_key" ON "antrian"("id_booking");

-- CreateIndex
CREATE UNIQUE INDEX "pembayaran_id_booking_key" ON "pembayaran"("id_booking");

-- CreateIndex
CREATE UNIQUE INDEX "invoice_id_booking_key" ON "invoice"("id_booking");

-- CreateIndex
CREATE UNIQUE INDEX "transaksi_id_booking_key" ON "transaksi"("id_booking");

-- CreateIndex
CREATE UNIQUE INDEX "feedback_id_booking_key" ON "feedback"("id_booking");

-- AddForeignKey
ALTER TABLE "kendaraan" ADD CONSTRAINT "kendaraan_id_pelanggan_fkey" FOREIGN KEY ("id_pelanggan") REFERENCES "pelanggan"("id_pelanggan") ON DELETE RESTRICT ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE "booking" ADD CONSTRAINT "booking_id_pelanggan_fkey" FOREIGN KEY ("id_pelanggan") REFERENCES "pelanggan"("id_pelanggan") ON DELETE RESTRICT ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE "booking" ADD CONSTRAINT "booking_id_kendaraan_fkey" FOREIGN KEY ("id_kendaraan") REFERENCES "kendaraan"("id_kendaraan") ON DELETE RESTRICT ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE "booking" ADD CONSTRAINT "booking_id_layanan_fkey" FOREIGN KEY ("id_layanan") REFERENCES "layanan"("id_layanan") ON DELETE RESTRICT ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE "antrian" ADD CONSTRAINT "antrian_id_booking_fkey" FOREIGN KEY ("id_booking") REFERENCES "booking"("id_booking") ON DELETE RESTRICT ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE "pembayaran" ADD CONSTRAINT "pembayaran_id_booking_fkey" FOREIGN KEY ("id_booking") REFERENCES "booking"("id_booking") ON DELETE RESTRICT ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE "invoice" ADD CONSTRAINT "invoice_id_booking_fkey" FOREIGN KEY ("id_booking") REFERENCES "booking"("id_booking") ON DELETE RESTRICT ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE "transaksi" ADD CONSTRAINT "transaksi_id_booking_fkey" FOREIGN KEY ("id_booking") REFERENCES "booking"("id_booking") ON DELETE RESTRICT ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE "transaksi" ADD CONSTRAINT "transaksi_id_kasir_fkey" FOREIGN KEY ("id_kasir") REFERENCES "kasir"("id_kasir") ON DELETE SET NULL ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE "feedback" ADD CONSTRAINT "feedback_id_booking_fkey" FOREIGN KEY ("id_booking") REFERENCES "booking"("id_booking") ON DELETE RESTRICT ON UPDATE CASCADE;
