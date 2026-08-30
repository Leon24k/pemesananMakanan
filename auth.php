<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Memastikan user sudah login.
 * Jika belum login, dialihkan ke login.php
 */
function require_login() {
    if (!isset($_SESSION['login_user']) || !isset($_SESSION['id_user'])) {
        header("Location: login.php");
        exit;
    }
}

/**
 * Memastikan user yang login memiliki role 'admin'.
 * Jika bukan admin, dialihkan ke login.php atau index.php
 */
function require_admin() {
    require_login();
    if (!isset($_SESSION['user_status']) || $_SESSION['user_status'] !== 'admin') {
        header("Location: index.php");
        exit;
    }
}

/**
 * Memastikan user yang login memiliki role 'user'.
 */
function require_user() {
    require_login();
}
?>
