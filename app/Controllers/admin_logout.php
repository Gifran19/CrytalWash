<?php
/**
 * CrystalWash - Admin Logout Controller
 */

// Clear admin session
unset($_SESSION['admin_logged_in']);
unset($_SESSION['admin_id']);
unset($_SESSION['admin_username']);
unset($_SESSION['admin_nama']);

header('Location: index.php?page=login&success=logout');
exit;
?>
