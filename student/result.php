<?php
session_start();
require_once '../config/db_connect.php';
isStudent();

$quiz_id = $_GET['quiz_id'];
$result = $pdo->prepare("SELECT * FROM results WHERE user_id = ? AND quiz_id = ? ORDER BY submitted_at DESC LIMIT 1");
$result->execute([$_SESSION['user_id'], $quiz_id]);
$result = $result->fetch();

$quiz = $pdo->prepare("SELECT * FROM quizzes WHERE id = ?");
$quiz->execute([$quiz_id]);
$quiz = $quiz->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Result</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <main class="container mx-auto p-4" role="main">
        <section aria-labelledby="result-heading">
            <h2 id="result-heading" class="text-xl font-bold">Quiz Result</h2>
            <p>Quiz: <?php echo htmlspecialchars($quiz['title']); ?></p>
            <p>Score: <?php echo $result['score']; ?>/100</p>
            <p>Status: <?php echo $result['score'] >= 40 ? 'Pass' : 'Fail'; ?></p>
            <a href="dashboard.php" class="text-blue-600 underline">Back to Dashboard</a>
        </section>
    </main>
</body>
</html>