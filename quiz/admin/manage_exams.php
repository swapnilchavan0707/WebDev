<?php
require_once '../config/db.php';
require_once '../includes/admin_header.php';

if (isset($_POST['add_exam'])) {

    $topic_name = trim($_POST['topic_name']);
    $total_questions = intval($_POST['total_questions']);
    $duration = intval($_POST['duration']);

    if ($topic_name != "" && $total_questions > 0) {

        $stmt = $pdo->prepare("
            INSERT INTO exams (topic_name, total_questions, duration)
            VALUES (?, ?, ?)
        ");

        $stmt->execute([$topic_name, $total_questions, $duration]);

        echo "<script>alert('Exam Added Successfully');window.location.href='manage_exams.php';</script>";
        exit;
    }
}

/* =========================
   DELETE EXAM
========================= */
if (isset($_GET['delete'])) {

    $id = $_GET['delete'];

    $stmt = $pdo->prepare("DELETE FROM exams WHERE id = ?");
    $stmt->execute([$id]);

    echo "<script>alert('Exam Deleted');window.location.href='manage_exams.php';</script>";
    exit;
}

/* =========================
   LOAD EXAMS
========================= */
$stmt = $pdo->prepare("SELECT * FROM exams ORDER BY id DESC");
$stmt->execute();
$exams = $stmt->fetchAll();
?>

<div class="max-w-6xl mx-auto">

    <h1 class="text-3xl font-bold mb-6 text-blue-700">
        Manage Exams
    </h1>

    <!-- ADD EXAM FORM -->
    <div class="bg-white p-6 rounded shadow mb-8">

        <h2 class="text-xl font-bold mb-4">Add New Exam</h2>

        <form method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <input type="text"
                   name="topic_name"
                   placeholder="Topic Name"
                   class="border p-3 rounded"
                   required>

            <input type="number"
                   name="total_questions"
                   placeholder="Total Questions"
                   class="border p-3 rounded"
                   required>

            <input type="number"
                   name="duration"
                   placeholder="Duration (minutes)"
                   class="border p-3 rounded"
                   required>

            <button type="submit"
                    name="add_exam"
                    class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700 md:col-span-3">

                <i class="fa-solid fa-plus mr-2"></i>
                Add Exam

            </button>

        </form>

    </div>

    <!-- EXAMS TABLE -->
    <div class="bg-white p-6 rounded shadow">

        <h2 class="text-xl font-bold mb-4">All Exams</h2>

        <div class="overflow-x-auto">

            <table class="w-full border-collapse">

                <thead>
                    <tr class="bg-blue-600 text-white">
                        <th class="p-3 text-left">ID</th>
                        <th class="p-3 text-left">Topic</th>
                        <th class="p-3 text-left">Questions</th>
                        <th class="p-3 text-left">Duration</th>
                        <th class="p-3 text-left">Created At</th>
                        <th class="p-3 text-left">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($exams as $exam): ?>

                        <tr class="border-b hover:bg-gray-100">

                            <td class="p-3">
                                <?php echo $exam['id']; ?>
                            </td>

                            <td class="p-3 font-bold">
                                <?php echo htmlspecialchars($exam['topic_name']); ?>
                            </td>

                            <td class="p-3">
                                <?php echo $exam['total_questions']; ?>
                            </td>

                            <td class="p-3">
                                <?php echo $exam['duration']; ?> min
                            </td>

                            <td class="p-3">
                                <?php echo $exam['created_at']; ?>
                            </td>

                            <td class="p-3 space-x-2">

                                <!-- CREATE EXAM -->
                                <a href="add_questions.php?exam_id=<?php echo $exam['id']; ?>"
                                   class="bg-green-600 text-white px-3 py-1 rounded text-sm">

                                    Create

                                </a>

                                <!-- EDIT EXAM -->
                                <a href="edit_exam.php?id=<?php echo $exam['id']; ?>"
                                   class="bg-yellow-500 text-white px-3 py-1 rounded text-sm">

                                    Edit

                                </a>

                                <!-- DELETE  EXAM -->
                                <a href="?delete=<?php echo $exam['id']; ?>"
                                   onclick="return confirm('Are you sure?')"
                                   class="bg-red-600 text-white px-3 py-1 rounded text-sm">

                                    Delete

                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                    <?php if (count($exams) == 0): ?>

                        <tr>
                            <td colspan="6" class="text-center p-5 text-gray-500">
                                No exams found
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php include '../includes/admin_footer.php'; ?>