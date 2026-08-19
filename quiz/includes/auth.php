<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
| This file should be included at the TOP of every admin page.
| It ensures only logged-in admins can access admin panel.
|
| Example usage:
|   include '../includes/auth.php';
*/

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {

    // Redirect to admin login page
    header("Location: ../admin/index.php");
    exit();
}

// OPTIONAL: You can store admin details in session
$admin_id = $_SESSION['admin_id'] ?? null;
$admin_name = $_SESSION['admin_name'] ?? 'Administrator';
?>