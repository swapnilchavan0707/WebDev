<?php
session_start();
include 'config/db.php';

// Check exam id
if (!isset($_GET['exam_id'])) {
    header("Location: quizzes.php");
    exit;
}

$exam_id = $_GET['exam_id'];

// Fetch exam details
$stmt = $pdo->prepare("SELECT * FROM exams WHERE id = ?");
$stmt->execute([$exam_id]);
$exam = $stmt->fetch();

if (!$exam) {
    die("Invalid Exam Selected");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Start Quiz - Quizora</title>

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
<div class="bg-blue-700 text-white text-center p-5">
    <h1 class="text-3xl font-bold">Start Your Quiz</h1>
</div>

<!-- FORM -->
<div class="max-w-2xl mx-auto mt-10 bg-white p-8 rounded shadow">

    <h2 class="text-2xl font-bold mb-4 text-center">
        <?php echo htmlspecialchars($exam['topic_name']); ?>
    </h2>

    <p class="text-gray-600 text-center mb-6">

        Total Questions:
        <b><?php echo $exam['total_questions']; ?></b>
        <br>

        Time:
        <b><?php echo $exam['duration']; ?> Minutes</b>

    </p>

    <div class="bg-yellow-50 border border-yellow-300 p-4 rounded mb-6">

        <h3 class="font-bold mb-2">
            <i class="fa-solid fa-circle-info mr-1"></i>
            Instructions
        </h3>

        <ul class="list-disc ml-5 text-sm text-gray-700">
            <li>Each question carries equal marks.</li>
            <li>Timer will run continuously for full exam.</li>
            <li>Do not refresh the page during exam.</li>
            <li>Exam will auto-submit when time ends.</li>
        </ul>

    </div>

    <!-- FORM -->
    <form method="POST">

        <label class="font-bold">Enter Your Name</label>

        <input type="text"
               name="candidate_name"
               placeholder="Your Full Name"
               class="w-full border p-3 rounded mt-2 mb-4"
               required>

        <button type="submit"
                class="w-full bg-blue-600 text-white py-3 rounded hover:bg-blue-700">

            <i class="fa-solid fa-play mr-2"></i>
            Start Exam

        </button>

    </form>

</div>

</body>

</html>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $candidate_name = trim($_POST['candidate_name']);

    if ($candidate_name == "") {
        echo "<script>alert('Enter your name');</script>";
        exit;
    }

    if (strlen($candidate_name) > 100) {
        die("Name too long");
    }

    // Store session securely
    $_SESSION['exam_id'] = $exam_id;
    $_SESSION['candidate_name'] = $candidate_name;

    // Redirect to quiz room
    header("Location: quiz_room.php");
    exit;
}
?>