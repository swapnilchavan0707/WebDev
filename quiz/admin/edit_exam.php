<?php
require_once '../config/db.php';
require_once '../includes/admin_header.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<script>
            alert('Invalid Exam ID');
            window.location.href='manage_exams.php';
          </script>";
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM exams WHERE id = ?");
$stmt->execute([$id]);
$exam = $stmt->fetch();

if (!$exam) {
    echo "<script>
            alert('Exam not found');
            window.location.href='manage_exams.php';
          </script>";
    exit;
}

if (isset($_POST['update_exam'])) {

    $topic_name = trim($_POST['topic_name']);
    $total_questions = intval($_POST['total_questions']);
    $duration = intval($_POST['duration']);

    if ($topic_name != "" && $total_questions > 0) {

        $stmt = $pdo->prepare("
            UPDATE exams
            SET topic_name = ?,
                total_questions = ?,
                duration = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $topic_name,
            $total_questions,
            $duration,
            $id
        ]);

        echo "<script>
                alert('Exam Updated Successfully');
                window.location.href='manage_exams.php';
              </script>";
        exit;
    }
}
?>

<div class="max-w-3xl mx-auto">

    <h1 class="text-3xl font-bold mb-6 text-blue-700">
        Edit Exam
    </h1>

    <div class="bg-white p-6 rounded shadow">

        <form method="POST" class="space-y-4">

            <!-- TOPIC NAME -->
            <div>
                <label class="font-bold">Topic Name</label>
                <input type="text"
                       name="topic_name"
                       value="<?php echo htmlspecialchars($exam['topic_name']); ?>"
                       class="w-full border p-3 rounded mt-2"
                       required>
            </div>

            <!-- TOTAL QUESTIONS -->
            <div>
                <label class="font-bold">Total Questions</label>
                <input type="number"
                       name="total_questions"
                       value="<?php echo $exam['total_questions']; ?>"
                       class="w-full border p-3 rounded mt-2"
                       required>
            </div>

            <!-- DURATION -->
            <div>
                <label class="font-bold">Duration (Minutes)</label>
                <input type="number"
                       name="duration"
                       value="<?php echo $exam['duration']; ?>"
                       class="w-full border p-3 rounded mt-2"
                       required>
            </div>

            <!-- BUTTONS -->
            <div class="flex gap-3">

                <button type="submit"
                        name="update_exam"
                        class="bg-green-600 text-white px-6 py-3 rounded hover:bg-green-700">

                    Update Exam

                </button>

                <a href="manage_exams.php"
                   class="bg-gray-600 text-white px-6 py-3 rounded hover:bg-gray-700">

                    Cancel

                </a>

            </div>

        </form>

    </div>

</div>

<?php include '../includes/admin_footer.php'; ?>