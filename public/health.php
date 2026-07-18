<?php
define('BASE_PATH', dirname(__DIR__));

header('Content-Type: application/json');

try {
    require_once BASE_PATH . '/app/Config/database.php';
    // Test database connection
    $stmt = $conn->query("SELECT 1");
    if ($stmt) {
        http_response_code(200);
        echo json_encode([
            'status' => 'healthy',
            'database' => 'connected',
            'timestamp' => time()
        ]);
        exit;
    }
} catch (Exception $e) {
    // Fallthrough to 500 error
}

http_response_code(500);
echo json_encode([
    'status' => 'unhealthy',
    'database' => 'disconnected',
    'timestamp' => time()
]);
exit;
