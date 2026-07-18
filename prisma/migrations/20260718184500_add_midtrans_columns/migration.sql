-- AlterTable
ALTER TABLE "pembayaran" ADD COLUMN "midtrans_order_id" VARCHAR(100),
ADD COLUMN "midtrans_transaction_id" VARCHAR(100),
ADD COLUMN "midtrans_status" VARCHAR(50),
ADD COLUMN "paid_at" TIMESTAMPTZ(6),
ADD COLUMN "updated_at" TIMESTAMPTZ(6);
