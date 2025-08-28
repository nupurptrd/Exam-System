<?php
session_start();
require_once '../config/db_connect.php';
isAdmin();

$quizzes = $pdo->query("SELECT q.*, u.name as teacher_name FROM quizzes q LEFT JOIN users u ON q.teacher_id = u.id")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Quizzes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <main class="container mx-auto p-4" role="main">
        <section aria-labelledby="manage-quizzes-heading">
            <h2 id="manage-quizzes-heading" class="text-xl font-bold">Manage Quizzes</h2>
            <table class="w-full border-collapse border">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-2">Title</th>
                        <th class="border p-2">Teacher</th>
                        <th class="border p-2">Organization</th>
                        <th class="border p-2">Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($quizzes as $quiz): ?>
                        <tr>
                            <td class="border p-2"><?php echo htmlspecialchars($quiz['title']); ?></td>
                            <td class="border p-2"><?php echo htmlspecialchars($quiz['teacher_name']); ?></td>
                            <td class="border p-2"><?php echo htmlspecialchars($quiz['organization']); ?></td>
                            <td class="border p-2"><?php echo htmlspecialchars($quiz['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>