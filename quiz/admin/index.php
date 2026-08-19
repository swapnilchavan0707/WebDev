<?php
session_start();
require_once '../config/db.php';

$error = "";

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username !== "" && $password !== "") {

        // Fetch admin by username
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {

            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];

            header("Location: dashboard.php");
            exit;

        } else {
            $error = "Invalid username or password!";
        }

    } else {
        $error = "Please fill in all fields!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Quizora Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
        * {
            font-family: "Times New Roman", Times, serif;
        }
    </style>
</head>

<body class="bg-gradient-to-r from-blue-700 via-indigo-700 to-purple-800 min-h-screen flex items-center justify-center">

    <div class="bg-white w-full max-w-md p-8 rounded-2xl shadow-2xl">

        <!-- HEADER -->
        <div class="text-center mb-6">

            <div class="bg-blue-600 text-white w-14 h-14 mx-auto flex items-center justify-center rounded-xl shadow-lg">
                <i class="fa-solid fa-user-shield text-xl"></i>
            </div>

            <h1 class="text-2xl font-bold mt-4 text-gray-800">
                Quizora Admin
            </h1>

            <p class="text-gray-500 text-sm">
                Secure Login Panel
            </p>

        </div>

        <!-- ERROR MESSAGE -->
        <?php if (!empty($error)): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-center">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- LOGIN FORM -->
        <form method="POST" class="space-y-4">

            <div>
                <label class="text-gray-600">Username</label>
                <input type="text" name="username" required
                       class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="text-gray-600">Password</label>
                <input type="password" name="password" required
                       class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition font-bold">

                <i class="fa-solid fa-right-to-bracket mr-2"></i>
                Login

            </button>

        </form>

        <!-- BACK LINK -->
        <p class="text-center mt-5 text-sm text-gray-500">
            <a href="../index.php" class="text-blue-600 hover:underline">
                ← Back to Website
            </a>
        </p>

    </div>

</body>

</html>