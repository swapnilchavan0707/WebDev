<?php
session_start();
include 'config/db.php';

if (!isset($_GET['id'])) {
    header("Location: quizzes.php");
    exit;
}

$exam_id_string = $_GET['id'];

/* ============================
   FETCH MAIN RESULT + EXAM NAME + EMAIL
============================ */
$stmt = $pdo->prepare("
    SELECT r.*, e.topic_name
    FROM results r
    JOIN exams e ON r.exam_id = e.id
    WHERE r.exam_id_string = ?
");

$stmt->execute([$exam_id_string]);
$result = $stmt->fetch();

if (!$result) {
    echo "<h2>Result not found</h2>";
    exit;
}

/* ============================
   FETCH QUESTION-WISE DETAILS
============================ */
$stmt = $pdo->prepare("
    SELECT rd.*, q.question_text, q.correct_option,
           q.option_a, q.option_b, q.option_c, q.option_d
    FROM result_details rd
    JOIN questions q ON rd.question_id = q.id
    WHERE rd.result_string_id = ?
");

$stmt->execute([$exam_id_string]);
$details = $stmt->fetchAll();

/* ============================
   SAFE CALCULATIONS
============================ */
$percentage = 0;

if ($result['total_score'] > 0) {
    $percentage = ($result['score'] / $result['total_score']) * 100;
}

$correct = $result['score'];
$wrong = $result['total_score'] - $result['score'];

/* ============================
   FUNCTION: GET OPTION TEXT
============================ */
function getOptionText($row, $option)
{
    switch ($option) {
        case 'A': return $row['option_a'];
        case 'B': return $row['option_b'];
        case 'C': return $row['option_c'];
        case 'D': return $row['option_d'];
        default: return '';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Quizora - Result</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
        * {
            font-family: "Times New Roman", Times, serif;
        }
    </style>
</head>

<body class="bg-gray-100">

<!-- HEADER -->
<div class="bg-blue-700 text-white p-5 text-center">
    <h1 class="text-3xl font-bold">Quizora Exam Result</h1>
</div>

<!-- RESULT CARD -->
<div class="max-w-4xl mx-auto mt-8 bg-white p-6 rounded shadow">

    <h2 class="text-2xl font-bold mb-4">
        Candidate: <?php echo htmlspecialchars($result['candidate_name']); ?>
    </h2>

    <p class="text-lg">
        <b>Exam:</b> <?php echo htmlspecialchars($result['topic_name']); ?>
    </p>

    <p class="text-lg">
        <b>Exam ID:</b> <?php echo $result['exam_id_string']; ?>
    </p>

    <?php if (!empty($result['email'])): ?>
    <p class="text-lg">
        <b>Email:</b> <?php echo htmlspecialchars($result['email']); ?>
    </p>
    <?php endif; ?>

    <p class="text-lg">
        <b>Score:</b> <?php echo $result['score']; ?> / <?php echo $result['total_score']; ?>
    </p>

    <p class="text-lg">
        <b>Correct Answers:</b> <?php echo $correct; ?>
    </p>

    <p class="text-lg">
        <b>Wrong Answers:</b> <?php echo $wrong; ?>
    </p>

    <p class="text-lg">
        <b>Percentage:</b> <?php echo round($percentage, 2); ?>%
    </p>

    <!-- STATUS -->
    <?php if ($percentage >= 40): ?>
        <div class="mt-4 p-3 bg-green-100 text-green-700 font-bold rounded">
            <i class="fa-solid fa-circle-check mr-2"></i> Passed
        </div>
    <?php else: ?>
        <div class="mt-4 p-3 bg-red-100 text-red-700 font-bold rounded">
            <i class="fa-solid fa-circle-xmark mr-2"></i> Failed
        </div>
    <?php endif; ?>

</div>

<!-- QUESTION REVIEW -->
<div class="max-w-4xl mx-auto mt-8">

    <h2 class="text-2xl font-bold mb-4">Answer Review</h2>

    <?php foreach ($details as $index => $row): ?>

        <div class="bg-white p-5 rounded shadow mb-4">

            <h3 class="font-bold text-lg mb-3">
                Q<?php echo $index + 1; ?>.
                <?php echo htmlspecialchars($row['question_text']); ?>
            </h3>

            <p class="mb-1">
                <b>Your Answer:</b>
                <?php echo htmlspecialchars(getOptionText($row, $row['selected_option'])); ?>
                (<?php echo $row['selected_option']; ?>)
            </p>

            <p class="mb-1">
                <b>Correct Answer:</b>
                <?php echo htmlspecialchars(getOptionText($row, $row['correct_option'])); ?>
                (<?php echo $row['correct_option']; ?>)
            </p>

            <?php if ($row['is_correct'] == 1): ?>
                <p class="text-green-600 font-bold mt-2">
                    ✔ Correct
                </p>
            <?php else: ?>
                <p class="text-red-600 font-bold mt-2">
                    ✖ Wrong
                </p>
            <?php endif; ?>

        </div>

    <?php endforeach; ?>

</div>

</div>

<!-- BACK BUTTON -->
<div class="text-center mt-8 mb-10">

    <a href="quizzes.php"
       class="bg-gray-600 text-white px-6 py-3 rounded hover:bg-gray-700">

        <i class="fa-solid fa-arrow-left mr-2"></i>
        Back to Quizzes

    </a>

</div>

</body>
</html>