<?php
/**
 * CrystalWash - Database Connection
 * Koneksi ke Supabase PostgreSQL via PDO (Connection Pooler / PgBouncer)
 */

// Helper to load .env file manually in plain PHP (local development fallback)
$envFile = BASE_PATH . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        if (strpos($line, '=') === false) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        // Remove quotes if any
        if (preg_match('/^"(.*)"$/', $value, $matches)) {
            $value = $matches[1];
        } elseif (preg_match('/^\'(.*)\'$/', $value, $matches)) {
            $value = $matches[1];
        }
        // Railway environment variable has priority, do not overwrite if already set
        if (getenv($name) === false) {
            putenv("$name=$value");
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

$dbUrl = getenv('DATABASE_URL');
$isProduction = getenv('APP_ENV') === 'production' || getenv('RAILWAY_ENVIRONMENT') !== false;

if (empty($dbUrl)) {
    error_log("Database configuration error: DATABASE_URL is missing.");
    header("HTTP/1.1 500 Internal Server Error");
    echo "<h1>Layanan database belum terkonfigurasi.</h1>";
    exit;
}

$parsedUrl = parse_url($dbUrl);
if ($parsedUrl === false || !isset($parsedUrl['host'])) {
    error_log("Database configuration error: DATABASE_URL is invalid.");
    header("HTTP/1.1 500 Internal Server Error");
    echo "<h1>Layanan database tidak valid.</h1>";
    exit;
}

$host = $parsedUrl['host'] ?? '';
$port = $parsedUrl['port'] ?? '5432';
$user = $parsedUrl['user'] ?? '';
$pass = $parsedUrl['pass'] ?? '';
$db   = ltrim($parsedUrl['path'] ?? '', '/');

// Clean query params from DB name if any (e.g. ?pgbouncer=true)
if (strpos($db, '?') !== false) {
    $db = explode('?', $db)[0];
}

$dsn = "pgsql:host=$host;port=$port;dbname=$db;sslmode=require";

try {
    $conn = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => true, // Wajib untuk PgBouncer Transaction mode
    ]);
} catch (PDOException $e) {
    // Log detailed connection exception locally/internally
    error_log("Database connection failure: " . $e->getMessage());
    header("HTTP/1.1 500 Internal Server Error");
    echo "<h1>Koneksi database gagal. Silakan coba beberapa saat lagi.</h1>";
    exit;
}
?>
