<?php
session_start();
require_once 'config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        switch ($user['role']) {
            case 'admin':
                header("Location: admin/dashboard.php");
                break;
            
            case 'student':
                header("Location: student/dashboard.php");
                break;
        }
        exit;
    } else {
        $error = "Invalid credentials";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-gray-100">
    <main class="container mx-auto p-4" role="main">
        <section aria-labelledby="login-heading">
            <h2 id="login-heading" class="text-xl font-bold">Login</h2>
            <?php if (isset($error)) echo "<p class='text-red-500' role='alert'>$error</p>"; ?>
            <form method="POST" >
                <label for="username"class="block mt-2">Username :</label>
                <input type="text" id="username" name="username" minlength="5" required title="Username must be at least 5 characters long" required class="border p-2 w-full">
                <label for="password" class="block mt-2">Password:</label>
                <input type="password" id="password" name="password" minlength="5" maxlength="255"required title="password must be at least 8 characters long" required class="border p-2 w-full" >
                <button type="submit" class="bg-blue-600 text-white p-2 mt-4">Login</button>
            </form>
            <p class="mt-4">Don't have an account? <a href="register.php" class="text-blue-600 underline">Register</a></p>
        </section>
    </main>
</body>
</html>