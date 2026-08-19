<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

$currentPage = basename($_SERVER['PHP_SELF']);
$adminName = $_SESSION['admin_name'] ?? 'Admin';
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<title>Quizora Admin Panel</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://cdn.tailwindcss.com"></script>

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

<style>
* {
    font-family: "Times New Roman", Times, serif;
}

.active-link {
    background-color: #2563eb;
    color: white;
}
</style>
</head>

<body class="bg-gray-100">

<!-- TOP NAVBAR -->
<nav class="bg-blue-700 text-white shadow-lg">

    <div class="flex justify-between items-center px-6 py-4">

        <div class="flex items-center space-x-3">

            <div class="bg-white text-blue-700 w-10 h-10 flex items-center justify-center rounded-lg">
                <i class="fa-solid fa-user-shield"></i>
            </div>

            <div>
                <h1 class="text-xl font-bold">Quizora Admin</h1>
                <p class="text-xs text-blue-200">Control Panel</p>
            </div>

        </div>

        <div class="flex items-center space-x-6">

            <span class="hidden md:block">
                <i class="fa-solid fa-circle-user mr-1"></i>
                <?php echo htmlspecialchars($adminName); ?>
            </span>

            <a href="logout.php"
               class="bg-white text-blue-700 px-4 py-2 rounded hover:bg-gray-200">

                Logout
            </a>

        </div>

    </div>

</nav>

<!-- WRAPPER -->
<div class="flex">

<!-- SIDEBAR -->
<aside class="w-64 bg-white shadow-md min-h-screen">

    <div class="p-4">

        <h2 class="text-gray-600 font-bold mb-4">MENU</h2>

        <ul class="space-y-2">

            <li>
                <a href="dashboard.php"
                   class="block p-3 rounded hover:bg-blue-100
                   <?php echo ($currentPage == 'dashboard.php') ? 'active-link' : ''; ?>">
                    <i class="fa-solid fa-gauge mr-2"></i> Dashboard
                </a>
            </li>

            <li>
                <a href="manage_admins.php"
                   class="block p-3 rounded hover:bg-blue-100
                   <?php echo ($currentPage == 'manage_admins.php') ? 'active-link' : ''; ?>">
                    <i class="fa-solid fa-users mr-2"></i> Manage Admins
                </a>
            </li>

            <li>
                <a href="manage_exams.php"
                   class="block p-3 rounded hover:bg-blue-100
                   <?php echo ($currentPage == 'manage_exams.php') ? 'active-link' : ''; ?>">
                    <i class="fa-solid fa-file-pen mr-2"></i> Manage Exams
                </a>
            </li>

            <li>
                <a href="add_questions.php"
                   class="block p-3 rounded hover:bg-blue-100
                   <?php echo ($currentPage == 'add_questions.php') ? 'active-link' : ''; ?>">
                    <i class="fa-solid fa-circle-plus mr-2"></i> Add Questions
                </a>
            </li>

            <li>
                <a href="results.php"
                   class="block p-3 rounded hover:bg-blue-100
                   <?php echo ($currentPage == 'results.php') ? 'active-link' : ''; ?>">
                    <i class="fa-solid fa-chart-line mr-2"></i> Results
                </a>
            </li>

        </ul>

    </div>

</aside>

<!-- MAIN START -->
<main class="flex-1 p-6">