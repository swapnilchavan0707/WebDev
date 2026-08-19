<?php
include 'config/db.php';
include 'includes/header.php';

try {
    $stmt = $pdo->prepare("SELECT * FROM exams ORDER BY id DESC");
    $stmt->execute();
    $exams = $stmt->fetchAll();
} catch (Exception $e) {
    die("Error fetching exams: " . $e->getMessage());
}
?>

<!-- PAGE HEADER -->
<section class="bg-gradient-to-r from-blue-800 to-indigo-700 text-white py-16">

    <div class="max-w-7xl mx-auto px-6 text-center">

        <h1 class="text-5xl font-bold mb-4">
            Available Quizzes
        </h1>

        <p class="text-blue-100 text-lg">
            Select a quiz topic and start your exam instantly.
        </p>

    </div>

</section>

<!-- QUIZ LIST -->
<section class="py-20 bg-gray-100">

    <div class="max-w-7xl mx-auto px-6">

        <?php if (count($exams) > 0): ?>

            <div class="grid md:grid-cols-3 gap-8">

                <?php foreach ($exams as $row): ?>

                    <?php
                        // SAFE fallback (IMPORTANT)
                        $duration = $row['duration'] ?? 0;
                    ?>

                    <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition">

                        <!-- CARD HEADER -->
                        <div class="bg-blue-600 text-white p-5">

                            <h2 class="text-2xl font-bold">
                                <i class="fa-solid fa-book-open mr-2"></i>
                                <?php echo htmlspecialchars($row['topic_name']); ?>
                            </h2>

                        </div>

                        <!-- CARD BODY -->
                        <div class="p-6">

                            <p class="text-gray-600 mb-3">
                                <i class="fa-solid fa-list mr-2 text-blue-600"></i>
                                Total Questions:
                                <span class="font-bold">
                                    <?php echo $row['total_questions']; ?>
                                </span>
                            </p>

                            <p class="text-gray-600 mb-5">
                                <i class="fa-solid fa-clock mr-2 text-green-600"></i>
                                Time:
                                <span class="font-bold">
                                    <?php echo $duration; ?> Minutes
                                </span>
                            </p>

                            <a href="start_quiz.php?exam_id=<?php echo $row['id']; ?>"
                               class="block text-center bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 transition">

                                <i class="fa-solid fa-play mr-2"></i>
                                Start Test

                            </a>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <div class="text-center py-20">

                <i class="fa-solid fa-circle-exclamation text-6xl text-gray-400 mb-4"></i>

                <h2 class="text-3xl font-bold text-gray-700 mb-2">
                    No Quizzes Available
                </h2>

                <p class="text-gray-500">
                    Please check back later.
                </p>

            </div>

        <?php endif; ?>

    </div>

</section>

<?php include 'includes/footer.php'; ?>