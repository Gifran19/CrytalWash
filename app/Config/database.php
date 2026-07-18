<?php
/**
 * CrystalWash - Database Connection
 * Koneksi ke Supabase PostgreSQL via PDO (Connection Pooler / PgBouncer)
 */

$host = "aws-1-ap-southeast-1.pooler.supabase.com";
$port = "6543";
$db   = "postgres";
$user = "postgres.umzvgjgirhlbveelvdny";
$pass = "Wonosobo19!"; 

/*$host = "aws-0-ap-southeast-1.pooler.supabase.com";
$port = "6543";
$db   = "postgres";
$user = "postgres.snkgfxcvujxsqqywzmya";
$pass = "#CrystalWash01"; */

$dsn = "pgsql:host=$host;port=$port;dbname=$db;sslmode=require";

try {
    $conn = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => true, // Wajib untuk PgBouncer Transaction mode
    ]);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>
