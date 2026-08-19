<?php
session_start();
include 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: quizzes.php");
    exit;
}

$exam_id = $_POST['exam_id'];
$answers = $_POST['answers'] ?? [];

$candidate_name = $_POST['candidate_name'] ?? ($_SESSION['candidate_name'] ?? 'Unknown');

/* Optional email */
$email = $_SESSION['email'] ?? null;


$stmt = $pdo->prepare("SELECT * FROM questions WHERE exam_id = ?");
$stmt->execute([$exam_id]);
$questions = $stmt->fetchAll();

$total_questions = count($questions);
$score = 0;


$exam_id_string = "EXAM-" . uniqid();

try {

    $pdo->beginTransaction();


    $stmt = $pdo->prepare("
        INSERT INTO results 
        (exam_id_string, candidate_name, exam_id, score, total_score, email)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $exam_id_string,
        $candidate_name,
        $exam_id,
        $score,
        $total_questions,
        $email
    ]);

    foreach ($questions as $question) {

        $qid = $question['id'];
        $correct = $question['correct_option'];
        $selected = $answers[$qid] ?? null;

        if ($selected === $correct) {
            $score++;
            $is_correct = 1;
        } else {
            $is_correct = 0;
        }

        $stmt = $pdo->prepare("
            INSERT INTO result_details 
            (result_string_id, question_id, selected_option, is_correct)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->execute([
            $exam_id_string,
            $qid,
            $selected,
            $is_correct
        ]);
    }

    $stmt = $pdo->prepare("
        UPDATE results 
        SET score = ?
        WHERE exam_id_string = ?
    ");

    $stmt->execute([
        $score,
        $exam_id_string
    ]);

    $pdo->commit();

} catch (Exception $e) {

    $pdo->rollBack();
    die("Error saving result: " . $e->getMessage());
}


$_SESSION['result_id'] = $exam_id_string;


header("Location: result.php?id=" . $exam_id_string);
exit;
?>