<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!-- SIDEBAR -->
<aside class="w-64 bg-white shadow-md min-h-screen">

    <div class="p-4">

        <!-- MENU TITLE -->
        <h2 class="text-gray-600 font-bold mb-4">
            MENU
        </h2>

        <ul class="space-y-2 text-gray-700">

            <!-- DASHBOARD -->
            <li>
                <a href="dashboard.php"
                   class="block p-3 rounded hover:bg-blue-100
                   <?php echo ($currentPage == 'dashboard.php') ? 'bg-blue-600 text-white' : ''; ?>">

                    <i class="fa-solid fa-gauge mr-2"></i>
                    Dashboard

                </a>
            </li>

            <!-- MANAGE ADMINS -->
            <li>
                <a href="manage_admins.php"
                   class="block p-3 rounded hover:bg-blue-100
                   <?php echo ($currentPage == 'manage_admins.php') ? 'bg-blue-600 text-white' : ''; ?>">

                    <i class="fa-solid fa-users mr-2"></i>
                    Manage Admins

                </a>
            </li>

            <!-- MANAGE EXAMS -->
            <li>
                <a href="manage_exams.php"
                   class="block p-3 rounded hover:bg-blue-100
                   <?php echo ($currentPage == 'manage_exams.php') ? 'bg-blue-600 text-white' : ''; ?>">

                    <i class="fa-solid fa-file-pen mr-2"></i>
                    Manage Exams

                </a>
            </li>

            <!-- ADD QUESTIONS -->
            <li>
                <a href="add_questions.php"
                   class="block p-3 rounded hover:bg-blue-100
                   <?php echo ($currentPage == 'add_questions.php') ? 'bg-blue-600 text-white' : ''; ?>">

                    <i class="fa-solid fa-circle-plus mr-2"></i>
                    Add Questions

                </a>
            </li>

            <!-- RESULTS -->
            <li>
                <a href="results.php"
                   class="block p-3 rounded hover:bg-blue-100
                   <?php echo ($currentPage == 'results.php') ? 'bg-blue-600 text-white' : ''; ?>">

                    <i class="fa-solid fa-chart-line mr-2"></i>
                    Results

                </a>
            </li>

        </ul>

    </div>

</aside>