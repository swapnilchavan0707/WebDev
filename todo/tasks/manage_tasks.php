<?php
session_start();
include "../config/db.php";

/* ================= SECURITY ================= */
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$current_user_id = $_SESSION['user_id'];

/* ================= ADD TASK ================= */
if (isset($_POST['add_task'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    $sql = "INSERT INTO tasks (user_id, title, description, status) 
            VALUES (:user_id, :title, :description, :status)";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':user_id' => $current_user_id,
        ':title' => $title,
        ':description' => $description,
        ':status' => $status
    ]);
}

/* ================= DELETE TASK ================= */
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Ensures users can only delete their own tasks
    $del = $conn->prepare("DELETE FROM tasks WHERE task_id = :id AND user_id = :user_id");
    $del->execute([
        ':id' => $id,
        ':user_id' => $current_user_id
    ]);
}

/* ================= UPDATE TASK ================= */
if (isset($_POST['update_task'])) {
    $id = $_POST['task_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    $sql = "UPDATE tasks SET 
            title = :title, 
            description = :description, 
            status = :status 
            WHERE task_id = :id AND user_id = :user_id";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':title' => $title,
        ':description' => $description,
        ':status' => $status,
        ':id' => $id,
        ':user_id' => $current_user_id
    ]);
}

/* ================= FETCH TASKS ================= */
$stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id = :user_id ORDER BY task_id DESC");
$stmt->execute([':user_id' => $current_user_id]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Tasks</title>
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
    <h1 class="text-2xl font-bold text-blue-700">Manage Tasks</h1>

    <a href="../dashboard.php"
       class="bg-gray-700 text-white px-5 py-2 rounded-lg hover:bg-gray-800">
        Back to Dashboard
    </a>
</div>

<!-- ADD TASK -->
<div class="max-w-6xl mx-auto mt-8 bg-white p-6 rounded-xl shadow">

    <h2 class="text-xl font-bold text-gray-800 mb-4">Add New Task</h2>

    <form method="POST" class="grid md:grid-cols-3 gap-4">

        <input name="title" placeholder="Task Title"
        class="border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 md:col-span-2" required>

        <select name="status" class="border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" required>
            <option value="Pending">Pending</option>
            <option value="In Progress">In Progress</option>
            <option value="Completed">Completed</option>
        </select>

        <textarea name="description" placeholder="Task Description" rows="2"
        class="border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 md:col-span-3" required></textarea>

        <button name="add_task"
        class="bg-blue-600 text-white py-2 rounded hover:bg-blue-700 md:col-span-3">
            Add Task
        </button>

    </form>
</div>

<!-- TABLE -->
<div class="max-w-6xl mx-auto mt-10 bg-white p-6 rounded-xl shadow">

<h2 class="text-xl font-bold text-gray-800 mb-4">Task List</h2>

<div class="overflow-x-auto">

<table class="w-full border-collapse">

<thead>
<tr class="bg-blue-600 text-white text-left">
    <th class="p-3">Sr No</th>
    <th class="p-3">Title</th>
    <th class="p-3">Description</th>
    <th class="p-3">Status</th>
    <th class="p-3">Date Added</th>
    <th class="p-3">Action</th>
</tr>
</thead>

<tbody>

<?php $i = 1; foreach($tasks as $t){ ?>

<tr class="border-b hover:bg-gray-50">

<td class="p-3"><?= $i++ ?></td>

<td class="p-3 font-semibold"><?= htmlspecialchars($t['title']) ?></td>

<td class="p-3 text-gray-600"><?= htmlspecialchars($t['description']) ?></td>

<td class="p-3">
    <span class="px-2 py-1 rounded text-xs font-bold text-white 
        <?= $t['status'] === 'Completed' ? 'bg-green-600' : ($t['status'] === 'In Progress' ? 'bg-yellow-500' : 'bg-red-500') ?>">
        <?= $t['status'] ?>
    </span>
</td>

<td class="p-3 text-sm text-gray-500"><?= date("d-M-Y h:i A", strtotime($t['created_at'])) ?></td>

<td class="p-3 flex gap-2">

<!-- EDIT -->
<button onclick="openEdit(
'<?= $t['task_id'] ?>',
'<?= addslashes(htmlspecialchars($t['title'])) ?>',
'<?= addslashes(htmlspecialchars($t['description'])) ?>',
'<?= $t['status'] ?>'
)"
class="bg-yellow-500 text-white px-4 py-1 rounded hover:bg-yellow-600">
Edit
</button>

<!-- DELETE -->
<a href="?delete=<?= $t['task_id'] ?>"
onclick="return confirm('Are you sure you want to delete this task?')"
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

<h2 class="text-xl font-bold mb-4">Edit Task</h2>

<form method="POST">

<input type="hidden" name="task_id" id="e_id">

<div class="mb-2">
    <label class="text-sm text-gray-600">Title</label>
    <input name="title" id="e_title" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-400" required>
</div>

<div class="mb-2">
    <label class="text-sm text-gray-600">Description</label>
    <textarea name="description" id="e_description" rows="3" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-400" required></textarea>
</div>

<div class="mb-3">
    <label class="text-sm text-gray-600">Status</label>
    <select name="status" id="e_status" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-400" required>
        <option value="Pending">Pending</option>
        <option value="In Progress">In Progress</option>
        <option value="Completed">Completed</option>
    </select>
</div>

<button name="update_task"
class="bg-green-600 text-white w-full py-2 rounded hover:bg-green-700">
Update Task
</button>

</form>

<button onclick="closeEdit()" class="mt-3 w-full text-gray-600">
Close
</button>

</div>
</div>

<script>

function openEdit(id, title, desc, status){
    document.getElementById("editModal").classList.remove("hidden");

    document.getElementById("e_id").value = id;
    document.getElementById("e_title").value = title;
    document.getElementById("e_description").value = desc;
    document.getElementById("e_status").value = status;
}

function closeEdit(){
    document.getElementById("editModal").classList.add("hidden");
}

</script>

</body>
</html>