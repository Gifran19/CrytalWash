-- ==========================================================
-- CrystalWash - Seed Data
-- Layanan (Services) default
-- ==========================================================

INSERT INTO layanan (nama_layanan, harga) VALUES
  ('Quick Wash', 50000),
  ('Full Wash', 100000),
  ('Interior Detail', 150000),
  ('Engine Wash', 120000)
ON CONFLICT DO NOTHING;
