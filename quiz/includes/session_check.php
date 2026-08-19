<?php

// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check admin login status
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {

    // Optional message (before redirect)
    $_SESSION['login_error'] = "Please login to access admin panel.";

    // Redirect to login page
    header("Location: ../admin/index.php");
    exit();
}

// Optional: store admin info for use in pages
$admin_id = $_SESSION['admin_id'] ?? null;
$admin_name = $_SESSION['admin_name'] ?? 'Administrator';
?>