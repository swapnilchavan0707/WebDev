<?php
session_start();
include "../config/db.php";

/* ================= SECURITY ================= */
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

/* ================= ADD ADMIN ================= */
if (isset($_POST['add_admin'])) {

    $first = $_POST['first'];
    $last = $_POST['last'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = "Admin";

    $check = $conn->prepare("SELECT user_id FROM users WHERE email = :email");
    $check->execute([':email' => $email]);

    if (!$check->fetch()) {

        $sql = "INSERT INTO users 
        (first_name,last_name,mobile_number,email,password,role)
        VALUES (:first,:last,:mobile,:email,:password,:role)";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':first' => $first,
            ':last' => $last,
            ':mobile' => $mobile,
            ':email' => $email,
            ':password' => $password,
            ':role' => $role
        ]);
    }
}

/* ================= DELETE ================= */
if (isset($_GET['delete'])) {

    $id = $_GET['delete'];

    $del = $conn->prepare("DELETE FROM users WHERE user_id = :id AND role='Admin'");
    $del->execute([':id' => $id]);
}

/* ================= UPDATE ================= */
if (isset($_POST['update_admin'])) {

    $id = $_POST['user_id'];
    $first = $_POST['first'];
    $last = $_POST['last'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $sql = "UPDATE users SET 
        first_name=:first,
        last_name=:last,
        mobile_number=:mobile,
        email=:email,
        password=:password
        WHERE user_id=:id AND role='Admin'";
    } else {
        $sql = "UPDATE users SET 
        first_name=:first,
        last_name=:last,
        mobile_number=:mobile,
        email=:email
        WHERE user_id=:id AND role='Admin'";
    }

    $stmt = $conn->prepare($sql);

    $params = [
        ':first' => $first,
        ':last' => $last,
        ':mobile' => $mobile,
        ':email' => $email,
        ':id' => $id
    ];

    if (!empty($_POST['password'])) {
        $params[':password'] = $password;
    }

    $stmt->execute($params);
}

/* ================= FETCH ================= */
$stmt = $conn->prepare("SELECT * FROM users WHERE role='Admin' ORDER BY user_id DESC");
$stmt->execute();
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Admin</title>
<script src="https://cdn.tailwindcss.com"></script>

<style>
body{
    font-family:"Times New Roman", Times, serif;
}
</style>
</head>

<body class="bg-gray-100">

<!-- TOP BAR -->
<div class="bg-white shadow px-6 py-4 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-blue-700">Manage Admin</h1>

    <a href="../dashboard.php"
       class="bg-gray-700 text-white px-5 py-2 rounded-lg hover:bg-gray-800">
        Back to Dashboard
    </a>
</div>

<!-- ADD ADMIN -->
<div class="max-w-6xl mx-auto mt-8 bg-white p-6 rounded-xl shadow">

    <h2 class="text-xl font-bold text-gray-800 mb-4">Add New Admin</h2>

    <form method="POST" class="grid md:grid-cols-3 gap-4">

        <input name="first" placeholder="First Name"
        class="border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" required>

        <input name="last" placeholder="Last Name"
        class="border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" required>

        <input name="mobile" placeholder="Mobile"
        class="border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" required>

        <input name="email" placeholder="Email"
        class="border p-2 rounded md:col-span-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>

        <input name="password" type="password" placeholder="Password"
        class="border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" required>

        <button name="add_admin"
        class="bg-blue-600 text-white py-2 rounded hover:bg-blue-700 md:col-span-3">
            Add Admin
        </button>

    </form>
</div>

<!-- TABLE -->
<div class="max-w-6xl mx-auto mt-10 bg-white p-6 rounded-xl shadow">

<h2 class="text-xl font-bold text-gray-800 mb-4">Admin List</h2>

<div class="overflow-x-auto">

<table class="w-full border-collapse">

<thead>
<tr class="bg-blue-600 text-white text-left">
    <th class="p-3">Sr No</th>
    <th class="p-3">Name</th>
    <th class="p-3">Email</th>
    <th class="p-3">Mobile</th>
    <th class="p-3">Action</th>
</tr>
</thead>

<tbody>

<?php $i = 1; foreach(array_reverse($admins) as $a){ ?>

<tr class="border-b hover:bg-gray-50">

<td class="p-3"><?= $i++ ?></td>

<td class="p-3 font-semibold">
<?= $a['first_name']." ".$a['last_name'] ?>
</td>

<td class="p-3"><?= $a['email'] ?></td>

<td class="p-3"><?= $a['mobile_number'] ?></td>

<td class="p-3 flex gap-2">

<!-- EDIT -->
<button onclick="openEdit(
'<?= $a['user_id'] ?>',
'<?= $a['first_name'] ?>',
'<?= $a['last_name'] ?>',
'<?= $a['mobile_number'] ?>',
'<?= $a['email'] ?>'
)"
class="bg-yellow-500 text-white px-4 py-1 rounded hover:bg-yellow-600">
Edit
</button>

<!-- DELETE -->
<a href="?delete=<?= $a['user_id'] ?>"
onclick="return confirm('Are you sure?')"
class="bg-red-500 text-white px-4 py-1 rounded hover:bg-red-600">
Delete
</a>

</td>

</tr>

<?php } ?>

</tbody>
</table>

</div>
</div>

<!-- EDIT MODAL -->
<div id="editModal"
class="hidden fixed inset-0 bg-black/50 flex items-center justify-center">

<div class="bg-white w-96 p-6 rounded-xl shadow-lg">

<h2 class="text-xl font-bold mb-4">Edit Admin</h2>

<form method="POST">

<input type="hidden" name="user_id" id="e_id">

<input name="first" id="e_first" class="w-full border p-2 mb-2 rounded" placeholder="First Name">
<input name="last" id="e_last" class="w-full border p-2 mb-2 rounded" placeholder="Last Name">
<input name="mobile" id="e_mobile" class="w-full border p-2 mb-2 rounded" placeholder="Mobile">
<input name="email" id="e_email" class="w-full border p-2 mb-2 rounded" placeholder="Email">

<input name="password" type="password"
class="w-full border p-2 mb-3 rounded"
placeholder="New Password (leave blank to keep current)">

<button name="update_admin"
class="bg-green-600 text-white w-full py-2 rounded hover:bg-green-700">
Update Admin
</button>

</form>

<button onclick="closeEdit()" class="mt-3 w-full text-gray-600">
Close
</button>

</div>
</div>

<script>

function openEdit(id,f,l,m,e){
    document.getElementById("editModal").classList.remove("hidden");

    document.getElementById("e_id").value = id;
    document.getElementById("e_first").value = f;
    document.getElementById("e_last").value = l;
    document.getElementById("e_mobile").value = m;
    document.getElementById("e_email").value = e;
}

function closeEdit(){
    document.getElementById("editModal").classList.add("hidden");
}

</script>

</body>
</html>