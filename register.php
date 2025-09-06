<?php
require_once 'config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $name = $_POST['name'];
    $role = $_POST['role'];
    $Center = $_POST['Center'];

    try {
        $stmt = $pdo->prepare("INSERT INTO users ( username, password, name, role, Center ) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$username, $password, $name, $role, $Center]);
        header("Location: login.php");
        exit;
    } catch (PDOException $e) {
        $error = "Registration failed: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-gray-100">
    <main class="container mx-auto p-4" >
        <section aria-labelledby="register-heading">
            <h2 id="register-heading" class="text-xl font-bold">Register</h2>
            <?php if (isset($error)) echo "<p class='text-red-500' role='alert'>$error</p>"; ?>
            <form method="POST" >
                <label for="username"class="block mt-2">Username (min 5 characters):</label>
                <input type="text" id="username" name="username" minlength="5" required title="Username must be at least 5 characters long" required class="border p-2 w-full">
                <label for="password" class="block mt-2">Password:</label>
                <input type="password" id="password" name="password" minlength="5" maxlength="255"required title="password must be at least 8 characters long" required class="border p-2 w-full" >
                <label for="name" class="block mt-2">Name:</label>
                <input type="text" id="name" name="name" required class="border p-2 w-full" >
                <label for="role" class="block mt-2">Role:</label>
                <select id="role" name="role" required class="border p-2 w-full" >
                    <option value="admin">Admin</option>
                    <option value="student">Student</option>
                </select>
                <label for="Center" class="block mt-2">Center:</label>
                <input type="text" id="Center" name="Center" class="border p-2 w-full">
                <button type="submit" class="bg-blue-600 text-white p-2 mt-4">Register</button>
            </form>
        </section>
    </main>
</body>
</html>