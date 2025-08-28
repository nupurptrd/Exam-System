<?php
session_start();
require_once '../config/db_connect.php';
isAdmin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $name = $_POST['name'];
    $role = $_POST['role'];
    $organization = $_POST['organization'];

    $stmt = $pdo->prepare("INSERT INTO users (username, password, name, role, organization) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$username, $password, $name, $role, $organization]);
}

$users = $pdo->query("SELECT * FROM users")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <main class="container mx-auto p-4" role="main">
        <section aria-labelledby="manage-users-heading">
            <h2 id="manage-users-heading" class="text-xl font-bold">Manage Users</h2>
            <form method="POST" aria-label="Add user form">
                <label for="username" class="block">Username:</label>
                <input type="text" id="username" name="username" required class="border p-2 w-full" aria-required="true">
                <label for="password" class="block mt-2">Password:</label>
                <input type="password" id="password" name="password" required class="border p-2 w-full" aria-required="true">
                <label for="name" class="block mt-2">Name:</label>
                <input type="text" id="name" name="name" required class="border p-2 w-full" aria-required="true">
                <label for="role" class="block mt-2">Role:</label>
                <select id="role" name="role" required class="border p-2 w-full" aria-required="true">
                    <option value="admin">Admin</option>
                    <option value="teacher">Teacher</option>
                    <option value="student">Student</option>
                </select>
                <label for="organization" class="block mt-2">Organization:</label>
                <input type="text" id="organization" name="organization" class="border p-2 w-full">
                <button type="submit" class="bg-blue-600 text-white p-2 mt-4">Add User</button>
            </form>
            <h3 class="text-lg font-bold mt-4">User List</h3>
            <table class="w-full border-collapse border">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-2">Username</th>
                        <th class="border p-2">Name</th>
                        <th class="border p-2">Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="border p-2"><?php echo htmlspecialchars($user['username']); ?></td>
                            <td class="border p-2"><?php echo htmlspecialchars($user['name']); ?></td>
                            <td class="border p-2"><?php echo htmlspecialchars($user['role']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>