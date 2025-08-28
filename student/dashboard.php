<?php
session_start();
require_once '../config/db_connect.php';

function isStudent() {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
        header("Location: ../index.php");
        exit;
    }
}
isStudent();

$quizzes = $pdo->query("SELECT * FROM quizzes")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <main class="container mx-auto p-4" role="main">
        <section aria-labelledby="student-dashboard-heading">
            <h2 id="student-dashboard-heading" class="text-xl font-bold">Student Dashboard</h2>
            <p>Welcome, Student!</p>
            <h3 class="text-lg font-bold mt-4">Available Quizzes</h3>
            <ul class="list-disc pl-5">
                <?php foreach ($quizzes as $quiz): ?>
                    <li><a href="take_quiz.php?id=<?php echo $quiz['id']; ?>" class="text-blue-600 underline"><?php echo htmlspecialchars($quiz['title']); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </section>
    </main>
</body>
</html>