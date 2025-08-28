<?php
session_start();
require_once '../config/db_connect.php';
isStudent();

$quiz_id = $_GET['id'];
$quiz = $pdo->prepare("SELECT * FROM quizzes WHERE id = ?");
$quiz->execute([$quiz_id]);
$quiz = $quiz->fetch();

$questions = $pdo->prepare("SELECT * FROM questions WHERE quiz_id = ?");
$questions->execute([$quiz_id]);
$questions = $questions->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $score = 0;
    foreach ($questions as $q) {
        $answer = $_POST['question_' . $q['id']] ?? '';
        if ($answer === $q['correct_option']) {
            $score += 2; // 2 marks per correct answer
        }
    }
    $stmt = $pdo->prepare("INSERT INTO results (user_id, quiz_id, score) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $quiz_id, $score]);
    header("Location: result.php?quiz_id=$quiz_id");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Quiz</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        let timeLeft = 10800; // 3 hours in seconds
        function startTimer() {
            const timer = document.getElementById('timer');
            const interval = setInterval(() => {
                timeLeft--;
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                timer.innerText = `Time Left: ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
                if (timeLeft <= 0) {
                    clearInterval(interval);
                    document.querySelector('form').submit();
                }
            }, 1000);
        }
        window.onload = startTimer;
    </script>
</head>
<body class="bg-gray-100">
    <main class="container mx-auto p-4" role="main">
        <section aria-labelledby="quiz-heading">
            <h2 id="quiz-heading" class="text-xl font-bold"><?php echo htmlspecialchars($quiz['title']); ?></h2>
            <p id="timer" aria-live="assertive">Time Left: 180:00</p>
            <form method="POST" aria-label="Quiz form">
                <?php foreach ($questions as $index => $q): ?>
                    <div class="question mt-4 p-4 border">
                        <p class="font-bold">Question <?php echo $index + 1; ?>: <?php echo htmlspecialchars($q['question_text']); ?></p>
                        <label><input type="radio" name="question_<?php echo $q['id']; ?>" value="a" required aria-required="true"> <?php echo htmlspecialchars($q['option_a']); ?></label><br>
                        <label><input type="radio" name="question_<?php echo $q['id']; ?>" value="b" required aria-required="true"> <?php echo htmlspecialchars($q['option_b']); ?></label><br>
                        <label><input type="radio" name="question_<?php echo $q['id']; ?>" value="c" required aria-required="true"> <?php echo htmlspecialchars($q['option_c']); ?></label><br>
                        <label><input type="radio" name="question_<?php echo $q['id']; ?>" value="d" required aria-required="true"> <?php echo htmlspecialchars($q['option_d']); ?></label>
                    </div>
                <?php endforeach; ?>
                <button type="submit" class="bg-blue-600 text-white p-2 mt-4" <?php if (count($questions) < 1) echo 'disabled'; ?>>Submit Quiz</button>
            </form>
        </section>
    </main>
</body>
</html>