<?php


require_once BASE_PATH . '/app/Config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $is_ajax = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') || isset($_POST['ajax']);

    // Validate input
    if (empty($username) || empty($password)) {
        if ($is_ajax) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => 'empty',
                'message' => trans('admin_login_err_empty')
            ]);
            exit;
        }
        header('Location: index.php?page=login&error=empty');
        exit;
    }

    try {
        // Check kasir credentials in database
        $stmt = $conn->prepare("SELECT id_kasir, username, password FROM kasir WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        $kasir = $stmt->fetch();

        if ($kasir && password_verify($password, $kasir['password'])) {
            // Login successful
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id']        = $kasir['id_kasir'];
            $_SESSION['admin_username']  = $kasir['username'];
            $_SESSION['admin_nama']      = $kasir['username'];

            if ($is_ajax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'redirect' => 'index.php?page=admin_dashboard'
                ]);
                exit;
            }
            header('Location: index.php?page=admin_dashboard');
            exit;
        } elseif ($username === 'admin' && $password === 'admin123') {
            // Fallback for default admin
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id']        = 0;
            $_SESSION['admin_username']  = 'admin';
            $_SESSION['admin_nama']      = 'Administrator';

            if ($is_ajax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'redirect' => 'index.php?page=admin_dashboard'
                ]);
                exit;
            }
            header('Location: index.php?page=admin_dashboard');
            exit;
        } else {
            // Invalid credentials
            if ($is_ajax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'error' => 'invalid',
                    'message' => trans('admin_login_err_invalid')
                ]);
                exit;
            }
            header('Location: index.php?page=login&error=invalid');
            exit;
        }
    } catch (PDOException $e) {
        // Database error - fallback to hardcoded admin for safety
        if ($username === 'admin' && $password === 'admin123') {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id']        = 0;
            $_SESSION['admin_username']  = 'admin';
            $_SESSION['admin_nama']      = 'Administrator';

            if ($is_ajax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'redirect' => 'index.php?page=admin_dashboard'
                ]);
                exit;
            }
            header('Location: index.php?page=admin_dashboard');
            exit;
        }

        if ($is_ajax) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => 'invalid',
                'message' => trans('admin_login_err_invalid')
            ]);
            exit;
        }
        header('Location: index.php?page=login&error=invalid');
        exit;
    }
} else {
    header('Location: index.php?page=login');
    exit;
}
?>
