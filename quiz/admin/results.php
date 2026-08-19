<?php
require_once '../config/db.php';
require_once '../includes/admin_header.php';

$search = "";
$results = [];

if (isset($_GET['search'])) {

    $search = trim($_GET['search']);

    $stmt = $pdo->prepare("
        SELECT *
        FROM results
        WHERE exam_id_string LIKE ?
           OR candidate_name LIKE ?
           OR email LIKE ?
        ORDER BY id DESC
    ");

    $stmt->execute([
        "%$search%",
        "%$search%",
        "%$search%"
    ]);

    $results = $stmt->fetchAll();

} else {

    $stmt = $pdo->prepare("
        SELECT *
        FROM results
        ORDER BY id DESC
    ");

    $stmt->execute();
    $results = $stmt->fetchAll();
}
?>

<div class="max-w-7xl mx-auto">

    <h1 class="text-3xl font-bold mb-6 text-blue-700">
        Exam Results
    </h1>

    <!-- SEARCH BOX -->
    <div class="bg-white p-6 rounded shadow mb-6">

        <form method="GET" class="flex gap-3">

            <input type="text"
                   name="search"
                   value="<?php echo htmlspecialchars($search); ?>"
                   placeholder="Search by Exam ID / Name / Email"
                   class="w-full border p-3 rounded">

            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700">

                <i class="fa-solid fa-search mr-2"></i>
                Search

            </button>

        </form>

    </div>

    <!-- RESULTS TABLE -->
    <div class="bg-white p-6 rounded shadow overflow-x-auto">

        <table class="w-full border-collapse">

            <thead>
                <tr class="bg-blue-600 text-white text-left">

                    <th class="p-3">Exam ID</th>
                    <th class="p-3">Candidate</th>
                    <th class="p-3">Score</th>
                    <th class="p-3">Total</th>
                    <th class="p-3">Percentage</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Date</th>

                </tr>
            </thead>

            <tbody>

                <?php if (count($results) > 0): ?>

                    <?php foreach ($results as $row): ?>

                        <?php
                            $percentage = 0;

                            if ($row['total_score'] > 0) {
                                $percentage = ($row['score'] / $row['total_score']) * 100;
                            }
                        ?>

                        <tr class="border-b hover:bg-gray-100">

                            <td class="p-3 font-bold">
                                <?php echo $row['exam_id_string']; ?>
                            </td>

                            <td class="p-3">
                                <?php echo htmlspecialchars($row['candidate_name']); ?>
                            </td>

                            <td class="p-3">
                                <?php echo $row['score']; ?>
                            </td>

                            <td class="p-3">
                                <?php echo $row['total_score']; ?>
                            </td>

                            <td class="p-3 font-bold">
                                <?php echo round($percentage, 2); ?>%
                            </td>

                            <td class="p-3">

                                <?php if ($percentage >= 40): ?>
                                    <span class="text-green-600 font-bold">Passed</span>
                                <?php else: ?>
                                    <span class="text-red-600 font-bold">Failed</span>
                                <?php endif; ?>

                            </td>

                            <td class="p-3 text-sm text-gray-600">
                                <?php echo $row['attempted_at']; ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="8" class="text-center p-5 text-gray-500">
                            No results found
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

<?php include '../includes/admin_footer.php'; ?>