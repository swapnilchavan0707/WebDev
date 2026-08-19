<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: index.php");
    exit;
}

$role = $_SESSION['role'];
$name = $_SESSION['name'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - FlowTrack</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
        body{
            font-family: "Times New Roman", Times, serif;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-100 via-white to-gray-200 min-h-screen">

<!-- TOP BAR -->
<div class="bg-white/90 backdrop-blur-md shadow-md px-8 py-5 flex justify-between items-center">

    <div>
        <h1 class="text-3xl font-bold text-blue-700">
            Welcome, <?php echo $name; ?>
        </h1>
        <p class="text-gray-500 text-sm mt-1">
            FlowTrack Productivity Dashboard
        </p>
    </div>

    <div class="flex items-center gap-6">

        <div class="text-gray-600">
            Role:
            <span class="font-bold text-blue-600">
                <?php echo $role; ?>
            </span>
        </div>

        <a href="auth/logout.php"
           class="bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-xl shadow-md transition">
            <i class="fa-solid fa-right-from-bracket mr-2"></i>
            Logout
        </a>

    </div>

</div>

<!-- DASHBOARD TITLE -->
<div class="text-center mt-12">
    <h2 class="text-4xl font-bold text-gray-800">
        Control Center
    </h2>
    <p class="text-gray-500 mt-2">
        Manage your system modules based on your role
    </p>
</div>

<!-- CARDS CONTAINER (Changed from grid to flex alignment for perfect centering) -->
<div class="max-w-6xl mx-auto mt-14 flex flex-col md:flex-row justify-center items-stretch gap-10 px-6 flex-wrap">

    <!-- ADMIN -->
    <?php if($role == "Super Admin"){ ?>
    <a href="admin/manage_admin.php"
       class="bg-white p-10 rounded-3xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2 border border-gray-100 w-full md:w-[calc(33.333%-1.67rem)] min-w-[280px]">

        <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mb-5">
            <i class="fa-solid fa-user-shield text-blue-600 text-2xl"></i>
        </div>

        <h3 class="text-2xl font-bold text-gray-800 mb-2">Manage Admin</h3>
        <p class="text-gray-600 leading-relaxed">
            Create, update, and remove admin accounts. Full system control access.
        </p>

    </a>
    <?php } ?>

    <!-- CUSTOMER -->
    <?php if($role == "Super Admin" || $role == "Admin"){ ?>
    <a href="customer/manage_customer.php"
       class="bg-white p-10 rounded-3xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2 border border-gray-100 w-full md:w-[calc(33.333%-1.67rem)] min-w-[280px]">

        <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center mb-5">
            <i class="fa-solid fa-users text-green-600 text-2xl"></i>
        </div>

        <h3 class="text-2xl font-bold text-gray-800 mb-2">Manage Customers</h3>
        <p class="text-gray-600 leading-relaxed">
            Handle customer accounts, details, and user data efficiently.
        </p>

    </a>
    <?php } ?>

    <!-- TASKS -->
    <a href="tasks/manage_tasks.php"
       class="bg-white p-10 rounded-3xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2 border border-gray-100 w-full md:w-[calc(33.333%-1.67rem)] min-w-[280px]">

        <div class="w-14 h-14 bg-yellow-100 rounded-2xl flex items-center justify-center mb-5">
            <i class="fa-solid fa-list-check text-yellow-600 text-2xl"></i>
        </div>

        <h3 class="text-2xl font-bold text-gray-800 mb-2">Manage Tasks</h3>
        <p class="text-gray-600 leading-relaxed">
            Create, track, and manage your personal and team tasks easily.
        </p>

    </a>

</div>

<!-- FOOTER TEXT -->
<div class="text-center mt-20 text-gray-500 text-sm">
    <p>FlowTrack © 2026 | Built for Productivity & Performance</p>
</div>

</body>
</html>