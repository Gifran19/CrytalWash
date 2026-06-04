<?php
// Front Controller
session_start();

// Define BASE_PATH for easier includes
define('BASE_PATH', dirname(__DIR__));

// Route Actions (POST/Redirects)
$action = $_GET['action'] ?? null;
if ($action) {
    $allowed_actions = ['auth_booking', 'payment_gateway', 'process_order', 'submit_review', 'admin_login', 'admin_logout', 'admin_manage_layanan', 'admin_pay_transaction', 'admin_update_status'];
    if (in_array($action, $allowed_actions)) {
        require_once BASE_PATH . "/app/Controllers/{$action}.php";
        exit;
    }
}

// Route Pages (Views)
$page = $_GET['page'] ?? 'home';
$allowed_pages = ['home', 'checkout', 'work_progress', 'review', 'finish', 'invoice', 'login', 'admin_dashboard'];

if (in_array($page, $allowed_pages)) {
    require_once BASE_PATH . "/app/Views/pages/{$page}.php";
} else {
    http_response_code(404);
    echo "<h1>404 Not Found</h1>";
}
