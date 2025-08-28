<?php
session_start();
require_once '../config/db_connect.php';
isAdmin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    #$organization = $_POST['organization'];
    #$questions = $_POST['questions'];

    if (count($questions) > 50) {
        $error = "Maximum 50 questions allowed.";
    } else {
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("INSERT INTO quizzes (title) VALUES (?)");
            $stmt->execute([$title, $_SESSION['user_id']]);
            $quiz_id = $pdo->lastInsertId();

            $stmt = $pdo->prepare("INSERT INTO questions (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option) VALUES (?, ?, ?, ?, ?, ?, ?)");
            foreach ($questions as $q) {
                $stmt->execute([$quiz_id, $q['text'], $q['a'], $q['b'], $q['c'], $q['d'], $q['correct']]);
            }
            $pdo->commit();
            header("Location: dashboard.php");
            exit;
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = "Failed to create quiz: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Quiz</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        let questionCount = 1;
        function addQuestion() {
            if (questionCount >= 50) {
                alert('Maximum 50 questions allowed.');
                return;
            }
            questionCount++;
            const container = document.getElementById('questions-container');
            const div = document.createElement('div');
            div.className = 'question mt-4 p-4 border';
            div.innerHTML = `
                <h4 class="font-bold">Question ${questionCount}</h4>
                <label for="questions[${questionCount-1}][text]" class="block">Question Text:</label>
                <textarea name="questions[${questionCount-1}][text]" required class="border p-2 w-full" aria-required="true"></textarea>
                <label for="questions[${questionCount-1}][a]" class="block mt-2">Option A:</label>
                <input type="text" name="questions[${questionCount-1}][a]" required class="border p-2 w-full" aria-required="true">
                <label for="questions[${questionCount-1}][b]" class="block mt-2">Option B:</label>
                <input type="text" name="questions[${questionCount-1}][b]" required class="border p-2 w-full" aria-required="true">
                <label for="questions[${questionCount-1}][c]" class="block mt-2">Option C:</label>
                <input type="text" name="questions[${questionCount-1}][c]" required class="border p-2 w-full" aria-required="true">
                <label for="questions[${questionCount-1}][d]" class="block mt-2">Option D:</label>
                <input type="text" name="questions[${questionCount-1}][d]" required class="border p-2 w-full" aria-required="true">
                <label for="questions[${questionCount-1}][correct]" class="block mt-2">Correct Option:</label>
                <select name="questions[${questionCount-1}][correct]" required class="border p-2 w-full" aria-required="true">
                    <option value="a">A</option>
                    <option value="b">B</option>
                    <option value="c">C</option>
                    <option value="d">D</option>
                </select>
            `;
            container.appendChild(div);
        }
    </script>
</head>
<body class="bg-gray-100">
    <main class="container mx-auto p-4" role="main">
        <section aria-labelledby="create-quiz-heading">
            <h2 id="create-quiz-heading" class="text-xl font-bold">Create Quiz</h2>
            <?php if (isset($error)) echo "<p class='text-red-500' role='alert'>$error</p>"; ?>
            <form method="POST" aria-label="Create quiz form">
                <label for="title" class="block">Quiz Title:</label>
                <input type="text" id="title" name="title" required class="border p-2 w-full" aria-required="true">
                <label for="organization" class="block mt-2">Organization:</label>
                <input type="text" id="organization" name="organization" class="border p-2 w-full">
                <div id="questions-container" class="mt-4">
                    <div class="question p-4 border">
                        <h4 class="font-bold">Question 1</h4>
                        <label for="questions[0][text]" class="block">Question Text:</label>
                        <textarea name="questions[0][text]" required class="border p-2 w-full" aria-required="true"></textarea>
                        <label for="questions[0][a]" class="block mt-2">Option A:</label>
                        <input type="text" name="questions[0][a]" required class="border p-2 w-full" aria-required="true">
                        <label for="questions[0][b]" class="block mt-2">Option B:</label>
                        <input type="text" name="questions[0][b]" required class="border p-2 w-full" aria-required="true">
                        <label for="questions[0][c]" class="block mt-2">Option C:</label>
                        <input type="text" name="questions[0][c]" required class="border p-2 w-full" aria-required="true">
                        <label for="questions[0][d]" class="block mt-2">Option D:</label>
                        <input type="text" name="questions[0][d]" required class="border p-2 w-full" aria-required="true">
                        <label for="questions[0][correct]" class="block mt-2">Correct Option:</label>
                        <select name="questions[0][correct]" required class="border p-2 w-full" aria-required="true">
                            <option value="a">A</option>
                            <option value="b">B</option>
                            <option value="c">C</option>
                            <option value="d">D</option>
                        </select>
                    </div>
                </div>
                <button type="button" onclick="addQuestion()" class="bg-green-600 text-white p-2 mt-4">Add Question</button>
                <button type="submit" class="bg-blue-600 text-white p-2 mt-4">Create Quiz</button>
            </form>
        </section>
    </main>
</body>
</html>