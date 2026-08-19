<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit;
}

include '../includes/admin_header.php';
?>

<!-- DASHBOARD CONTENT START -->

<h1 class="text-3xl font-bold text-gray-800 mb-6">
    Welcome Admin Dashboard
</h1>

<?php include '../includes/admin_footer.php'; ?>