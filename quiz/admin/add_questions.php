<?php
require_once '../config/db.php';
require_once '../includes/admin_header.php';

$selected_exam = $_GET['exam_id'] ?? null;
$edit_question = null;

if (isset($_GET['edit'])) {

    $stmt = $pdo->prepare("
        SELECT * FROM questions WHERE id = ?
    ");
    $stmt->execute([$_GET['edit']]);
    $edit_question = $stmt->fetch();
}

if (isset($_POST['add_question'])) {

    $exam_id = $_POST['exam_id'];
    $question_text = trim($_POST['question_text']);
    $option_a = trim($_POST['option_a']);
    $option_b = trim($_POST['option_b']);
    $option_c = trim($_POST['option_c']);
    $option_d = trim($_POST['option_d']);
    $correct_option = $_POST['correct_option'];

    if ($exam_id && $question_text != "") {

        $stmt = $pdo->prepare("
            INSERT INTO questions
            (exam_id, question_text, option_a, option_b, option_c, option_d, correct_option)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $exam_id,
            $question_text,
            $option_a,
            $option_b,
            $option_c,
            $option_d,
            $correct_option
        ]);

        echo "<script>
                alert('Question Added Successfully');
                window.location.href='add_questions.php?exam_id=$exam_id';
              </script>";
        exit;
    }
}

if (isset($_POST['update_question'])) {

    $exam_id = $_POST['exam_id'];
    $id = $_POST['question_id'];

    $question_text = trim($_POST['question_text']);
    $option_a = trim($_POST['option_a']);
    $option_b = trim($_POST['option_b']);
    $option_c = trim($_POST['option_c']);
    $option_d = trim($_POST['option_d']);
    $correct_option = $_POST['correct_option'];

    $stmt = $pdo->prepare("
        UPDATE questions
        SET question_text = ?,
            option_a = ?,
            option_b = ?,
            option_c = ?,
            option_d = ?,
            correct_option = ?
        WHERE id = ?
    ");

    $stmt->execute([
        $question_text,
        $option_a,
        $option_b,
        $option_c,
        $option_d,
        $correct_option,
        $id
    ]);

    echo "<script>
            alert('Question Updated Successfully');
            window.location.href='add_questions.php?exam_id=$exam_id';
          </script>";
    exit;
}


if (isset($_GET['delete'])) {

    $id = $_GET['delete'];
    $exam_id = $_GET['exam_id'];

    $stmt = $pdo->prepare("DELETE FROM questions WHERE id = ?");
    $stmt->execute([$id]);

    echo "<script>
            alert('Question Deleted');
            window.location.href='add_questions.php?exam_id=$exam_id';
          </script>";
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM exams ORDER BY id DESC");
$stmt->execute();
$exams = $stmt->fetchAll();


$questions = [];

if ($selected_exam) {

    $stmt = $pdo->prepare("
        SELECT * FROM questions WHERE exam_id = ?
    ");
    $stmt->execute([$selected_exam]);
    $questions = $stmt->fetchAll();
}
?>

<div class="max-w-6xl mx-auto">

    <h1 class="text-3xl font-bold mb-6 text-blue-700">
        Add Questions
    </h1>

    <!-- SELECT EXAM -->
    <div class="bg-white p-6 rounded shadow mb-6">

        <form method="GET">

            <label class="font-bold">Select Exam</label>

            <select name="exam_id"
                    class="w-full border p-3 rounded mt-2"
                    onchange="this.form.submit()"
                    required>

                <option value="">-- Select Exam --</option>

                <?php foreach ($exams as $exam): ?>

                    <option value="<?php echo $exam['id']; ?>"
                        <?php echo ($selected_exam == $exam['id']) ? 'selected' : ''; ?>>

                        <?php echo htmlspecialchars($exam['topic_name']); ?>

                    </option>

                <?php endforeach; ?>

            </select>

        </form>

    </div>

    <?php if ($selected_exam): ?>

    <!-- ADD / EDIT QUESTION FORM -->
    <div class="bg-white p-6 rounded shadow mb-8">

        <h2 class="text-xl font-bold mb-4">
            <?php echo $edit_question ? "Edit Question" : "Add New Question"; ?>
        </h2>

        <form method="POST" class="space-y-3">

            <input type="hidden" name="exam_id" value="<?php echo $selected_exam; ?>">

            <?php if ($edit_question): ?>
                <input type="hidden" name="question_id" value="<?php echo $edit_question['id']; ?>">
            <?php endif; ?>

            <textarea name="question_text"
                      class="w-full border p-3 rounded"
                      required><?php echo $edit_question['question_text'] ?? ''; ?></textarea>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <input type="text" name="option_a" placeholder="Option A"
                       class="border p-3 rounded"
                       value="<?php echo $edit_question['option_a'] ?? ''; ?>" required>

                <input type="text" name="option_b" placeholder="Option B"
                       class="border p-3 rounded"
                       value="<?php echo $edit_question['option_b'] ?? ''; ?>" required>

                <input type="text" name="option_c" placeholder="Option C"
                       class="border p-3 rounded"
                       value="<?php echo $edit_question['option_c'] ?? ''; ?>" required>

                <input type="text" name="option_d" placeholder="Option D"
                       class="border p-3 rounded"
                       value="<?php echo $edit_question['option_d'] ?? ''; ?>" required>

            </div>

            <select name="correct_option"
                    class="w-full border p-3 rounded"
                    required>

                <option value="">Select Correct Answer</option>

                <option value="A" <?php if(($edit_question['correct_option'] ?? '')=='A') echo 'selected'; ?>>Option A</option>
                <option value="B" <?php if(($edit_question['correct_option'] ?? '')=='B') echo 'selected'; ?>>Option B</option>
                <option value="C" <?php if(($edit_question['correct_option'] ?? '')=='C') echo 'selected'; ?>>Option C</option>
                <option value="D" <?php if(($edit_question['correct_option'] ?? '')=='D') echo 'selected'; ?>>Option D</option>

            </select>

            <button type="submit"
                    name="<?php echo $edit_question ? 'update_question' : 'add_question'; ?>"
                    class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700">

                <?php echo $edit_question ? "Update Question" : "Add Question"; ?>

            </button>

        </form>

    </div>

    <!-- QUESTIONS LIST -->
    <div class="bg-white p-6 rounded shadow">

        <h2 class="text-xl font-bold mb-4">Questions List</h2>

        <?php if (count($questions) > 0): ?>

            <?php foreach ($questions as $index => $q): ?>

                <div class="border p-4 mb-4 rounded bg-gray-50">

                    <h3 class="font-bold mb-2">
                        Q<?php echo $index + 1; ?>.
                        <?php echo htmlspecialchars($q['question_text']); ?>
                    </h3>

                    <ul class="ml-4 text-gray-700">
                        <li>A: <?php echo $q['option_a']; ?></li>
                        <li>B: <?php echo $q['option_b']; ?></li>
                        <li>C: <?php echo $q['option_c']; ?></li>
                        <li>D: <?php echo $q['option_d']; ?></li>
                    </ul>

                    <p class="mt-2 text-green-600 font-bold">
                        Correct Answer: <?php echo $q['correct_option']; ?>
                    </p>

                    <div class="mt-3 space-x-3">

                        <a href="add_questions.php?exam_id=<?php echo $selected_exam; ?>&edit=<?php echo $q['id']; ?>"
                           class="bg-blue-600 text-white px-3 py-1 rounded text-sm">

                            Edit

                        </a>

                        <a href="?delete=<?php echo $q['id']; ?>&exam_id=<?php echo $selected_exam; ?>"
                           onclick="return confirm('Delete this question?')"
                           class="bg-red-600 text-white px-3 py-1 rounded text-sm">

                            Delete

                        </a>

                    </div>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <p class="text-gray-500">No questions added yet.</p>

        <?php endif; ?>

    </div>

    <?php endif; ?>

</div>

<?php include '../includes/admin_footer.php'; ?>