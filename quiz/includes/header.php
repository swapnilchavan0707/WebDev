<?php
session_start();
$currentPage = basename($_SERVER['PHP_SELF']);

// Optional future use (admin login check)
$isAdminLoggedIn = isset($_SESSION['admin_id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Quizora - Online Examination System</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
        *{
            font-family: "Times New Roman", Times, serif;
        }

        .active-link{
            color:#2563eb;
            font-weight:bold;
        }
    </style>
</head>

<body class="bg-gray-50">

<!-- NAVBAR -->
<nav class="bg-white shadow-md sticky top-0 z-50">

    <div class="max-w-7xl mx-auto px-4">

        <div class="flex justify-between items-center h-20">

            <!-- LOGO -->
            <a href="index.php" class="flex items-center space-x-3">

                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white w-12 h-12 flex items-center justify-center rounded-xl shadow-lg">
                    <i class="fa-solid fa-graduation-cap text-xl"></i>
                </div>

                <div>
                    <h1 class="text-2xl font-bold text-blue-700 leading-tight">
                        Quizora
                    </h1>
                    <p class="text-xs text-gray-500">
                        Learn. Test. Improve.
                    </p>
                </div>

            </a>

            <!-- DESKTOP MENU -->
            <div class="hidden md:flex items-center space-x-8 text-lg">

                <a href="index.php"
                   class="<?php echo ($currentPage == 'index.php') ? 'active-link' : 'text-gray-700 hover:text-blue-600'; ?>">
                    <i class="fa-solid fa-house mr-1"></i> Home
                </a>

                <a href="quizzes.php"
                   class="<?php echo ($currentPage == 'quizzes.php') ? 'active-link' : 'text-gray-700 hover:text-blue-600'; ?>">
                    <i class="fa-solid fa-book-open mr-1"></i> Quizzes
                </a>

                <a href="verify.php"
                   class="<?php echo ($currentPage == 'verify.php') ? 'active-link' : 'text-gray-700 hover:text-blue-600'; ?>">
                    <i class="fa-solid fa-circle-check mr-1"></i> Verify
                </a>

                <a href="help.php"
                   class="<?php echo ($currentPage == 'help.php') ? 'active-link' : 'text-gray-700 hover:text-blue-600'; ?>">
                    <i class="fa-solid fa-circle-question mr-1"></i> Help
                </a>

                <!-- ADMIN LOGIN -->
                <a href="admin/index.php"
                   class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition shadow">

                    <i class="fa-solid fa-user-shield mr-2"></i>
                    Admin Login

                </a>

            </div>

            <!-- MOBILE BUTTON -->
            <button id="menu-btn" class="md:hidden text-2xl text-blue-700">
                <i class="fa-solid fa-bars"></i>
            </button>

        </div>

    </div>

    <!-- MOBILE MENU -->
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t">

        <div class="flex flex-col p-4 space-y-4 text-lg">

            <a href="index.php"
               class="<?php echo ($currentPage == 'index.php') ? 'active-link' : 'text-gray-700'; ?>">
                <i class="fa-solid fa-house mr-2"></i> Home
            </a>

            <a href="quizzes.php"
               class="<?php echo ($currentPage == 'quizzes.php') ? 'active-link' : 'text-gray-700'; ?>">
                <i class="fa-solid fa-book-open mr-2"></i> Quizzes
            </a>

            <a href="help.php"
               class="<?php echo ($currentPage == 'help.php') ? 'active-link' : 'text-gray-700'; ?>">
                <i class="fa-solid fa-circle-question mr-2"></i> Help
            </a>

            <a href="admin/index.php"
               class="bg-blue-600 text-white text-center py-3 rounded-lg">

                <i class="fa-solid fa-user-shield mr-2"></i>
                Admin Login

            </a>

        </div>

    </div>

</nav>

<!-- MOBILE MENU SCRIPT -->
<script>
document.getElementById('menu-btn').addEventListener('click', function () {
    document.getElementById('mobile-menu').classList.toggle('hidden');
});
</script>