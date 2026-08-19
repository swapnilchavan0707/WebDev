<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['exam_id']) || !isset($_SESSION['candidate_name'])) {
    header("Location: quizzes.php");
    exit;
}

$exam_id = $_SESSION['exam_id'];
$candidate_name = $_SESSION['candidate_name'];

// Fetch exam details (NOW includes duration)
$stmt = $pdo->prepare("SELECT * FROM exams WHERE id = ?");
$stmt->execute([$exam_id]);
$exam = $stmt->fetch();

if (!$exam) {
    die("Invalid Exam");
}

// Fetch questions
$stmt = $pdo->prepare("SELECT * FROM questions WHERE exam_id = ?");
$stmt->execute([$exam_id]);
$questions = $stmt->fetchAll();

$total_questions = count($questions);

// TOTAL EXAM TIMER (NEW LOGIC)
$total_time = $exam['duration'] * 60; // minutes → seconds
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Quizora - Quiz Room</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/js/all.min.js"></script>

    <style>
        * {
            font-family: "Times New Roman", Times, serif;
        }
    </style>
</head>

<body class="bg-gray-100">

<!-- HEADER -->
<div class="bg-blue-700 text-white p-4 flex justify-between">
    <h2 class="text-xl font-bold">Quizora Exam Portal</h2>

    <div>
        Candidate: <b><?php echo htmlspecialchars($candidate_name); ?></b>
    </div>
</div>

<!-- TIMER -->
<div class="text-center p-4 bg-white shadow">
    <h3 class="text-2xl font-bold text-red-600">
        Time Left:
        <span id="timer"></span>
    </h3>
</div>

<!-- QUIZ FORM -->
<form id="quizForm" action="process_quiz.php" method="POST">

    <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
    <input type="hidden" name="candidate_name" value="<?php echo $candidate_name; ?>">

    <div class="max-w-4xl mx-auto mt-6">

        <?php foreach ($questions as $index => $q): ?>

            <div class="question bg-white p-6 rounded-lg shadow mb-6 question-box"
                 data-index="<?php echo $index; ?>"
                 style="<?php echo $index == 0 ? '' : 'display:none;'; ?>">

                <h3 class="text-xl font-bold mb-4">
                    Q<?php echo $index + 1; ?>.
                    <?php echo htmlspecialchars($q['question_text']); ?>
                </h3>

                <div class="space-y-3">

                    <label>
                        <input type="radio" name="answers[<?php echo $q['id']; ?>]" value="A">
                        <?php echo $q['option_a']; ?>
                    </label><br>

                    <label>
                        <input type="radio" name="answers[<?php echo $q['id']; ?>]" value="B">
                        <?php echo $q['option_b']; ?>
                    </label><br>

                    <label>
                        <input type="radio" name="answers[<?php echo $q['id']; ?>]" value="C">
                        <?php echo $q['option_c']; ?>
                    </label><br>

                    <label>
                        <input type="radio" name="answers[<?php echo $q['id']; ?>]" value="D">
                        <?php echo $q['option_d']; ?>
                    </label>

                </div>

            </div>

        <?php endforeach; ?>

        <!-- BUTTONS -->
        <div class="flex justify-between mt-6">

            <button type="button"
                    id="prevBtn"
                    class="bg-gray-500 text-white px-6 py-2 rounded">
                Previous
            </button>

            <button type="button"
                    id="nextBtn"
                    class="bg-blue-600 text-white px-6 py-2 rounded">
                Next
            </button>

            <button type="submit"
                    class="bg-green-600 text-white px-6 py-2 rounded">
                Submit Quiz
            </button>

        </div>

    </div>

</form>

<!-- JAVASCRIPT -->
<script>

let current = 0;
let questions = document.querySelectorAll(".question-box");

// SHOW QUESTION
function showQuestion(index) {
    questions.forEach((q, i) => {
        q.style.display = (i === index) ? "block" : "none";
    });
}

// NEXT QUESTION
function nextQuestion() {
    if (current < questions.length - 1) {
        current++;
        showQuestion(current);
    }
}

// PREVIOUS QUESTION
document.getElementById("prevBtn").addEventListener("click", function () {
    if (current > 0) {
        current--;
        showQuestion(current);
    }
});

// NEXT BUTTON
document.getElementById("nextBtn").addEventListener("click", function () {
    nextQuestion();
});

// ===============================
// ✅ UPDATED TIMER (EXAM LEVEL)
// ===============================
let timeLeft = <?php echo $total_time; ?>;

let timerDisplay = document.getElementById("timer");

function updateTimer() {

    let minutes = Math.floor(timeLeft / 60);
    let seconds = timeLeft % 60;

    timerDisplay.innerHTML =
        minutes + ":" + (seconds < 10 ? "0" + seconds : seconds);

    if (timeLeft <= 0) {
        clearInterval(timerInterval);
        document.getElementById("quizForm").submit();
    }

    timeLeft--;
}

let timerInterval = setInterval(updateTimer, 1000);
updateTimer();

</script>

</body>
</html>