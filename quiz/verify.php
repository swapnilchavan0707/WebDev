<?php
include 'config/db.php';
include 'includes/header.php';

$resultData = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $exam_id_string = trim($_POST['exam_id']);

    if (!empty($exam_id_string)) {

        // ============================
        // FETCH RESULT + EXAM NAME
        // ============================
        $stmt = $pdo->prepare("
            SELECT r.*, e.topic_name
            FROM results r
            JOIN exams e ON r.exam_id = e.id
            WHERE r.exam_id_string = ?
        ");

        $stmt->execute([$exam_id_string]);
        $resultData = $stmt->fetch();
    }
}
?>

<!-- HEADER SECTION -->
<div class="bg-blue-700 text-white text-center p-6">
    <h1 class="text-3xl font-bold">
        Verify Your Exam Result
    </h1>
    <p class="text-blue-100 mt-2">
        Enter your Exam ID to check your result
    </p>
</div>

<!-- SEARCH BOX -->
<div class="max-w-xl mx-auto mt-10 bg-white p-8 rounded shadow">

    <form method="POST">

        <label class="font-bold">Enter Exam ID</label>

        <input type="text"
               name="exam_id"
               placeholder="Example: EXAM-20260621153025124"
               class="w-full border p-3 rounded mt-2 mb-4"
               required>

        <button type="submit"
                class="w-full bg-blue-600 text-white py-3 rounded hover:bg-blue-700">

            <i class="fa-solid fa-magnifying-glass mr-2"></i>
            Verify Result

        </button>

    </form>

</div>

<!-- RESULT DISPLAY -->
<?php if ($resultData): ?>

<?php
$percentage = ($resultData['score'] / $resultData['total_score']) * 100;
$status = ($percentage >= 40) ? "PASS" : "FAIL";
?>

<div class="max-w-xl mx-auto mt-8 bg-white p-6 rounded shadow">

    <h2 class="text-2xl font-bold mb-4 text-center text-green-600">
        Result Found ✔
    </h2>

    <p><b>Candidate Name:</b> <?php echo htmlspecialchars($resultData['candidate_name']); ?></p>

    <p><b>Exam:</b> <?php echo htmlspecialchars($resultData['topic_name']); ?></p>

    <p><b>Exam ID:</b> <?php echo $resultData['exam_id_string']; ?></p>

    <?php if (!empty($resultData['email'])): ?>
    <p><b>Email:</b> <?php echo htmlspecialchars($resultData['email']); ?></p>
    <?php endif; ?>

    <p><b>Score:</b> <?php echo $resultData['score']; ?> / <?php echo $resultData['total_score']; ?></p>

    <p><b>Percentage:</b> <?php echo round($percentage, 2); ?>%</p>

    <!-- STATUS -->
    <div class="mt-4 p-3 rounded text-center font-bold">

        <?php if ($status == "PASS"): ?>
            <span class="text-green-700">✔ Passed</span>
        <?php else: ?>
            <span class="text-red-700">✖ Failed</span>
        <?php endif; ?>

    </div>

</div>

<?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>

<div class="max-w-xl mx-auto mt-8 bg-white p-6 rounded shadow text-center">

    <h2 class="text-red-600 font-bold text-xl">
        ❌ No Result Found
    </h2>

    <p class="text-gray-600 mt-2">
        Please check your Exam ID and try again.
    </p>

</div>

<?php endif; ?>

<?php include 'includes/footer.php'; ?>