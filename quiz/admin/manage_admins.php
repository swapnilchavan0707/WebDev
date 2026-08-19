<?php
session_start();
require_once '../config/db.php';

// LOGIN CHECK
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit;
}

$success = "";
$error = "";

if (isset($_POST['add_admin'])) {

    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $mobile     = trim($_POST['mobile_number']);
    $alt_mobile = trim($_POST['alt_mobile_number']);
    $email      = trim($_POST['email']);
    $username   = trim($_POST['username']);
    $password   = trim($_POST['password']);

    if ($first_name && $last_name && $mobile && $email && $username && $password) {

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $pdo->prepare("
            INSERT INTO admins 
            (first_name, last_name, mobile_number, alt_mobile_number, email, username, password)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $first_name,
            $last_name,
            $mobile,
            $alt_mobile,
            $email,
            $username,
            $hashedPassword
        ]);

        $success = "Admin added successfully!";
    } else {
        $error = "Please fill all required fields!";
    }
}

if (isset($_GET['delete'])) {

    $id = $_GET['delete'];

    $stmt = $pdo->prepare("DELETE FROM admins WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: manage_admins.php");
    exit;
}

$editAdmin = null;

if (isset($_GET['edit'])) {

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editAdmin = $stmt->fetch();
}

/* UPDATE SUBMIT */
if (isset($_POST['update_admin'])) {

    $id = $_POST['id'];

    $stmt = $pdo->prepare("
        UPDATE admins SET 
        first_name=?,
        last_name=?,
        mobile_number=?,
        alt_mobile_number=?,
        email=?,
        username=?
        WHERE id=?
    ");

    $stmt->execute([
        $_POST['first_name'],
        $_POST['last_name'],
        $_POST['mobile_number'],
        $_POST['alt_mobile_number'],
        $_POST['email'],
        $_POST['username'],
        $id
    ]);

    header("Location: manage_admins.php");
    exit;
}

$admins = $pdo->query("SELECT * FROM admins ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<title>Manage Admins - Quizora</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://cdn.tailwindcss.com"></script>

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

<style>
* { font-family: "Times New Roman", Times, serif; }
</style>
</head>

<body class="bg-gray-100">

<div class="max-w-7xl mx-auto p-6">

<!-- HEADER -->
<div class="flex justify-between items-center mb-6">

    <h1 class="text-3xl font-bold text-gray-800">
        <i class="fa-solid fa-users text-blue-600 mr-2"></i>
        Manage Admins
    </h1>

    <a href="dashboard.php"
       class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Back
    </a>

</div>

<!-- MESSAGES -->
<?php if ($success): ?>
    <div class="bg-green-100 text-green-700 p-3 mb-4 rounded">
        <?= $success ?>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
        <?= $error ?>
    </div>
<?php endif; ?>

<!-- ADD / EDIT FORM -->
<div class="bg-white p-6 rounded-xl shadow mb-8">

<h2 class="text-xl font-bold mb-4">
    <?= $editAdmin ? "Edit Admin" : "Add New Admin" ?>
</h2>

<form method="POST" class="grid md:grid-cols-3 gap-4">

    <?php if ($editAdmin): ?>
        <input type="hidden" name="id" value="<?= $editAdmin['id'] ?>">
    <?php endif; ?>

    <input type="text" name="first_name"
           value="<?= $editAdmin['first_name'] ?? '' ?>"
           placeholder="First Name" class="p-2 border rounded" required>

    <input type="text" name="last_name"
           value="<?= $editAdmin['last_name'] ?? '' ?>"
           placeholder="Last Name" class="p-2 border rounded" required>

    <input type="text" name="mobile_number"
           value="<?= $editAdmin['mobile_number'] ?? '' ?>"
           placeholder="Mobile Number" class="p-2 border rounded" required>

    <input type="text" name="alt_mobile_number"
           value="<?= $editAdmin['alt_mobile_number'] ?? '' ?>"
           placeholder="Alt Mobile" class="p-2 border rounded">

    <input type="email" name="email"
           value="<?= $editAdmin['email'] ?? '' ?>"
           placeholder="Email" class="p-2 border rounded" required>

    <input type="text" name="username"
           value="<?= $editAdmin['username'] ?? '' ?>"
           placeholder="Username" class="p-2 border rounded" required>

    <?php if (!$editAdmin): ?>
        <input type="password" name="password"
               placeholder="Password" class="p-2 border rounded" required>
    <?php endif; ?>

    <button name="<?= $editAdmin ? 'update_admin' : 'add_admin' ?>"
            class="md:col-span-3 bg-blue-600 text-white py-2 rounded hover:bg-blue-700">

        <?= $editAdmin ? "Update Admin" : "Add Admin" ?>

    </button>

</form>

</div>

<!-- TABLE -->
<div class="bg-white p-6 rounded-xl shadow">

<h2 class="text-xl font-bold mb-4">All Admins</h2>

<table class="w-full border">

<thead class="bg-gray-200">
<tr>
    <th class="p-2">Sr. No</th>
    <th class="p-2">Name</th>
    <th class="p-2">Username</th>
    <th class="p-2">Email</th>
    <th class="p-2">Mobile</th>
    <th class="p-2">Action</th>
</tr>
</thead>

<tbody>
<?php $sr = 1; ?>
<?php foreach ($admins as $admin): ?>
<tr class="text-center border-t">

    <td class="p-2"><?= $sr++ ?></td>

    <td class="p-2">
        <?= $admin['first_name'] . " " . $admin['last_name'] ?>
    </td>

    <td class="p-2"><?= $admin['username'] ?></td>
    <td class="p-2"><?= $admin['email'] ?></td>
    <td class="p-2"><?= $admin['mobile_number'] ?></td>

    <td class="p-2 space-x-2">

        <a href="?edit=<?= $admin['id'] ?>"
           class="text-blue-600 hover:underline">
            <i class="fa-solid fa-pen"></i> Edit
        </a>

        <a href="?delete=<?= $admin['id'] ?>"
           onclick="return confirm('Delete this admin?')"
           class="text-red-600 hover:underline">
            <i class="fa-solid fa-trash"></i> Delete
        </a>

    </td>

</tr>
<?php endforeach; ?>
</tbody>

</table>

</div>

</div>

</body>
</html>